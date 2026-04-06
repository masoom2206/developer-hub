<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Web Development', 'icon' => 'globe', 'description' => 'General web development tutorials and articles'],
            ['name' => 'Laravel', 'icon' => 'code', 'description' => 'Laravel framework tips, tricks, and tutorials'],
            ['name' => 'JavaScript', 'icon' => 'terminal', 'description' => 'JavaScript ecosystem and frontend development'],
            ['name' => 'CSS & Design', 'icon' => 'palette', 'description' => 'CSS techniques, UI/UX design, and styling'],
            ['name' => 'DevTools', 'icon' => 'wrench', 'description' => 'Developer tools, IDEs, and productivity'],
            ['name' => 'Career', 'icon' => 'briefcase', 'description' => 'Career advice, interviews, and professional growth'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'icon' => $category['icon'],
                'description' => $category['description'],
            ]);
        }
    }
}
