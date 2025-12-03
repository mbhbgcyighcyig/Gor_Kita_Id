<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FieldSeeder extends Seeder
{
    public function run()
    {
        $field = [
            // Futsal
            ['name' => 'Futsal 1', 'type' => 'futsal', 'price_per_hour' => 150000, 'description' => 'Lapangan futsal standar internasional dengan rumput sintetis premium', 'is_active' => true],
            ['name' => 'Futsal 2', 'type' => 'futsal', 'price_per_hour' => 150000, 'description' => 'Lapangan futsal dengan pencahayaan LED dan AC', 'is_active' => true],
            ['name' => 'Futsal 3', 'type' => 'futsal', 'price_per_hour' => 150000, 'description' => 'Lapangan futsal outdoor dengan view terbaik', 'is_active' => true],
            
            // Badminton
            ['name' => 'Badminton 1', 'type' => 'badminton', 'price_per_hour' => 40000, 'description' => 'Lapangan badminton indoor dengan karpet BWF standard', 'is_active' => true],
            ['name' => 'Badminton 2', 'type' => 'badminton', 'price_per_hour' => 40000, 'description' => 'Lapangan badminton AC dengan lighting profesional', 'is_active' => true],
            ['name' => 'Badminton 3', 'type' => 'badminton', 'price_per_hour' => 40000, 'description' => 'Lapangan badminton dengan fasilitas shower', 'is_active' => true],
            ['name' => 'Badminton 4', 'type' => 'badminton', 'price_per_hour' => 40000, 'description' => 'Lapangan badminton VIP dengan sound system', 'is_active' => true],
            
            // Mini Soccer
            ['name' => 'Mini Soccer 1', 'type' => 'minisoccer', 'price_per_hour' => 400000, 'description' => 'Lapangan mini soccer outdoor dengan rumput sintetis terbaik', 'is_active' => true],
        ];

        foreach ($fields as $field) {
            DB::table('fields')->insert([
                'name' => $field['name'],
                'type' => $field['type'],
                'price_per_hour' => $field['price_per_hour'],
                'description' => $field['description'],
                'is_active' => $field['is_active'],
                
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}