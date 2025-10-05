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
        $kategori = Kategori::all();
        return view('kategori.index', compact('kategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'kode_kategori' => 'required|string|max:50|unique:kategoris',
        ]);

        Kategori::create($request->all());

        return redirect()->route('kategori.index')->with('success', 'kategori sudah berhasil di tambahkan');
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
    public function edit(Kategori $kategori)
    {
        //
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        //

        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'kode_kategori' => 'required|string|max:50|unique:kategoris,kode_kategori,'. $kategori->id,
        ]);

        $kategori->update($request->all());
        return redirect()->route('kategori.index')->with('success', 'kategori berhasil di perbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        //
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'kategori berhasil di hapus');
    }
}
