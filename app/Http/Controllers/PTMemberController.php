<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class PTMemberController extends Controller
{
    public function index()
    {
        // Pastikan user adalah PT dan memiliki personal_trainer_id
        if (!auth()->user()->personal_trainer_id) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $members = Member::where('personal_trainer_id', auth()->user()->personal_trainer_id)
            ->with(['activeMembership', 'personalTrainer'])
            ->paginate(15);

        return view('pt-members.index', compact('members'));
    }

    public function show(Member $member)
    {
        // Pastikan member ini adalah member dari PT yang login
        if ($member->personal_trainer_id !== auth()->user()->personal_trainer_id) {
            return redirect()->route('pt-members.index')->with('error', 'Anda tidak memiliki akses ke member ini.');
        }

        $member->load(['memberships.payments', 'personalTrainer']);
        
        return view('pt-members.show', compact('member'));
    }
}