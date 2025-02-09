<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); 
            $table->boolean('is_default')->default(false); // Kategori default (tidak bisa dihapus)
            $table->boolean('is_default_for_users')->default(false); // Admin bisa pilih kategori default untuk user
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
}