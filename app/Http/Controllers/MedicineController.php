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
        'sort_order' => 'nullable|integer|min:1',
        'dosage' => 'nullable|string|max:50',
        'quantity' => 'nullable|string|max:50',
        'instructions' => 'nullable|string|max:500',
        'route' => 'nullable|string|max:50',  // ✅ Optional
    ]);

    // Auto-increment sort_order
    if (empty($validated['sort_order'])) {
        $validated['sort_order'] = Medicine::where('medicine_group_id', $validated['medicine_group_id'])
            ->max('sort_order') + 1;
    }

    Medicine::create($validated);

    return redirect()->route('medicine-groups.show', $validated['medicine_group_id'])
        ->with('success', 'Medicine added successfully.');
}

// ✅ NEW: Bulk Store Method
public function bulkStore(Request $request)
{
    $validated = $request->validate([
        'medicine_group_id' => 'required|exists:medicine_groups,id',
        'medicines' => 'required|array|min:1',
        'medicines.*.sort_order' => 'nullable|integer|min:1',
        'medicines.*.name' => 'required|string|max:255',
        'medicines.*.dosage' => 'nullable|string|max:50',
        'medicines.*.quantity' => 'nullable|string|max:50',
        'medicines.*.instructions' => 'nullable|string|max:500',
        'medicines.*.route' => 'nullable|string|max:50',  // ✅ Optional
    ]);

    $groupId = $validated['medicine_group_id'];
    $maxSortOrder = Medicine::where('medicine_group_id', $groupId)->max('sort_order');
    
    $createdCount = 0;
    
    foreach ($validated['medicines'] as $index => $medicineData) {
        // Skip if name is empty (validation should catch this, but just in case)
        if (empty($medicineData['name'])) {
            continue;
        }
        
        // Auto-increment sort_order
        if (empty($medicineData['sort_order'])) {
            $medicineData['sort_order'] = $maxSortOrder + $index + 1;
        }
        
        $medicineData['medicine_group_id'] = $groupId;
        
        Medicine::create($medicineData);
        $createdCount++;
    }

    return redirect()->route('medicine-groups.show', $groupId)
        ->with('success', "{$createdCount} medicine(s) added successfully.");
}

    public function edit(Medicine $medicine)
    {
        return view('pages.medicines.edit', compact('medicine'));
    }

   
public function update(Request $request, Medicine $medicine)
{
    // Check if request is AJAX/JSON
    if ($request->expectsJson() || $request->ajax()) {
        $validated = $request->validate([
            'sort_order' => 'nullable|integer|min:1',
            'name' => 'required|string|max:255',
            'dosage' => 'nullable|string|max:50',
            'quantity' => 'nullable|string|max:50',
            // route optional - not updating via inline edit
        ]);

        $medicine->update($validated);

        return response()->json([
            'success' => true,
            'id' => $medicine->id,
            'sort_order' => $medicine->sort_order,
            'name' => $medicine->name,
            'dosage' => $medicine->dosage,
            'quantity' => $medicine->quantity,
            'route' => $medicine->route,
        ]);
    }
    
    // Fallback for non-AJAX (original behavior)
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'sort_order' => 'nullable|integer|min:1',
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