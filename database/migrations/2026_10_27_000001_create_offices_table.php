<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();      // código institucional (OFI-01)
            $table->string('name', 120)->unique();
            $table->string('location', 160)->nullable();
            $table->boolean('is_active')->default(true); // desactivar en vez de borrar (RN-07)
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
