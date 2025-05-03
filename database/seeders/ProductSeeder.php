<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all categories
        $categories = Category::all();

        // Sample products data
        $products = [
            [
                'name' => 'Napa Extra Tablet',
                'description' => 'Paracetamol 500mg + Caffeine 65mg. Relieves mild to moderate pain and reduces fever.',
                'price' => 35.00,
                'stock' => 100,
                'manufacturer' => 'Beximco Pharmaceuticals Ltd.',
                'category_id' => $categories->where('slug', 'medicine')->first()->id,
                'featured' => true,
            ],
            [
                'name' => 'Seclo 20mg Capsule',
                'description' => 'Omeprazole 20mg. Used to treat certain stomach and esophagus problems such as acid reflux and ulcers.',
                'price' => 8.50,
                'stock' => 80,
                'manufacturer' => 'Square Pharmaceuticals Ltd.',
                'category_id' => $categories->where('slug', 'medicine')->first()->id,
                'featured' => false,
            ],
            [
                'name' => 'Ceevit 250mg Tablet',
                'description' => 'Vitamin C 250mg. Helps maintain healthy skin, blood vessels, bones and cartilage.',
                'price' => 3.00,
                'stock' => 150,
                'manufacturer' => 'ACME Laboratories Ltd.',
                'category_id' => $categories->where('slug', 'medicine')->first()->id,
                'featured' => false,
            ],
            [
                'name' => 'Digital Blood Pressure Monitor',
                'description' => 'Automatic digital blood pressure monitor with memory function. Easy to use and accurate readings.',
                'price' => 2500.00,
                'stock' => 25,
                'manufacturer' => 'Omron',
                'category_id' => $categories->where('slug', 'healthcare')->first()->id,
                'featured' => true,
            ],
            [
                'name' => 'Glucometer Kit',
                'description' => 'Complete blood glucose monitoring system with test strips and lancets.',
                'price' => 1800.00,
                'stock' => 30,
                'manufacturer' => 'Accu-Chek',
                'category_id' => $categories->where('slug', 'healthcare')->first()->id,
                'featured' => false,
            ],
            [
                'name' => 'Infrared Thermometer',
                'description' => 'Non-contact infrared thermometer for accurate temperature measurement.',
                'price' => 1200.00,
                'stock' => 40,
                'manufacturer' => 'Microlife',
                'category_id' => $categories->where('slug', 'healthcare')->first()->id,
                'featured' => false,
            ],
            [
                'name' => 'Moisturizing Face Cream',
                'description' => 'Hydrating face cream for all skin types. Keeps skin soft and supple.',
                'price' => 450.00,
                'stock' => 60,
                'manufacturer' => 'Neutrogena',
                'category_id' => $categories->where('slug', 'beauty')->first()->id,
                'featured' => true,
            ],
            [
                'name' => 'Sunscreen Lotion SPF 50',
                'description' => 'High protection sunscreen lotion that protects against harmful UVA and UVB rays.',
                'price' => 650.00,
                'stock' => 45,
                'manufacturer' => 'La Roche-Posay',
                'category_id' => $categories->where('slug', 'beauty')->first()->id,
                'featured' => false,
            ],
            [
                'name' => 'Complete Blood Count Test',
                'description' => 'Comprehensive blood test that evaluates overall health and detects a wide range of disorders.',
                'price' => 500.00,
                'stock' => 100,
                'manufacturer' => 'MediMart Labs',
                'category_id' => $categories->where('slug', 'lab-test')->first()->id,
                'featured' => true,
            ],
            [
                'name' => 'Vitamin D Test',
                'description' => 'Test to measure the level of Vitamin D in your blood.',
                'price' => 1200.00,
                'stock' => 100,
                'manufacturer' => 'MediMart Labs',
                'category_id' => $categories->where('slug', 'lab-test')->first()->id,
                'featured' => false,
            ],
            [
                'name' => 'Baby Diaper Pack',
                'description' => 'Ultra-absorbent diapers for babies. Pack of 40 pieces.',
                'price' => 1100.00,
                'stock' => 70,
                'manufacturer' => 'Pampers',
                'category_id' => $categories->where('slug', 'baby-mom-care')->first()->id,
                'featured' => true,
            ],
            [
                'name' => 'Baby Shampoo',
                'description' => 'Gentle, tear-free shampoo specially formulated for babies.',
                'price' => 280.00,
                'stock' => 55,
                'manufacturer' => 'Johnson & Johnson',
                'category_id' => $categories->where('slug', 'baby-mom-care')->first()->id,
                'featured' => false,
            ],
            [
                'name' => 'Hand Sanitizer',
                'description' => '70% alcohol-based hand sanitizer that kills 99.9% of germs. 250ml bottle.',
                'price' => 150.00,
                'stock' => 120,
                'manufacturer' => 'Lifebuoy',
                'category_id' => $categories->where('slug', 'home-care')->first()->id,
                'featured' => true,
            ],
            [
                'name' => 'Disinfectant Spray',
                'description' => 'Multi-purpose disinfectant spray that kills germs on surfaces. 400ml can.',
                'price' => 320.00,
                'stock' => 90,
                'manufacturer' => 'Dettol',
                'category_id' => $categories->where('slug', 'home-care')->first()->id,
                'featured' => false,
            ],
            [
                'name' => 'Calcium + Vitamin D3 Tablets',
                'description' => 'Dietary supplement for bone health. Contains 500mg Calcium and 400 IU Vitamin D3.',
                'price' => 450.00,
                'stock' => 85,
                'manufacturer' => 'Nature\'s Way',
                'category_id' => $categories->where('slug', 'supplement')->first()->id,
                'featured' => true,
            ],
            [
                'name' => 'Omega-3 Fish Oil Capsules',
                'description' => 'Dietary supplement for heart and brain health. Contains EPA and DHA. 60 capsules.',
                'price' => 850.00,
                'stock' => 75,
                'manufacturer' => 'GNC',
                'category_id' => $categories->where('slug', 'supplement')->first()->id,
                'featured' => false,
            ],
            [
                'name' => 'Protein Powder',
                'description' => 'Whey protein powder for muscle recovery and growth. 1kg pack.',
                'price' => 2800.00,
                'stock' => 40,
                'manufacturer' => 'Optimum Nutrition',
                'category_id' => $categories->where('slug', 'food-nutrition')->first()->id,
                'featured' => true,
            ],
            [
                'name' => 'Organic Honey',
                'description' => 'Pure, raw, and unfiltered organic honey. 500g jar.',
                'price' => 550.00,
                'stock' => 60,
                'manufacturer' => 'Organic Harvest',
                'category_id' => $categories->where('slug', 'food-nutrition')->first()->id,
                'featured' => false,
            ],
        ];

        // Insert products
        foreach ($products as $productData) {
            Product::create([
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'description' => $productData['description'],
                'price' => $productData['price'],
                'stock' => $productData['stock'],
                'manufacturer' => $productData['manufacturer'],
                'category_id' => $productData['category_id'],
                'featured' => $productData['featured'],
                'status' => 'active',
            ]);
        }
    }
}
