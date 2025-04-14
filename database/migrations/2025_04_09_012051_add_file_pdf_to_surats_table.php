<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('surats', 'file_pdf')) {
            Schema::table('surats', function (Blueprint $table) {
                $table->string('file_pdf')->nullable()->after('template_surat');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('surats', 'file_pdf')) {
            Schema::table('surats', function (Blueprint $table) {
                $table->dropColumn('file_pdf');
            });
        }
    }
};
