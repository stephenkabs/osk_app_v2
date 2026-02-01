<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();

            /* ======================
               BASIC IDENTIFICATION
            ====================== */
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('nrc_no')->index();
            $table->string('phone_number')->nullable();
            $table->string('previous_address')->nullable();

            /* ======================
               FILES
            ====================== */
            $table->string('nrc_image')->nullable();
            $table->string('agreement_signature')->nullable();

            /* ======================
               USER & STATUS
            ====================== */
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->index();

            /* ======================
               QUICKBOOKS
            ====================== */
            $table->string('quickbooks_customer_id')->nullable()->index();

            /* ======================
               AGREEMENT TRACKING
            ====================== */
            $table->boolean('agreement_accepted')->default(false);
            $table->timestamp('agreement_accepted_at')->nullable();
            $table->string('agreement_version')->nullable();
            $table->longText('agreement_text')->nullable();
            $table->string('agreement_ip')->nullable();
            $table->string('agreement_user_agent', 255)->nullable();

            /* ======================
               SLUG (ROUTE MODEL BINDING)
            ====================== */
            $table->string('slug')->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
