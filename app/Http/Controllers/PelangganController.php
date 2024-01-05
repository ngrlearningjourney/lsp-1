<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index_pelanggan(){
        return view('index-pelanggan');
    }

    public function fetch_pelanggan(){
        $pelanggans = Pelanggan::where('hapus_pelanggan',0)->get();
        return datatables()::of($pelanggans)->addIndexColumn()->make(true);
    }

    public function create_pelanggan(){
        return view('form-insert-pelanggan');
    }

    public function store_pelanggan(Request $request){
        $request->validate([
            'nama_pelanggan' => 'required | min:10 | max:225',
        ]);

        Pelanggan::create([
            "nama" => $request->nama_pelanggan,
            "hapus_pelanggan" => 0,
        ]);

        return redirect('/index-pelanggan');
    }
}
