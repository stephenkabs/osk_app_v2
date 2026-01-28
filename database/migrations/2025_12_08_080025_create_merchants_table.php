<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('merchants', function (Blueprint $table) {
        $table->id();

        // Relationship: merchant belongs to a user
        $table->foreignId('user_id')
              ->constrained('users')
              ->onDelete('cascade'); // delete merchant if user is deleted

        $table->string('name');
        $table->string('api_key')->unique();
        $table->string('callback_url')->nullable();
        $table->enum('status', ['active', 'inactive'])->default('active');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};
