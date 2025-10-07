<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $Supplier = Supplier::all();
        return view('supplier.index', compact('Supplier'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'nomor_telepon' => 'required|string|max:15',
            'email' => 'nullable|email|max:255|unique:suppliers',
        ]);

        Supplier::create($request->all());

        return redirect()->route('supplier.index')->with('success', 'supplier berhasil ditambahkan');
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
    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'nomor_telepon' => 'required|string|max:15',
            'email' => 'nullable|email|max:255|unique:suppliers,email,'. $supplier->id,
        ]);

        $supplier->update($request->all());
        
        return redirect()->route('supplier.index')->with('success', 'Data berhasil di perbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
        $supplier->delete();

        return redirect()->route('supplier.index')->with('success', 'Data berhasil di hapus');
    }
}
