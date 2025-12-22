<?php

namespace App\Exports;

use App\Models\TransaksiMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransaksiMasukExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Ambil semua transaksi, BERSERTA relasi ke barang, supplier, user, dan rab
        return TransaksiMasuk::with(['barang_it', 'supplier', 'user', 'rab'])->get();
    }

    /**
     * Menentukan judul kolom (Header)
     */
    public function headings(): array
    {
        return [
            'Tanggal Masuk',
            'Nama Barang',
            'Supplier',
            'Jumlah',
            'Harga Satuan',
            'Total Harga',
            'Kode RAB',
            'Diinput Oleh',
            'Keterangan',
        ];
    }

    /**
     * Mengubah data per baris
     */
    public function map($transaksi): array
    {
        return [
            $transaksi->tanggal_masuk,
            $transaksi->barang_it->nama_barang ?? 'N/A', // Ambil nama dari relasi
            $transaksi->supplier->nama_supplier ?? 'N/A', // Ambil nama dari relasi
            $transaksi->jumlah_masuk,
            $transaksi->harga_satuan,
            ($transaksi->jumlah_masuk * $transaksi->harga_satuan), // Hitung total
            $transaksi->rab->kode_rab ?? '-', // Ambil kode dari relasi
            $transaksi->user->name ?? 'N/A', // Ambil nama dari relasi
            $transaksi->keterangan,
        ];
    }
}