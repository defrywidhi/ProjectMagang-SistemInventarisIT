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



class TransaksiMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $transaksis_masuk = TransaksiMasuk::with(['barang_it', 'supplier', 'user'])->latest()->get();

        return view('transaksi-masuk.index', compact('transaksis_masuk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $barangs = BarangIT::all();
        $suppliers = Supplier::all();

        // Ambil HANYA RAB yang sudah Disetujui
        $rabs = Rab::where('status', 'Disetujui')->get();

        // Ambil rab_id dari URL (query string)
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

        return redirect()->route('transaksi-masuk.index')->with('success', 'Data Transaksi Berhasil Dimasukkan');
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
    public function edit(TransaksiMasuk $transaksi_masuk)
    {
        //
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

        $jumlah_lama = $transaksi_masuk->jumlah_masuk;
        $barang_lama_id = $transaksi_masuk->barang_it_id;

        $transaksi_masuk->update($validateData);

        $barang_lama = BarangIT::find($barang_lama_id);
        $barang_baru = BarangIT::find($transaksi_masuk->barang_it_id);

        if ($barang_lama) {
            $barang_lama->stok -= $jumlah_lama;
            $barang_lama->save();
        }

        if ($barang_baru) {
            $barang_baru->stok += $transaksi_masuk->jumlah_masuk;
            $barang_baru->save();
        }

        return redirect()->route('transaksi-masuk.index')->with('success', 'Data Transaksi Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransaksiMasuk $transaksi_masuk)
    {
        //
        $barang = BarangIT::find($transaksi_masuk->barang_it_id);

        $transaksi_masuk->delete();

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
}
