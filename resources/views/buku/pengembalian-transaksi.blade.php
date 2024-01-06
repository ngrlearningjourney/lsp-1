@extends('template.dashboard')

@section('content')
<form action="/store-pengembalian-buku/{{ $id_transaksi_buku }}" method="post">
    @csrf
    <div class="mb-3 row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Nomor Transaksi:</label>
        <div class="col-sm-10">
            <input type="text" name="id_transaksi" readonly class="form-control-plaintext" id="staticEmail" value="{{ $id_transaksi }}">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Nama Buku:</label>
        <div class="col-sm-10">
            <input type="text" name="nama_buku" readonly class="form-control-plaintext" id="staticEmail" value="{{ $nama_buku }}">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Tanggal Peminjaman:</label>
        <div class="col-sm-10">
            <input type="text" name="tanggal_awal_peminjaman" readonly class="form-control-plaintext" id="staticEmail" value="{{ $tanggal_awal_peminjaman }}">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Tanggal Wajib Pengembalian:</label>
        <div class="col-sm-10">
            <input type="text" name="tanggal_akhir_peminjaman" readonly class="form-control-plaintext" id="staticEmail" value="{{ $tanggal_akhir_peminjaman }}">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Tanggal Pengembalian</label>
        <div class="col-sm-10">
            <input type="text" name="tanggal_hari_ini" readonly class="form-control-plaintext" id="staticEmail" value="{{ $tanggal_hari_ini }}">
        </div>
    </div>
    <div>
        <button class="btn btn-primary w-100" type="submit">Kembalikan!</button>
    </div>
</form>

@endsection