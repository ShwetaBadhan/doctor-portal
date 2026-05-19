<?php
namespace App\Http\Controllers;

use App\Models\MedicineGroup;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::with('group')->latest()->paginate(20);
        return view('pages.medicines.index', compact('medicines'));
    }

    public function create(Request $request)
    {
        $groupId = $request->query('group');
        $group = $groupId ? MedicineGroup::findOrFail($groupId) : null;
        return view('pages.medicines.create', compact('group'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'medicine_group_id' => 'required|exists:medicine_groups,id',
            'name' => 'required|string|max:255',
            'dosage' => 'nullable|string|max:50',
            'quantity' => 'nullable|string|max:50',
            'instructions' => 'nullable|string|max:500',
            'route' => 'nullable|string|max:50',
        ]);
        Medicine::create($validated);
        return redirect()->route('medicine-groups.show', $validated['medicine_group_id'])
            ->with('success', 'Medicine added.');
    }

    public function edit(Medicine $medicine)
    {
        return view('pages.medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dosage' => 'nullable|string|max:50',
            'quantity' => 'nullable|string|max:50',
            'instructions' => 'nullable|string|max:500',
            'route' => 'nullable|string|max:50',
        ]);
        $medicine->update($validated);
        return redirect()->route('medicine-groups.show', $medicine->group)
            ->with('success', 'Medicine updated.');
    }

    public function destroy(Medicine $medicine)
    {
        $group = $medicine->group;
        $medicine->delete();
        return redirect()->route('medicine-groups.show', $group)
            ->with('success', 'Medicine deleted.');
    }
}