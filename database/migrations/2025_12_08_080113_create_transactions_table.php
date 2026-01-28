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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('merchant_id')->constrained()->cascadeOnDelete();

        $table->string('msisdn');
        $table->decimal('amount', 10, 2);
        $table->string('gateway');
        $table->string('reference')->unique();
        $table->string('status')->default('PENDING');

        $table->string('wirepick_gw_id')->nullable();
        $table->string('mno_request_id')->nullable();
        $table->string('mno_fintxn_id')->nullable();

        $table->json('raw_request')->nullable();
        $table->json('raw_response')->nullable();

        $table->string('callback_url')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
