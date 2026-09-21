<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $categories = [
        //     ['name' => 'Sports', 'description' => 'Sports events like football, basketball, tennis, etc.'],
        //     ['name' => 'Music', 'description' => 'Music events like concerts, festivals, etc.'],
        //     ['name' => 'Art', 'description' => 'Art events like painting, sculpture, etc.'],
        //     ['name' => 'Technology', 'description' => 'Technology events like conferences, workshops, etc.'],
        // ];

        // foreach ($categories as $category) {
        //     Category::create($category);
        // }

        Category::factory(20)->create();
    }
}
