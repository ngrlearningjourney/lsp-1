<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\FileBuku;
use App\Models\Transaksi;
use App\Models\TransaksiBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;

class BukuController extends Controller
{
    public function index_buku(){
        return view('/buku/index-buku');
    }

    public function fetch_buku(){
        $bukus = Buku::where('hapus_buku',0)->get();
        return datatables()::of($bukus)
        ->addColumn('actions',
        '
            <div class="d-flex">
                <a href="/edit-buku/{{ $id }}" class="btn btn-primary me-2">Ubah</a>
                <button type="button" id="{{ $id }}" class="btn btn-danger delete">Hapus</button>
            </div>
        '
        )
        ->rawColumns(['actions'])
        ->addIndexColumn()
        ->make(true);
    }

    public function create_buku(){
        return view('/buku/form-insert-buku');
    }

    public function store_buku(Request $request){
        $request->validate([
            'nama_buku' => 'required | min:10 | max:225',
            'deskripsi_buku' => 'required | min:10 | max:225',
        ]);


        $buku_terbaru = Buku::create([
            "nama_buku" => $request->nama_buku,
            "deskripsi_buku" => $request->deskripsi_buku,
            "jumlah_buku" => 1,
            "hapus_buku" => 0
        ]);

        $data = new FileBuku();
        $file = $request->file;
        if($file){
            $filename = time().'.'. $file->getClientOriginalExtension();
            $file->move('assets',$filename);
            $data->id_buku = $buku_terbaru->id;
            $data->file = $filename;
            $data->hapus_file_buku = 0;
            $data->save();
        }

        return redirect('/index-buku')->with('message','buku berhasil dimasukkan');
    }

    public function delete_buku($id){
        Buku::find($id)->update([
            'hapus_buku' => 1
        ]);

        return redirect('/index-buku')->with('message','buku berhasil dihapus');
    }

    public function edit_buku($id){
        $buku = Buku::find($id);
        $file_bukus = FileBuku::where('id_buku',$id)->where('hapus_file_buku',0)->get();
        $array_file_bukus = [];
        if($file_bukus){
            foreach($file_bukus as $file_buku){
                array_push($array_file_bukus,(object)[
                    "id_file" => $file_buku->id,
                    "file" => $file_buku->file
                ]);
            }
        }
        return view('/buku/form-update-buku',[
            "id_buku" => $buku->id,
            "nama_buku" => $buku->nama_buku,
            "deskripsi_buku" => $buku->deskripsi_buku,
            "array_file_bukus" => $array_file_bukus
        ]);

    }

    public function delete_gambar($id,Request $request){
        FileBuku::find($id)->update([
            "hapus_file_buku" => 1
        ]);

        return redirect('/edit-buku/'. $request->id_buku)->with('message','berhasil menghapus gambar');
    }

    public function update_buku($id,Request $request){
        $request->validate([
            'nama_buku' => 'required | min:10 | max:225',
            'deskripsi_buku' => 'required | min:10 | max:225',
        ]);

        Buku::find((int)$id)->update([
            "nama_buku" => $request->nama_buku,
            "deskripsi_buku" => $request->deskripsi_buku
        ]);

        $data = new FileBuku();
        $file = $request->file;
        if($file){
            $filename = time().'.'. $file->getClientOriginalExtension();
            $file->move('assets',$filename);
            $data->id_buku = (int)$id;
            $data->file = $filename;
            $data->hapus_file_buku = 0;
            $data->save();
        }

        return redirect('/index-buku')->with('message','buku berhasil dirubah');
    }

    public function index_pengembalian_buku(){
        return view('/buku/index-pengembalian-buku');
    }

    public function fetch_pengembalian_buku() {
        $data = TransaksiBuku::leftJoin('bukus','transaksi_bukus.id_buku','=','bukus.id')
        ->where('hapus_transaksi_buku',0)
        ->get([
            "transaksi_bukus.id_transaksi",
            "transaksi_bukus.id_buku",
            "bukus.nama_buku",
            "transaksi_bukus.tanggal_awal_peminjaman",
            "transaksi_bukus.tanggal_akhir_peminjaman",
            "transaksi_bukus.tanggal_pengembalian",
        ]);

        $array_data = [];
        foreach($data as $d){
            array_push($array_data,(object)[
                "nama_buku" => $d->nama_buku,
                "tanggal_awal_peminjaman" => $d->tanggal_awal_peminjaman,
                "tanggal_akhir_peminjaman" => $d->tanggal_akhir_peminjaman,
                "tanggal_pengembalian" => $d->tanggal_pengembalian,
                "href" => '/create-pengembalian-buku/'. $d->id_transaksi. '_'. $d->id_buku,
                "id_membatalkan" => $d->id_transaksi. '_'. $d->id_buku,
                "id_transaksi" => $d->id_transaksi,
                "id_buku" => $d->id_buku,
            ]);
        }

        return datatables()::of($array_data)
        ->addColumn('actions','component/action-index-pengembalian-buku')
        ->rawColumns(['actions'])
        ->addIndexColumn()
        ->make(true);
    }

    public function create_pengembalian_buku($id){
        $explode = explode("_", $id);
        $id_transaksi = $explode[0];
        $id_buku = $explode[1];
        
        $transaksi_buku = TransaksiBuku::where('id_transaksi',(int)$id_transaksi)->where('id_buku',(int)$id_buku)->where('hapus_transaksi_buku',0)
        ->leftJoin('bukus','transaksi_bukus.id_buku','=','bukus.id')
        ->get()->first();


        return view('/buku/pengembalian-transaksi',[
            "nama_buku" => $transaksi_buku->nama_buku,
            "id_transaksi" => $transaksi_buku->id_transaksi,
            "tanggal_awal_peminjaman" => $transaksi_buku->tanggal_awal_peminjaman,
            "tanggal_akhir_peminjaman" => $transaksi_buku->tanggal_akhir_peminjaman,
            "tanggal_hari_ini" => date('Y-m-d'),
            "id_buku" => $transaksi_buku->id_buku,
            "id_transaksi_buku" => $id
        ]);
    }

    public function store_pengembalian_buku($id, Request $request){
        $explode = explode("_", $id);
        $id_transaksi = $explode[0];
        $id_buku = $explode[1];

        TransaksiBuku::where('id_transaksi',(int)$id_transaksi)->where('id_buku',(int)$id_buku)->where('hapus_transaksi_buku',0)->update([
            "tanggal_pengembalian" => $request->tanggal_hari_ini
        ]);
        
        Buku::find($id_buku)->update([
            "jumlah_buku" => 1
        ]);
        return redirect('/index-pengembalian-buku')->with('message','pengembalian sukses!');
    }

    public function delete_pengembalian_buku($id){
        $explode = explode("_", $id);
        $id_transaksi = $explode[0];
        $id_buku = $explode[1];

        TransaksiBuku::where('id_transaksi',(int)$id_transaksi)->where('id_buku',(int)$id_buku)->where('hapus_transaksi_buku',0)->update([
            "tanggal_pengembalian" => null
        ]);

        Buku::find($id_buku)->update([
            "jumlah_buku" => 0
        ]);

        return redirect('/index-pengembalian-buku')->with('message','Pembatalan sukses!');
    }
}
