<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('solicitante')->after('password'); // RN-02
            $table->foreignId('office_id')->after('role')->constrained('offices')->restrictOnDelete(); // RN-03
            $table->index('office_id');
            $table->index('role');
        });

        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'solicitante'))");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('office_id');
            $table->dropIndex(['role']);
            $table->dropColumn('role');
        });

        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
    }
};
