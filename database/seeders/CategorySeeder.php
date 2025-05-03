<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define categories
        $categories = [
            'Medicine' => 'Prescription and over-the-counter medications',
            'Healthcare' => 'Medical devices and healthcare equipment',
            'Beauty' => 'Skincare, haircare, and cosmetics',
            'Lab Test' => 'Laboratory tests and diagnostics',
            'Baby & Mom Care' => 'Products for babies and mothers',
            'Home Care' => 'Home healthcare and hygiene products',
            'Supplement' => 'Vitamins and dietary supplements',
            'Food & Nutrition' => 'Healthy food and nutritional products'
        ];

        // Insert categories
        foreach ($categories as $name => $description) {
            $slug = Str::slug($name);
            
            // Check if the category already exists
            $existingCategory = Category::where('slug', $slug)->first();
            
            if (!$existingCategory) {
                Category::create([
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $description,
                ]);
            }
        }
    }
}
