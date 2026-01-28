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
Schema::create('property_payments', function (Blueprint $table) {
    $table->id();

    $table->foreignId('property_id')->constrained()->cascadeOnDelete();
    $table->foreignId('lease_agreement_id')->constrained('property_lease_agreements')->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // tenant

    $table->date('payment_date');
    $table->string('payment_month'); // e.g. 2025-09
    $table->decimal('amount', 12, 2);

    $table->enum('method', ['cash','bank','mobile_money','manual'])->default('manual');
    $table->string('reference')->nullable();

    $table->enum('status', ['paid','partial','reversed'])->default('paid');

    $table->foreignId('recorded_by')->constrained('users'); // landlord/admin
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_payments');
    }
};
