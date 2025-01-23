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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama tugas
            $table->enum('category', ['All', 'Work', 'Diary', 'Practice'])->default('All'); // Kategori
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium'); // Prioritas
            $table->dateTime('deadline')->nullable(); // Deadline
            $table->timestamps(); // Waktu create/update
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
