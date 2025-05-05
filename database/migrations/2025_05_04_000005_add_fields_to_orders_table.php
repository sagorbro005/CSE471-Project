<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // All necessary fields are now present in the main orders table migration.
        // This migration is now redundant and intentionally left blank to avoid duplicate column errors.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed.
    }
};
