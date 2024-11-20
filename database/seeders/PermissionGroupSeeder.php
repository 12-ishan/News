<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permission_group')->insert([
            [
                'name' => 'News Manager',
                'status' => 1,
                'sortOrder' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Landing Page Manager',
                'status' => 1,
                'sortOrder' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'General Settings',
                'status' => 1,
                'sortOrder' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                 'name' => 'Contact Leads',
                 'status' => 1,
                 'sortOrder' => 4,
                 'created_at' => now(),
                 'updated_at' => now(),
            ],
            [
                'name' => 'News Category Manager',
                'status' => 1,
                'sortOrder' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ]
            
        ]);
    }
}
