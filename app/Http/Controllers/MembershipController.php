<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Member;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Membership::with(['member', 'payments']);
        
        // Search by member name or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('member', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $memberships = $query->latest()->paginate(10);
        
        return view('memberships.index', compact('memberships'));
    }

    public function create()
    {
        $members = Member::all();
        return view('memberships.create', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_code_number' => 'required|string|unique:memberships,member_code,NULL,id,member_code,FLX-' . $request->member_code_number,
            'member_id' => 'required|exists:members,id',
            'type' => 'required|in:monthly,yearly,custom',
            'category' => 'required|in:regular,pt',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'required|numeric|min:0',
            'transaction_date' => 'required|date'
        ]);

        $membershipData = $request->all();
        $membershipData['member_code'] = 'FLX-' . $request->member_code_number;
        
        Membership::create($membershipData);
        return redirect()->route('memberships.index')->with('success', 'Membership berhasil ditambahkan.');
    }

    public function show(Membership $membership)
    {
        $membership->load('member', 'payments');
        return view('memberships.show', compact('membership'));
    }

    public function edit(Membership $membership)
    {
        $members = Member::all();
        return view('memberships.edit', compact('membership', 'members'));
    }

    public function update(Request $request, Membership $membership)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'type' => 'required|in:monthly,yearly,custom',
            'category' => 'required|in:regular,pt',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'required|numeric|min:0',
            'transaction_date' => 'required|date'
        ]);

        $membership->update($request->all());
        return redirect()->route('memberships.index')->with('success', 'Membership berhasil diupdate.');
    }

    public function destroy(Membership $membership)
    {
        $membership->delete();
        return redirect()->route('memberships.index')->with('success', 'Membership berhasil dihapus.');
    }
}