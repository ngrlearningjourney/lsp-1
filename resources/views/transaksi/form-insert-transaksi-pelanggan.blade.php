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
  <div class="title">
    <h2>Daftar Pelanggan</h2>
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
@endsection

@push('js')
  <script>
    $(document).ready(function(){
      $('#tabel_pelanggan').DataTable({
        processing:true,
        serverSide:true,
        ajax:{
          url:"{{ route('fetch.transaksi.pelanggan') }}"
        },
        columns:[
          {
            data:'nama_pelanggan',
            name:'nama_pelanggan'
          },
          {
            data:'action',
            data:'action'
          }
        ]
      })
    })
  </script>
@endpush