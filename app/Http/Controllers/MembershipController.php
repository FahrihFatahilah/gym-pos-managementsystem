<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Member;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::with('member')->latest()->paginate(10);
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
            'member_id' => 'required|exists:members,id',
            'type' => 'required|in:monthly,yearly,custom',
            'category' => 'required|in:regular,pt',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'required|numeric|min:0'
        ]);

        Membership::create($request->all());
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
            'price' => 'required|numeric|min:0'
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