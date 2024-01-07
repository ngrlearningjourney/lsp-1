<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index_pelanggan(){
        // menunjukkan halaman index pelanggan
        return view('/pelanggan/index-pelanggan');
    }

    public function fetch_pelanggan(){
        // menagmbil data pelanggan dari database guna di masukkan ke dalam datatable

        // method ini juga akan menambahkan satu kolom pada datatable yang berisikan tombol edit dan delete pengguna
        $pelanggans = Pelanggan::where('hapus_pelanggan',0)->get();
        return datatables()::of($pelanggans)
        ->addColumn('actions','
        <div class="d-flex">
            <a href="/edit-pelanggan/{{ $id }}" class="btn btn-primary me-2">Ubah</a>
            <button type="button" id="{{ $id }}" class="btn btn-danger delete">Hapus</button>
        </div>
        ')
        ->rawColumns(['actions'])
        ->addIndexColumn()
        ->make(true);
    }

    public function create_pelanggan(){
        //method yang bertujuan menunjuuakan form menambahkan pelanggan
        return view('/pelanggan/form-insert-pelanggan',[
            "slug" => "pelanggan"
        ]);
    }

    public function store_pelanggan(Request $request){

        // method yang memasukkan nama pelanggan ke dalam database pelanggan
        $request->validate([
            'nama_pelanggan' => 'required | min:10 | max:225',
        ]);

        Pelanggan::create([
            "nama_pelanggan" => $request->nama_pelanggan,
            "hapus_pelanggan" => 0,
        ]);

        return redirect('/index-pelanggan')->with('message','berhasil memasukkan pelanggan');
    }

    public function delete_pelanggan($id){
        // method yang digunakan untuk menghapus pelanggan
        Pelanggan::find($id)->update([
            'hapus_pelanggan' => 1
        ]);

        return redirect('/index-pelanggan')->with('message','Pelanggan berhasil dihapus');
    }

    public function edit_pelanggan($id){
        // method yang digunakan untuk menampilkan form update pelanggan
        $pelanggan = Pelanggan::find((int)$id);
        return view('/pelanggan/form-update-pelanggan',[
            "nama_pelanggan" => $pelanggan->nama_pelanggan,
            "id_pelanggan" => $pelanggan->id,
            "slug" => "pelanggan"
        ]);
    }

    public function update_pelanggan($id, Request $request){
        // method yang berguna untuk mengbah data pelaggan yang ada pada database
        $request->validate([
            "nama_pelanggan" => 'required'
        ]);

        Pelanggan::find($id)->update([
            "nama_pelanggan" => $request->nama_pelanggan
        ]);

        return redirect('/index-pelanggan')->with('message','Pelanggan berhasil diubah');
    }
}
