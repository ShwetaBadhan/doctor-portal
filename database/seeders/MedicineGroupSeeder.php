<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicineGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $groups = [
        ['name' => 'CA BLOOD', 'code' => 'GD-4'],
        ['name' => 'AUTISM-1', 'code' => 'GD-3'],
        ['name' => 'AUTISM-2', 'code' => 'GD-3'],
        ['name' => 'CKD-1', 'code' => 'CKD'],
        ['name' => 'CKD-2', 'code' => 'CKD'],
        ['name' => 'I B S', 'code' => null],
        ['name' => 'PARALYSIS', 'code' => null],
        ['name' => 'DEAF AND MUTE', 'code' => null],
        ['name' => 'CANCER', 'code' => null],
        ['name' => 'BRAIN TUMOR/CLOT', 'code' => null],
        ['name' => 'CA BREST', 'code' => null],
        ['name' => 'LIVER CIRRHOSIS', 'code' => null],
    ];
    
    foreach ($groups as $group) {
        $created = \App\Models\MedicineGroup::create($group);
        // You can add medicines here programmatically if needed
    }
    }
}
