<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('education_programs', function (Blueprint $table) {
            $table->boolean('is_custom_schedule')->default(false)->after('is_open');
        });
    }

    public function down(): void
    {
        Schema::table('education_programs', function (Blueprint $table) {
            $table->dropColumn('is_custom_schedule');
        });
    }
};
