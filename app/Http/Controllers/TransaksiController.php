<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\TransaksiBuku;
use DateTime;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index_transaksi(){
        // method ini digunakna untuk menapilkan welcome page yang berisikan index transaksi
        return view('/transaksi/welcome');
    }
    public function create_transaksi_pelanggan(){
        // method ini digunakna untuk menapilkan form pemilihan pelanggan yang berisikan index daftar pelanggan
        return view('/transaksi/form-insert-transaksi-pelanggan');
    }

    public function fetch_transaksi_pelanggan(){
        // method ini digunakan untuk mengambil data dan mengimplementasikannya kepada data table

        // data yang diambil disini merupakan data pelanggan dan melalui ini kita juga menambah satu kolom baru yang berisikan button pilih pelanggan

        $pelanggans = Pelanggan::where('hapus_pelanggan',0)->get();
        return datatables()::of($pelanggans)
        ->addColumn('action','<a href="/pilih-pelanggan/{{ $id }}" class="btn btn-primary">Pilih</a>')
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }

    public function fetch_transaksi_buku(){
        // method ini bertujuan untuk mengambil data yang berada pada table buku

        // method ini juga menambahkan column baru pada datatable yang akan diisikan checkbox untuk memili buku yang ingin di pinjam

        $bukus = Buku::where('hapus_buku',0)->where('jumlah_buku',1)->get();
        return datatables()::of($bukus)
                    ->addColumn('actions','<input class="form-check-input" name="{{ "buku_". $id }}" type="checkbox" value="{{ "buku_". $id }}" id="flexCheckDefault">')
                    ->rawColumns(['actions'])
                    ->addIndexColumn()
                    ->make(true);
    }

    public function create_transaksi($id){
        // method ini bertujuan utnuk menampilkan form insert transaksi buku

        $pelanggan = Pelanggan::find($id);
        $date_now = date('Y-m-d');
        $tanggal_pengembalian = date('Y-m-d',strtotime("$date_now +7 day"));

        return view('/transaksi/form-insert-transaksi',[
            'id_pelanggan' => $pelanggan->id,
            'nama_pelanggan' => $pelanggan->nama_pelanggan,
            'tanggal_peminjaman' => $date_now,
            'tanggal_pengembalian' => $tanggal_pengembalian,
            'slug' => 'transaksi'
        ]);
    }

    public function store_transaksi(Request $request){
        // method ini bertujuan untuk memasukkan data transaksi peminjaman buku ke dalam database

        // dimulai dengan memvalidasi input dan mengecek kesalahan user
        $request->validate([
            'deskripsi_transaksi' => 'required'
        ]);
        $array_bukus = [];
        foreach($request->all() as $r){
            if(str_contains($r,'buku_')){
                array_push($array_bukus,substr($r,5));
            }
        }
        if($array_bukus){
            // hingga memasukkan transaksi ke dalam database sesuai dengan ketentuan atribut masing masing
            $transaski_terbaru = Transaksi::create([
                "id_pelanggan" => $request->id_pelanggan,
                "deskripsi_transaksi" => $request->deskripsi_transaksi,
                "hapus_transaksi" => 0,
            ]);
            $date_now = date('Y-m-d');
            $tanggal_pengembalian = date('Y-m-d',strtotime("$date_now +7 day"));
            foreach($array_bukus as $buku){
                TransaksiBuku::create([
                    "id_transaksi" => $transaski_terbaru->id,
                    "id_buku" => (int)$buku,
                    "tanggal_awal_peminjaman" => $date_now,
                    "tanggal_akhir_peminjaman" => $tanggal_pengembalian,
                    "tanggal_pengembalian" => null,
                    "hapus_transaksi_buku" => 0
                ]);

                Buku::find((int)$buku)->update([
                    "jumlah_buku" => 0
                ]);
            }
            // jika benar akan kembali ke halaman daftar transaksi
            return redirect('/index-transaksi')->with('message','berhasil menambahkan transaksi');
        }else{

            // jika salah akan mengembalikan pemberitahuan dan mengembalikan ke form memasukkan transaksi peminjaman
            return redirect('/pilih-pelanggan/'. $request->id_pelanggan)->with('message','transaksi gagal dilakukan');
        }
    }

    public function fetch_transaksi(){
        // method ini bertujuan mengambil data transaksi pada database dan menampilkannya pada datatable
        $array_transaksis = [];

        $transaksis = Transaksi::leftJoin('pelanggans','transaksis.id_pelanggan','=','pelanggans.id')->where("transaksis.hapus_transaksi",0)
        ->get([
            "pelanggans.nama_pelanggan",
            "transaksis.id",
            "transaksis.deskripsi_transaksi",
            "transaksis.created_at"
        ]);

        // mencari tahu buku apa saja yang dipinjam pada 1 kali transaksi
        foreach($transaksis as $transaksi){
            $array_bukus = [];
            $bukus = TransaksiBuku::where('id_transaksi',$transaksi->id)->where('tanggal_pengembalian',null)->where('hapus_transaksi_buku',0)->leftJoin('bukus','transaksi_bukus.id_buku','=','bukus.id')
            ->get([
                "bukus.nama_buku",
                "transaksi_bukus.tanggal_awal_peminjaman",
                "transaksi_bukus.tanggal_akhir_peminjaman"
            ]);
            foreach($bukus as $buku){
                array_push($array_bukus,$buku->nama_buku);
            };
            $buku_pinjaman = implode(",", $array_bukus);
            array_push($array_transaksis, (object)[
                "id_transaksi" => $transaksi->id,
                "nama_pelanggan" =>$transaksi->nama_pelanggan,
                "deskripsi_transaksi" => $transaksi->deskripsi_transaksi,
                "tercatat" => $transaksi->created_at->toDateString(),
                "buku"=> $buku_pinjaman,
                "tanggal_awal_peminjaman" => $bukus[0]->tanggal_awal_peminjaman,
                "tanggal_akhir_peminjaman" => $bukus[0]->tanggal_akhir_peminjaman,
            ]);
        }
        
        // menampilkannya pada datatable dan menambahkan 2 tombol tambahan untuk memberikan kemudahan pengguna untuk mengedit dan menghapus transaksi peminjaman
        return datatables()::of($array_transaksis)
        ->addColumn('actions','
            <div class="d-flex">
                <a href="/edit-transaksi/{{ $id_transaksi }}" class="btn btn-primary me-2">Ubah</a>
                <button type="button" id="{{ $id_transaksi }}" class="btn btn-danger delete">Hapus</button>
            </div>
        ')
        ->rawColumns(['actions'])
        ->addIndexColumn()
        ->make(true);

    }


    public function edit_transaksi($id){
        // mencari transaksi yang sesuai dengan yang dipilih pada halaman index transaksi dan menampilkannya pada halaman edit transaksi
        $transaksis = Transaksi::find($id)
        ->leftJoin('pelanggans','transaksis.id_pelanggan','=','pelanggans.id')
        ->leftJoin('transaksi_bukus','transaksis.id','=','transaksi_bukus.id_transaksi')
        ->get();

        $nama_pelanggan = $transaksis[0]->nama_pelanggan;
        $id_pelanggan = $transaksis[0]->id_pelanggan;
        $tanggal_awal_peminjaman = $transaksis[0]->tanggal_awal_peminjaman;
        $tanggal_akhir_peminjaman = $transaksis[0]->tanggal_akhir_peminjaman;
        $deskripsi_transaksi = $transaksis[0]->deskripsi_transaksi;
        

        // menampilkan seluruh data yang dikumpulkan pada form guna mengubah transaksi
        return view('transaksi/form-update-transaksi',[
            "nama_pelanggan" => $nama_pelanggan,
            "tanggal_awal_peminjaman" => $tanggal_awal_peminjaman,
            "tanggal_akhir_peminjaman" => $tanggal_akhir_peminjaman,
            "id_pelanggan" => $id_pelanggan,
            "deskripsi_transaksi" => $deskripsi_transaksi,
            "id_transaksi"=>$id,
        ]);
    }

    public function fetch_edit_transaksi_buku(Request $request){
        // mengambil buku yang telah dipinjam oleh pelanggan
        $transaksis = TransaksiBuku::where('id_transaksi',(int)$request->data)->where('hapus_transaksi_buku',0)->get();

        // mengumpulkan buku buku yang telah dipinjam pelanggan dalam bentuk array of object
        $array_bukus = [];
        $array_id_buku = [];
        foreach($transaksis as $transaksi){
            $buku = Buku::find($transaksi->id_buku);
            array_push($array_bukus,(object)[
                "id_buku" =>$buku->id, 
                "nama_buku" => $buku->nama_buku,
                "deskripsi_buku" => $buku->deskripsi_buku,
                "terpilih" => 1,
                "name" => "buku". $buku->id
            ]);
            array_push($array_id_buku, $buku->id);
        }

        // mengumpulkan buku buku yang belum dipinjam pelanggan dalam bentuk array of object
        $buku_tidak_terpilihs = Buku::whereNotIn('id',$array_id_buku)->where('hapus_buku',0)->where('jumlah_buku',1)->get();
        foreach($buku_tidak_terpilihs as $buku_tidak_terpilih){
            array_push($array_bukus,(object)[
                "id_buku" =>$buku_tidak_terpilih->id, 
                "nama_buku" => $buku_tidak_terpilih->nama_buku,
                "deskripsi_buku" => $buku_tidak_terpilih->deskripsi_buku,
                "terpilih" => 0,
                "name" => "buku". $buku_tidak_terpilih->id
            ]);
        };

        // menampilkannya dalam bentuk datatable
        return datatables()::of($array_bukus)
        ->addColumn('actions', 'component/action-update-transaksi')
        ->rawColumns(['actions'])
        ->addIndexColumn()
        ->make(true);
        
    }   

    public function update_transaksi($id,Request $request){

        // method ini berguna untuk melakukan pembaruan transaksi

        // dimulai dari pemilihan buku baru yang dipilih
        $array_bukus = [];
        foreach($request->all() as $r){
            if(str_contains($r,'buku')){
                array_push($array_bukus,substr($r,4));
            }
        }

        // dan memasukkan transaksi perubahan maupun mengubah beberapa value attribute ke dalam database
        if($array_bukus){
            $transaksi_sebelum_edits = TransaksiBuku::where('id_transaksi',$id)->get();
            $id_buku_sebelum_edits = [];
            foreach($transaksi_sebelum_edits as $transaksi_sebelum_edit){
                array_push($id_buku_sebelum_edits,(int)$transaksi_sebelum_edit->id_buku);
            }
            Buku::whereIn('id',$id_buku_sebelum_edits)->update([
                "jumlah_buku" => 1
            ]);
            
            $array_bukus = [];
            foreach($request->all() as $r){
                if(str_contains($r,'buku')){
                    array_push($array_bukus,(int)substr($r,4));
                }
            }
    
            Buku::whereIn('id',$array_bukus)->update([
                "jumlah_buku" => 0
            ]);
    
            Transaksi::find($id)->update([
                "deskripsi_transaksi" => $request->deskripsi_transaksi
            ]);
    
            TransaksiBuku::where("id_transaksi",$id)->update([
                "hapus_transaksi_buku" => 1
            ]);
    
            $date_now = date('Y-m-d');
            $tanggal_pengembalian = date('Y-m-d',strtotime("$date_now +7 day"));
            foreach($array_bukus as $buku){
                TransaksiBuku::create([
                    "id_transaksi" => (int)$id,
                    "id_buku" => (int)$buku,
                    "tanggal_awal_peminjaman" => $date_now,
                    "tanggal_akhir_peminjaman" => $tanggal_pengembalian,
                    "tanggal_pengembalian" => null,
                    "hapus_transaksi_buku" => 0
                ]);
            }
            // jika berhasil 
            return redirect('/index-transaksi')->with('message','transaksi berhasil dirubah');
        }else{
            // jika tidak berhasil 
            return redirect('/edit-transaksi/'. $id)->with('message','transaksi gagal dirubah');
        }
    }

    public function delete_transaksi($id){
        // method ini berguna untuk melakukan penghapusan transaksi peminjaman
        $transaksi_bukus = TransaksiBuku::where("id_transaksi",$id)->where('hapus_transaksi_buku',0)->get();
        $array_id_buku = [];
        foreach($transaksi_bukus as $transaksi_buku){
            array_push($array_id_buku,$transaksi_buku->id_buku);
        }

        // buku dikembalikan seperti semula agar dapat dipinjam
        foreach($array_id_buku as $id_buku){
            Buku::find($id_buku)->update([
                "jumlah_buku" => 1
            ]);
        }

        // transaksi buku -- pengubahan isi pivot table antara transaksi dan buku --lama terhapus
        TransaksiBuku::where('id_transaksi',(int)$id)->update([
            "hapus_transaksi_buku" => 1
        ]);

        // transaksi lama terhapus
        Transaksi::where('id',(int)$id)->update([
            "hapus_transaksi" => 1
        ]);

        return redirect('/index-transaksi')->with('message','berhasil dihapus');
    }
}
