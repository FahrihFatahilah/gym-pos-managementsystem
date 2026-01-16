<?php

namespace App\Http\Controllers;

use App\Models\PTMember;
use App\Models\PersonalTrainer;
use App\Models\Packet;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PTMemberController extends Controller
{
    public function index(Request $request)
    {
        $query = PTMember::with(['personalTrainer', 'packet', 'user']);
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by trainer (for admin)
        if ($request->has('trainer_id') && $request->trainer_id) {
            $query->where('personal_trainer_id', $request->trainer_id);
        }
        
        // If user is PT, only show their members
        if (auth()->user()->role === 'pt' && auth()->user()->personal_trainer_id) {
            $query->where('personal_trainer_id', auth()->user()->personal_trainer_id);
        }
        
        $ptMembers = $query->latest()->paginate(15);
        $trainers = PersonalTrainer::where('is_active', true)->get();
        
        return view('pt-members.index', compact('ptMembers', 'trainers'));
    }

    public function create()
    {
        $trainers = PersonalTrainer::where('is_active', true)->get();
        $packets = Packet::where('is_active', true)
            ->whereIn('type', ['individual', 'couple', 'group'])
            ->orderBy('type')
            ->orderBy('sessions')
            ->get();
        
        return view('pt-members.create', compact('trainers', 'packets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_code_number' => 'required|string|unique:p_t_members,member_code,NULL,id,member_code,FLX-' . $request->member_code_number,
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:p_t_members,phone',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'personal_trainer_id' => 'required|exists:personal_trainers,id',
            'packet_id' => 'required|exists:packets,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'payment_method' => 'required|in:cash,qris,transfer',
            'notes' => 'nullable|string',
            'transaction_date' => 'required|date'
        ]);

        $packet = Packet::findOrFail($request->packet_id);
        $endDate = Carbon::parse($request->start_date)->addDays($packet->duration_days);

        PTMember::create([
            'member_code' => 'FLX-' . $request->member_code_number,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'personal_trainer_id' => $request->personal_trainer_id,
            'packet_id' => $request->packet_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'sessions_remaining' => $packet->sessions,
            'total_sessions' => $packet->sessions,
            'amount_paid' => $packet->price,
            'payment_method' => $request->payment_method,
            'status' => 'active',
            'notes' => $request->notes,
            'user_id' => auth()->id(),
            'transaction_date' => $request->transaction_date
        ]);

        return redirect()->route('pt-members.index')
            ->with('success', 'Member PT berhasil ditambahkan.');
    }

    public function show(PTMember $ptMember)
    {
        // Check access for PT users
        if (auth()->user()->role === 'pt' && 
            auth()->user()->personal_trainer_id !== $ptMember->personal_trainer_id) {
            return redirect()->route('pt-members.index')
                ->with('error', 'Anda tidak memiliki akses ke member ini.');
        }

        $ptMember->load(['personalTrainer', 'packet', 'user']);
        
        return view('pt-members.show', compact('ptMember'));
    }

    public function edit(PTMember $ptMember)
    {
        $trainers = PersonalTrainer::where('is_active', true)->get();
        $packets = Packet::where('is_active', true)
            ->whereIn('type', ['individual', 'couple', 'group'])
            ->orderBy('type')
            ->orderBy('sessions')
            ->get();
        
        return view('pt-members.edit', compact('ptMember', 'trainers', 'packets'));
    }

    public function update(Request $request, PTMember $ptMember)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:p_t_members,phone,' . $ptMember->id,
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'sessions_remaining' => 'required|integer|min:0',
            'notes' => 'nullable|string'
        ];
        
        if (!$ptMember->member_code) {
            $rules['member_code_number'] = 'required|string';
        }
        
        $request->validate($rules);
        
        $updateData = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'sessions_remaining' => $request->sessions_remaining,
            'notes' => $request->notes
        ];
        
        if (!$ptMember->member_code && $request->member_code_number) {
            $updateData['member_code'] = 'FLX-' . $request->member_code_number;
        }

        $ptMember->update($updateData);

        // Update status based on sessions and date
        $ptMember->updateStatus();

        return redirect()->route('pt-members.show', $ptMember)
            ->with('success', 'Data member PT berhasil diupdate.');
    }

    public function destroy(PTMember $ptMember)
    {
        $ptMember->delete();

        return redirect()->route('pt-members.index')
            ->with('success', 'Member PT berhasil dihapus.');
    }

    public function useSession(PTMember $ptMember)
    {
        if ($ptMember->sessions_remaining <= 0) {
            return back()->with('error', 'Sesi sudah habis.');
        }

        $ptMember->decrement('sessions_remaining');
        $ptMember->updateStatus();

        return back()->with('success', 'Sesi berhasil digunakan. Sisa sesi: ' . $ptMember->sessions_remaining);
    }
    
    public function restoreSession(PTMember $ptMember)
    {
        // Only admin can restore sessions
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Hanya admin yang dapat mengembalikan sesi.');
        }
        
        if ($ptMember->sessions_remaining >= $ptMember->total_sessions) {
            return back()->with('error', 'Sesi sudah maksimal.');
        }

        $ptMember->increment('sessions_remaining');
        $ptMember->updateStatus();

        return back()->with('success', 'Sesi berhasil dikembalikan. Sisa sesi: ' . $ptMember->sessions_remaining);
    }

    public function renew(PTMember $ptMember)
    {
        $trainers = PersonalTrainer::where('is_active', true)->get();
        $packets = Packet::where('is_active', true)
            ->whereIn('type', ['individual', 'couple', 'group'])
            ->orderBy('type')
            ->orderBy('sessions')
            ->get();
        
        return view('pt-members.renew', compact('ptMember', 'trainers', 'packets'));
    }

    public function processRenewal(Request $request, PTMember $ptMember)
    {
        $request->validate([
            'packet_id' => 'required|exists:packets,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'payment_method' => 'required|in:cash,qris,transfer',
            'transaction_date' => 'required|date'
        ]);

        $packet = Packet::findOrFail($request->packet_id);

        $ptMember->update([
            'packet_id' => $request->packet_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'sessions_remaining' => $packet->sessions,
            'total_sessions' => $packet->sessions,
            'amount_paid' => $packet->price,
            'payment_method' => $request->payment_method,
            'status' => 'active',
            'transaction_date' => $request->transaction_date
        ]);

        return redirect()->route('pt-members.index')
            ->with('success', 'Membership berhasil diperpanjang.');
    }

    public function addMember(PTMember $ptMember)
    {
        if ($ptMember->packet->type !== 'group') {
            return redirect()->route('pt-members.index')
                ->with('error', 'Fitur ini hanya untuk paket group.');
        }

        return view('pt-members.add-member', compact('ptMember'));
    }

    public function storeAddMember(Request $request, PTMember $ptMember)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'additional_fee' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,qris,transfer',
            'transaction_date' => 'required|date'
        ]);

        // Create new PT member with same packet and trainer
        PTMember::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'personal_trainer_id' => $ptMember->personal_trainer_id,
            'packet_id' => $ptMember->packet_id,
            'start_date' => $ptMember->start_date,
            'end_date' => $ptMember->end_date,
            'sessions_remaining' => $ptMember->sessions_remaining,
            'total_sessions' => $ptMember->total_sessions,
            'amount_paid' => $request->additional_fee,
            'payment_method' => $request->payment_method,
            'status' => 'active',
            'notes' => 'Member tambahan dari group: ' . $ptMember->name,
            'user_id' => auth()->id(),
            'transaction_date' => $request->transaction_date
        ]);

        return redirect()->route('pt-members.index')
            ->with('success', 'Member berhasil ditambahkan ke group.');
    }
}