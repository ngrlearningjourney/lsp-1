@extends('template.dashboard')

@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" />  
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
@endpush

@section('content')
@if(session()->has('message'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>    {{ session('message') }} </strong>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="mt-3">
  <div class="title">
    <h2>Daftar Buku</h2>
  </div>
</div>
<div class="index mt-3">
  <div class="table-responsive small">
    <table class="table table-striped table-sm" id="tabel_pengembalian">
        <thead>
          <tr>
            <th scope="col">Id Transaksi</th>
            <th scope="col">Nama Pelanggan</th>
            <th scope="col">Nama Buku</th>
            <th scope="col">Tanggal Peminjaman</th>
            <th scope="col">Tanggal Wajib Pengembalian</th>
            <th scope="col">Tanggal Pengembalian</th>
            <th scope="col">Aksi</th>
          </tr>
        </thead>
    </table>
  </div>
</div>

<!-- modal hapus per id -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" id="form_action" method="post">
        @csrf
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Penghapusan</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h4 align="center">Apakah benar ingin melakukan penghapusan?</h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button class="btn btn-danger" type="submit">Hapus!</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function(){
      $('#tabel_pengembalian').DataTable({
        processing:true,
        serverSide:true,
        ajax:{
          url:"{{ route('fetch.pengembalian.buku') }}"
        },
        columns:[
          {
            data:'id_transaksi',
            name:'id_transaksi'
          },
          {
            data:'nama_pelanggan',
            name:'nama_pelanggan'
          },
          {
            data:'nama_buku',
            name:'nama_buku'
          },
          {
            data:'tanggal_awal_peminjaman',
            name:'tanggal_awal_peminjaman'
          },
          {
            data:'tanggal_akhir_peminjaman',
            name:'tanggal_pengembalian'
          },
          {
            data:'tanggal_pengembalian',
            name:'tanggal_pengembalian'
          },
          {
            data:'actions',
            name:'actions'
          },
        ]
      })
    })

    let id_transaksi_buku;
    $(document).on('click','.delete',function(){
      id_transaksi_buku = $(this).attr('id');
      $('#confirmModal').modal('show');
      let link = "/delete-pengembalian-buku/" + id_transaksi_buku;
      document.getElementById("form_action").setAttribute("action", link);
    })
</script>
@endpush