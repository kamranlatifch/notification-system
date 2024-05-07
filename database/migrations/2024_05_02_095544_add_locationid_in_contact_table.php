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
        // Check if the column does not exist before adding it
        if (!Schema::hasColumn('contacts', 'location_id')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->bigInteger('location_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the column only if it exists
        if (Schema::hasColumn('contacts', 'location_id')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->dropColumn('location_id');
            });
        }
    }
};