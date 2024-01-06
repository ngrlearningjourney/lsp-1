<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = "bukus";

    protected $primaryKey = "id";

    protected $fillable = [
        "nama_buku",
        "deskripsi_buku",
        "jumlah_buku",
        "hapus_buku"
    ];

    public function transaksis(){
        return $this->belongsToMany(Transaksi::class);
    }

    public function fileBuku(){
        return $this->hasMany(FileBuku::class);
    }
}
