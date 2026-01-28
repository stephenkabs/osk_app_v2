<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('property_lease_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // tenant
            $table->string('slug')->unique();
                $table->longText('terms_snapshot')->nullable();

            // Core lease fields
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->unsignedTinyInteger('payment_day')->default(1); // 1..31

            $table->decimal('rent_amount', 12, 2);
            $table->decimal('deposit_amount', 12, 2)->nullable();
            $table->decimal('utilities_cap', 12, 2)->nullable(); // e.g. shared utilities cap

            // Banking / payment info
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();

            // Parties meta (optional copies for the PDF print)
            $table->string('landlord_name')->nullable();
            $table->string('landlord_email')->nullable();
            $table->string('tenant_email')->nullable();
            $table->string('tenant_id_no')->nullable();

            $table->string('reference')->nullable(); // internal ref
            $table->string('lease_number')->nullable(); // public doc number

            $table->enum('status', ['pending','active','ended'])->default('active');
            $table->date('signed_at')->nullable();

            $table->text('notes')->nullable();
            $table->string('tenant_signature_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_lease_agreements');
    }
};
