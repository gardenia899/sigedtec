<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('patrimonial_code', 20)->unique(); // RN-04: único e inmutable
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete(); // relación 1:N
            $table->string('brand', 80);
            $table->string('model', 80);
            $table->string('serial_number', 80)->nullable();
            $table->string('cpu', 120)->nullable();
            $table->unsignedSmallInteger('ram_gb')->nullable();
            $table->unsignedSmallInteger('storage_gb')->nullable();
            $table->string('storage_type', 20)->nullable();
            $table->string('status', 20)->default('disponible'); // D-08: foto actual, historia en status_history
            $table->date('acquisition_date')->nullable();
            $table->text('observations')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz(); // eliminación lógica (RF-13)

            $table->index('category_id');
            $table->index('status');
        });

        DB::statement("ALTER TABLE equipment ADD CONSTRAINT equipment_status_check CHECK (status IN ('disponible', 'asignado', 'mantenimiento'))");
        DB::statement('ALTER TABLE equipment ADD CONSTRAINT equipment_ram_check CHECK (ram_gb IS NULL OR ram_gb > 0)');
        DB::statement('ALTER TABLE equipment ADD CONSTRAINT equipment_storage_check CHECK (storage_gb IS NULL OR storage_gb > 0)');
        DB::statement("ALTER TABLE equipment ADD CONSTRAINT equipment_storage_type_check CHECK (storage_type IS NULL OR storage_type IN ('hdd', 'ssd', 'nvme', 'emmc'))");

        // RN-05: serie única entre equipos no borrados lógicamente
        DB::statement('CREATE UNIQUE INDEX equipment_serial_number_active_unique ON equipment (serial_number) WHERE serial_number IS NOT NULL AND deleted_at IS NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
