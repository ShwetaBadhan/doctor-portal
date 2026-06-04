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
        Schema::table('appointments', function (Blueprint $table) {
            // Adds the column after 'cerebral_fluid'
            $table->string('delusion')->nullable()->after('cerebral_fluid');
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('delusion');
        });
    }
};
