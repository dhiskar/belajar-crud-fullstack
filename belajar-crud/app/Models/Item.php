<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_persediaan',
        'nama_persediaan',
        'satuan',
        'jumlah',
        'berat',
        'harga'
    ];
}
