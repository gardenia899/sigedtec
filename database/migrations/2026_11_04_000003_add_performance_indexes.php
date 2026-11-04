<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Índices alineados con las consultas reales:
     * - panel y bitácora ordenan status_history por fecha descendente;
     * - asignaciones ordenan request_items por fecha;
     * - inventario filtra por estado y categoría a la vez;
     * - la bandeja y el panel filtran solicitudes por estado y fecha,
     *   y el solicitante por usuario y estado.
     */
    public function up(): void
    {
        Schema::table('status_history', function (Blueprint $table) {
            $table->index('created_at');
        });

        Schema::table('request_items', function (Blueprint $table) {
            $table->index('created_at');
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->index(['status', 'category_id']);
        });

        Schema::table('equipment_requests', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('equipment_requests', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['user_id', 'status']);
            $table->index('user_id');
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->dropIndex(['status', 'category_id']);
        });

        Schema::table('request_items', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('status_history', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
    }
};
