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
        Schema::table('surat2s', function (Blueprint $table) {
            $table->string('nama_bersangkutan')->nullable();
            $table->string('nama_pemohon')->nullable();
            $table->text('alamat_tinggal')->nullable();
        });
    }
    
    public function down(): void
    {
        Schema::table('surat2s', function (Blueprint $table) {
            $table->dropColumn(['nama_bersangkutan', 'nama_pemohon', 'alamat_tinggal']);
        });
    }
    
};
