<!DOCTYPE html>
<html>
<head>
    <title>RAB {{ $rab->kode_rab }}</title>
    <style>
        @page { size: A4; margin: 1cm 2cm; }
        body { font-family: "Times New Roman", Times, serif; font-size: 12px; line-height: 1.2; color: #000; }
        
        .header-table { width: 100%; border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 10px; }
        .header-table td { vertical-align: middle; }
        .logo-cell { width: 100px; text-align: center; }
        .logo-cell img { width: 80px; height: auto; }
        .text-cell { text-align: center; }
        .text-cell h2 { margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase; }
        .text-cell h3 { margin: 0; font-size: 14px; font-weight: bold; text-transform: uppercase; }
        .text-cell p { margin: 2px 0; font-size: 11px; }
        
        .meta-info { margin-bottom: 20px; font-size: 12px; font-weight: bold; }
        .meta-table { width: 100%; border: none; }
        .meta-table td { padding: 2px 0; vertical-align: top; }
        .label-col { width: 100px; }
        .sep-col { width: 10px; }
        
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 5px; vertical-align: middle; }
        .data-table th { text-align: center; font-weight: bold; background-color: #fff; }
        .col-number { text-align: center; font-size: 10px; }
        
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        
        .signature-table { width: 100%; margin-top: 30px; page-break-inside: avoid; }
        .signature-table td { text-align: center; vertical-align: top; width: 50%; }
        .signature-space { height: 70px; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('dist/assets/img/logoKU.png') }}" alt="Logo">
            </td>
            <td class="text-cell">
                <h2>YAYASAN KERTHA USADA</h2>
                <h3>RUMAH SAKIT UMUM KERTHA USADA<br>SINGARAJA</h3>
                <p>Jl. Cendrawasih No. 5 - 7 Telp(0362) 26277. 26278 Fax(0362)</p>
                <p>22741Singaraja- Bali</p>
            </td>
        </tr>
    </table>

    <div class="meta-info">
        <table class="meta-table">
            <tr>
                <td class="label-col">PEKERJAAN</td>
                <td class="sep-col">:</td>
                <td>{{ $rab->judul }}</td>
            </tr>
            <tr>
                <td class="label-col">LOKASI</td>
                <td class="sep-col">:</td>
                <td>RSU KERTHA USADA</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Uraian Barang</th>
                <th width="15%">Foto</th>
                <th width="8%">Jumlah</th>
                <th width="15%">Harga Satuan</th>
                <th width="10%">Ongkir</th>
                <th width="10%">Asuransi pengiriman</th>
                <th width="15%">Sub total</th>
            </tr>
            <tr class="col-number">
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
                <td>8</td>
            </tr>
        </thead>
        <tbody>
            @php $totalKeseluruhan = 0; @endphp
            @forelse ($rab->details as $detail)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $detail->nama_barang_diajukan }}</td>
                    <td class="text-center">
                        <!-- Placeholder for image if available in future -->
                        @if(isset($detail->gambar) && $detail->gambar)
                            <img src="{{ public_path('storage/'.$detail->gambar) }}" style="width: 50px; height: auto;">
                        @endif
                    </td>
                    <td class="text-center">{{ $detail->jumlah }}</td>
                    <td class="text-end">Rp {{ number_format($detail->perkiraan_harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-end">{{ $detail->ongkir > 0 ? number_format($detail->ongkir, 0, ',', '.') : '-' }}</td>
                    <td class="text-end">{{ $detail->asuransi > 0 ? number_format($detail->asuransi, 0, ',', '.') : '-' }}</td>
                    <td class="text-end">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                </tr>
                @php $totalKeseluruhan += $detail->total_harga; @endphp
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada detail barang.</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="7" class="text-end" style="font-weight: bold;">TOTAL</td>
                <td class="text-end" style="font-weight: bold;">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            {{-- KOLOM DIREKTUR --}}
            <td width="50%">
                <br>
                Direktur RSU Kertha Usada
                
                <div class="signature-space" style="position: relative;">
                    @if($ttdDirektur)
                        {{-- Tampilkan hasil Base64 --}}
                        <img src="{{ $ttdDirektur }}" 
                             style="width: 50px; height: auto; position: absolute; top: 10px; left: 50%; transform: translateX(-50%);">
                    @endif
                </div>

                ( <strong>dr. I Wayan Parna Arianta, MARS</strong> )
            </td>
            {{-- KOLOM MANAJER --}}
            <td width="50%">
                Singaraja, {{ \Carbon\Carbon::parse($rab->direktur_at ?? now())->format('d F Y') }}<br>
                Direktur RSU Kertha Usada
                
                <div class="signature-space" style="position: relative;">
                    @if($ttdManager)
                        {{-- Tampilkan hasil Base64 --}}
                        <img src="{{ $ttdManager }}" 
                             style="width: 50px; height: auto; position: absolute; top: 10px; left: 50%; transform: translateX(-50%);">
                    @endif
                </div>

                ( <strong>dr. I Komang Heri Sukrastawan</strong> )
            </td>
        </tr>
    </table>
</body>
</html>