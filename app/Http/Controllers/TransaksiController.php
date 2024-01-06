<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\TransaksiBuku;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index_transaksi(){
        return view('/transaksi/welcome');
    }
    public function create_transaksi_pelanggan(){
        return view('/transaksi/form-insert-transaksi-pelanggan');
    }

    public function fetch_transaksi_pelanggan(){
        $pelanggans = Pelanggan::where('hapus_pelanggan',0)->get();
        return datatables()::of($pelanggans)
        ->addColumn('action','<a href="/pilih-pelanggan/{{ $id }}" class="btn btn-primary">Pilih</a>')
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }

    public function fetch_transaksi_buku(){
        $bukus = Buku::where('hapus_buku',0)->where('jumlah_buku',1)->get();
        return datatables()::of($bukus)
                    ->addColumn('actions','<input class="form-check-input" name="{{ "buku_". $id }}" type="checkbox" value="{{ "buku_". $id }}" id="flexCheckDefault">')
                    ->rawColumns(['actions'])
                    ->addIndexColumn()
                    ->make(true);
    }

    public function create_transaksi($id){
        $pelanggan = Pelanggan::find($id);
        $date_now = date('Y-m-d');
        $tanggal_pengembalian = date('Y-m-d',strtotime("$date_now +7 day"));
        // dd($pelangga);

        return view('/transaksi/form-insert-transaksi',[
            'id_pelanggan' => $pelanggan->id,
            'nama_pelanggan' => $pelanggan->nama_pelanggan,
            'tanggal_peminjaman' => $date_now,
            'tanggal_pengembalian' => $tanggal_pengembalian
        ]);
    }

    public function store_transaksi(Request $request){
        $array_bukus = [];
        foreach($request->all() as $r){
            if(str_contains($r,'buku_')){
                array_push($array_bukus,substr($r,5));
            }
        }
        if($array_bukus){
            $transaski_terbaru = Transaksi::create([
                "id_pelanggan" => $request->id_pelanggan,
                "deskripsi_transaksi" => $request->deskripsi_transaksi,
                "hapus_transaksi" => 0
            ]);
            $date_now = date('Y-m-d');
            $tanggal_pengembalian = date('Y-m-d',strtotime("$date_now +7 day"));
            foreach($array_bukus as $buku){
                TransaksiBuku::create([
                    "id_transaksi" => $transaski_terbaru->id,
                    "id_buku" => (int)$buku,
                    "tanggal_awal_peminjaman" => $date_now,
                    "tanggal_akhir_peminjaman" => $tanggal_pengembalian,
                    "tanggal_pengembalian" => null
                ]);

                Buku::find((int)$buku)->update([
                    "jumlah_buku" => 0
                ]);
            }
            return redirect('/');
        }else{
            dd('gakada');
        }
    }
}
