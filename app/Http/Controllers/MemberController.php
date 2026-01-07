<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Membership;
use App\Models\Payment;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of members
     */
    public function index(Request $request)
    {
        $query = Member::with('activeMembership');
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $members = $query->latest()->paginate(10);
        
        return view('members.index', compact('members'));
    }

    /**
     * Show the form for creating a new member
     */
    public function create()
    {
        $personalTrainers = \App\Models\PersonalTrainer::where('is_active', true)->get();
        $packets = \App\Models\Packet::where('is_active', true)
            ->whereIn('type', ['membership', 'daily'])
            ->orderBy('type')
            ->orderBy('duration_days')
            ->get();
        
        return view('members.create', compact('personalTrainers', 'packets'));
    }

    /**
     * Store a newly created member
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:members,phone',
            'email' => 'nullable|email|unique:members,email',
            'address' => 'nullable|string',
            'personal_trainer_id' => 'nullable|exists:personal_trainers,id',
            'fitness_goals' => 'nullable|string',
            'packet_id' => 'required|exists:packets,id',
            'start_date' => 'required|date',
            'payment_method' => 'required|in:cash,qris,transfer'
        ]);

        $packet = \App\Models\Packet::findOrFail($request->packet_id);
        $endDate = \Carbon\Carbon::parse($request->start_date)->addDays($packet->duration_days);
        
        $ptPrice = 0;
        if ($request->personal_trainer_id) {
            $trainer = \App\Models\PersonalTrainer::find($request->personal_trainer_id);
            $ptPrice = $trainer ? $trainer->hourly_rate : 0;
        }

        // Create member
        $member = Member::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'personal_trainer_id' => $request->personal_trainer_id,
            'fitness_goals' => $request->fitness_goals,
            'status' => 'active'
        ]);

        $totalPrice = $packet->price + $ptPrice;

        // Create membership
        $membership = Membership::create([
            'member_id' => $member->id,
            'packet_id' => $packet->id,
            'type' => $packet->type,
            'start_date' => $request->start_date,
            'end_date' => $endDate,
            'price' => $totalPrice,
            'status' => 'active'
        ]);

        // Create payment record
        Payment::create([
            'member_id' => $member->id,
            'membership_id' => $membership->id,
            'amount' => $totalPrice,
            'payment_method' => $request->payment_method,
            'status' => 'completed',
            'payment_date' => now(),
            'notes' => 'Pembayaran membership saat pendaftaran',
            'user_id' => auth()->id()
        ]);

        return redirect()->route('members.index')
            ->with('success', 'Member dan membership berhasil ditambahkan.');
    }

    /**
     * Display the specified member
     */
    public function show(Member $member)
    {
        $member->load(['memberships.payments', 'payments']);
        
        return view('members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified member
     */
    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified member
     */
    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:members,phone,' . $member->id,
            'email' => 'nullable|email|unique:members,email,' . $member->id,
            'address' => 'nullable|string'
        ]);

        $member->update($request->all());

        return redirect()->route('members.index')
            ->with('success', 'Member berhasil diupdate.');
    }

    /**
     * Remove the specified member
     */
    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Member berhasil dihapus.');
    }

    /**
     * Show expired members for renewal
     */
    public function expired()
    {
        $expiredMembers = Member::where('status', 'expired')
            ->with(['memberships' => function($query) {
                $query->latest();
            }])
            ->latest()
            ->paginate(10);
            
        return view('members.expired', compact('expiredMembers'));
    }

    /**
     * Show renewal form for expired member
     */
    public function renew(Member $member)
    {
        if ($member->status !== 'expired') {
            return redirect()->route('members.index')
                ->with('error', 'Member ini masih aktif.');
        }
        
        $member->load(['memberships' => function($query) {
            $query->orderBy('end_date', 'desc');
        }]);
        
        return view('members.renew', compact('member'));
    }

    /**
     * Process membership renewal
     */
    public function processRenewal(Request $request, Member $member)
    {
        $request->validate([
            'membership_type' => 'required|in:daily,monthly,yearly,custom',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'membership_price' => 'required_if:membership_type,custom|nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,qris,transfer'
        ]);

        // Determine membership price
        $price = match($request->membership_type) {
            'daily' => 25000,
            'monthly' => 150000,
            'yearly' => 1500000,
            'custom' => $request->membership_price
        };

        // Create new membership
        $membership = Membership::create([
            'member_id' => $member->id,
            'type' => $request->membership_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'price' => $price,
            'status' => 'active'
        ]);

        // Create payment record
        Payment::create([
            'member_id' => $member->id,
            'membership_id' => $membership->id,
            'amount' => $price,
            'payment_method' => $request->payment_method,
            'status' => 'completed',
            'payment_date' => now(),
            'notes' => 'Perpanjangan membership'
        ]);

        // Update member status to active
        $member->update(['status' => 'active']);

        return redirect()->route('members.expired')
            ->with('success', 'Membership berhasil diperpanjang.');
    }
}