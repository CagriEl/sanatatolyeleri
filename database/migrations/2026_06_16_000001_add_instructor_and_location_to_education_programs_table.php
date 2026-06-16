<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('education_programs', function (Blueprint $table) {
            $table->string('instructor')->nullable()->after('title');
            $table->string('location')->nullable()->after('age_range');
        });
    }

    public function down(): void
    {
        Schema::table('education_programs', function (Blueprint $table) {
            $table->dropColumn(['instructor', 'location']);
        });
    }
};
