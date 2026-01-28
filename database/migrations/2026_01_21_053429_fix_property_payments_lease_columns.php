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
        Schema::table('property_payments', function (Blueprint $table) {

            /**
             * ❌ REMOVE WRONG / LEGACY COLUMN
             */
            if (Schema::hasColumn('property_payments', 'property_lease_agreement_id')) {
                $table->dropForeign(['property_lease_agreement_id']);
                $table->dropColumn('property_lease_agreement_id');
            }

            /**
             * ✅ ADD CORRECT LEASE COLUMN (if missing)
             */
            if (!Schema::hasColumn('property_payments', 'lease_id')) {
                $table->foreignId('lease_id')
                    ->after('property_id')
                    ->constrained('property_lease_agreements')
                    ->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_payments', function (Blueprint $table) {

            /**
             * Restore old column (rollback safety)
             */
            $table->foreignId('property_lease_agreement_id')
                ->after('property_id')
                ->constrained('property_lease_agreements')
                ->cascadeOnDelete();

            /**
             * Remove new column
             */
            $table->dropForeign(['lease_id']);
            $table->dropColumn('lease_id');
        });
    }
};
