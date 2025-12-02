<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('constancias', function (Blueprint $table) {
            // Renombrar codigo_qr a codigo_verificacion
            $table->renameColumn('codigo_qr', 'codigo_verificacion');
        });
    }

    public function down(): void
    {
        Schema::table('constancias', function (Blueprint $table) {
            $table->renameColumn('codigo_verificacion', 'codigo_qr');
        });
    }
};
