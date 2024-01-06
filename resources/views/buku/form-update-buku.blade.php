@extends('template.dashboard')

@section('content')
<div class="mt-3">
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>    {{ session('message') }} </strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong> Mengubah Buku Gagal Dilakukan! </strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <form action="/update-buku/{{ $id_buku }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-floating mb-3">
            <input type="text" name="nama_buku" class="form-control" id="floatingInput" placeholder="name@example.com" value="{{ $nama_buku }}">
            <label for="floatingInput">Nama Buku</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" name="deskripsi_buku" class="form-control" id="floatingInput" placeholder="name@example.com" value="{{ $deskripsi_buku }}">
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
    @if(count($array_file_bukus) > 0)
            @foreach($array_file_bukus as $array_file_buku)
            <div class="alert alert-dark d-flex justify-content-between align-items-center mt-2" role="alert">
                <img src="/assets/{{ $array_file_buku->file }}" alt="" srcset="" width="200px" height="200px">

                <h6>{{ $array_file_buku->file }}</h2>
                
                <form action="/hapus-gambar/{{ $array_file_buku->id_file }}" method="post">
                    @csrf
                    <div class="mb-3 row" style="display: none;">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Nama Pelanggan:</label>
                        <div class="col-sm-10">
                            <input type="text" name="id_buku" readonly class="form-control-plaintext" id="staticEmail" value="{{ $id_buku }}">
                        </div>
                    </div>
                    <button class="btn btn-danger" type="submit">Hapus!</button>
                </form>
            </div>
            @endforeach
    @endif
</div>

@endsection