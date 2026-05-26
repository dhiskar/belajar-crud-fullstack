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
        Schema::create('jenis_pelanggans', function (Blueprint $blueprint) {
            $blueprint->id(); // ID Auto Increment sebagai Primary Key internal
            
            // Kolom Kode Jenis: Kita buat string, maksimal 20 karakter, dan bersikap UNIK (tidak boleh kembar)
            $blueprint->string('kd_jenis_customer', 20)->unique();
            
            // Kolom Nama Jenis: Contoh "Distributor" atau "Plastic Manufacturer"
            $blueprint->string('nama_jenis_customer', 100);
            
            // Kolom Keterangan: Boleh kosong (nullable) jika user tidak ingin mengisi tambahan catatan
            $blueprint->text('keterangan')->nullable();
            
            $blueprint->timestamps(); // Otomatis membuat kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_pelanggans');
    }
};