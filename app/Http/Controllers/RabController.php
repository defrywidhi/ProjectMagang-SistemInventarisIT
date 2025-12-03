<?php

namespace App\Http\Controllers;

use App\Models\Rab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\RabDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail; // <-- Wajib ada
use App\Models\User; // <-- Wajib ada buat cari email Manajer

class RabController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $rabs = Rab::with(['pengaju', 'penyetuju'])->latest()->get();
        return view('rab.index', compact('rabs'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rab.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_dibuat' => 'required|date',
        ]);

        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 'Draft';

        $tanggal = Carbon::parse($validatedData['tanggal_dibuat']);
        $tahun = $tanggal->format('Y');
        $bulan = $tanggal->format('m');

        $nomorUrutTerakhir = Rab::whereYear('tanggal_dibuat', $tahun)
            ->whereMonth('tanggal_dibuat', $bulan)
            ->max('id');

        $nomorUrutBaru = $nomorUrutTerakhir ? $nomorUrutTerakhir + 1 : 1;

        $nomorUrutFormatted = str_pad($nomorUrutBaru, 3, '0', STR_PAD_LEFT);

        $validatedData['kode_rab'] = "RAB/{$tahun}/{$bulan}/{$nomorUrutFormatted}";

        Rab::create($validatedData);

        return redirect()->route('rab.index')->with('success', 'Rab Baru Berhasil Dibuat');
    }


    public function storeDetail(Request $request, Rab $rab)
    {
        // 1. Validasi input dari form
        $validatedData = $request->validate([
            'nama_barang_diajukan' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'perkiraan_harga_satuan' => 'required|integer|min:1',
            'ongkir' => 'nullable|integer|min:0',
            'asuransi' => 'nullable|integer|min:0',
        ]);

        // 2. Hitung total harga otomatis
        $jumlah = $validatedData['jumlah'];
        $harga_satuan = $validatedData['perkiraan_harga_satuan'];
        $ongkir = $request->input('ongkir', 0); // Ambil ongkir, default 0 jika null
        $asuransi = $request->input('asuransi', 0); // Ambil asuransi, default 0 jika null

        $validatedData['total_harga'] = ($jumlah * $harga_satuan) + $ongkir + $asuransi;

        // 3. Tambahkan rab_id
        $validatedData['rab_id'] = $rab->id;

        // 4. Simpan data ke tabel rab_details
        RabDetail::create($validatedData);

        // 5. Kembalikan ke halaman show dengan pesan sukses
        return redirect()->route('rab.show', $rab->id)
            ->with('success', 'Item baru berhasil ditambahkan ke RAB!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rab $rab)
    {
        //
        $rab->load(['details', 'pengaju', 'penyetuju']);
        return view('rab.show', compact('rab'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rab $rab)
    {
        //
        return view('rab.edit', compact('rab'));
    }

    public function editDetail(RabDetail $rab_detail)
    {
        //
        if ($rab_detail->rab->status != 'Draft' && $rab_detail->rab->status != 'Ditolak') {
            return redirect()->route('rab.show', $rab_detail->rab_id)
                ->with('error', 'Item tidak dapat diedit karena RAB sudah ' . $rab_detail->rab->status . '!');
        }

        return view('rab_details.edit', compact('rab_detail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rab $rab)
    {
        //
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_dibuat' => 'required|date',
        ]);

        $rab->update($request->only(['judul', 'tanggal_dibuat']));

        return redirect()->route('rab.index')->with('success_edit', 'Rab Berhasil Diperbaharui');
    }

    public function updateDetail(Request $request, RabDetail $rab_detail)
    {

        if ($rab_detail->rab->status != 'Draft' && $rab_detail->rab->status != 'Ditolak') {
            return redirect()->route('rab.show', $rab_detail->rab_id)
                ->with('error', 'Item tidak dapat diupdate karena RAB sudah ' . $rab_detail->rab->status . '!');
        }

        // 1. Validasi input dari form
        $validatedData = $request->validate([
            'nama_barang_diajukan' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'perkiraan_harga_satuan' => 'required|integer|min:1',
            'ongkir' => 'nullable|integer|min:0',
            'asuransi' => 'nullable|integer|min:0',
        ]);

        $jumlah = $validatedData['jumlah'];
        $harga = $validatedData['perkiraan_harga_satuan'];
        $ongkir = $request->input('ongkir', 0); // Ambil ongkir, default 0 jika null
        $asuransi = $request->input('asuransi', 0); // Ambil asuransi, default 0 jika null

        $validatedData['total_harga'] = ($jumlah * $harga) + $ongkir + $asuransi;

        $rab_detail->update($validatedData);

        return redirect()->route('rab.show', $rab_detail->rab_id)
            ->with('success', 'Item RAB Berhasil Diperbaharui');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rab $rab)
    {
        // 1. Ambil user yang sedang login
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 2. Validasi Status & Role
        // Cek apakah user BUKAN admin?
        if (!$user->hasRole('admin')) {
            // Jika bukan admin, dia HANYA boleh menghapus status Draft atau Ditolak
            if ($rab->status != 'Draft' && $rab->status != 'Ditolak') {
                return redirect()->route('rab.index')
                    ->with('error', 'Anda tidak memiliki izin menghapus RAB yang sudah diproses.');
            }
        }

        // 3. Lakukan Penghapusan
        // Kita biarkan detail terhapus otomatis oleh database (Cascade On Delete)
        $rab->delete();

        return redirect()->route('rab.index')->with('success', 'RAB Berhasil Dihapus');
    }

    public function destroyDetail(RabDetail $rab_detail)
    {
        //
        $rab = $rab_detail->rab;

        if ($rab->status != 'Draft' && $rab->status != 'Ditolak') {
            return redirect()->route('rab.show', $rab->id)
                ->with('error', 'Item tidak dapat dihapus karena RAB sudah ' . $rab->status . '!');
        }

        $rab_detail->delete();

        return redirect()->route('rab.show', $rab->id)
            ->with('success', 'Data berhasil dihapus dari RAB!');
    }


    // Function untuk mengajukan RAB
    public function ajukanApproval(Request $request, Rab $rab)
    {
        if ($rab->status != 'Draft' && $rab->status != 'Ditolak') {
            return redirect()->route('rab.show', $rab->id)
                ->with('error', 'RAB tidak dapat diajukan karena sudah ' . $rab->status . '!');
        }

        if ($rab->details()->count() == 0) {
            return redirect()->route('rab.show', $rab->id)
                ->with('error', 'RAB tidak dapat diajukan karena tidak memiliki rincian barang!');
        }

        $rab->status = 'Menunggu Approval';
        $rab->approved_by = null;
        $rab->tanggal_disetujui = null;
        $rab->catatan_approval = null;
        $rab->save();

        // ---- TAMBAHKAN KODE INI (KIRIM EMAIL KE SEMUA MANAJER) ----
        // Cari semua user yang punya role 'manager'
        $managers = User::role('manager')->get();

        // ...
        foreach ($managers as $manager) {
            Mail::send('emails.rab-ajukan', ['rab' => $rab], function ($message) use ($manager, $rab) {
                $message->to($manager->email);

                // LOGIKA SUBJECT DINAMIS
                if ($rab->catatan_approval) {
                    $subject = '[REVISI] Menunggu Approval: ' . $rab->judul;
                } else {
                    $subject = '[BARU] Menunggu Approval: ' . $rab->judul;
                }

                $message->subject($subject);
            });
        }
        // ...
        // -----------------------------------------------------------

        return redirect()->route('rab.show', $rab->id)->with('success', 'RAB berhasil diajukan! Notifikasi terkirim ke Manajer.');
    }

    // method untuk approval RAB
    public function approveRAB(Request $request, Rab $rab)
    {
        if ($rab->status != 'Menunggu Approval') {
            return redirect()->route('rab.show', $rab->id)
                ->with('error', 'RAB tidak dapat disetujui');
        }

        $rab->status = 'Disetujui';
        $rab->approved_by = Auth::id();
        $rab->tanggal_disetujui = Carbon::now();
        $rab->save();

        // Kirim email ke pengaju
        Mail::send('emails.rab-status', ['rab' => $rab], function ($message) use ($rab) {
            $message->to($rab->pengaju->email);
            $message->subject('RAB DISETUJUI: ' . $rab->kode_rab);
        });

        return redirect()->route('rab.show', $rab->id)
            ->with('success', 'RAB Berhasil Disetujui');
    }

    // metod untuk menolak RAB
    public function rejectRAB(Request $request, Rab $rab)
    {
        if ($rab->status != 'Menunggu Approval') {
            return redirect()->route('rab.show', $rab->id)
                ->with('error', 'RAB tidak dapat ditolak');
        }

        $request->validate([
            'catatan_approval' => 'required|string|max:255',
        ]);

        $rab->status = 'Ditolak';
        $rab->approved_by = Auth::id();
        $rab->tanggal_disetujui = Carbon::now();
        $rab->catatan_approval = $request->catatan_approval;
        $rab->save();

        // Kirim email ke pengaju
        Mail::send('emails.rab-status', ['rab' => $rab], function ($message) use ($rab) {
            $message->to($rab->pengaju->email);
            $message->subject('RAB DITOLAK: ' . $rab->kode_rab);
        });

        return redirect()->route('rab.show', $rab->id)
            ->with('success', 'RAB Berhasil Ditolak');
    }

    /**
     * Mengambil detail RAB sebagai JSON untuk AJAX.
     */
    public function getRabDetailsJson(Rab $rab)
    {
        // Kita ambil detailnya menggunakan relasi 'details' yang sudah kita buat
        $details = $rab->details;

        // Kita kembalikan sebagai JSON
        return response()->json($details);
    }

    /**
     * Membuat dan men-download RAB sebagai PDF.
     */
    public function cetakPDF(Rab $rab)
    {
        // 1. Pastikan RAB sudah disetujui
        if ($rab->status != 'Disetujui') {
            return redirect()->route('rab.show', $rab->id)->with('error', 'Hanya RAB yang sudah Disetujui yang bisa dicetak.');
        }

        // 2. Ambil data (kita load relasi biar tidak error)
        $rab->load(['details', 'pengaju', 'penyetuju']);

        // 3. Panggil "Mesin Cetak" (DomPDF)
        $pdf = PDF::loadView('rab.rab_pdf', compact('rab'));

        // 4. Buat nama file YANG AMAN (ganti '/' dengan '-')
        $kode_rab_safe = str_replace('/', '-', $rab->kode_rab); // Ini akan jadi "RAB-2025-11-001"
        $namaFile = 'RAB-' . $kode_rab_safe . '.pdf'; // Hasil: "RAB-RAB-2025-11-001.pdf"

        // 5. Download file PDF-nya
        return $pdf->stream($namaFile);

        // (Opsi: jika ingin ditampilkan di browser saja, ganti baris di atas dengan:)
        // return $pdf->stream($namaFile);
    }
}
