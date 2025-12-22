@extends ('layouts.master')

@section('title', 'Transaksi Masuk')
@section('content_title', 'Transaksi Masuk')


@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Formulir Transaksi Masuk</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('transaksi-masuk.store') }}" method="post">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="barang_it_id">Pilih Barang</label>
                            <select class="form-select @error('barang_it_id') is-invalid @enderror" name="barang_it_id" id="barang_it_id" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach ($barangs as $item_barang)
                                    <option value="{{ $item_barang->id }}" {{ old('barang_it_id') == $item_barang->id ? 'selected' : '' }}>
                                        {{-- Format Tampilan: Nama Barang (Merk) - [Kondisi] - Stok: 10 --}}
                                        {{ $item_barang->nama_barang }} 
                                        ({{ $item_barang->merk ?? '-' }}) 
                                        - [{{ $item_barang->kondisi }}] 
                                        - Stok: {{ $item_barang->stok }}
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_it_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="supplier_id">Pilih Supplier Barang</label>
                            <select name="supplier_id" id="supplier_id" class="mb-3 form-select" required>
                                <option value=""> -- Pilih Supplier Barang --</option>
                                @foreach ($suppliers as $item_supplier)
                                <option value="{{ $item_supplier->id }}" {{ old('supplier_id') == $item_supplier->id ? 'selected' : '' }}>
                                    {{ $item_supplier->nama_supplier }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="rab_id">Asal RAB (Opsional)</label>
                            <select class="form-control mb-3" name="rab_id" id="rab_id">
                                <option value="">Pilih RAB (Jika Ada)</option>
                                @foreach ($rabs as $rab)
                                <option value="{{ $rab->id }}" {{ old('rab_id', $selected_rab_id ?? '') == $rab->id ? 'selected' : '' }}>
                                    {{ $rab->kode_rab }} - {{ $rab->judul }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Tempat Mengambil data JSON untuk mengisi data otomatis --}}
                        <div class="form-group" id="rab-items-container" style="display: none;"> {{-- Awalnya tersembunyi --}}
                            <label for="rab_detail_item">Pilih Item dari RAB (Contekan)</label>
                            <select class="form-control mb-3" id="rab_detail_item">
                                <option value="">Pilih barang sesuai di RAB</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_masuk">Masukkan Jumlah Barang</label>
                            <input value="{{ old('jumlah_masuk') }}" class="form-control mb-3 @error('jumlah_masuk') is-invalid @enderror" type="number" name="jumlah_masuk" id="jumlah_masuk" required>
                            @error('jumlah_masuk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tanggal_masuk">Tanggal Barang Masuk</label>
                            <input value="{{ old('tanggal_masuk', date('Y-m-d')) }}" class="form-control mb-3 @error('tanggal_masuk') is-invalid @enderror" type="date" name="tanggal_masuk" id="tanggal_masuk" required>
                            @error('tanggal_masuk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="harga_satuan">Masukkan Harga Satuan Barang</label>
                            <input value="{{ old('harga_satuan') }}" class="form-control mb-3 @error('harga_satuan') is-invalid @enderror" type="number" name="harga_satuan" id="harga_satuan" required>
                            @error('harga_satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan Barang</label>
                            <input value="{{ old('keterangan') }}" class="form-control mb-3 @error('keterangan') is-invalid @enderror" type="text" name="keterangan" id="keterangan">
                            @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('transaksi-masuk.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @endsection

        
@push('scripts')
<script>
    // 1. Tangkap Elemen HTML
    const rabIdDropdown = document.getElementById('rab_id');
    const rabDetailDropdown = document.getElementById('rab_detail_item');
    const rabItemsContainer = document.getElementById('rab-items-container');
    const jumlahInput = document.getElementById('jumlah_masuk');
    const hargaInput = document.getElementById('harga_satuan');
    const barangDropdown = document.getElementById('barang_it_id'); // Dropdown Master Barang

    // 2. Fungsi Fetch Data RAB
    function fetchRabDetails(rabId) {
        if (!rabId) {
            rabItemsContainer.style.display = 'none';
            rabDetailDropdown.innerHTML = '<option value="">-- Pilih Item untuk Auto-fill --</option>';
            return;
        }

        // URL Route (Pastikan route ini ada di web.php)
        let url = '{{ route("rab.getDetailsJson", ["rab" => ":id"]) }}';
        url = url.replace(':id', rabId);

        rabDetailDropdown.innerHTML = '<option value="">Loading...</option>';
        rabItemsContainer.style.display = 'block';

        fetch(url)
            .then(response => response.json())
            .then(details => {
                populateDetailsDropdown(details);
            })
            .catch(error => {
                console.error('Error:', error);
                rabDetailDropdown.innerHTML = '<option value="">Gagal memuat item</option>';
            });
    }

    // 3. Fungsi Isi Dropdown Contekan
    function populateDetailsDropdown(details) {
        rabDetailDropdown.innerHTML = '<option value="">-- Pilih Item dari RAB --</option>';
        rabDetailDropdown.dataset.details = JSON.stringify(details); // Simpan data di atribut

        details.forEach(detail => {
            const option = document.createElement('option');
            option.value = detail.id;
            // Tampilkan info lengkap di dropdown
            option.text = `${detail.nama_barang_diajukan} (Qty: ${detail.jumlah})`;
            rabDetailDropdown.appendChild(option);
        });
    }

    // 4. Event Listener saat memilih Item Contekan
    rabDetailDropdown.addEventListener('change', function() {
        const selectedDetailId = this.value;
        if (!selectedDetailId) return;

        const details = JSON.parse(rabDetailDropdown.dataset.details);
        const selectedDetail = details.find(d => d.id == selectedDetailId);

        if (selectedDetail) {
            // A. Auto-Fill Jumlah & Harga
            jumlahInput.value = selectedDetail.jumlah;
            hargaInput.value = selectedDetail.perkiraan_harga_satuan;

            // B. Auto-Select Barang Master (FITUR BARU)
            // Kita cari option di dropdown barang utama yang value-nya sama dengan detail.barang_it_id
            if (selectedDetail.barang_it_id) {
                // Set value dropdown barang
                barangDropdown.value = selectedDetail.barang_it_id;
                
                // Trigger event change (siapa tahu ada script lain yg nunggu event ini)
                barangDropdown.dispatchEvent(new Event('change'));

                // Visual Feedback (Opsional): Kasih border hijau sebentar biar user tahu
                barangDropdown.classList.add('border-success');
                setTimeout(() => barangDropdown.classList.remove('border-success'), 1000);
            } else {
                // Warning kalau ternyata barangnya belum dikonversi (Jaga-jaga)
                alert("Barang ini belum terhubung ke Master Data. Silakan cek kembali konversi barang.");
            }
        }
    });

    // 5. Inisialisasi Awal
    document.addEventListener('DOMContentLoaded', function() {
        const selectedRabId = rabIdDropdown.value;
        if (selectedRabId) {
            fetchRabDetails(selectedRabId);
        }
    });

    // 6. Event Ganti RAB
    rabIdDropdown.addEventListener('change', function() {
        fetchRabDetails(this.value);
        // Reset form input kalau ganti RAB
        jumlahInput.value = '';
        hargaInput.value = '';
        barangDropdown.value = ''; 
    });
</script>
@endpush