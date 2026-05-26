<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pelanggan extends Model
{
    protected $table = 'm_pelanggan';
    protected $primaryKey = 'kd_customer';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_customer',
        'nama_customer',
        'kd_jenis_customer',
        'kd_salesman',
        'alamat'
    ];

    // 1. Hubungan ke Model Jenis Pelanggan
    // Pastikan nama class Model-nya sesuai (misal: JenisPelanggan)
    public function jenis_pelanggan(): BelongsTo
    {
        return $this->belongsTo(JenisPelanggan::class, 'kd_jenis_customer', 'kd_jenis_customer');
    }

    // 2. Hubungan ke Model Salesman
    // Pastikan nama class Model-nya sesuai (misal: Salesman)
    public function salesman(): BelongsTo
    {
        return $this->belongsTo(Salesman::class, 'kd_salesman', 'kd_salesman');
    }
}