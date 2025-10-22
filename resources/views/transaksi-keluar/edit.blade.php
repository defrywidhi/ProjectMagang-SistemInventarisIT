@extends ('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h1>Edit Transaksi Keluar</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('transaksi-keluar.update', $transaksi_keluar->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="barang_it_id">Barang Yang Keluar</label>
                    <select class="form-control" id="barang_it_id" name="barang_it_id" required>
                        @foreach($barangs as $item)
                        <option value="{{ $item->id }}" {{ $item->id == $transaksi_keluar->barang_it_id ? 'selected' : '' }}>{{ $item->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="jumlah_keluar">Jumlah Barang Keluar</label>
                    <input type="number" class="form-control" id="jumlah_keluar" name="jumlah_keluar" required value="{{ $transaksi_keluar->jumlah_keluar }}">
                </div>
                <div class="form-group">
                    <label for="tanggal_keluar">Tanggal Keluar</label>
                    <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" required value="{{ $transaksi_keluar->tanggal_keluar }}">
                </div>
                <div class="form-group">
                    <label for="keterangan">keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ $transaksi_keluar->keterangan }}</textarea>
                </div>
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection