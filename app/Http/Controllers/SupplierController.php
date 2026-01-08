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
        $suppliers = Supplier::all();
        return view('supplier.index', compact('suppliers'));
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
    // public function store(Request $request)
    // {
    //     //
    //     $request->validate([
    //         'nama_supplier' => 'required|string|max:255',
    //         'alamat' => 'required|string|max:500',
    //         'nomor_telepon' => 'required|string|max:15',
    //         'email' => 'nullable|email|max:255|unique:suppliers',
    //     ]);

    //     Supplier::create($request->all());

    //     return redirect()->route('supplier.index')->with('success', 'supplier berhasil ditambahkan');
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
    // public function edit(Supplier $supplier)
    // {
    //     return view('supplier.edit', compact('supplier'));
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Supplier $supplier)
    // {
    //     $request->validate([
    //         'nama_supplier' => 'required|string|max:255',
    //         'alamat' => 'required|string|max:500',
    //         'nomor_telepon' => 'required|string|max:15',
    //         'email' => 'nullable|email|max:255|unique:suppliers,email,'. $supplier->id,
    //     ]);

    //     $supplier->update($request->all());
        
    //     return redirect()->route('supplier.index')->with('success', 'Data berhasil di perbaharui');
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Supplier $supplier)
    // {
    //     //
    //     if($supplier->transaksiMasuks()->exists()){
    //         return redirect()->route('supplier.index')->with('error', 'Data tidak dapat dihapus karena memiliki transaksi masuk');
    //     }

    //     $supplier->delete();

    //     return redirect()->route('supplier.index')->with('success', 'Data berhasil di hapus');
    // }



    // =========================================
    // ========  METHOD UNTUK AJAX ========
    // =========================================
    public function store(Request $request)
    {
        // 1. Validasi (Tetap sama)
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'nomor_telepon' => 'required|string|max:15',
            'email' => 'nullable|email|max:255|unique:suppliers',
        ]);

        // 2. Simpan Data
        Supplier::create($request->all());

        // 3. --- LOGIKA BARU (AJAX) ---
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Supplier berhasil ditambahkan!',
            ]);
        }

        // Fallback (Redirect biasa)
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan');
    }

    // 1. EDIT AJAX
    public function edit(Supplier $supplier)
    {
        if (request()->ajax()) {
            return response()->json($supplier);
        }
        return view('supplier.edit', compact('supplier'));
    }

    // 2. UPDATE AJAX
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'nomor_telepon' => 'required|string|max:15',
            'email' => 'nullable|email|max:255|unique:suppliers,email,'.$supplier->id,
        ]);

        $supplier->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data supplier berhasil diperbarui!',
            ]);
        }

        return redirect()->route('supplier.index')->with('success', 'Data berhasil di perbaharui');
    }

    // 3. DESTROY AJAX
    public function destroy(Supplier $supplier)
    {
        // Cek relasi (jika ada transaksi)
        if ($supplier->transaksiMasuks()->exists()) {
            if (request()->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menghapus! Supplier ini memiliki riwayat transaksi.'
                ], 422);
            }
            return redirect()->route('supplier.index')->with('error', 'Gagal menghapus! Supplier ini memiliki riwayat transaksi.');
        }

        $supplier->delete();

        if (request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Supplier berhasil dihapus!'
            ]);
        }

        return redirect()->route('supplier.index')->with('success', 'Data berhasil di hapus');
    }
}
