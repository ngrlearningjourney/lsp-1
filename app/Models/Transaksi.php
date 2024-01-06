<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = "transaksis";

    protected $primaryKey = "id";

    protected $fillable = [
        "id_pelanggan",
        "deskripsi_transaksi",
        "hapus_transaksi"
    ];

    public function pelanggans(){
        return $this->belongsTo(Pelanggan::class);
    }

    public function bukus(){
        return $this->belongsToMany(Buku::class);
    }
}
