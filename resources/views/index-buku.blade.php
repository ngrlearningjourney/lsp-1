@extends('template.dashboard')

@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" />  
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
@endpush

@section('content')
<div class="mt-3 d-flex justify-content-between">
  <div class="title">
    <h2>Daftar Buku</h2>
  </div>
  <div>
    <a href="{{ route('create.buku') }}" class="btn btn-primary">Tambah Buku</a>
  </div>
</div>
<div class="index mt-3">
  <div class="table-responsive small">
    <table class="table table-striped table-sm" id="tabel_buku">
        <thead>
          <tr>
            <th scope="col">Nama Buku</th>
            <th scope="col">Deskripsi Buku</th>
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
      $('#tabel_buku').DataTable({
        processing:true,
        serverSide:true,
        ajax:{
          url:"{{ route('fetch.buku') }}"
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
            name:'actions'
          }
        ]
      })
    })

    let id_buku;
    $(document).on('click','.delete',function(){
      id_buku = $(this).attr('id');
      $('#confirmModal').modal('show');
      let link = "/hapus-buku/" + id_buku;
      document.getElementById("form_action").setAttribute("action", link);
    })

    
  </script>
@endpush