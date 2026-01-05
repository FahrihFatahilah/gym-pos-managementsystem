<?php

namespace App\Http\Controllers;

use App\Models\DailyUser;
use App\Models\Member;
use App\Models\GymSetting;
use App\Models\PersonalTrainer;
use Illuminate\Http\Request;

class DailyUserController extends Controller
{
    public function index(Request $request)
    {
        $query = DailyUser::with('personalTrainer');
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('date')) {
            $query->whereDate('visit_date', $request->date);
        }
        
        $dailyUsers = $query->latest('visit_date')->paginate(15);
        
        return view('daily-users.index', compact('dailyUsers'));
    }

    public function create()
    {
        $personalTrainers = \App\Models\PersonalTrainer::where('is_active', true)->get();
        return view('daily-users.create', compact('personalTrainers'));
    }

    public function checkHistory(Request $request)
    {
        $phone = $request->phone;
        
        // Cek history daily users
        $dailyHistory = DailyUser::where('phone', $phone)
            ->with('personalTrainer')
            ->orderBy('visit_date', 'desc')
            ->get();
        
        // Cek history membership
        $memberHistory = Member::where('phone', $phone)
            ->with(['memberships' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->first();
        
        return response()->json([
            'daily_history' => $dailyHistory,
            'member_history' => $memberHistory
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'personal_trainer_id' => 'nullable|exists:personal_trainers,id',
            'fitness_goals' => 'nullable|string',
            'visit_date' => 'required|date',
            'daily_price_type' => 'required|in:regular,premium',
            'payment_method' => 'required|in:cash,qris,transfer'
        ]);

        $gymSettings = GymSetting::getSettings();
        $ptPrice = 0;
        
        if ($request->personal_trainer_id) {
            $trainer = PersonalTrainer::find($request->personal_trainer_id);
            $ptPrice = $trainer ? $trainer->hourly_rate : 0;
        }

        $dailyPrice = $request->daily_price_type === 'premium' 
            ? $gymSettings->daily_price_premium 
            : $gymSettings->daily_price_regular;

        $totalAmount = $dailyPrice + $ptPrice;

        DailyUser::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'personal_trainer_id' => $request->personal_trainer_id,
            'fitness_goals' => $request->fitness_goals,
            'visit_date' => $request->visit_date,
            'amount_paid' => $totalAmount,
            'payment_method' => $request->payment_method
        ]);

        return redirect()->route('daily-users.index')
            ->with('success', 'Pengunjung Harian berhasil ditambahkan.');
    }

    public function show(DailyUser $dailyUser)
    {
        $dailyUser->load('personalTrainer');
        return view('daily-users.show', compact('dailyUser'));
    }

    public function destroy(DailyUser $dailyUser)
    {
        $dailyUser->delete();
        return redirect()->route('daily-users.index')
            ->with('success', 'Pengunjung Harian berhasil dihapus.');
    }
}