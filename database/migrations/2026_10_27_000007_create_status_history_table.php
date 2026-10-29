<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment')->restrictOnDelete();
            $table->foreignId('request_id')->nullable()->constrained('equipment_requests')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // actor
            $table->string('from_status', 20)->nullable();  // NULL en el alta inicial
            $table->string('to_status', 20);
            $table->string('reason', 30);                    // creacion|asignacion|devolucion|mantenimiento|reparacion|edicion_manual
            $table->string('notes', 255)->nullable();
            $table->timestampTz('created_at')->useCurrent(); // instante exacto del cambio (RN-19)
            // Sin updated_at: tabla append-only, bitácora inmutable (RNF-10)

            $table->index(['equipment_id', 'created_at']);
            $table->index('request_id');
        });

        DB::statement("ALTER TABLE status_history ADD CONSTRAINT status_history_from_status_check CHECK (from_status IS NULL OR from_status IN ('disponible', 'asignado', 'mantenimiento'))");
        DB::statement("ALTER TABLE status_history ADD CONSTRAINT status_history_to_status_check CHECK (to_status IN ('disponible', 'asignado', 'mantenimiento'))");
        DB::statement("ALTER TABLE status_history ADD CONSTRAINT status_history_reason_check CHECK (reason IN ('creacion', 'asignacion', 'devolucion', 'mantenimiento', 'reparacion', 'edicion_manual'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('status_history');
    }
};