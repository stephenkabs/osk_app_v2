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
  Schema::create('investment_payments', function (Blueprint $table) {
    $table->id();

    $table->foreignId('investment_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();        // investor user

    $table->decimal('amount', 12, 2);
    $table->string('currency')->default('ZMW');

    $table->enum('method', ['mobile_money','bank','manual'])->default('mobile_money');

    $table->string('gateway')->nullable();                                 // airtel|mtn|zamtel
    $table->string('gateway_reference')->nullable();                       // gw_id/txn id etc
    $table->string('gateway_status')->default('pending');                  // pending|success|failed
    $table->json('gateway_payload')->nullable();

    $table->timestamps();

    $table->index(['gateway_status','gateway']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_payments');
    }
};
