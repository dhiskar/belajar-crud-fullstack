<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salesman', function (Blueprint $table) {
            // kd_salesman sebagai Primary Key (Bukan id auto-increment)
            $table->string('kd_salesman', 10)->primary();
            $table->string('nama_salesman', 100);
            
            // tgl_input menggunakan tipe data date untuk tanggal bergabung
            $table->date('tgl_input');
            
            $table->text('keterangan')->nullable();
            $table->timestamps(); // Menjaga record created_at & updated_at bawaan laravel
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salesmen');
    }
};
