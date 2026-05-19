<?php
namespace App\Http\Controllers;

use App\Models\MedicineGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MedicineGroupController extends Controller
{
    public function index()
    {
        $groups = MedicineGroup::withCount('medicines')->latest()->paginate(15);
        return view('pages.medicine-groups.index', compact('groups'));
    }

    public function create() { return view('pages.medicine-groups.create'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:medicine_groups,name',
            'code' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:500',
        ]);
        MedicineGroup::create($validated);
        return redirect()->route('medicine-groups.index')->with('success', 'Group created.');
    }

    public function show(MedicineGroup $medicineGroup)
    {
        $medicineGroup->load('medicines');
        return view('pages.medicine-groups.show', compact('medicineGroup'));
    }

    public function edit(MedicineGroup $medicineGroup) { return view('pages.medicine-groups.edit', compact('medicineGroup')); }

    public function update(Request $request, MedicineGroup $medicineGroup)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('medicine_groups')->ignore($medicineGroup->id)],
            'code' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:500',
        ]);
        $medicineGroup->update($validated);
        return redirect()->route('medicine-groups.index')->with('success', 'Group updated.');
    }

    public function destroy(MedicineGroup $medicineGroup)
    {
        $medicineGroup->delete();
        return redirect()->route('medicine-groups.index')->with('success', 'Group deleted.');
    }
}