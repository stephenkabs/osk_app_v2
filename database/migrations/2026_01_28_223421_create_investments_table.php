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
Schema::create('investments', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')->constrained()->cascadeOnDelete();       // investor user
    $table->foreignId('property_id')->constrained()->cascadeOnDelete();    // QBO item mapped property

    $table->unsignedInteger('shares');                                    // how many shares bought
    $table->decimal('price_per_share', 12, 2);                             // qbo_unit_price snapshot
    $table->decimal('total_amount', 12, 2);                                // shares * price/share

    $table->enum('status', ['pending','confirmed','cancelled','failed'])
          ->default('pending');

    // QBO sync tracking
    $table->string('qbo_sync_status')->nullable();                         // pending|success|failed
    $table->text('qbo_sync_error')->nullable();
    $table->timestamp('confirmed_at')->nullable();

    $table->timestamps();

    $table->index(['property_id','status']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
