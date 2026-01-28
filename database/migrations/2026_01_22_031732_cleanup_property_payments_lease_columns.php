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
    Schema::table('property_payments', function (Blueprint $table) {

        // Drop foreign key first (important)
        if (Schema::hasColumn('property_payments', 'lease_id')) {
            $table->dropForeign(['lease_id']);
            $table->dropColumn('lease_id');
        }

    });
}

    /**
     * Reverse the migrations.
     */
  public function down()
{
    Schema::table('property_payments', function (Blueprint $table) {
        $table->foreignId('lease_id')
              ->constrained('property_lease_agreements')
              ->cascadeOnDelete();
    });
}
};
