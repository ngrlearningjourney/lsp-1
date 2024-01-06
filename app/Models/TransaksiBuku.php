<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiBuku extends Model
{
    use HasFactory;

    protected $table = "transaksi_bukus";

    protected $fillable = [
        "id_transaksi",
        "id_buku",
        "tanggal_awal_peminjaman",
        "tanggal_akhir_peminjaman",
        "tanggal_pengembalian"
    ];
}
