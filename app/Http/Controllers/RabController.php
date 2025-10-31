<?php

namespace App\Http\Controllers;

use App\Models\Rab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\RabDetail;

class RabController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $rabs = Rab::with(['pengaju', 'penyetuju'])->latest()->get();
        return view('rab.index', compact('rabs'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rab.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_dibuat' => 'required|date',
        ]);

        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 'Draft';

        $tanggal = Carbon::parse($validatedData['tanggal_dibuat']);
        $tahun = $tanggal->format('Y');
        $bulan = $tanggal->format('m');

        $nomorUrutTerakhir = Rab::whereYear('tanggal_dibuat', $tahun)
            ->whereMonth('tanggal_dibuat', $bulan)
            ->max('id');

        $nomorUrutBaru = $nomorUrutTerakhir ? $nomorUrutTerakhir + 1 : 1;

        $nomorUrutFormatted = str_pad($nomorUrutBaru, 3, '0', STR_PAD_LEFT);

        $validatedData['kode_rab'] = "RAB/{$tahun}/{$bulan}/{$nomorUrutFormatted}";

        Rab::create($validatedData);

        return redirect()->route('rab.index')->with('success', 'Rab Baru Berhasil Dibuat');
    }


    public function storeDetail(Request $request, Rab $rab)
    {
        // 1. Validasi input dari form
        $validatedData = $request->validate([
            'nama_barang_diajukan' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'perkiraan_harga_satuan' => 'required|integer|min:1',
            'ongkir' => 'nullable|integer|min:0',
            'asuransi' => 'nullable|integer|min:0',
        ]);

        // 2. Hitung total harga otomatis
        $jumlah = $validatedData['jumlah'];
        $harga_satuan = $validatedData['perkiraan_harga_satuan'];
        $ongkir = $request->input('ongkir', 0); // Ambil ongkir, default 0 jika null
        $asuransi = $request->input('asuransi', 0); // Ambil asuransi, default 0 jika null

        $validatedData['total_harga'] = ($jumlah * $harga_satuan) + $ongkir + $asuransi;

        // 3. Tambahkan rab_id
        $validatedData['rab_id'] = $rab->id;

        // 4. Simpan data ke tabel rab_details
        RabDetail::create($validatedData);

        // 5. Kembalikan ke halaman show dengan pesan sukses
        return redirect()->route('rab.show', $rab->id)
            ->with('success_detail', 'Item baru berhasil ditambahkan ke RAB!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rab $rab)
    {
        //
        $rab->load(['details', 'pengaju', 'penyetuju']);
        return view('rab.show', compact('rab'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rab $rab)
    {
        //
        return view('rab.edit', compact('rab'));
    }

    public function editDetail(RabDetail $rab_detail)
    {
        //
        if ($rab_detail->rab->status != 'Draft') {
            return redirect()->route('rab.show', $rab_detail->rab_id)
                ->with('error', 'Item tidak dapat diupdate karena RAB sudah ' . $rab_detail->rab->status . '!');
        }

        return view('rab_details.edit', compact('rab_detail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rab $rab)
    {
        //
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_dibuat' => 'required|date',
        ]);

        $rab->update($request->only(['judul', 'tanggal_dibuat']));

        return redirect()->route('rab.index')->with('success_edit', 'Rab Berhasil Diperbaharui');
    }

    public function updateDetail(Request $request, RabDetail $rab_detail)
    {

        if ($rab_detail->rab->status != 'Draft') {
            return redirect()->route('rab.show', $rab_detail->rab_id)
                ->with('error', 'Item tidak dapat diupdate karena RAB sudah ' . $rab_detail->rab->status . '!');
        }

        // 1. Validasi input dari form
        $validatedData = $request->validate([
            'nama_barang_diajukan' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'perkiraan_harga_satuan' => 'required|integer|min:1',
            'ongkir' => 'nullable|integer|min:0',
            'asuransi' => 'nullable|integer|min:0',
        ]);

        $jumlah = $validatedData['jumlah'];
        $harga = $validatedData['perkiraan_harga_satuan'];
        $ongkir = $request->input('ongkir', 0); // Ambil ongkir, default 0 jika null
        $asuransi = $request->input('asuransi', 0); // Ambil asuransi, default 0 jika null

        $validatedData['total_harga'] = ($jumlah * $harga) + $ongkir + $asuransi;

        $rab_detail->update($validatedData);

        return redirect()->route('rab.show', $rab_detail->rab_id)
            ->with('success_detail', 'Item RAB Berhasil Diperbaharui');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rab $rab)
    {
        //
        if ($rab->details()->exists()) {
            return redirect()->route('rab.index')->with('error', 'Rab tidak dapat dihapus karena memiliki item detail');
        }

        $rab->delete();

        return redirect()->route('rab.index')->with('success', 'Rab Berhasil Dihapus');
    }

    public function destroyDetail(RabDetail $rab_detail)
    {
        //
        $rab = $rab_detail->rab;

        if ($rab->status != 'Draft') {
            return redirect()->route('rab.show', $rab->id)
                ->with('error', 'Item tidak dapat dihapus karena RAB sudah ' . $rab->status . '!');
        }

        $rab_detail->delete();

        return redirect()->route('rab.show', $rab->id)
            ->with('success_detail', 'Data berhasil dihapus dari RAB!');
    }
}
