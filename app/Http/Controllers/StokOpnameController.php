<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokOpname;
use App\Models\BarangIT;
use App\Models\StokOpnameDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class StokOpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $stokOpnames = StokOpname::with(['auditor'])->latest()->get();

        return view('stok-opname.index', compact('stokOpnames'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('stok-opname.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $validatedData = $request->validate([
            'tanggal_opname' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        // 2. Ambil semua data barang yang ada saat ini
        $barangs = BarangIT::all();

        // Cek jika tidak ada barang, jangan buat sesi
        if ($barangs->isEmpty()) {
            return redirect()->route('stok-opname.index')->with('error', 'Gagal memulai sesi. Belum ada data barang di sistem.');
        }

        // 3. Kita pakai "Database Transaction"
        // Ini jurus aman: Jika ada 1 saja error, semua dibatalkan.
        try {
            DB::beginTransaction();

            // 4. Buat Sesi Stok Opname (Induk)
            $stokOpname = StokOpname::create([
                'tanggal_opname' => $validatedData['tanggal_opname'],
                'catatan' => $validatedData['catatan'],
                'user_id' => Auth::id(),
                'status' => 'Pending', // Status awal 'Pending'
            ]);

            // 5. "Sihir Snapshot": Loop semua barang dan copy stoknya
            foreach ($barangs as $barang) {
                StokOpnameDetail::create([
                    'stok_opname_id' => $stokOpname->id,
                    'barang_it_id' => $barang->id,
                    'stok_sistem' => $barang->stok, // <--- Ini intinya! Meng-copy stok sistem saat ini
                    'stok_fisik' => 0, // Default 0, akan diisi manual
                    'selisih' => -$barang->stok, // Selisih awal = 0 - stok sistem
                ]);
            }

            // 6. Jika semua berhasil, simpan permanen
            DB::commit();

            // 7. Arahkan ke halaman "show" (detail) untuk mulai mengisi
            return redirect()->route('stok-opname.show', $stokOpname->id)
                ->with('success', 'Sesi stok opname baru berhasil dibuat. Silakan mulai input stok fisik.');
        } catch (\Exception $e) {
            // 8. Jika ada 1 saja error, batalkan semua
            DB::rollBack();
            return redirect()->route('stok-opname.index')
                ->with('error', 'Terjadi kesalahan! Gagal membuat sesi stok opname. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StokOpname $stokOpname)
    {
        //
        $stokOpname->load(['details', 'auditor']);
        return view('stok-opname.show', compact('stokOpname'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // Method untuk MENAMPILKAN form edit header
    public function edit(StokOpname $stokOpname)
    {
        // Hanya bisa edit jika 'Pending'
        if ($stokOpname->status != 'Pending') {
            return redirect()->route('stok-opname.index')->with('error', 'Sesi yang sudah selesai tidak bisa diedit.');
        }
        return view('stok-opname.edit', compact('stokOpname'));
    }

    // Method untuk MENYIMPAN edit header
    public function update(Request $request, StokOpname $stokOpname)
    {
        // Hanya bisa update jika 'Pending'
        if ($stokOpname->status != 'Pending') {
            return redirect()->route('stok-opname.index')->with('error', 'Sesi yang sudah selesai tidak bisa diedit.');
        }

        $validatedData = $request->validate([
            'tanggal_opname' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $stokOpname->update($validatedData);

        return redirect()->route('stok-opname.index')->with('success', 'Data sesi opname berhasil diupdate.');
    }

    // GANTI NAMA method 'update' yang lama menjadi 'saveDetails'
    public function saveDetails(Request $request, StokOpname $stokOpname)
    {
        // 1. Validasi: Pastikan status masih 'Pending'
        if ($stokOpname->status != 'Pending') {
            return redirect()->route('stok-opname.show', $stokOpname->id)
                ->with('error', 'Gagal! Sesi stok opname ini sudah selesai.');
        }

        // 2. Validasi input (array)
        $request->validate([
            'stok_fisik' => 'required|array',
            'stok_fisik.*' => 'required|integer|min:0',
            'keterangan_item' => 'nullable|array',
            'keterangan_item.*' => 'nullable|string|max:255',
        ]);

        // 3. Database Transaction (sudah benar)
        try {
            DB::beginTransaction();

            // 4. Loop dan update detail (sudah benar)
            foreach ($request->stok_fisik as $detail_id => $stok_fisik) {
                $detail = StokOpnameDetail::find($detail_id);
                if ($detail) {
                    $stok_sistem = $detail->stok_sistem;
                    $selisih = $stok_fisik - $stok_sistem;
                    $detail->update([
                        'stok_fisik' => $stok_fisik,
                        'selisih' => $selisih,
                        'keterangan_item' => $request->keterangan_item[$detail_id] ?? null,
                    ]);
                }
            }

            // 5. Update status induk (sudah benar)
            $stokOpname->status = 'Selesai';
            $stokOpname->save();

            DB::commit();

            return redirect()->route('stok-opname.index')
                ->with('success', 'Hasil stok opname berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('stok-opname.show', $stokOpname->id)
                ->with('error', 'Terjadi kesalahan! Gagal menyimpan hasil. ' . $e->getMessage());
        }
    }

    // Method destroy (sudah benar)
    public function destroy(StokOpname $stokOpname)
    {
        // 6. Logika pencegahan hapus JIKA SUDAH SELESAI
        if ($stokOpname->status == 'Selesai') {
            return redirect()->route('stok-opname.index')->with('error', 'Sesi yang sudah selesai tidak bisa dihapus.');
        }

        // ... (sisa logika delete sudah benar) ...
        $stokOpname->delete();
        return redirect()->route('stok-opname.index')->with('success', 'Sesi stok opname berhasil dihapus.');
    }
}
