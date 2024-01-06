@if($tanggal_pengembalian == null)
    <a href="{{ $href }}" class="btn btn-primary">Mengembalikan</a>
@else
    <button type="button" id="{{ $id_membatalkan }}" class="btn btn-danger delete">Membatalkan</a>
@endif