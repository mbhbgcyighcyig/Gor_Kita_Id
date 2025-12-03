<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $existingAdmin = DB::table('users')->where('email', 'admin@gor.com')->first();
        
        if (!$existingAdmin) {
            DB::table('users')->insert([
                'name' => 'Admin GOR',
                'email' => 'admin@gor.com',
                'phone' => '08210017726', // tambahkan phone
                'role' => 'admin',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Admin user created successfully!');
        } else {
            $this->command->info('Admin user already exists!');
        }
    }
}