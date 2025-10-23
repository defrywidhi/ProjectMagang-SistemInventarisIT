<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangIT;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\TransaksiKeluar;



class TransaksiKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $transaksi_keluar = TransaksiKeluar::with('barang_it', 'user')->latest()->get();
        return view('transaksi-keluar.index', compact('transaksi_keluar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $barangs = BarangIT::all();
        return view('transaksi-keluar.create', compact('barangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $barang = BarangIT::findOrFail($request->barang_it_id);

        $validateData = $request->validate([
            'barang_it_id' => 'required|exists:barang_it,id',
            'jumlah_keluar' => 'required|integer|min:1|max:' . $barang->stok,
            'tanggal_keluar' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $validateData['user_id'] = Auth::id();

        $transaksi_keluar = TransaksiKeluar::create($validateData);

        $barang->stok -= $transaksi_keluar->jumlah_keluar;
        $barang->save();

        return redirect()->route('transaksi-keluar.index')
            ->with('success', 'Transaksi keluar berhasil ditambahkan.');
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
    public function edit(TransaksiKeluar $transaksi_keluar)
    {
        //
        $barangs = BarangIT::all();

        return view('transaksi-keluar.edit', compact('transaksi_keluar', 'barangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransaksiKeluar $transaksi_keluar)
    {

        // 1. Ambil data barang BARU untuk cek stok maksimal
        $barang_baru = BarangIT::findOrFail($request->barang_it_id);
        // 2. Ambil data barang LAMA untuk revert stok
        $barang_lama = BarangIT::find($transaksi_keluar->barang_it_id); // Find biasa, mungkin barang lama sudah dihapus

        // 3. Validasi (sesuaikan aturan max)
        $stok_tersedia_untuk_update = $barang_baru->stok;
        // Jika barangnya sama, tambahkan stok lama kembali untuk perhitungan max
        if ($barang_lama && $barang_lama->id == $barang_baru->id) {
            $stok_tersedia_untuk_update += $transaksi_keluar->jumlah_keluar;
        }

        $validatedData = $request->validate([
            'barang_it_id' => 'required|exists:barang_it,id',
            'jumlah_keluar' => 'required|integer|min:1|max:' . $stok_tersedia_untuk_update, // Gunakan stok yg sdh disesuaikan
            'tanggal_keluar' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // 4. Simpan data LAMA sebelum diupdate
        $jumlah_lama = $transaksi_keluar->jumlah_keluar;

        // 5. Tambahkan kembali stok barang LAMA
        if ($barang_lama) {
            $barang_lama->stok += $jumlah_lama;
            $barang_lama->save();
        }

        // 6. Update data transaksinya
        $transaksi_keluar->update($validatedData);

        // 7. Kurangi stok barang BARU
        // $barang_baru diambil ulang untuk memastikan data stok terbaru setelah save barang_lama
        $barang_baru_updated = BarangIT::find($transaksi_keluar->barang_it_id);
        if ($barang_baru_updated) {
            $barang_baru_updated->stok -= $transaksi_keluar->jumlah_keluar; // Kurangi dengan jumlah BARU
            $barang_baru_updated->save();
        }


        // Perbaiki typo 'succsess'
        return redirect()->route('transaksi-keluar.index')
            ->with('success', 'Transaksi keluar berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransaksiKeluar $transaksi_keluar)
    {
        $barang = BarangIT::find($transaksi_keluar->barang_it_id); // Find biasa, just in case

        // Simpan jumlah keluar sebelum dihapus
        $jumlah_dihapus = $transaksi_keluar->jumlah_keluar;

        $transaksi_keluar->delete();

        if ($barang) {
            $barang->stok += $jumlah_dihapus; // Tambahkan kembali stok
            $barang->save();
        }

        // Perbaiki typo 'succsess'
        return redirect()->route('transaksi-keluar.index')
            ->with('success', 'Transaksi keluar berhasil dihapus.');
    }
}
