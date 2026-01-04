<?php

namespace App\Http\Controllers;

use App\Models\PersonalTrainer;
use Illuminate\Http\Request;

class PersonalTrainerController extends Controller
{
    public function index()
    {
        $trainers = PersonalTrainer::withCount('members')->paginate(15);
        return view('personal-trainers.index', compact('trainers'));
    }

    public function create()
    {
        return view('personal-trainers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'specialization' => 'nullable|string',
            'hourly_rate' => 'required|numeric|min:0'
        ]);

        PersonalTrainer::create($request->all());

        return redirect()->route('personal-trainers.index')
            ->with('success', 'Personal Trainer berhasil ditambahkan.');
    }

    public function show(PersonalTrainer $personalTrainer)
    {
        $personalTrainer->load(['members.activeMembership']);
        return view('personal-trainers.show', compact('personalTrainer'));
    }

    public function edit(PersonalTrainer $personalTrainer)
    {
        return view('personal-trainers.edit', compact('personalTrainer'));
    }

    public function update(Request $request, PersonalTrainer $personalTrainer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'specialization' => 'nullable|string',
            'hourly_rate' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        $personalTrainer->update($request->all());

        return redirect()->route('personal-trainers.index')
            ->with('success', 'Personal Trainer berhasil diperbarui.');
    }

    public function destroy(PersonalTrainer $personalTrainer)
    {
        $personalTrainer->delete();
        return redirect()->route('personal-trainers.index')
            ->with('success', 'Personal Trainer berhasil dihapus.');
    }
}