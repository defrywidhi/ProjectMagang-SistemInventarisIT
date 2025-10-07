<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangIT;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;

class BarangITController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $barang = BarangIT::all();

        return view('barang.index', compact('barang'));
    }

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
    public function store(Request $request)
    {
        //
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_barang' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:barang_it',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'kondisi' => 'required|in:Baru,Bekas,Rusak',
            'lokasi_penyimpanan' => 'nullable|string|max:255',
            'gambar_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar_barang')) {
            $file = $request->file('gambar_barang');
            $namaFile = time()."_".$file->getClientOriginalName();
            $file->storeAs('public/gambar_barang', $namaFile);
            $data['gambar_barang'] = $namaFile;
        }

        BarangIT::create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
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
    public function edit(BarangIT $barang)
    {
        //
        $kategoris = Kategori::all();

        return view('barang.edit', compact('barang','kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangIT $barang)
    {
        //
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_barang' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:barang_it,serial_number,'. $barang->id,
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'kondisi' => 'required|in:Baru,Bekas,Rusak',
            'lokasi_penyimpanan' => 'nullable|string|max:255',
            'gambar_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('gambar_barang');

        if($request->hasFile('gambar_barang')){
            if ($barang->gambar_barang){
            Storage::delete('public/gambar_barang/'. $barang->gambar_barang);
            }
            
            $file = $request->file('gambar_barang');
            $fileName = time()."-".$file->getClientOriginalName();
            $file->storeAs('public/gambar_barang', $fileName);


            $data['gambar_barang'] = $fileName;
        }

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'data berhasil di perbaharui');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangIT $barang)
    {
        //
        if ($barang->gambar_barang) {
            Storage::delete('public/gambar_barang/' . $barang->gambar_barang);
        }

        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Data Berhasil di hapus');
    }
}
