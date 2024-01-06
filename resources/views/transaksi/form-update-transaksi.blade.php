@extends('template.dashboard')

@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" />  
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
@endpush

@section('content')
<div class="mt-3">
@if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong> Mengubah Transaksi Gagal Dilakukan! </strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
@endif
@if(session()->has('message'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>    {{ session('message') }} </strong>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
    <form action="/update-transaksi/{{ $id_transaksi }}" method="post">
        @csrf
        <div class="mb-3 row" style="display: none;">
            <label for="staticEmail" class="col-sm-2 col-form-label">Nama Pelanggan:</label>
            <div class="col-sm-10">
                <input type="text" name="id_pelanggan" readonly class="form-control-plaintext" id="staticEmail" value="{{ $id_pelanggan }}">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Nama Pelanggan:</label>
            <div class="col-sm-10">
                <input type="text" name="nama_pelanggan" readonly class="form-control-plaintext" id="staticEmail" value="{{ $nama_pelanggan }}">
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
        <div class="mt-3">
            <div class="title">
                <h2>Daftar Buku</h2>
            </div>
        </div>
        <div class="index mt-3">
            <div class="table-responsive small">
                <table class="table table-striped table-sm" id="tabel_buku">
                    <thead>
                    <tr>
                        <th scope="col">Nama Buku</th>
                        <th scope="col">Deskripsi Buku</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="form-floating mt-3">
            <textarea class="form-control" name="deskripsi_transaksi" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px">{{ $deskripsi_transaksi }}</textarea>
            <label for="floatingTextarea2">Deskripsi Transkasi</label>
        </div>
        <div class="mt-3">
            <button class="btn btn-primary w-100" type="submit">Tambah!</button>
        </div>
    </form>
</div>
@endsection

@push('js')
<script>
  let test = "{{ $id_transaksi }}";
  $('#tabel_buku').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:"{{ route('fetch.edit.transaksi.buku') }}",
        data:{
            data : test
        }
    },
    columns:[
        {
            data:'nama_buku',
            name:'nama_buku'
        },
        {
            data:'deskripsi_buku',
            name:'deskripsi_buku'
        },
        {
            data:'actions',
            name:'actions',
            orderable:true
        }
    ],
    order:[[1,'desc']]
  })
</script>
@endpush