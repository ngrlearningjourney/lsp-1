@extends('template.dashboard')

@section('content')
<div class="mt-3">
    <form action="/update-pelanggan/{{ $id_pelanggan }}" method="post">
        @csrf
        <div class="form-floating mb-3">
            <input type="text" name="nama_pelanggan" class="form-control" id="floatingInput" value="{{ $nama_pelanggan }}" placeholder="name@example.com">
            <label for="floatingInput">Nama Pelanggan</label>
        </div>
        <div>
            <button class="btn btn-primary" type="submit">Ubah!</button>
        </div>
    </form>
</div>
@endsection