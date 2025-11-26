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
                        <div class="form-group">
                            <label for="barang_it_id">Pilih Nama Barang</label>
                            <div class="form-group">
                                <label for="barang_it_id">Pilih Nama Barang</label>
                                <select class="form-select mb-3" name="barang_it_id" id="barang_it_id" required>
                                    <option value=""> -- Pilih Nama Barang --</option>
                                    @foreach ($barangs as $item_barang)
                                    <option value="{{ $item_barang->id }}" {{ old('barang_it_id') == $item_barang->id ? 'selected' : '' }}>
                                        {{ $item_barang->nama_barang }} (Merk: {{ $item_barang->merk ?? '-' }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
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
            // Kita "tangkap" dulu semua elemen HTML yang akan kita pakai
            const rabIdDropdown = document.getElementById('rab_id');
            const rabDetailDropdown = document.getElementById('rab_detail_item');
            const rabItemsContainer = document.getElementById('rab-items-container');
            const jumlahInput = document.getElementById('jumlah_masuk');
            const hargaInput = document.getElementById('harga_satuan');

            // Kita buat "saluran telepon" (AJAX) untuk mengambil data
            function fetchRabDetails(rabId) {
                // Jika user memilih "-- Pilih RAB --" (kosong), kita sembunyikan lagi
                if (!rabId) {
                    rabItemsContainer.style.display = 'none';
                    rabDetailDropdown.innerHTML = '<option value="">-- Pilih Item untuk Auto-fill --</option>';
                    return;
                }

                // Siapkan URL "telepon"-nya. Kita ganti placeholder :id dengan ID rab yang asli
                let url = '{{ route("rab.getDetailsJson", ["rab" => ":id"]) }}';
                url = url.replace(':id', rabId);

                // Tampilkan "Loading..." selagi menelepon
                rabDetailDropdown.innerHTML = '<option value="">Loading...</option>';
                rabItemsContainer.style.display = 'block'; // Tampilkan panggungnya

                // Mulai "menelepon" ke server
                fetch(url)
                    .then(response => response.json()) // Ubah jawaban telepon (JSON) jadi data
                    .then(details => { // 'details' adalah array data yang dikirim dari controller
                        populateDetailsDropdown(details);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        rabDetailDropdown.innerHTML = '<option value="">Gagal memuat item</option>';
                    });
            }

            // Fungsi ini untuk mengisi dropdown "contekan"
            function populateDetailsDropdown(details) {
                // Kosongkan pilihan lama
                rabDetailDropdown.innerHTML = '<option value="">-- Pilih Item untuk Auto-fill --</option>';

                // Simpan semua data detail di dropdown (biar gampang diambil nanti)
                rabDetailDropdown.dataset.details = JSON.stringify(details);

                // Loop semua data detail barang dan buat <option> baru
                details.forEach(detail => {
                    const option = document.createElement('option');
                    option.value = detail.id;
                    // Tampilkan nama, jumlah, dan harga di teks pilihan
                    option.text = `${detail.nama_barang_diajukan} (Qty: ${detail.jumlah} @ Rp ${detail.perkiraan_harga_satuan})`;
                    rabDetailDropdown.appendChild(option);
                });
            }

            // === INI "SIHIR" TERAKHIRNYA ===
            // Saat kita memilih item dari dropdown "contekan"...
            rabDetailDropdown.addEventListener('change', function() {
                const selectedDetailId = this.value; // Ambil ID item yang dipilih

                // Jika memilih "-- Pilih Item --", kosongkan form
                if (!selectedDetailId) {
                    jumlahInput.value = '';
                    hargaInput.value = '';
                    return;
                }

                // Ambil data lengkap yang tadi kita simpan
                const details = JSON.parse(rabDetailDropdown.dataset.details);
                // Cari data detail yang ID-nya cocok
                const selectedDetail = details.find(d => d.id == selectedDetailId);

                if (selectedDetail) {
                    // "Sihir!" Masukkan data ke form!
                    jumlahInput.value = selectedDetail.jumlah;
                    hargaInput.value = selectedDetail.perkiraan_harga_satuan;
                }
            });

            // === INI "PEMICU"-NYA ===
            // Pemicu 1: Saat halaman ini dimuat, langsung cek apakah RAB sudah terpilih
            document.addEventListener('DOMContentLoaded', function() {
                const selectedRabId = rabIdDropdown.value;
                if (selectedRabId) {
                    // Jika sudah terpilih (karena kita datang dari rab.show),
                    // langsung "telepon" server untuk ambil datanya
                    fetchRabDetails(selectedRabId);
                }
            });

            // Pemicu 2: Saat user MENGGANTI pilihan di dropdown RAB
            rabIdDropdown.addEventListener('change', function() {
                // "Telepon" server untuk ambil data RAB yang baru dipilih
                fetchRabDetails(this.value);
            });
        </script>
        @endpush