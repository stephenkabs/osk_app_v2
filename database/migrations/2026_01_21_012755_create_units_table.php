<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('units')) {
            // Table already exists; skip creating it.
            return;
        }

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('code')->nullable();
            $table->string('slug')->nullable();
            $table->string('type')->nullable();
            $table->unsignedInteger('floor')->nullable();
            $table->unsignedTinyInteger('bedrooms')->nullable();
            $table->unsignedTinyInteger('bathrooms')->nullable();
            $table->decimal('rent_amount', 12, 2)->nullable();
            $table->decimal('deposit_amount', 12, 2)->nullable();
            $table->decimal('size_sq_m', 8, 2)->nullable();
            $table->string('status')->default('available');
            $table->string('photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Only drop if you truly want rollback to remove it
        if (Schema::hasTable('units')) {
            Schema::dropIfExists('units');
        }
    }
};
