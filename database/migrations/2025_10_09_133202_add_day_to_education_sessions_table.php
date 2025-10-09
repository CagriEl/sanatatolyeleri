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
    Schema::table('education_sessions', function (Blueprint $table) {
        $table->string('day')->nullable()->after('education_program_id');
    });
}

public function down()
{
    Schema::table('education_sessions', function (Blueprint $table) {
        $table->dropColumn('day');
    });
}
};
