<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_payments', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'paid',
                'partial',
                'failed',
                'reversed'
            ])->default('paid')->change();
        });
    }

    public function down(): void
    {
        Schema::table('property_payments', function (Blueprint $table) {
            $table->enum('status', ['pending'])->default('pending')->change();
        });
    }
};
