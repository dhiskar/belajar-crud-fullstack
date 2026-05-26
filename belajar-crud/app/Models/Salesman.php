<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salesman extends Model
{
    // Mengarahkan ke nama tabel yang benar
    protected $table = 'salesman';

    // Mengunci primary key custom kita
    protected $primaryKey = 'kd_salesman';

    // Beritahu laravel kalau primary key kita bukan angka auto-increment
    public $incrementing = false;
    protected $keyType = 'string';

    // Daftarkan kolom yang boleh diisi massal
    protected $fillable = [
        'kd_salesman',
        'nama_salesman',
        'tgl_input',
        'keterangan'
    ];
}