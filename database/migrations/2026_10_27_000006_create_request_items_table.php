<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('equipment_requests')->cascadeOnDelete();
            $table->foreignId('equipment_id')->constrained('equipment')->restrictOnDelete();
            $table->foreignId('assigned_by')->constrained('users')->restrictOnDelete(); // admin que asigna
            $table->string('status_before', 20);
            $table->string('status_after', 20);
            $table->timestampTz('returned_at')->nullable(); // NULL = asignación activa (RN-21, PD-02)
            $table->string('return_condition', 20)->nullable();
            $table->text('return_observations')->nullable();
            $table->timestampsTz(); // created_at = instante exacto de la asignación (RN-20)

            $table->unique(['request_id', 'equipment_id']);
        });

        DB::statement("ALTER TABLE request_items ADD CONSTRAINT request_items_status_before_check CHECK (status_before IN ('disponible', 'asignado', 'mantenimiento'))");
        DB::statement("ALTER TABLE request_items ADD CONSTRAINT request_items_status_after_check CHECK (status_after IN ('disponible', 'asignado', 'mantenimiento'))");
        DB::statement("ALTER TABLE request_items ADD CONSTRAINT request_items_return_condition_check CHECK (return_condition IS NULL OR return_condition IN ('operativo', 'requiere_mantenimiento'))");

        // RN-21 / SDD §15.4: un equipo no puede estar en dos asignaciones activas a la vez (a nivel de PostgreSQL)
        DB::statement('CREATE UNIQUE INDEX request_items_equipment_active_unique ON request_items (equipment_id) WHERE returned_at IS NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('request_items');
    }
};