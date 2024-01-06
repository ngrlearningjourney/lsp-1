<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = "pelanggans";

    protected $primaryKey = "id";

    protected $fillable = [
        "nama_pelanggan",
        "hapus_pelanggan"
    ];

    public function transaksi(){
        return $this->hasMany(Transaksi::class);
    }
}
