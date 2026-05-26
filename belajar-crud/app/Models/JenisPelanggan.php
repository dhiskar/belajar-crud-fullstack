<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPelanggan extends Model
{
    // Beritahu Laravel bahwa nama tabel kita di Postgres adalah 'jenis_pelanggans'
    protected $table = 'jenis_pelanggans';

    // Daftarkan kolom yang boleh diisi secara massal saat form disubmit
    protected $fillable = [
        'kd_jenis_customer',
        'nama_jenis_customer',
        'keterangan'
    ];
}