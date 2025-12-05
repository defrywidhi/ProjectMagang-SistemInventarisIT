<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangIT;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use App\Models\TransaksiKeluar;
use App\Models\TransaksiMasuk;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BarangExport;

class BarangITController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     //
    //     $barangs = BarangIT::all();

    //     return view('barang.index', compact('barangs'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $kategoris = Kategori::all();
        return view('barang.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    //     $request->validate([
    //         'kategori_id' => 'required|exists:kategoris,id',
    //         'nama_barang' => 'required|string|max:255',
    //         'merk' => 'nullable|string|max:255',
    //         'serial_number' => 'nullable|string|max:255|unique:barang_it',
    //         'deskripsi' => 'nullable|string',
    //         'stok_minimum' => 'required|integer|min:0',
    //         'kondisi' => 'required|in:Baru,Bekas,Rusak',
    //         'lokasi_penyimpanan' => 'nullable|string|max:255',
    //         'gambar_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     ]);

    //     $data = $request->all();

    //     if ($request->hasFile('gambar_barang')) {
    //         $file = $request->file('gambar_barang');
    //         $namaFile = time() . "_" . $file->getClientOriginalName();
    //         $file->storeAs('public/gambar_barang', $namaFile);
    //         $data['gambar_barang'] = $namaFile;
    //     }

    //     BarangIT::create($data);

    //     return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    // }

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
    // public function edit(BarangIT $barang)
    // {
    //     //
    //     $kategoris = Kategori::all();

    //     return view('barang.edit', compact('barang', 'kategoris'));
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, BarangIT $barang)
    // {
    //     //
    //     $request->validate([
    //         'kategori_id' => 'required|exists:kategoris,id',
    //         'nama_barang' => 'required|string|max:255',
    //         'merk' => 'nullable|string|max:255',
    //         'serial_number' => 'nullable|string|max:255|unique:barang_it,serial_number,' . $barang->id,
    //         'deskripsi' => 'nullable|string',
    //         'stok_minimum' => 'required|integer|min:0',
    //         'kondisi' => 'required|in:Baru,Bekas,Rusak',
    //         'lokasi_penyimpanan' => 'nullable|string|max:255',
    //         'gambar_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
    //     ]);

    //     $data = $request->except('gambar_barang');

    //     if ($request->hasFile('gambar_barang')) {
    //         if ($barang->gambar_barang) {
    //             Storage::delete('public/gambar_barang/' . $barang->gambar_barang);
    //         }

    //         $file = $request->file('gambar_barang');
    //         $fileName = time() . "-" . $file->getClientOriginalName();
    //         $file->storeAs('public/gambar_barang/', $fileName);


    //         $data['gambar_barang'] = $fileName;
    //     }

    //     $barang->update($data);

    //     return redirect()->route('barang.index')->with('success', 'data berhasil di perbaharui');
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(BarangIT $barang)
    // {
    //     //
    //     if ($barang->transaksiMasuks()->exists() || $barang->transaksiKeluars()->exists()) {
    //         return redirect()->route('barang.index')->with('error', 'Barang ini memiliki riwayat transaksi, tidak dapat dihapus');
    //     }

    //     if ($barang->gambar_barang) {
    //         Storage::delete('public/gambar_barang/' . $barang->gambar_barang);
    //     }

    //     $barang->delete();

    //     return redirect()->route('barang.index')->with('success', 'Data Berhasil di hapus');
    // }

    /**
     * Menangani ekspor data barang ke Excel.
     */
    public function exportExcel()
    {
        // 1. Tentukan nama file
        $namaFile = 'laporan_stok_barang_' . date('Y-m-d') . '.xlsx';

        // 2. Panggil "mesin" Excel untuk men-download
        return Excel::download(new BarangExport, $namaFile);
    }




    // =============================================
    // ====== METHOD UNTUK AJAX ====================
    // =============================================

    public function index()
    {
        // Ambil barang (untuk tabel)
        $barangs = BarangIT::with('kategori')->get();
        
        // Ambil kategori (UNTUK DROPDOWN DI MODAL)
        $kategoris = Kategori::all(); 

        return view('barang.index', compact('barangs', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_barang' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:barang_it',
            'stok_minimum' => 'required|integer|min:0',
            'kondisi' => 'required|in:Baru,Bekas,Rusak',
            'lokasi_penyimpanan' => 'nullable|string|max:255',
            'gambar_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        // Logika Upload Gambar
        if ($request->hasFile('gambar_barang')) {
            $file = $request->file('gambar_barang');
            $namaFile = time()."_".$file->getClientOriginalName();
            $file->storeAs('public/gambar_barang', $namaFile);
            $data['gambar_barang'] = $namaFile;
        }

        BarangIT::create($data);

        // --- RESPON AJAX ---
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Barang berhasil ditambahkan!',
            ]);
        }

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    // 1. EDIT AJAX
    public function edit(BarangIT $barang)
    {
        if (request()->ajax()) {
            return response()->json($barang);
        }
        // Fallback (jika tidak pakai ajax)
        $kategoris = Kategori::all();
        return view('barang.edit', compact('barang', 'kategoris'));
    }

    // 2. UPDATE AJAX (Dengan Logika Gambar)
    public function update(Request $request, BarangIT $barang)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_barang' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            // Unique ignore ID saat ini
            'serial_number' => 'nullable|string|max:255|unique:barang_it,serial_number,' . $barang->id,
            'stok_minimum' => 'required|integer|min:0',
            'kondisi' => 'required|in:Baru,Bekas,Rusak',
            'lokasi_penyimpanan' => 'nullable|string|max:255',
            'gambar_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except(['gambar_barang', 'stok']); // Stok tidak boleh diedit lewat sini

        // Logika Ganti Gambar
        if ($request->hasFile('gambar_barang')) {
            // Hapus gambar lama jika ada
            if ($barang->gambar_barang) {
                Storage::delete('public/gambar_barang/' . $barang->gambar_barang);
            }
            // Simpan gambar baru
            $file = $request->file('gambar_barang');
            $namaFile = time() . "_" . $file->getClientOriginalName();
            $file->storeAs('public/gambar_barang', $namaFile);
            $data['gambar_barang'] = $namaFile;
        }

        $barang->update($data);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data barang berhasil diperbarui!',
            ]);
        }

        return redirect()->route('barang.index')->with('success', 'Data berhasil diperbaharui');
    }

    // 3. DESTROY AJAX
    public function destroy(BarangIT $barang)
    {
        // Cek relasi (Proteksi)
        if ($barang->transaksiMasuks()->exists() || $barang->transaksiKeluars()->exists()){
            $pesan = 'Barang ini memiliki riwayat transaksi, tidak dapat dihapus!';
            if (request()->ajax()) {
                return response()->json(['status' => 'error', 'message' => $pesan], 422);
            }
            return redirect()->route('barang.index')->with('error', $pesan);
        }

        // Hapus Gambar
        if ($barang->gambar_barang) {
            Storage::delete('public/gambar_barang/' . $barang->gambar_barang);
        }

        $barang->delete();

        if (request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Barang berhasil dihapus!'
            ]);
        }

        return redirect()->route('barang.index')->with('success', 'Data Berhasil di hapus');
    }
}
