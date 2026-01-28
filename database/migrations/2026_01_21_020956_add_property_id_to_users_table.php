<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'property_id')) {
                $table->foreignId('property_id')
                      ->nullable()
                      ->after('id')
                      ->constrained()
                      ->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'property_id')) {
                $table->dropConstrainedForeignId('property_id');
            }
        });
    }
};
