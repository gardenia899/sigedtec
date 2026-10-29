<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained('offices')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete(); // solicitante
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->unsignedSmallInteger('quantity');
            $table->text('justification');
            $table->string('status', 20)->default('pendiente'); // máquina de estados §11.2
            $table->text('admin_observation')->nullable();      // obligatoria al rechazar (RN-18)
            $table->foreignId('processed_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestampTz('processed_at')->nullable();    // instante exacto de la decisión (RN-17)
            $table->timestampsTz();

            $table->index('status');
            $table->index('office_id');
            $table->index('created_at');
        });

        DB::statement('ALTER TABLE equipment_requests ADD CONSTRAINT equipment_requests_quantity_check CHECK (quantity > 0)');
        DB::statement("ALTER TABLE equipment_requests ADD CONSTRAINT equipment_requests_status_check CHECK (status IN ('pendiente', 'aprobada', 'rechazada', 'cancelada'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_requests');
    }
};