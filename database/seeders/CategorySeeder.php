<?php

namespace Database\Seeders; //

use Illuminate\Database\Seeder;
use App\Models\Category; // 

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::firstOrCreate(['name' => 'Work'], ['is_default' => true]);
        Category::firstOrCreate(['name' => 'Practice'], ['is_default' => true]);
        Category::firstOrCreate(['name' => 'Diary'], ['is_default' => false]);
    }
}

