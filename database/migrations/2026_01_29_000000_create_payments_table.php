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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // 🔗 Relationships
            $table->foreignId('partner_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('property_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // 💰 Payment details
            $table->decimal('amount', 14, 2);
            $table->string('currency', 5)->default('ZMW');
            $table->string('method', 30); // MTN, AIRTEL, CARD, etc

            // 🧾 Wirepick / reference tracking
            $table->string('reference')->unique();

            // 📊 Status lifecycle
            // pending | paid | failed | payment_completed
            $table->string('status', 30)->default('pending');

            // 🏘 Shares purchased
            $table->decimal('my_total_shares', 14, 4)->nullable();

            // 🧠 Optional metadata (future-proofing)
            $table->json('meta')->nullable();

            $table->timestamps();

            // 🔍 Helpful indexes
            $table->index('status');
            $table->index('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
