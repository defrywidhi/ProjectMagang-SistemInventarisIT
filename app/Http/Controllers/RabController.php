<?php

namespace App\Http\Controllers;

use App\Models\Rab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\RabDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
    
    // ==============================================================
    // ====================== KUMPULAN METHOD LAMA ==================
    // ==============================================================

    /**public function store(Request $request)
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
    }*/
    
    /**public function edit(Rab $rab)
    {
        //
        return view('rab.edit', compact('rab'));
    }*/
    
    /**public function update(Request $request, Rab $rab)
    {
        //
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_dibuat' => 'required|date',
        ]);

        $rab->update($request->only(['judul', 'tanggal_dibuat']));

        return redirect()->route('rab.index')->with('success_edit', 'Rab Berhasil Diperbaharui');
    }*/

    /*public function editDetail(RabDetail $rab_detail)
    {
        //
        if ($rab_detail->rab->status != 'Draft' && $rab_detail->rab->status != 'Ditolak') {
            return redirect()->route('rab.show', $rab_detail->rab_id)
                ->with('error', 'Item tidak dapat diedit karena RAB sudah ' . $rab_detail->rab->status . '!');
        }

        return view('rab_details.edit', compact('rab_detail'));
    }*/

    /*public function updateDetail(Request $request, RabDetail $rab_detail)
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
    }*/

    // =====================================================================
    // =============== AKHIR KUMPULAN METHOD LAMA =========================
    // =====================================================================

    // =====================================================================
    // =============== AWAL KUMPULAN METHOD BARU ==========================
    // =====================================================================
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'tanggal_dibuat' => 'required|date',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();
        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 'Draft';

        // 2. Generate Kode RAB (Logika Lama Tetap Dipakai)
        $tanggal = Carbon::parse($validatedData['tanggal_dibuat']);
        $tahun = $tanggal->format('Y');
        $bulan = $tanggal->format('m');

        $nomorUrutTerakhir = Rab::whereYear('tanggal_dibuat', $tahun)
            ->whereMonth('tanggal_dibuat', $bulan)
            ->max('id');

        $nomorUrutBaru = $nomorUrutTerakhir ? $nomorUrutTerakhir + 1 : 1;
        $nomorUrutFormatted = str_pad($nomorUrutBaru, 3, '0', STR_PAD_LEFT);

        $validatedData['kode_rab'] = "RAB/{$tahun}/{$bulan}/{$nomorUrutFormatted}";

        // 3. Simpan
        Rab::create($validatedData);

        // 4. Return JSON jika AJAX
        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'RAB berhasil dibuat!']);
        }

        return redirect()->route('rab.index')->with('success', 'Rab Baru Berhasil Dibuat');
    }

    /**
     * Show the form for editing (Modifikasi untuk AJAX).
     */
    public function edit(Rab $rab)
    {
        // Jika request via AJAX (dari tombol edit di index), kirim data JSON
        if (request()->ajax()) {
            return response()->json($rab);
        }

        // Fallback jika akses via URL langsung (jarang dipakai kalau sudah SPA)
        return view('rab.edit', compact('rab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rab $rab)
    {
        // 1. Validasi
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'tanggal_dibuat' => 'required|date',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // 2. Cek Status (Proteksi)
        // Hanya boleh edit Judul/Tanggal jika status masih Draft/Ditolak
        if ($rab->status != 'Draft' && $rab->status != 'Ditolak') {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Gagal! RAB sudah diproses, tidak bisa diedit.'], 403);
            }
            return back()->with('error', 'RAB tidak bisa diedit karena status sudah ' . $rab->status);
        }

        // 3. Update
        $rab->update($request->only(['judul', 'tanggal_dibuat']));

        // 4. Return JSON
        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'RAB berhasil diperbarui!']);
        }

        return redirect()->route('rab.index')->with('success_edit', 'Rab Berhasil Diperbaharui');
    }

    public function editDetail(RabDetail $rab_detail)
    {
        // Cek Status (Proteksi)
        if ($rab_detail->rab->status != 'Draft' && $rab_detail->rab->status != 'Ditolak') {
             if (request()->ajax()) {
                return response()->json(['message' => 'Tidak bisa diedit karena status RAB sudah ' . $rab_detail->rab->status], 403);
            }
            return redirect()->route('rab.show', $rab_detail->rab_id)
                ->with('error', 'Item tidak dapat diedit!');
        }

        // --- UBAHAN: JIKA AJAX, KIRIM JSON ---
        if (request()->ajax()) {
            return response()->json($rab_detail);
        }

        // Fallback view lama (jika diakses langsung via URL)
        return view('rab_details.edit', compact('rab_detail'));
    }

    public function updateDetail(Request $request, RabDetail $rab_detail)
    {
        // Cek Status
        if ($rab_detail->rab->status != 'Draft' && $rab_detail->rab->status != 'Ditolak') {
            if ($request->ajax()) {
                return response()->json(['message' => 'Gagal update! Status RAB terkunci.'], 403);
            }
            return back()->with('error', 'Gagal update!');
        }

        // 1. Validasi
        $validatedData = $request->validate([
            'nama_barang_diajukan' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'perkiraan_harga_satuan' => 'required|integer|min:1',
            'ongkir' => 'nullable|integer|min:0',
            'asuransi' => 'nullable|integer|min:0',
        ]);

        // 2. Hitung Ulang Total
        $jumlah = $validatedData['jumlah'];
        $harga = $validatedData['perkiraan_harga_satuan'];
        $ongkir = $request->input('ongkir', 0);
        $asuransi = $request->input('asuransi', 0);

        $validatedData['total_harga'] = ($jumlah * $harga) + $ongkir + $asuransi;

        // 3. Update
        $rab_detail->update($validatedData);

        // --- UBAHAN: RETURN JSON JIKA AJAX ---
        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Item berhasil diperbarui!']);
        }

        return redirect()->route('rab.show', $rab_detail->rab_id)
            ->with('success', 'Item RAB Berhasil Diperbaharui');
    }


    // =====================================================================
    // =============== AKHIR METHOD BARU ==================================
    // =====================================================================

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
    // public function ajukanApproval(Request $request, Rab $rab)
    // {
    //     if ($rab->status != 'Draft' && $rab->status != 'Ditolak') {
    //         return redirect()->route('rab.show', $rab->id)
    //             ->with('error', 'RAB tidak dapat diajukan karena sudah ' . $rab->status . '!');
    //     }

    //     if ($rab->details()->count() == 0) {
    //         return redirect()->route('rab.show', $rab->id)
    //             ->with('error', 'RAB tidak dapat diajukan karena tidak memiliki rincian barang!');
    //     }

    //     $rab->status = 'Menunggu Approval';
    //     $rab->approved_by = null;
    //     $rab->tanggal_disetujui = null;
    //     $rab->catatan_approval = null;
    //     $rab->save();

    //     // ---- TAMBAHKAN KODE INI (KIRIM EMAIL KE SEMUA MANAJER) ----
    //     // Cari semua user yang punya role 'manager'
    //     $managers = User::role('manager')->get();

    //     // ...
    //     foreach ($managers as $manager) {
    //         Mail::send('emails.rab-ajukan', ['rab' => $rab], function ($message) use ($manager, $rab) {
    //             $message->to($manager->email);

    //             // LOGIKA SUBJECT DINAMIS
    //             if ($rab->catatan_approval) {
    //                 $subject = '[REVISI] Menunggu Approval: ' . $rab->judul;
    //             } else {
    //                 $subject = '[BARU] Menunggu Approval: ' . $rab->judul;
    //             }

    //             $message->subject($subject);
    //         });
    //     }

    //     return redirect()->route('rab.show', $rab->id)->with('success', 'RAB berhasil diajukan! Notifikasi terkirim ke Manajer.');
    // }

    // // method untuk approval RAB
    // public function approveRAB(Request $request, Rab $rab)
    // {
    //     if ($rab->status != 'Menunggu Approval') {
    //         return redirect()->route('rab.show', $rab->id)
    //             ->with('error', 'RAB tidak dapat disetujui');
    //     }

    //     $rab->status = 'Disetujui';
    //     $rab->approved_by = Auth::id();
    //     $rab->tanggal_disetujui = Carbon::now();
    //     $rab->save();

    //     // Kirim email ke pengaju
    //     Mail::send('emails.rab-status', ['rab' => $rab], function ($message) use ($rab) {
    //         $message->to($rab->pengaju->email);
    //         $message->subject('RAB DISETUJUI: ' . $rab->kode_rab);
    //     });

    //     return redirect()->route('rab.show', $rab->id)
    //         ->with('success', 'RAB Berhasil Disetujui');
    // }

    // // metod untuk menolak RAB
    // public function rejectRAB(Request $request, Rab $rab)
    // {
    //     if ($rab->status != 'Menunggu Approval') {
    //         return redirect()->route('rab.show', $rab->id)
    //             ->with('error', 'RAB tidak dapat ditolak');
    //     }

    //     $request->validate([
    //         'catatan_approval' => 'required|string|max:255',
    //     ]);

    //     $rab->status = 'Ditolak';
    //     $rab->approved_by = Auth::id();
    //     $rab->tanggal_disetujui = Carbon::now();
    //     $rab->catatan_approval = $request->catatan_approval;
    //     $rab->save();

    //     // Kirim email ke pengaju
    //     Mail::send('emails.rab-status', ['rab' => $rab], function ($message) use ($rab) {
    //         $message->to($rab->pengaju->email);
    //         $message->subject('RAB DITOLAK: ' . $rab->kode_rab);
    //     });

    //     return redirect()->route('rab.show', $rab->id)
    //         ->with('success', 'RAB Berhasil Ditolak');
    // }

    /**
     * TAHAP 1: ADMIN MENGAJUKAN KE MANAJER
     * Menggantikan method ajukanApproval yang lama
     */
    public function ajukanApproval(Request $request, Rab $rab)
    {
        // 1. Validasi Status (Hanya Draft atau Ditolak yang bisa diajukan)
        if ($rab->status != 'Draft' && $rab->status != 'Ditolak') {
            return redirect()->route('rab.show', $rab->id)
                ->with('error', 'RAB tidak dapat diajukan karena status: ' . $rab->status);
        }

        // 2. Validasi Isi Barang
        if ($rab->details()->count() == 0) {
            return redirect()->route('rab.show', $rab->id)
                ->with('error', 'RAB kosong! Mohon isi rincian barang terlebih dahulu.');
        }

        // 3. Update Status ke Level 1 (Menunggu Manager)
        $rab->status = 'Menunggu Manager'; 
        
        // 4. Reset Data Approval Lama (Jika ini pengajuan ulang setelah ditolak)
        // Kita kosongkan jejak approval sebelumnya biar bersih
        $rab->manager_id = null;
        $rab->manager_at = null;
        $rab->direktur_id = null;
        $rab->direktur_at = null;
        $rab->catatan_approval = null;
        $rab->save();

        // 5. Kirim Email ke Semua Manajer
        // (Logika email Abang yang lama tetap dipakai, hanya subject disesuaikan)
        $managers = User::role('manager')->get();
        foreach ($managers as $manager) {
            try {
                Mail::send('emails.rab-ajukan', ['rab' => $rab], function ($message) use ($manager, $rab) {
                    $message->to($manager->email);
                    $subject = $rab->catatan_approval 
                        ? '[REVISI] Menunggu Approval Manajer: ' . $rab->judul 
                        : '[BARU] Menunggu Approval Manajer: ' . $rab->judul;
                    $message->subject($subject);
                });
            } catch (\Exception $e) {
                // Biarkan lanjut meski email gagal (opsional: log error)
            }
        }

        return redirect()->route('rab.show', $rab->id)
            ->with('success', 'RAB berhasil diajukan ke Manajer.');
    }

    /**
     * TAHAP 2: MANAJER MENYETUJUI (LEVEL 1)
     * Method BARU pengganti approveRAB
     */
    public function approveManager(Request $request, Rab $rab)
    {
        // 1. Cek Status (Harus Menunggu Manager)
        if ($rab->status != 'Menunggu Manager') {
            return back()->with('error', 'Gagal! Status RAB tidak valid untuk disetujui Manajer.');
        }

        // 2. Verifikasi Password (Tanda Tangan Keamanan)
        $request->validate(['password' => 'required']);
        
        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->with('error', 'Password Salah! Gagal menyetujui dokumen.');
        }

        // 3. Cek Apakah User Punya TTD di Profile?
        if (!Auth::user()->ttd) {
            return back()->with('error', 'Anda belum mengupload Tanda Tangan Digital di menu Profil.');
        }

        // 4. Update RAB (Naik ke Level 2: Direktur)
        $rab->update([
            'status' => 'Menunggu Direktur',
            'manager_id' => Auth::id(),
            'manager_at' => now(),
        ]);

        // (Opsional: Di sini bisa tambah logic kirim email ke Direktur)

        return back()->with('success', 'Disetujui Manajer! Dokumen diteruskan ke Direktur.');
    }

    /**
     * TAHAP 3: DIREKTUR MENYETUJUI (LEVEL 2 - FINAL)
     * Method BARU
     */
    public function approveDirektur(Request $request, Rab $rab)
    {
        // 1. Cek Status (Harus Menunggu Direktur)
        if ($rab->status != 'Menunggu Direktur') {
            return back()->with('error', 'Gagal! RAB belum disetujui Manajer atau sudah selesai.');
        }

        // 2. Verifikasi Password
        $request->validate(['password' => 'required']);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->with('error', 'Password Salah! Gagal menyetujui dokumen.');
        }

        // 3. Cek Tanda Tangan
        if (!Auth::user()->ttd) {
            return back()->with('error', 'Anda belum mengupload Tanda Tangan Digital di menu Profil.');
        }

        // 4. Update RAB (FINAL)
        $rab->update([
            'status' => 'Disetujui',
            'direktur_id' => Auth::id(),
            'direktur_at' => now(),
        ]);

        // 5. Kirim Email Notifikasi ke Pengaju (Bahwa RAB sudah Goal)
        try {
            Mail::send('emails.rab-status', ['rab' => $rab], function ($message) use ($rab) {
                $message->to($rab->pengaju->email);
                $message->subject('RAB DISETUJUI (FINAL): ' . $rab->kode_rab);
            });
        } catch (\Exception $e) {}

        return back()->with('success', 'RAB Telah Disetujui Sepenuhnya (FINAL).');
    }

    /**
     * METHOD MENOLAK (REJECT)
     * Update agar bisa dipakai Manager maupun Direktur
     */
    public function rejectRAB(Request $request, Rab $rab)
    {
        // 1. Validasi Status (Bisa ditolak di tahap Manager maupun Direktur)
        if ($rab->status != 'Menunggu Manager' && $rab->status != 'Menunggu Direktur') {
            return back()->with('error', 'RAB tidak dalam status menunggu approval, tidak bisa ditolak.');
        }

        // 2. Validasi Input Alasan
        $request->validate([
            'catatan_approval' => 'required|string|max:255',
        ]);

        // 3. Update Status jadi Ditolak
        // Kita tidak perlu simpan siapa yg nolak di kolom khusus, cukup di logs/history kalau mau.
        // Di sini kita reset kolom approval agar jelas.
        $rab->status = 'Ditolak';
        $rab->catatan_approval = $request->catatan_approval . ' (Ditolak oleh: ' . Auth::user()->name . ')';
        
        // Reset approval (Opsional: tergantung kebijakan, mau direset atau dibiarkan history-nya)
        // Di sini saya biarkan history approval manager jika yang nolak direktur, 
        // tapi statusnya tetap Ditolak.
        $rab->save();

        // 4. Kirim Email Penolakan ke Pengaju
        try {
            Mail::send('emails.rab-status', ['rab' => $rab], function ($message) use ($rab) {
                $message->to($rab->pengaju->email);
                $message->subject('RAB DITOLAK: ' . $rab->kode_rab);
            });
        } catch (\Exception $e) {}

        return back()->with('success', 'RAB Berhasil Ditolak.');
    }

    /**
     * Mengambil detail RAB sebagai JSON untuk AJAX.
     */
    public function getDetailsJson(Rab $rab)
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
        // if ($rab->status != 'Disetujui') {
        //     return redirect()->route('rab.show', $rab->id)->with('error', 'Hanya RAB Disetujui yang bisa dicetak.');
        // }

        $rab->load(['details', 'pengaju', 'manager', 'direktur']);

        // --- FUNGSI BASE64 DIPINDAH KE SINI ---
        $encodeImage = function($path) {
            if ($path && file_exists(storage_path('app/public/' . $path))) {
                $fullPath = storage_path('app/public/' . $path);
                $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                $data = file_get_contents($fullPath);
                return 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
            return null;
        };

        // Siapkan variabel gambar untuk dikirim ke View
        $ttdManager = ($rab->manager) ? $encodeImage($rab->manager->ttd) : null;
        $ttdDirektur = ($rab->direktur) ? $encodeImage($rab->direktur->ttd) : null;

        $data = [
            'rab' => $rab,
            'ttdManager' => $ttdManager,   // Kirim variabel ini
            'ttdDirektur' => $ttdDirektur  // Kirim variabel ini
        ];

        $pdf = PDF::loadView('rab.rab_pdf', $data);
        
        $kode_rab_safe = str_replace('/', '-', $rab->kode_rab);
        return $pdf->stream('RAB-' . $kode_rab_safe . '.pdf');
    }
}
