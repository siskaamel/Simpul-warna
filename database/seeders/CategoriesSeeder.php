<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categories;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categories::insert(
            [
                [
                    'name' => 'Gelang', 
                    'slug' => 'Gelang',
                    'description' => 'Gelang Tali',
                ],
                [
                    'name' => 'Kalung', 
                    'slug' => 'Kalung',
                    'description' => 'Kalung',

                ],
                [
                    'name' => 'Cincin', 
                    'slug' => 'Cincin',
                    'description' => 'Cincin',

                ],
                [
                    'name' => 'Anting', 
                    'slug' => 'Anting',
                    'description' => 'Anting',

                ],
            ]
        );
        
    }
}
