<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileBuku extends Model
{
    use HasFactory;

    protected $table = "file_bukus";

    protected $primaryKey = "id";

    protected $fillable = [
        "id_buku",
        "file",
        "hapus_file_buku"
    ];

    public function buku(){
        return $this->belongsTo(Buku::class);
    }
}
