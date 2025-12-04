<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $kategoris = Kategori::all();
        return view('kategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('kategori.create');
    }

    // /**
    //  * Store tanpa ajax.
    //  */
    // public function store(Request $request)
    // {
    //     //
    //     $request->validate([
    //         'nama_kategori' => 'required|string|max:255',
    //         'kode_kategori' => 'required|string|max:50|unique:kategoris',
    //     ]);

    //     Kategori::create($request->all());

    //     return redirect()->route('kategori.index')->with('success', 'kategori sudah berhasil di tambahkan');
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
    // public function edit(Kategori $kategori)
    // {
    //     //
    //     return view('kategori.edit', compact('kategori'));
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, Kategori $kategori)
    // {
    //     //

    //     $request->validate([
    //         'nama_kategori' => 'required|string|max:255',
    //         'kode_kategori' => 'required|string|max:50|unique:kategoris,kode_kategori,'. $kategori->id,
    //     ]);

    //     $kategori->update($request->all());
    //     return redirect()->route('kategori.index')->with('success', 'kategori berhasil di perbaharui');
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Kategori $kategori)
    // {
    //     //
    //     if($kategori->barangs()->exists()){
    //         return redirect()->route('kategori.index')->with('error', 'Data tidak dapat dihapus karena memiliki barang');
    //     }

    //     $kategori->delete();

    //     return redirect()->route('kategori.index')->with('success', 'kategori berhasil di hapus');
    // }







    // Function CRUD With AJAX ============================

    public function store(Request $request)
    {
    // 1. Validasi (Tetap Sama)
    $request->validate([
        'nama_kategori' => 'required|string|max:255',
        'kode_kategori' => 'required|string|max:50|unique:kategoris',
    ]);

    // 2. Simpan Data (Tetap Sama)
    Kategori::create($request->all());

    // 3. --- LOGIKA BARU DI SINI ---
    // Cek apakah request datang dari AJAX?
    if ($request->ajax()) {
        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil ditambahkan!',
        ]);
    }

    // Kalau bukan AJAX (fallback), lakukan redirect biasa
    return redirect()->route('kategori.index')->with('success', 'kategori sudah berhasil di tambahkan');
    
    }

    // ...

    // 1. EDIT: Untuk mengambil data kategori yang mau diedit (diisi ke modal)
    public function edit(Kategori $kategori)
    {
    // Jika dipanggil lewat AJAX (saat tombol edit diklik), kirim datanya saja
    if (request()->ajax()) {
        return response()->json($kategori);
    }
    
    // Fallback: kalau dibuka lewat browser biasa (opsional)
    return view('kategori.edit', compact('kategori'));
    }

    // 2. UPDATE: Untuk menyimpan perubahan
    public function update(Request $request, Kategori $kategori)
    {
    $request->validate([
        'nama_kategori' => 'required|string|max:255',
        'kode_kategori' => 'required|string|max:50|unique:kategoris,kode_kategori,'. $kategori->id,
    ]);

    $kategori->update($request->all());

    // Jika AJAX, kirim JSON sukses
    if ($request->ajax()) {
        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil diperbarui!',
        ]);
    }

    return redirect()->route('kategori.index')->with('success', 'kategori berhasil di perbaharui');
    }

    // 3. DESTROY: Untuk menghapus via AJAX
    public function destroy(Kategori $kategori)
    {
    // Cek relasi dulu (sama seperti sebelumnya)
    if ($kategori->barangs()->exists()) {
        // Jika AJAX, kirim error JSON (kode 422 Unprocessable Entity)
        if (request()->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus! Kategori ini masih digunakan oleh data barang.'
            ], 422);
        }
        return redirect()->route('kategori.index')->with('error', '...');
    }

    $kategori->delete();

    // Jika AJAX, kirim sukses
    if (request()->ajax()) {
        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil dihapus!'
        ]);
    }

    return redirect()->route('kategori.index')->with('success', 'kategori berhasil di hapus');
    }
}
