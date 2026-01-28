<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('property_lease_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();

            $table->text('parties_clause')->nullable();
            $table->text('property_clause')->nullable();
            $table->text('term_clause')->nullable();
            $table->text('rent_clause')->nullable();
            $table->text('deposit_clause')->nullable();
            $table->text('maintenance_clause')->nullable();
            $table->text('termination_clause')->nullable();
            $table->text('additional_terms')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_lease_templates');
    }
};
