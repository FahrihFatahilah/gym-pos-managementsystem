<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@flexgym.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'branch_id' => 1
        ]);

        // Staff/Kasir User
        User::create([
            'name' => 'Staff Kasir Bima',
            'email' => 'staff@flexgym.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'branch_id' => 1
        ]);

        // Owner User
        User::create([
            'name' => 'Owner Gym',
            'email' => 'owner@flexgym.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'branch_id' => null
        ]);
        
        
    }
}