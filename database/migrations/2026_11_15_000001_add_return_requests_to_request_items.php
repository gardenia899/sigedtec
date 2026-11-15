<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('request_items', function (Blueprint $table) {
            $table->timestampTz('return_requested_at')->nullable()->after('returned_at');
            $table->foreignId('return_requested_by')->nullable()->after('return_requested_at')->constrained('users')->nullOnDelete();
            $table->text('return_request_observations')->nullable()->after('return_requested_by');
        });
    }

    public function down(): void
    {
        Schema::table('request_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('return_requested_by');
            $table->dropColumn(['return_requested_at', 'return_request_observations']);
        });
    }
};
