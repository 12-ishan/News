<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('news_category')->insert([
            [
                'name' => 'World',
                'slug' => 'world',
                'status' => 1,
                'sortorder' => 1,
                'description' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sports',
                'slug' => 'sports',
                'status' => 1,
                'sortorder' => 2,
                'description' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'status' => 1,
                'sortorder' => 3,
                'description' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
