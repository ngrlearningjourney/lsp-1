<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BukuController extends Controller
{
    public function index_buku(){
        return view('index-buku');
    }

    public function fetch_buku(){
        $bukus = Buku::where('hapus_buku',0)->get();
        return datatables()::of($bukus)->addIndexColumn()->make(true);
    }

    public function create_buku(){
        return view('form-insert-buku');
    }

    public function store_buku(Request $request){
        $request->validate([
            'nama_buku' => 'required | min:10 | max:225',
            'deskripsi_buku' => 'required | min:10 | max:225',
        ]);

        Buku::create([
            "nama_buku" => $request->nama_buku,
            "deskripsi_buku" => $request->deskripsi_buku,
            "jumlah_buku" => 1,
            "hapus_buku" => 0
        ]);

        return redirect('/index-buku');
    }
}
