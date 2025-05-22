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
    if (!Schema::hasTable('surat2s')) {
        Schema::create('surat2s', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kelahiran')->nullable();
            $table->string('nama_bersangkutan')->nullable();
            $table->string('nama_pemohon')->nullable();
            $table->text('alamat_tinggal')->nullable();
            $table->date('tanggal')->nullable();
            $table->date('tanggal_lapor')->nullable(); // ⬅️ Tambahkan ini
            $table->string('link_ttd')->nullable();
            $table->string('file_pdf')->nullable();
            $table->timestamps();
        });
    }
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat2s');
    }
};
