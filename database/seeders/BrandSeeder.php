<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'TechPro', 'description' => 'Premium technology products'],
            ['name' => 'StyleCo', 'description' => 'Fashion-forward clothing brand'],
            ['name' => 'HomeEssentials', 'description' => 'Quality home and garden products'],
            ['name' => 'SportMax', 'description' => 'Professional sports equipment'],
            ['name' => 'BookWorm', 'description' => 'Curated book collection'],
            ['name' => 'PlayTime', 'description' => 'Fun toys and games'],
            ['name' => 'BeautyPlus', 'description' => 'Health and beauty essentials'],
            ['name' => 'AutoParts', 'description' => 'Reliable automotive parts'],
        ];

        foreach ($brands as $brand) {
            $exists = DB::table('brands')->where('slug', Str::slug($brand['name']))->exists();
            if (!$exists) {
                DB::table('brands')->insert([
                    'name' => $brand['name'],
                    'slug' => Str::slug($brand['name']),
                    'description' => $brand['description'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
