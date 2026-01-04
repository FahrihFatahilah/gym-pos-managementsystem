<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Bima',
                'code' => 'BMA001',
                'address' => 'Jl. Soekarno Hatta',
                'phone' => '(021) 5555-1234',
                'email' => 'bima@flexgym.com',
                'is_active' => true
            ]
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}