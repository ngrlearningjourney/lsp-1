@extends('template.dashboard')

@section('content')
@if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong> Memasukkan Buku Gagal Dilakukan! </strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
@endif
<div class="mt-3">
    <form action="{{ route('store.buku') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-floating mb-3">
            <input type="text" name="nama_buku" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Nama Buku</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" name="deskripsi_buku" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Deskripsi Buku</label>
        </div>
        <div class="mb-3">
          <label for="formFile" class="form-label">Input dokumen maupun gambar yang berkaitan dengan buku</label>
          <input class="form-control" name="file" type="file" id="formFile">
        </div>
        <div>
            <button class="btn btn-primary w-100" type="submit">Tambah!</button>
        </div>
    </form>
</div>
@endsection