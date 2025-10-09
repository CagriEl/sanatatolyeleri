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
    Schema::create('education_sessions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('education_program_id')->constrained()->onDelete('cascade');
        $table->time('start_time');
        $table->time('end_time');
        $table->integer('quota')->default(10);
        $table->integer('registered')->default(0);
        $table->integer('sort')->nullable(); // 🔹 Bu satırı ekledik
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_sessions');
    }
};
