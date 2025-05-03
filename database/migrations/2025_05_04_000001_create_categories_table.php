<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default categories
        $categories = [
            ['name' => 'Medicine', 'slug' => 'medicine', 'description' => 'Pharmaceutical products and medications', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Healthcare', 'slug' => 'healthcare', 'description' => 'General healthcare products', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Beauty', 'slug' => 'beauty', 'description' => 'Beauty and personal care products', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lab Test', 'slug' => 'lab-test', 'description' => 'Laboratory test services', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Baby & Mom Care', 'slug' => 'baby-mom-care', 'description' => 'Products for babies and mothers', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Home Care', 'slug' => 'home-care', 'description' => 'Home healthcare products', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Supplement', 'slug' => 'supplement', 'description' => 'Dietary and nutritional supplements', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Food Nutrition', 'slug' => 'food-nutrition', 'description' => 'Nutritional food products', 'created_at' => now(), 'updated_at' => now()]
        ];

        DB::table('categories')->insert($categories);
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
