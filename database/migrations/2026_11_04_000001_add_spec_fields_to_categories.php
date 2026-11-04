<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cada categoría define qué especificaciones técnicas se capturan al
     * registrar un equipo: CPU, RAM y almacenamiento solo aplican a equipos
     * de cómputo, no a impresoras, proyectores o periféricos.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->json('spec_fields')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('spec_fields');
        });
    }
};
