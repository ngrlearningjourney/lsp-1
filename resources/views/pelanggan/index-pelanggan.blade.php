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
<div class="mt-3 d-flex justify-content-between">
  <div class="title">
    <h2>Daftar Pelanggan</h2>
  </div>
  <div>
    <a href="{{ route('create.pelanggan') }}" class="btn btn-primary">Tambah Pelanggan</a>
  </div>
</div>
<div class="index mt-3">
  <div class="table-responsive small">
    <table class="table table-striped table-sm" id="tabel_pelanggan">
        <thead>
          <tr>
            <th scope="col">Nama Pelanggan</th>
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
      $('#tabel_pelanggan').DataTable({
        processing:true,
        serverSide:true,
        ajax:{
          url:"{{ route('fetch.pelanggan') }}"
        },
        columns:[
          {
            data:'nama_pelanggan',
            name:'nama_pelanggan'
          },
          {
            data:'actions',
            name:'actions'
          }
        ]
      })
    })

    let id_pelanggan;
    $(document).on('click','.delete',function(){
      id_pelanggan = $(this).attr('id');
      $('#confirmModal').modal('show');
      let link = "/hapus-pelanggan/" + id_pelanggan;
      document.getElementById("form_action").setAttribute("action", link);
    })
  </script>
@endpush