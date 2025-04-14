<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->string('nama_pengirim')->nullable();
            $table->string('nip_pengirim')->nullable();
            $table->string('jabatan_pengirim')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropColumn(['nama_pengirim', 'nip_pengirim', 'jabatan_pengirim']);
        });
    }
};
