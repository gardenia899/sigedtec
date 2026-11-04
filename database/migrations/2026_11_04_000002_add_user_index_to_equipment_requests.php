<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * El solicitante lista siempre sus propias solicitudes (`WHERE user_id`),
     * consulta que hoy recorría la tabla completa.
     */
    public function up(): void
    {
        Schema::table('equipment_requests', function (Blueprint $table) {
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('equipment_requests', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });
    }
};
