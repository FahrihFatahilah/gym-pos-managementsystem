<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GymSetting;

class GymSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GymSetting::create([
            'gym_name' => 'Flex Gym',
            'gym_address' => 'Jl. Soekarno Hatta',
            'gym_phone' => '(021) 5555-1234',
            'gym_email' => 'info@flexgym.com',
            'gym_website' => 'https://flexgym.com',
            'gym_description' => 'Gym modern dengan fasilitas lengkap dan trainer berpengalaman. Kami berkomitmen untuk membantu Anda mencapai tubuh ideal dan hidup sehat.',
            'receipt_footer' => "Terima kasih atas kunjungan Anda!\nSemoga sehat selalu\nFollow IG: @flexgym",
            'membership_monthly_price' => 150000,
            'membership_yearly_price' => 1500000,
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta'
        ]);
    }
}