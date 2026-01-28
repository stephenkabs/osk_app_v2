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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('property_name');
            $table->string('slug')->unique();
            $table->decimal('bidding_price', 15, 2)->nullable();
            $table->string('address')->nullable();
            $table->string('property_contact')->nullable();
            $table->string('property_email')->nullable();
            $table->integer('total_shares')->default(0)->nullable(); // Inventory quantity
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->integer('radius_m')->nullable()->default(100);

            $table->string('logo_path')->nullable();
            $table->json('images')->nullable();

            $table->string('qbo_item_id')->nullable();
            $table->decimal('qbo_unit_price', 15, 2)->nullable();
            $table->integer('qbo_qty_on_hand')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
