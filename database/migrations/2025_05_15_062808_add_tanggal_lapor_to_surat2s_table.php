<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat2s', function (Blueprint $table) {
            // Tambahkan kolom 'tanggal' dan 'tanggal_lapor' jika belum ada
            if (!Schema::hasColumn('surat2s', 'tanggal')) {
                $table->date('tanggal')->nullable();
            }

            if (!Schema::hasColumn('surat2s', 'tanggal_lapor')) {
                $table->date('tanggal_lapor')->nullable();
            }
        });
    }

    public function down(): void
{
    if (Schema::hasTable('surat2s')) {
        Schema::table('surat2s', function (Blueprint $table) {
            $table->dropColumn(['tanggal', 'tanggal_lapor']);
        });
    }
}
};
