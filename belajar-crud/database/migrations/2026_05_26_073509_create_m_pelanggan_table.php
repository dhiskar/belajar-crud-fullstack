<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('m_pelanggan', function (Blueprint $table) {
        $table->string('kd_customer', 20)->primary();
        $table->string('nama_customer', 150);
        $table->string('kd_jenis_customer', 20);
        $table->string('kd_salesman', 20);
        $table->text('alamat');
        $table->timestamps();

        // CUKUP SATU SAJA BARIS INI (Hapus jika ada duplikatnya di bawah)
        $table->foreign('kd_jenis_customer')
              ->references('kd_jenis_customer')
              ->on('jenis_pelanggans') 
              ->onDelete('restrict')
              ->onUpdate('cascade');

        // CUKUP SATU SAJA BARIS INI
        $table->foreign('kd_salesman')
              ->references('kd_salesman')
              ->on('salesman') 
              ->onDelete('restrict')
              ->onUpdate('cascade');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('m_pelanggan');
    }
};