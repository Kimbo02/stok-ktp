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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_surat')->unique(); // Untuk nomor surat yang tidak boleh ganda
            $table->text('keterangan');              // Text cocok untuk isi surat yang panjang
            $table->date('tanggal')->nullable();     // Tanggal surat
            $table->string('template_surat')->nullable(); // Path template Word yang diupload
            $table->string('file_pdf')->nullable();       // File hasil (bisa PDF/Word)

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
