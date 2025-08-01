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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('puzzle_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->nullable()->onDelete('cascade');
            $table->string('word', 255);
            $table->integer('score');
            $table->text('remaining_letters');
            $table->boolean('is_valid_word')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
