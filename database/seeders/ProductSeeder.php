<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get category and brand IDs
        $electronicsId = DB::table('categories')->where('slug', 'electronics')->value('id');
        $clothingId = DB::table('categories')->where('slug', 'clothing')->value('id');
        $homeId = DB::table('categories')->where('slug', 'home-garden')->value('id');
        
        $techProId = DB::table('brands')->where('slug', 'techpro')->value('id');
        $styleCoId = DB::table('brands')->where('slug', 'styleco')->value('id');
        $homeEssentialsId = DB::table('brands')->where('slug', 'homeessentials')->value('id');

        $products = [
            [
                'name' => 'Wireless Bluetooth Headphones',
                'slug' => 'wireless-bluetooth-headphones',
                'sku' => 'TECH-001',
                'category_id' => $electronicsId,
                'brand_id' => $techProId,
                'short_description' => 'Premium wireless headphones with noise cancellation',
                'description' => 'Experience crystal-clear audio with our premium wireless Bluetooth headphones. Features active noise cancellation, 30-hour battery life, and comfortable over-ear design.',
                'price' => 199.99,
                'compare_at_price' => 249.99,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Smart Watch Pro',
                'slug' => 'smart-watch-pro',
                'sku' => 'TECH-002',
                'category_id' => $electronicsId,
                'brand_id' => $techProId,
                'short_description' => 'Advanced smartwatch with health tracking',
                'description' => 'Stay connected and monitor your health with our advanced smartwatch. Includes heart rate monitoring, GPS, sleep tracking, and smartphone notifications.',
                'price' => 299.99,
                'compare_at_price' => 349.99,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Classic Denim Jacket',
                'slug' => 'classic-denim-jacket',
                'sku' => 'STYLE-001',
                'category_id' => $clothingId,
                'brand_id' => $styleCoId,
                'short_description' => 'Timeless denim jacket for every wardrobe',
                'description' => 'A timeless classic that never goes out of style. Made from premium denim with a comfortable fit and durable construction.',
                'price' => 79.99,
                'compare_at_price' => 99.99,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Modern Coffee Maker',
                'slug' => 'modern-coffee-maker',
                'sku' => 'HOME-001',
                'category_id' => $homeId,
                'brand_id' => $homeEssentialsId,
                'short_description' => 'Programmable coffee maker with thermal carafe',
                'description' => 'Start your day right with our programmable coffee maker. Features a thermal carafe to keep coffee hot for hours, programmable timer, and auto-shutoff.',
                'price' => 89.99,
                'compare_at_price' => 119.99,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Ergonomic Office Chair',
                'slug' => 'ergonomic-office-chair',
                'sku' => 'HOME-002',
                'category_id' => $homeId,
                'brand_id' => $homeEssentialsId,
                'short_description' => 'Comfortable ergonomic chair for long work sessions',
                'description' => 'Designed for comfort during long work sessions. Features adjustable height, lumbar support, and breathable mesh back.',
                'price' => 249.99,
                'compare_at_price' => 299.99,
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $product) {
            $exists = DB::table('products')->where('slug', $product['slug'])->exists();
            if (!$exists && $product['category_id'] && $product['brand_id']) {
                DB::table('products')->insert(array_merge($product, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }
}
