<?php

namespace App\Http\Controllers;

use App\Models\Packet;
use Illuminate\Http\Request;

class PacketController extends Controller
{
    public function index()
    {
        $packets = Packet::orderBy('type')->orderBy('sessions')->paginate(15);
        return view('packets.index', compact('packets'));
    }

    public function create()
    {
        return view('packets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:individual,couple,group,daily,membership',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'sessions' => 'nullable|integer|min:1',
            'duration_minutes' => 'nullable|integer|min:1',
            'membership_months' => 'nullable|integer|min:1',
            'description' => 'nullable|string'
        ]);

        $data = $request->only(['name', 'type', 'price', 'description']);
        $isPT = in_array($request->type, ['individual', 'couple', 'group']);
        $isMembership = $request->type === 'membership';
        
        if ($isPT) {
            $data['duration_days'] = $request->duration_days;
            $data['sessions'] = $request->sessions;
            $data['duration_minutes'] = $request->duration_minutes;
            $data['membership_months'] = null;
        } elseif ($isMembership) {
            $data['membership_months'] = $request->membership_months;
            $data['duration_days'] = $request->membership_months * 30;
            $data['sessions'] = 0;
            $data['duration_minutes'] = 0;
        } else {
            $data['duration_days'] = 30;
            $data['sessions'] = 0;
            $data['duration_minutes'] = 0;
            $data['membership_months'] = null;
        }
        
        $data['is_active'] = $request->has('is_active');

        Packet::create($data);

        return redirect()->route('packets.index')
            ->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(Packet $packet)
    {
        return view('packets.edit', compact('packet'));
    }

    public function update(Request $request, Packet $packet)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:individual,couple,group,daily,membership',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'sessions' => 'nullable|integer|min:1',
            'duration_minutes' => 'nullable|integer|min:1',
            'membership_months' => 'nullable|integer|min:1',
            'description' => 'nullable|string'
        ]);

        $data = $request->only(['name', 'type', 'price', 'description']);
        $isPT = in_array($request->type, ['individual', 'couple', 'group']);
        $isMembership = $request->type === 'membership';
        
        if ($isPT) {
            $data['duration_days'] = $request->duration_days;
            $data['sessions'] = $request->sessions;
            $data['duration_minutes'] = $request->duration_minutes;
            $data['membership_months'] = null;
        } elseif ($isMembership) {
            $data['membership_months'] = $request->membership_months;
            $data['duration_days'] = $request->membership_months * 30;
            $data['sessions'] = 0;
            $data['duration_minutes'] = 0;
        } else {
            $data['duration_days'] = 30;
            $data['sessions'] = 0;
            $data['duration_minutes'] = 0;
            $data['membership_months'] = null;
        }
        
        $data['is_active'] = $request->has('is_active');

        $packet->update($data);

        return redirect()->route('packets.index')
            ->with('success', 'Paket berhasil diupdate.');
    }

    public function destroy(Packet $packet)
    {
        $packet->delete(); // Soft delete

        return redirect()->route('packets.index')
            ->with('success', 'Paket berhasil dihapus.');
    }
}