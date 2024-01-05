@extends('template.dashboard')

@section('content')
<div class="mt-3">
    <form action="{{ route('store.buku') }}" method="post">
        @csrf
        <div class="form-floating mb-3">
            <input type="text" name="nama_buku" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Nama Buku</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" name="deskripsi_buku" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Deskripsi Buku</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" name="jumlah_buku" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Jumlah Buku</label>
        </div>
        <div>
            <button class="btn btn-primary" type="submit">Tambah!</button>
        </div>
    </form>
</div>
@endsection