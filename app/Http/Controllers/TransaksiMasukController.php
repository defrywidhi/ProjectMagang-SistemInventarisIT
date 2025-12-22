<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMasuk;
use Illuminate\Http\Request;
use App\Models\BarangIT;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use App\Models\Rab;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiMasukExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TransaksiKeluar;
use App\Models\RabDetail;
use App\Models\Kategori;



class TransaksiMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $transaksis_masuk = TransaksiMasuk::with(['barang_it', 'supplier', 'user', 'rab'])->latest()->get();
        return view('transaksi-masuk.index', compact('transaksis_masuk'));
    }

    /**
     * Show the form for creating a new resource.
     */
public function create(Request $request)
    {
        if ($request->has('rab_id') && $request->rab_id != null) {
            $rabId = $request->rab_id;
            
            $pendingItems = \App\Models\RabDetail::where('rab_id', $rabId)
                            ->whereNull('barang_it_id')
                            ->get();
            if ($pendingItems->count() > 0) {
                $kategoris = \App\Models\Kategori::all(); 
                return view('transaksi-masuk.konversi_barang', compact('pendingItems', 'rabId', 'kategoris'));
            }
        }

        $barangs = BarangIT::all();
        $suppliers = Supplier::all();
        $rabs = Rab::where('status', 'Disetujui')->get();
        $selected_rab_id = $request->input('rab_id');

        return view('transaksi-masuk.create', compact('barangs', 'suppliers', 'rabs', 'selected_rab_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validateData = $request->validate([
            'barang_it_id' => 'required|exists:barang_it,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'jumlah_masuk' => 'required|integer|min:1',
            'tanggal_masuk' => 'required|date',
            'harga_satuan' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'rab_id' => 'nullable|exists:rabs,id'
        ]);

        $validateData['user_id'] = Auth::id();

        $transaksi_masuk = TransaksiMasuk::create($validateData);

        $barang = BarangIT::find($transaksi_masuk->barang_it_id);
        $barang->stok += $transaksi_masuk->jumlah_masuk;
        $barang->save();

        // --- UBAH RETURN JADI JSON JIKA AJAX ---
        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Transaksi Masuk berhasil disimpan!']);
        }

        if($request->has('rab_id') && $request->rab_id != null) {
            // JIKA DARI RAB: Kembalikan user ke halaman Detail RAB tersebut
            // Supaya dia bisa langsung klik "Lanjut Pembelian" lagi.
            return redirect()->route('rab.show', $request->rab_id)
                ->with('success', 'Transaksi berhasil disimpan! Silakan input barang berikutnya.');
        }

        // JIKA TRANSAKSI BIASA: Kembali ke index transaksi
        return redirect()->route('transaksi-masuk.index')
            ->with('success', 'Data Transaksi Masuk berhasil ditambahkan');
    }



    public function storeKonversi(Request $request)
    {
        $request->validate([
            'rab_detail_id' => 'required',
            'kategori_id' => 'required',
            'merk' => 'nullable|string',
            'lokasi' => 'nullable|string',
        ]);

        $detail = \App\Models\RabDetail::findOrFail($request->rab_detail_id);

        $newFotoPath = null;
        if ($detail->foto_custom && \Illuminate\Support\Facades\Storage::disk('public')->exists($detail->foto_custom)) {
            $ext = pathinfo($detail->foto_custom, PATHINFO_EXTENSION);
            $newFileName = 'converted_' . time() . '_' . rand(100,999) . '.' . $ext;
            
            \Illuminate\Support\Facades\Storage::disk('public')->copy(
                $detail->foto_custom, 
                'gambar_barang/' . $newFileName
            );
            
            $newFotoPath = $newFileName;
        }

        $newBarang = \App\Models\BarangIT::create([
            'kategori_id' => $request->kategori_id,
            'nama_barang' => $detail->nama_barang_custom,
            'merk' => $request->merk,
            'stok' => 0,
            'stok_minimum' => 3,
            'kondisi' => 'Baru',
            'lokasi_penyimpanan' => $request->lokasi,
            'gambar_barang' => $newFotoPath,
            'deskripsi' => $detail->keterangan
        ]);

        $detail->update([
            'barang_it_id' => $newBarang->id,
            // Opsional: Kosongkan field custom biar database bersih, atau biarkan sebagai history
            // 'nama_barang_custom' => null, 
            // 'foto_custom' => null 
        ]);

        return back()->with('success', 'Barang berhasil didaftarkan ke Master Gudang!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(TransaksiMasuk $transaksi_masuk)
    // {
    //     //
    //     $barangs = BarangIT::all();
    //     $suppliers = Supplier::all();

    //     return view('transaksi-masuk.edit', compact('transaksi_masuk', 'barangs', 'suppliers'));
    // }

    // EDIT DENGAN AJAX
    public function edit(TransaksiMasuk $transaksi_masuk)
    {
        if (request()->ajax()) {
            // Kita butuh data barang & supplier juga untuk dropdown di modal
            return response()->json([
                'transaksi' => $transaksi_masuk,
                'barang_sekarang' => $transaksi_masuk->barang_it, // Biar dropdown kepilih
            ]);
        }
        // Fallback view biasa (opsional kalau mau tetap support non-ajax)
        $barangs = BarangIT::all();
        $suppliers = Supplier::all();
        return view('transaksi-masuk.edit', compact('transaksi_masuk', 'barangs', 'suppliers'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransaksiMasuk $transaksi_masuk)
    {
        $validateData = $request->validate([
            'barang_it_id' => 'required|exists:barang_it,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'jumlah_masuk' => 'required|integer|min:1',
            'tanggal_masuk' => 'required|date',
            'harga_satuan' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // Simpan data lama
        $jumlah_lama = $transaksi_masuk->jumlah_masuk;
        $barang_lama_id = $transaksi_masuk->barang_it_id;

        // Lakukan Update Transaksi
        $transaksi_masuk->update($validateData);

        // --- [LOGIKA STOK BARANG] ---
        // 1. Kembalikan stok lama (kurangi karena dulu nambah)
        $barang_lama = BarangIT::find($barang_lama_id);
        if ($barang_lama) {
            $barang_lama->stok -= $jumlah_lama;
            $barang_lama->save();
        }

        // 2. Tambahkan stok baru
        $barang_baru = BarangIT::find($transaksi_masuk->barang_it_id);
        if ($barang_baru) {
            $barang_baru->stok += $transaksi_masuk->jumlah_masuk;
            $barang_baru->save();
        }

        // --- [LOGIKA SINKRONISASI RETUR] ---
        // Jika ini adalah data RETUR, update juga catatan di Transaksi Keluar asalnya
        if ($transaksi_masuk->transaksi_keluar_id) {
            $transaksi_keluar_asal = TransaksiKeluar::find($transaksi_masuk->transaksi_keluar_id);
            
            if ($transaksi_keluar_asal) {
                // Hitung selisih perubahan jumlah
                $selisih = $transaksi_masuk->jumlah_masuk - $jumlah_lama;
                
                // Update jumlah dikembalikan di transaksi keluar
                $transaksi_keluar_asal->jumlah_dikembalikan += $selisih;
                
                // Validasi agar tidak melebihi jumlah keluar awal (Proteksi Tambahan)
                if ($transaksi_keluar_asal->jumlah_dikembalikan > $transaksi_keluar_asal->jumlah_keluar) {
                    // Rollback jika user nakal input kelebihan
                    return back()->with('error', 'Gagal update! Jumlah retur melebihi jumlah barang yang keluar.');
                }
                
                $transaksi_keluar_asal->save();
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Transaksi Masuk berhasil diperbarui!']);
        }

        return redirect()->route('transaksi-masuk.index')->with('success', 'Data Transaksi Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransaksiMasuk $transaksi_masuk)
    {
        // 1. Ambil data barang terkait
        $barang = BarangIT::find($transaksi_masuk->barang_it_id);

        // --- [BARU] VALIDASI STOK CUKUP ---
        // Cek apakah stok saat ini cukup untuk dikurangi?
        // Jika stok gudang (misal 0) LEBIH KECIL dari jumlah yang mau dihapus (misal 1),
        // Berarti barang ini sudah dipakai di transaksi lain (misal: lagi diservice).
        if ($barang && $barang->stok < $transaksi_masuk->jumlah_masuk) {
            return back()->with('error', 'Gagal menghapus! Barang ini sudah dikeluarkan/dipakai di transaksi lain (Stok tidak cukup). Batalkan dulu transaksi keluarnya.');
        }
        // ----------------------------------

        // 2. CEK: Apakah ini adalah data RETUR? (Punya bapak Transaksi Keluar?)
        if ($transaksi_masuk->transaksi_keluar_id) {
            $transaksi_keluar_asal = TransaksiKeluar::find($transaksi_masuk->transaksi_keluar_id);
            
            if ($transaksi_keluar_asal) {
                // Balikin/Kurangi angka jumlah_dikembalikan di bapaknya
                $transaksi_keluar_asal->jumlah_dikembalikan -= $transaksi_masuk->jumlah_masuk;
                
                // Pastikan tidak minus (Safety)
                if ($transaksi_keluar_asal->jumlah_dikembalikan < 0) {
                    $transaksi_keluar_asal->jumlah_dikembalikan = 0;
                }
                
                $transaksi_keluar_asal->save();
            }
        }

        // 3. Hapus Transaksi Masuk
        $transaksi_masuk->delete();

        // 4. Update Stok Barang (Kurangi stok)
        if ($barang) {
            $barang->stok -= $transaksi_masuk->jumlah_masuk;
            $barang->save();
        }

        return redirect()->route('transaksi-masuk.index')->with('success', 'Data Transaksi Berhasil Dihapus');
    }


    /**
     * Menangani ekspor data transaksi masuk ke Excel.
     */
    public function exportExcel()
    {
        $namaFile = 'laporan_pengadaan_' . date('Y-m-d') . '.xlsx';
        return Excel::download(new TransaksiMasukExport, $namaFile);
    }

    /**
     * Cetak Nota Transaksi Masuk
     */
    public function cetakInvoice(TransaksiMasuk $transaksi_masuk)
    {
        // Kita pakai nama variabel $transaksi biar singkat di view
        $transaksi = $transaksi_masuk;
        
        // Load view PDF
        $pdf = Pdf::loadView('transaksi-masuk.invoice_pdf', compact('transaksi'));

        // Nama file saat didownload
        $namaFile = 'Invoice-TRX-' . $transaksi->id . '.pdf';

        // Preview di browser (stream)
        return $pdf->stream($namaFile);
    }
}
