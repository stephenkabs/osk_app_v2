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
    // Schema::table('transactions', function (Blueprint $table) {
    //     if (Schema::hasColumn('transactions', 'wirepick_gw_id')) {
    //         $table->renameColumn('wirepick_gw_id', 'gw_id');
    //     }
    // });
}

    /**
     * Reverse the migrations.
     */
public function down()
{
    // Schema::table('transactions', function (Blueprint $table) {
    //     if (Schema::hasColumn('transactions', 'gw_id')) {
    //         $table->renameColumn('gw_id', 'wirepick_gw_id');
    //     }
    // });
}
};
