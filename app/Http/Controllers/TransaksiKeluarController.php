<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangIT;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\TransaksiKeluar;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TransaksiMasuk;
use App\Models\Supplier;
use Illuminate\Support\Facades\Validator;

class TransaksiKeluarController extends Controller
{
    public function index()
    {
        $transaksi_keluar = TransaksiKeluar::with('barang_it', 'user')->latest()->get();
        return view('transaksi-keluar.index', compact('transaksi_keluar'));
    }

    // Method Create untuk Shortcut dari Barang (Tetap Pertahankan logic ini)
    public function create(Request $request)
    {
        // Jika request via AJAX (Modal), kita tidak butuh method ini
        // Tapi jika akses lewat URL shortcut (tombol obeng/pakai di barang), ini tetap dipakai
        $barang_it = BarangIT::where('stok', '>', 0)->get(); // Hanya tampilkan yg ada stok
        
        $selected_barang_id = $request->query('barang_id');
        $tipe = $request->query('tipe');
        
        $default_keterangan = '';
        if ($tipe == 'service') {
            $default_keterangan = 'Pengiriman barang rusak untuk perbaikan/service.';
        } elseif ($tipe == 'pakai') {
            $default_keterangan = 'Penggunaan operasional / Peminjaman.';
        }

        return view('transaksi-keluar.create', compact('barang_it', 'selected_barang_id', 'default_keterangan'));
    }

    
    public function store(Request $request)
    {
        // 1. CEK APAKAH BARANG DIKIRIM?
        if (!$request->has('barang_it_id') || $request->barang_it_id == null) {
            // Jika AJAX
            if ($request->ajax()) {
                return response()->json(['errors' => ['barang_it_id' => ['Wajib memilih barang!']]], 422);
            }
            // Jika Browser Biasa
            return back()->with('error', 'Wajib memilih barang!');
        }

        // 2. AMBIL BARANG
        $barang = BarangIT::find($request->barang_it_id);
        
        // 3. CEK STOK (Manual)
        if ($barang && $request->jumlah_keluar > $barang->stok) {
            if ($request->ajax()) {
                return response()->json(['errors' => ['jumlah_keluar' => ["Stok tidak cukup! Sisa: " . $barang->stok]]], 422);
            }
            return back()->with('error', "Stok tidak cukup! Sisa: " . $barang->stok)->withInput();
        }

        // 4. VALIDASI SISANYA
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'barang_it_id' => 'required|exists:barang_it,id',
            'jumlah_keluar' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // 5. SIMPAN
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['jumlah_dikembalikan'] = 0;

        TransaksiKeluar::create($data);

        // 6. UPDATE STOK
        $barang->stok -= $request->jumlah_keluar;
        $barang->save();

        // 7. RESPON (INI YANG KITA PERBAIKI)
        // Cek: Apakah request ini dari AJAX (Modal)?
        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Transaksi berhasil disimpan!']);
        }

        // Jika BUKAN AJAX (alias dari Shortcut Barang), lakukan Redirect
        return redirect()->route('transaksi-keluar.index')->with('success', 'Transaksi keluar berhasil ditambahkan.');
    }

    // --- TAMBAHAN BARU: EDIT UNTUK AJAX ---
    public function edit(TransaksiKeluar $transaksi_keluar)
    {
        if (request()->ajax()) {
            return response()->json([
                'transaksi' => $transaksi_keluar,
                'barang_sekarang' => $transaksi_keluar->barang_it
            ]);
        }
        // Fallback view biasa
        $barangs = BarangIT::all();
        return view('transaksi-keluar.edit', compact('transaksi_keluar', 'barangs'));
    }

    public function update(Request $request, TransaksiKeluar $transaksi_keluar)
    {
        // 1. Ambil data barang BARU & LAMA
        $barang_baru = BarangIT::findOrFail($request->barang_it_id);
        $barang_lama = BarangIT::find($transaksi_keluar->barang_it_id);

        // 2. Hitung Stok Tersedia
        $stok_tersedia_max = $barang_baru->stok;
        if ($barang_lama->id == $barang_baru->id) {
            $stok_tersedia_max += $transaksi_keluar->jumlah_keluar; // Tambahkan stok lama biar bisa diedit
        }

        // 3. Validasi
        $request->validate([
            'barang_it_id' => 'required|exists:barang_it,id',
            'jumlah_keluar' => 'required|integer|min:1|max:' . $stok_tersedia_max,
            'tanggal_keluar' => 'required|date',
            'keterangan' => 'nullable|string',
        ], [
            'jumlah_keluar.max' => 'Stok tidak cukup untuk update! Maksimal: ' . $stok_tersedia_max
        ]);

        // Simpan jumlah lama
        $jumlah_lama = $transaksi_keluar->jumlah_keluar;

        // 4. Update Transaksi
        $transaksi_keluar->update($request->all());

        // 5. Logika Stok: Kembalikan Lama -> Kurangi Baru
        if ($barang_lama) {
            $barang_lama->stok += $jumlah_lama;
            $barang_lama->save();
        }

        $barang_baru_final = BarangIT::find($request->barang_it_id);
        if ($barang_baru_final) {
            $barang_baru_final->stok -= $request->jumlah_keluar;
            $barang_baru_final->save();
        }

        // --- TAMBAHAN AJAX RESPONSE ---
        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Transaksi keluar berhasil diperbarui!']);
        }

        return redirect()->route('transaksi-keluar.index')->with('success', 'Transaksi keluar berhasil diupdate.');
    }

    public function destroy(TransaksiKeluar $transaksi_keluar)
    {
        // Cek Anak Retur (Proteksi)
        if ($transaksi_keluar->transaksiMasuks()->exists()) {
            return back()->with('error', 'Gagal menghapus! Transaksi ini sudah memiliki data pengembalian/service. Hapus data pengembaliannya terlebih dahulu di menu Transaksi Masuk.');
        }

        // Kembalikan Stok
        $barang = $transaksi_keluar->barang_it;
        if ($barang) {
            $barang->stok += $transaksi_keluar->jumlah_keluar;
            $barang->save();
        }

        $transaksi_keluar->delete();

        return redirect()->route('transaksi-keluar.index')->with('success', 'Transaksi keluar berhasil dihapus.');
    }

    public function processRetur(Request $request, TransaksiKeluar $transaksi_keluar)
    {
        $request->validate([
            'jumlah_kembali' => 'required|integer|min:1',
            'jenis_retur' => 'required|string', // <--- Validasi Input Baru
            'kondisi_akhir' => 'required|in:Baru,Bekas,Rusak',
            'biaya_perbaikan' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // Cek sisa barang di luar
        $sisa_di_luar = $transaksi_keluar->jumlah_keluar - $transaksi_keluar->jumlah_dikembalikan;
        if ($request->jumlah_kembali > $sisa_di_luar) {
            return back()->with('error', 'Jumlah pengembalian melebihi sisa barang di luar!');
        }

        // Cari/Buat Barang Tujuan (Logic Stok)
        $barangAsli = $transaksi_keluar->barang_it;
        $barangTujuan = BarangIT::where('nama_barang', $barangAsli->nama_barang)
                                ->where('merk', $barangAsli->merk)
                                ->where('kondisi', $request->kondisi_akhir)
                                ->first();

        if (!$barangTujuan) {
            $barangTujuan = BarangIT::create([
                'nama_barang' => $barangAsli->nama_barang,
                'kategori_id' => $barangAsli->kategori_id,
                'merk' => $barangAsli->merk,
                'serial_number' => $barangAsli->serial_number ? $barangAsli->serial_number . '-R' : null,
                'stok' => 0,
                'stok_minimum' => 0,
                'kondisi' => $request->kondisi_akhir,
                'lokasi_penyimpanan' => $barangAsli->lokasi_penyimpanan,
                'gambar_barang' => $barangAsli->gambar_barang,
            ]);
        }

        // --- FORMAT KETERANGAN OTOMATIS ---
        // Format: [JENIS RETUR] - Catatan User
        // --- FORMAT KETERANGAN OTOMATIS (Sesuai Format TRK-ID) ---
        $jenis = $request->jenis_retur;
        $catatan = $request->keterangan ? "Catatan: " . $request->keterangan : "";
        // Ganti "Ref TRX Keluar" jadi "Ref: TRK-" biar sama persis kayak di tabel
        $keterangan_final = "[{$jenis}] - Ref: TRK-{$transaksi_keluar->id}. {$catatan}";
        // ----------------------------------

        $biaya = $request->input('biaya_perbaikan', 0);

        TransaksiMasuk::create([
            'barang_it_id' => $barangTujuan->id,
            'supplier_id' => 1, 
            'jumlah_masuk' => $request->jumlah_kembali,
            'tanggal_masuk' => now(),
            'harga_satuan' => $biaya,
            'keterangan' => $keterangan_final, // <--- Pakai yang sudah diformat
            'user_id' => Auth::id(),
            'transaksi_keluar_id' => $transaksi_keluar->id,
        ]);

        // Update Stok
        $barangTujuan->stok += $request->jumlah_kembali;
        $barangTujuan->save();

        // Update Transaksi Keluar
        $transaksi_keluar->jumlah_dikembalikan += $request->jumlah_kembali;
        $transaksi_keluar->save();

        return back()->with('success', 'Retur berhasil diproses!');
    }

    public function cetakBuktiKeluar(TransaksiKeluar $transaksi_keluar)
    {
        $transaksi = $transaksi_keluar;
        $pdf = Pdf::loadView('transaksi-keluar.bukti_keluar_pdf', compact('transaksi'));
        $namaFile = 'Bukti-Keluar-' . $transaksi->id . '.pdf';
        return $pdf->stream($namaFile);
    }
}