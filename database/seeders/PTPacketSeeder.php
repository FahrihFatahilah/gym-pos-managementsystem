<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Packet;

class PTPacketSeeder extends Seeder
{
    public function run(): void
    {
        $packets = [
            // Individual PT Packets
            [
                'name' => '1 Sesi',
                'type' => 'individual',
                'sessions' => 1,
                'duration_days' => 2,
                'price' => 90000,
                'duration_minutes' => 75,
                'description' => 'Non Treadmill',
                'is_active' => true
            ],
            [
                'name' => '4 Sesi',
                'type' => 'individual',
                'sessions' => 4,
                'duration_days' => 15,
                'price' => 340000,
                'duration_minutes' => 75,
                'description' => 'Non Treadmill',
                'is_active' => true
            ],
            [
                'name' => '8 Sesi',
                'type' => 'individual',
                'sessions' => 8,
                'duration_days' => 20,
                'price' => 680000,
                'duration_minutes' => 75,
                'description' => 'Sudah termasuk Treadmill per sesinya',
                'is_active' => true
            ],
            [
                'name' => '16 Sesi',
                'type' => 'individual',
                'sessions' => 16,
                'duration_days' => 30,
                'price' => 1280000,
                'duration_minutes' => 75,
                'description' => 'Sudah termasuk member Treadmill selama 30 hari',
                'is_active' => true
            ],
            
            // Couple PT Packets
            [
                'name' => '1 Sesi',
                'type' => 'couple',
                'sessions' => 1,
                'duration_days' => 2,
                'price' => 140000,
                'duration_minutes' => 100,
                'description' => 'Non Treadmill',
                'is_active' => true
            ],
            [
                'name' => '4 Sesi',
                'type' => 'couple',
                'sessions' => 4,
                'duration_days' => 15,
                'price' => 530000,
                'duration_minutes' => 100,
                'description' => 'Non Treadmill',
                'is_active' => true
            ],
            [
                'name' => '8 Sesi',
                'type' => 'couple',
                'sessions' => 8,
                'duration_days' => 20,
                'price' => 1000000,
                'duration_minutes' => 100,
                'description' => 'Sudah termasuk Treadmill per sesinya',
                'is_active' => true
            ],
            [
                'name' => '16 Sesi',
                'type' => 'couple',
                'sessions' => 16,
                'duration_days' => 30,
                'price' => 2000000,
                'duration_minutes' => 100,
                'description' => 'Sudah termasuk member Treadmill selama 30 hari',
                'is_active' => true
            ],
            
            // Group PT Packets
            [
                'name' => '1 Sesi',
                'type' => 'group',
                'sessions' => 1,
                'duration_days' => 3,
                'price' => 180000,
                'duration_minutes' => 120,
                'description' => 'Minimal 3 orang (non Treadmill) dan setiap penambahan peserta dikenakan 60,000/orang',
                'is_active' => true
            ]
        ];

        foreach ($packets as $packet) {
            Packet::create($packet);
        }
    }
}
