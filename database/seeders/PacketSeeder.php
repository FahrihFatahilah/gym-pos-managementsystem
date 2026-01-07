<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Packet;

class PacketSeeder extends Seeder
{
    public function run(): void
    {
        $packets = [
            // Daily Pass Packets
            [
                'name' => 'Non Treadmill',
                'type' => 'daily',
                'sessions' => 1,
                'duration_days' => 1,
                'price' => 20000,
                'duration_minutes' => 0,
                'description' => 'Akses gym tanpa treadmill',
                'is_active' => true
            ],
            [
                'name' => 'Treadmill',
                'type' => 'daily',
                'sessions' => 1,
                'duration_days' => 1,
                'price' => 40000,
                'duration_minutes' => 0,
                'description' => 'Akses gym dengan treadmill',
                'is_active' => true
            ],
            
            // Membership Packets - Non Treadmill
            [
                'name' => '1 Bulan Non Treadmill',
                'type' => 'membership',
                'sessions' => 0,
                'duration_days' => 30,
                'price' => 300000,
                'duration_minutes' => 0,
                'description' => 'Membership 1 bulan tanpa treadmill',
                'is_active' => true
            ],
            [
                'name' => '3 Bulan Non Treadmill',
                'type' => 'membership',
                'sessions' => 0,
                'duration_days' => 90,
                'price' => 700000,
                'duration_minutes' => 0,
                'description' => 'Membership 3 bulan tanpa treadmill',
                'is_active' => true
            ],
            [
                'name' => '6 Bulan Non Treadmill',
                'type' => 'membership',
                'sessions' => 0,
                'duration_days' => 180,
                'price' => 1100000,
                'duration_minutes' => 0,
                'description' => 'Membership 6 bulan tanpa treadmill',
                'is_active' => true
            ],
            [
                'name' => '12 Bulan Non Treadmill',
                'type' => 'membership',
                'sessions' => 0,
                'duration_days' => 365,
                'price' => 2000000,
                'duration_minutes' => 0,
                'description' => 'Membership 12 bulan tanpa treadmill',
                'is_active' => true
            ],
            
            // Membership Packets - With Treadmill
            [
                'name' => '1 Bulan Treadmill',
                'type' => 'membership',
                'sessions' => 0,
                'duration_days' => 30,
                'price' => 350000,
                'duration_minutes' => 0,
                'description' => 'Membership 1 bulan dengan treadmill',
                'is_active' => true
            ],
            [
                'name' => '3 Bulan Treadmill',
                'type' => 'membership',
                'sessions' => 0,
                'duration_days' => 90,
                'price' => 850000,
                'duration_minutes' => 0,
                'description' => 'Membership 3 bulan dengan treadmill',
                'is_active' => true
            ],
            [
                'name' => '6 Bulan Treadmill',
                'type' => 'membership',
                'sessions' => 0,
                'duration_days' => 180,
                'price' => 1450000,
                'duration_minutes' => 0,
                'description' => 'Membership 6 bulan dengan treadmill',
                'is_active' => true
            ],
            [
                'name' => '12 Bulan Treadmill',
                'type' => 'membership',
                'sessions' => 0,
                'duration_days' => 365,
                'price' => 2750000,
                'duration_minutes' => 0,
                'description' => 'Membership 12 bulan dengan treadmill',
                'is_active' => true
            ]
        ];

        foreach ($packets as $packet) {
            Packet::create($packet);
        }
    }
}
