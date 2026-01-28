<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('property_payments', function (Blueprint $table) {
            $table->foreignId('lease_id')
                  ->after('property_id')
                  ->constrained('property_lease_agreements')
                  ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('property_payments', function (Blueprint $table) {
            $table->dropForeign(['lease_id']);
            $table->dropColumn('lease_id');
        });
    }
};
