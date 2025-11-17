<?php

namespace App\Exports;

use App\Models\BarangIT;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // 1. Tambahkan ini
use Maatwebsite\Excel\Concerns\WithMapping; // 2. Tambahkan ini
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // 3. (Opsional) Biar lebar kolom otomatis

// 4. Tambahkan "jurus" baru
class BarangExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Ambil semua data barang, BERSERTA relasi kategori-nya
        return BarangIT::with('kategori')->get();
    }

    /**
     * Menentukan judul kolom (Header)
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Barang',
            'Kategori',
            'Merk',
            'Stok',
            'Stok Minimum',
            'Kondisi',
            'Lokasi',
            'Serial Number',
        ];
    }

    /**
     * Mengubah data per baris (memilih data yang mau diekspor)
     */
    public function map($barang): array
    {
        return [
            $barang->id,
            $barang->nama_barang,
            $barang->kategori->nama_kategori ?? 'N/A', // Ambil nama dari relasi
            $barang->merk,
            $barang->stok,
            $barang->stok_minimum,
            $barang->kondisi,
            $barang->lokasi_penyimpanan,
            $barang->serial_number,
        ];
    }
}