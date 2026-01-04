<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Payment;
use Carbon\Carbon;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            [
                'name' => 'John Doe',
                'phone' => '081234567890',
                'email' => 'john@example.com',
                'address' => 'Jl. Sudirman No. 123, Jakarta',
                'status' => 'active'
            ],[
                'name' => 'Bob Johnson',
                'phone' => '081234567892',
                'email' => 'bob@example.com',
                'address' => 'Jl. Gatot Subroto No. 789, Jakarta',
                'status' => 'expired'
            ],
        ];

        foreach ($members as $memberData) {
            $member = Member::create($memberData);
            
            // Create membership for active members
            if ($member->status === 'active') {
                $membership = Membership::create([
                    'member_id' => $member->id,
                    'type' => 'monthly',
                    'start_date' => Carbon::now()->startOfMonth(),
                    'end_date' => Carbon::now()->endOfMonth(),
                    'price' => 150000,
                    'status' => 'active'
                ]);

                // Create payment record
                Payment::create([
                    'member_id' => $member->id,
                    'membership_id' => $membership->id,
                    'amount' => 150000,
                    'payment_method' => 'cash',
                    'status' => 'completed',
                    'payment_date' => Carbon::now()->startOfMonth(),
                    'notes' => 'Pembayaran membership bulanan'
                ]);
            } else {
                // Create expired membership
                $membership = Membership::create([
                    'member_id' => $member->id,
                    'type' => 'monthly',
                    'start_date' => Carbon::now()->subMonth()->startOfMonth(),
                    'end_date' => Carbon::now()->subMonth()->endOfMonth(),
                    'price' => 150000,
                    'status' => 'expired'
                ]);

                Payment::create([
                    'member_id' => $member->id,
                    'membership_id' => $membership->id,
                    'amount' => 150000,
                    'payment_method' => 'cash',
                    'status' => 'completed',
                    'payment_date' => Carbon::now()->subMonth()->startOfMonth(),
                    'notes' => 'Pembayaran membership bulanan (expired)'
                ]);
            }
        }
    }
}