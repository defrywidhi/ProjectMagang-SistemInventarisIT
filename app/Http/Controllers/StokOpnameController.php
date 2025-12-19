<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokOpname;
use App\Models\BarangIT;
use App\Models\StokOpnameDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StokOpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stokOpnames = StokOpname::with(['auditor'])->latest()->get();
        // Kita kirim juga jumlah total barang aktif untuk info di Modal Create
        $totalBarang = BarangIT::where('stok', '>', 0)->count(); 
        
        return view('stok-opname.index', compact('stokOpnames', 'totalBarang'));
    }

    /**
     * Store a newly created resource in storage.
     * LOGIKA: Membuat Header SO + Generate Detail (Full / Random)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Modal
        $request->validate([
            'tanggal_opname' => 'required|date',
            'metode' => 'required|in:Full,Random',
            'jumlah_sampel' => 'nullable|integer|min:1', // Wajib jika Random
            'catatan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // 2. Generate Kode Unik (SO-YYYY-MM-001)
            $tahunBulan = date('Y-m', strtotime($request->tanggal_opname)); // 2023-12
            $lastSO = StokOpname::where('kode_opname', 'like', "SO-{$tahunBulan}-%")->count();
            $noUrut = str_pad($lastSO + 1, 3, '0', STR_PAD_LEFT);
            $kodeSO = "SO-{$tahunBulan}-{$noUrut}";

            // 3. Buat Header SO
            $stokOpname = StokOpname::create([
                'kode_opname' => $kodeSO,
                'tanggal_opname' => $request->tanggal_opname,
                'metode' => $request->metode,
                'catatan' => $request->catatan,
                'user_id' => Auth::id(),
                'status' => 'Pending',
            ]);

            // 4. Ambil Barang Sesuai Metode
            // Kita ambil barang yang stoknya > 0 saja (atau semua barang aktif, tergantung kebijakan)
            $query = BarangIT::where('stok', '>', 0);

            if ($request->metode == 'Random') {
                // Ambil Acak sejumlah sampel
                $jumlah = $request->jumlah_sampel ?? 10;
                $barangs = $query->inRandomOrder()->take($jumlah)->get();
            } else {
                // Ambil Semua (Full)
                $barangs = $query->get();
            }

            if ($barangs->isEmpty()) {
                throw new \Exception("Tidak ada barang yang bisa dicek (Stok Kosong semua).");
            }

            // 5. Masukkan ke Detail (Snapshot Stok Sistem)
            foreach ($barangs as $barang) {
                StokOpnameDetail::create([
                    'stok_opname_id' => $stokOpname->id,
                    'barang_it_id' => $barang->id,
                    'stok_sistem' => $barang->stok, // Kunci stok saat ini
                    'stok_fisik' => null, // Belum dicek
                    'selisih' => 0,
                    'status_fisik' => 'Belum Cek',
                ]);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['status' => 'success', 'message' => 'Sesi SO berhasil dibuat!']);
            }
            return redirect()->route('stok-opname.show', $stokOpname->id)->with('success', 'Sesi SO dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     * Tampilan untuk Input Hasil Cek
     */
    public function show(StokOpname $stokOpname)
    {
        $stokOpname->load(['details.barang', 'auditor']);
        return view('stok-opname.show', compact('stokOpname'));
    }


    public function update(Request $request, StokOpname $stokOpname)
    {
        if ($stokOpname->status == 'Selesai') {
            return response()->json(['message' => 'Sesi selesai tidak bisa diedit'], 403);
        }

        $request->validate([
            'tanggal_opname' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        // Update kode jika tanggal ganti bulan (Opsional, tapi ribet. Kita update data dasar aja)
        $stokOpname->update([
            'tanggal_opname' => $request->tanggal_opname,
            'catatan' => $request->catatan
        ]);

        return response()->json(['status' => 'success', 'message' => 'Informasi SO berhasil diupdate!']);
    }

    /**
     * UPDATE PER ITEM (Dipanggil via AJAX saat user klik Radio Button / Input Angka)
     */
    public function updateItem(Request $request, $detail_id)
    {
        $detail = StokOpnameDetail::findOrFail($detail_id);
        
        // Proteksi: Cek Status Header
        if ($detail->header->status == 'Selesai') {
            return response()->json(['message' => 'Sesi SO sudah selesai, data dikunci.'], 403);
        }

        $request->validate([
            'status_fisik' => 'required|in:Sesuai,Selisih',
            'stok_fisik' => 'required|integer|min:0',
            'keterangan' => 'nullable|string'
        ]);

        $selisih = $request->stok_fisik - $detail->stok_sistem;

        $detail->update([
            'status_fisik' => $request->status_fisik,
            'stok_fisik' => $request->stok_fisik,
            'selisih' => $selisih,
            'keterangan_item' => $request->keterangan
        ]);

        return response()->json([
            'status' => 'success',
            'selisih' => $selisih, // Kirim balik selisih buat update tampilan JS
            'message' => 'Data tersimpan.'
        ]);
    }

    /**
     * Finalisasi SO (Tutup Sesi)
     */
    public function selesaikanSO(StokOpname $stokOpname)
    {
        // Cek apakah ada barang yang belum dicek?
        $belumCek = $stokOpname->details()->where('status_fisik', 'Belum Cek')->exists();
        
        if ($belumCek) {
            return back()->with('error', 'Gagal! Masih ada barang yang belum dicek. Selesaikan semua dulu.');
        }

        $stokOpname->update(['status' => 'Selesai']);

        return back()->with('success', 'Stok Opname Selesai! Laporan telah dikunci.');
    }

    /**
     * Hapus SO
     */
    public function destroy(StokOpname $stokOpname)
    {
        $stokOpname->delete();
        return back()->with('success', 'Sesi SO dihapus.');
    }

    public function cetakPDF(StokOpname $stokOpname)
    {
        $stokOpname->load(['details.barangIt', 'auditor']);

        $data = [
            'so' => $stokOpname,
            'details' => $stokOpname->details,
            'tanggal_cetak' => now()->format('d F Y'),
        ];

        $pdf = Pdf::loadView('stok-opname.cetak_pdf', $data);

        $pdf->setPaper('A4', 'portrait');

        $namaFile = 'Laporan-SO-' . $stokOpname->kode_opname . '.pdf';
        return $pdf->stream($namaFile);
    }
}