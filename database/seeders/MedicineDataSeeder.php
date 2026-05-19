<?php

namespace Database\Seeders;

use App\Models\MedicineGroup;
use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicineDataSeeder extends Seeder
{
    public function run(): void
    {
        $medicineGroups = [
            [
                'name' => 'CA BLOOD',
                'code' => 'GD-4',
                'medicines' => [
                    ['name' => 'G.T-5 (SD5)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'N.T-1, C10, VER1, YE(SD5)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'LIV-DROP', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'APP, S3, VAN 1, C3, BE( E-1, D4)', 'dosage' => '3*1', 'quantity' => '10ML', 'instructions' => 'AM ONLY'],
                    ['name' => 'OBC3-1', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'GANGGREEN OIL (E-50),C3-10(SD10)', 'dosage' => null, 'quantity' => '100ML', 'instructions' => 'APPLY ON WHOLE SKIN'],
                    ['name' => 'TOXIN, GEN-FORT(SD6)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'S10, A3, F1, VER1, WE (SD10)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'S2, C2, VER1, C10, YE', 'dosage' => '3*2', 'quantity' => '30ML'],
                    ['name' => 'A2, F2, S5, C5, VER2, WE, BE (SD4)', 'dosage' => null, 'quantity' => '100ML', 'instructions' => 'APPLY ON UPPER ABDOMEN'],
                    ['name' => 'SKINO-1', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'SINO COLD', 'dosage' => null, 'quantity' => null, 'route' => 'GULBULES'],
                ]
            ],
            [
                'name' => 'AUTISM-1',
                'code' => 'GD-3',
                'medicines' => [
                    ['name' => 'G.T-1/S3, C3, A1, VER1, YE', 'dosage' => '3*4', 'quantity' => '30ML', 'instructions' => 'DT'],
                    ['name' => 'BIO-V', 'dosage' => '3*4', 'quantity' => '30ML', 'instructions' => 'DT'],
                    ['name' => 'N.T-1/C1, VER1, YE,P3', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'OBC13-1/S12, P4, S10, WE(E1) SD30', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'F DROP', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'F1, C1, WE (SD5)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'M.D-1/S5, C5, A2, GE', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'BIO-A, V.S.C (E1)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'S12 (SD30)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'VER 1, YE (200)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'SL', 'dosage' => '10*2', 'quantity' => '50ML'],
                    ['name' => 'HEAD OIL', 'dosage' => null, 'quantity' => '100ML'],
                    ['name' => 'THROAT OIL', 'dosage' => null, 'quantity' => '100ML'],
                    ['name' => 'AB OIL', 'dosage' => null, 'quantity' => '100ML'],
                ]
            ],
            [
                'name' => 'AUTISM-2',
                'code' => 'GD-3',
                'medicines' => [
                    ['name' => 'G.T-1/S3, C3, A1, VER1, YE', 'dosage' => '3*4', 'quantity' => '30ML', 'instructions' => 'DT'],
                    ['name' => 'BIO-V', 'dosage' => '3*4', 'quantity' => '30ML', 'instructions' => 'DT'],
                    ['name' => 'N.T-1/C1, VER1, YE,P3', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'OBC13-1/S12, P4, S10, WE(E1) SD30', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'F DROP (J-B)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'F1, C1, WE (SD5)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'M.D-1/S5, C5, A2, GE', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'BIO-A, V.S.C (E1)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'S12 (SD30)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'VER 1, YE (200)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'SL', 'dosage' => '10*2', 'quantity' => '50ML'],
                    ['name' => 'HEAD OIL', 'dosage' => null, 'quantity' => '100ML'],
                    ['name' => 'THROAT OIL', 'dosage' => null, 'quantity' => '100ML'],
                    ['name' => 'AB OIL', 'dosage' => null, 'quantity' => '100ML'],
                ]
            ],
            [
                'name' => 'AUTISM-3',
                'code' => 'GD-3',
                'medicines' => [
                    ['name' => 'G.T-1/S3, C3, A1, VER1, YE', 'dosage' => '3*4', 'quantity' => '30ML', 'instructions' => 'DT'],
                    ['name' => 'BIO-V', 'dosage' => '3*4', 'quantity' => '30ML', 'instructions' => 'DT'],
                    ['name' => 'N.T-1/C1, VER1, YE,P3', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'OBC13-1/S12, P4, S10, WE(E1) SD30', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'F DROP (J-B)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'F1, C1, WE (SD5)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'M.D-1/S5, C5, A2, GE', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'BIO-A, V.S.C (E1)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'S12 (SD30)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'VER 1, YE (200)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'SL', 'dosage' => '10*2', 'quantity' => '50ML'],
                    ['name' => 'HEAD OIL', 'dosage' => null, 'quantity' => '100ML'],
                    ['name' => 'THROAT OIL', 'dosage' => null, 'quantity' => '100ML'],
                    ['name' => 'AB OIL', 'dosage' => null, 'quantity' => '100ML'],
                    ['name' => 'VER 1, YE (GD4) SINGLE DOSE', 'dosage' => null, 'quantity' => null, 'instructions' => 'AFTER 4 DAYS'],
                ]
            ],
            [
                'name' => 'CKD-1',
                'code' => 'CKD',
                'medicines' => [
                    ['name' => 'G.T-5 S6, L1 , BE (E-5)', 'dosage' => '6*4', 'quantity' => '50ML', 'instructions' => 'DT'],
                    ['name' => 'N.T- C10, VER1, YE, P2 (SD5)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'M.D-5, S5, C5, A2, GE (SD5)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'LIV-DROP (SD4)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'BIO-U (SD2)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'L2, BE (SD4)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'S6, C6 (D200)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'S10, C10, C4, S, C6, S2, C2, L1, VAN1, VER1, BE, WE (SD6)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'C17, VAN 1, L1, GE (SD6)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'S6, F2, YE (1000)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'TOXIN (SD6)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'ESR , CRP', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'SL, S2, YE', 'dosage' => '30*2', 'quantity' => '100ML'],
                    ['name' => 'LIV HOT COMPRESS', 'dosage' => null, 'quantity' => '100ML'],
                    ['name' => 'C6, A1, F1, VER1, GE (SD2)', 'dosage' => null, 'quantity' => null, 'route' => 'GULBULES'],
                ]
            ],
            [
                'name' => 'CKD-2',
                'code' => 'CKD',
                'medicines' => [
                    ['name' => 'LIV-DROP (SD4)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'S6, P2 (SD8)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'S5 (SD4)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'OBC-10, WE (SD6)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'S6, C6, P2, VAN 1, BE (SD4)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'BIO-U (SD4)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'NEW CKD (SD8)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'MD, S5, C5, A2, GE (SD5)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'A3 (GD2)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'S6, C6, S11 (SD6)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'F1, S2, C10 (GD1)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'SL', 'dosage' => '30*2', 'quantity' => '100ML'],
                    ['name' => 'A2, C5, GE (SD1)', 'dosage' => '30*2', 'quantity' => '100ML', 'instructions' => 'EXTERNAL APPLY'],
                    ['name' => 'LIV HOT COMPRESS', 'dosage' => null, 'quantity' => '100ML'],
                    ['name' => 'C6, A1, F1, VER1, GE (SD2)', 'dosage' => null, 'quantity' => null, 'route' => 'GULBULES'],
                ]
            ],
            [
                'name' => 'I B S',
                'code' => null,
                'medicines' => [
                    ['name' => 'G.T-A3, L1, BE, WE (SD4)', 'dosage' => '6*4', 'quantity' => '50ML', 'instructions' => 'DT'],
                    ['name' => 'N.T-C10, VER 1, WE, P3( SD5)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'M.D-5 , S5, C5, A2, GE (SD5)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'LIV- DROP (SD4)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'OBC-10 (SD8)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'A2, C10, BE (SD10)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'TOXIN, GEN-FORT (SD6)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'A3, S10, C10 (SD6)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'F1, S1, VER1, WE (SD6)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'C13, S2, S10, C1, BE (SD4)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'S10, C10, F1, VER1, YE (SD8)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'HEAD OIL', 'dosage' => null, 'quantity' => '100ML'],
                    ['name' => 'LIV HOT COMPRESS', 'dosage' => null, 'quantity' => '100ML'],
                    ['name' => 'SL, BE, VER1', 'dosage' => null, 'quantity' => null, 'route' => 'SIP SIP'],
                    ['name' => 'A1, BE', 'dosage' => null, 'quantity' => null, 'route' => 'SIP SIP'],
                ]
            ],
            [
                'name' => 'PARALYSIS',
                'code' => null,
                'medicines' => [
                    ['name' => 'G.T-5 A3, L1, BE (SD4)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'N.T-5 C10,VER1, YE,P3 (SD5)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'M.D-5, S5, C5, A2, GE (SD5)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'LIV DROP (SD4)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'P.B', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'C5, GE (1000)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'APP, A2 (200)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'S1, C1, F1, VEN1, WE, BE (SD4)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'S1 (1000)', 'dosage' => '10*1', 'quantity' => '50ML', 'instructions' => 'BEFORE MEAL'],
                    ['name' => 'F1 (1000)', 'dosage' => '10*1', 'quantity' => '50ML', 'instructions' => 'AFTER MEAL'],
                    ['name' => 'CD-1', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'HEAD OIL', 'dosage' => null, 'quantity' => '100ML'],
                    ['name' => 'LIV HOT COMPRESS', 'dosage' => null, 'quantity' => '100ML'],
                ]
            ],
            [
                'name' => 'DEAF AND MUTE',
                'code' => null,
                'medicines' => [
                    ['name' => 'G.T-1/S3, C3, A1, VER1, YE, BE', 'dosage' => '3*4', 'quantity' => '30ML', 'instructions' => 'DT'],
                    ['name' => 'S5, C5 (E1)', 'dosage' => '3*4', 'quantity' => '30ML', 'instructions' => 'DT'],
                    ['name' => 'N.T-1/C1, VER1, YE, P3', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'S10, P4, C13, BE (SD4)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'F DRPOP(UD)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'F1, C1, WE (SD5)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'M.D-1/S5, C5, A2, GE', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'C5, GE, (D200)', 'dosage' => '3*4', 'quantity' => '30ML'],
                    ['name' => 'SL', 'dosage' => '10*2', 'quantity' => '50ML'],
                    ['name' => 'HEAD OIL', 'dosage' => null, 'quantity' => '100ML'],
                    ['name' => 'THROAT OIL', 'dosage' => null, 'quantity' => '100ML', 'instructions' => 'APPLY ON NECK AND BOTH SIDES OF FORE HEAD'],
                    ['name' => 'C13, C17, BE (SD4)', 'dosage' => null, 'quantity' => '100ML', 'instructions' => 'FOR GARGLE'],
                    ['name' => 'EAR PLUG', 'dosage' => null, 'quantity' => '30ML', 'route' => 'EAR PLUG'],
                    ['name' => 'C2', 'dosage' => null, 'quantity' => null, 'route' => 'GULBULES', 'instructions' => 'FOR SUCK'],
                ]
            ],
            [
                'name' => 'CANCER',
                'code' => null,
                'medicines' => [
                    ['name' => 'G.T-5, S3, C3, VER1, YE (SD6)', 'dosage' => '6*4', 'quantity' => '50ML', 'instructions' => 'DT'],
                    ['name' => 'N.T-C10, VER 1, YE, P2 (SD5)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'LIV DROP (SD4)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'OBC-1, BE (1000)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'ALL TYPE OF TUMOUR, C1 (SD8)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'OBC-3 (SD10)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'S2, C4, A2, L1, VAN1, BE, GE (D200)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'C4, VAN1 (D.I.T)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'SL', 'dosage' => '30*2', 'quantity' => '100ML'],
                    ['name' => 'S2, C1, C2, C5, GE', 'dosage' => null, 'quantity' => null, 'instructions' => 'NAVEL AND TAIL BONE'],
                    ['name' => 'A2, F2, S2, C2, VER2, WE, BE', 'dosage' => null, 'quantity' => '100ML', 'instructions' => 'APPLY ON LOWER ABDOMEN'],
                    ['name' => 'A2, L1, BE, VER1 (SD8)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'A2, C5, GE (SD4)', 'dosage' => null, 'quantity' => '100ML', 'instructions' => 'EXTERNAL APPLY'],
                    ['name' => 'S3, C4', 'dosage' => null, 'quantity' => null, 'route' => 'GULBULES', 'instructions' => 'TAKE 2 GULBULES AFTER 2 HOUR'],
                    ['name' => 'A1, BE (E1)', 'dosage' => null, 'quantity' => null, 'route' => 'SIP SIP', 'instructions' => '2 BOTTLE'],
                    ['name' => 'CA PILLS', 'dosage' => null, 'quantity' => null],
                ]
            ],
            [
                'name' => 'BRAIN TUMOR/CLOT',
                'code' => null,
                'medicines' => [
                    ['name' => 'G.T-5 S3, C3, VER1, BE (SD6)', 'dosage' => '7*4', 'quantity' => '50ML', 'instructions' => 'DT'],
                    ['name' => 'N.T-5 C1, VER1, YE, P3 (SD6)', 'dosage' => '7*4', 'quantity' => '50ML'],
                    ['name' => 'M.D-5, S5, C5, A2, GE (SD6)', 'dosage' => '7*4', 'quantity' => '50ML'],
                    ['name' => 'LIV DROP (SD4)', 'dosage' => '7*4', 'quantity' => '50ML'],
                    ['name' => 'S1, C1, F1, BE (SD6)', 'dosage' => '7*4', 'quantity' => '50ML'],
                    ['name' => 'S10, A3 WE (SD6)', 'dosage' => '7*4', 'quantity' => '50ML'],
                    ['name' => 'F1, C1, WE (SD6)', 'dosage' => '7*4', 'quantity' => '50ML'],
                    ['name' => 'S2, C5, VAN1, GE (SD30)', 'dosage' => '7*4', 'quantity' => '50ML'],
                    ['name' => 'SL', 'dosage' => '30*2', 'quantity' => '100ML'],
                    ['name' => 'HEAD OIL A2, C5, GE (E5)', 'dosage' => null, 'quantity' => '100ML'],
                    ['name' => 'LIV HOT COMPRESSED', 'dosage' => null, 'quantity' => '100ML'],
                    ['name' => 'C5, VAN1, GE (1000)', 'dosage' => null, 'quantity' => null, 'instructions' => 'SINGLE DOSE - AFTER 7 DAYS'],
                    ['name' => 'EYE DROP', 'dosage' => null, 'quantity' => null],
                ]
            ],
            [
                'name' => 'CA BREST',
                'code' => null,
                'medicines' => [
                    ['name' => 'G.T-5, S5, C4, RE (SD4)', 'dosage' => '6*4', 'quantity' => '50ML', 'instructions' => 'DT'],
                    ['name' => 'N.T-5, C10, VER1, YE, WE (E5)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'M.D-5, S5, C5, A2, GE', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'TOXIN -D6, GEN-F (SD6)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'C4, VAN1 (D1000)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'SL (SD30)', 'dosage' => '30*2', 'quantity' => '100ML'],
                    ['name' => 'A2, C5, GE (SD1)', 'dosage' => '6*2', 'quantity' => '30ML'],
                    ['name' => 'ORTHO DROP', 'dosage' => '6*2', 'quantity' => '30ML'],
                    ['name' => 'ESR, CRP (SD3)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'OBC-2, C4, L1 (SD2)', 'dosage' => '6*4', 'quantity' => '50ML'],
                    ['name' => 'CA PILLS', 'dosage' => null, 'quantity' => null, 'instructions' => '30 GULBULES IN 1 LITER THEN SIP SIP'],
                ]
            ],
            [
                'name' => 'LIVER CIRRHOSIS',
                'code' => null,
                'medicines' => [
                    ['name' => 'LIV DROP (SD4)', 'dosage' => '10*2', 'quantity' => '50ML'],
                    ['name' => 'MD, S5, A2, GE( SD4)', 'dosage' => '10*2', 'quantity' => '50ML'],
                    ['name' => 'S5 (D200)', 'dosage' => '10*2', 'quantity' => '50ML'],
                    ['name' => 'S5, F1, F2, WE (GD2)', 'dosage' => '10*2', 'quantity' => '50ML'],
                    ['name' => 'C10, VER1, GE, C15, A2, SD6)', 'dosage' => '10*2', 'quantity' => '50ML'],
                    ['name' => 'S1 (SD1)', 'dosage' => '8*2', 'quantity' => '50ML'],
                    ['name' => 'LOWER AB APPLICATION', 'dosage' => null, 'quantity' => '100ML'],
                    ['name' => 'OBC-15 S1, C15, VER1, GE (SD6)', 'dosage' => '10*2', 'quantity' => '50ML'],
                    ['name' => 'F1, S2, C10, S10, VER1, WE (SD6)', 'dosage' => '10*2', 'quantity' => '50ML', 'route' => 'ORAL'],
                    ['name' => 'S3 (SD6)', 'dosage' => '10*2', 'quantity' => '50ML', 'instructions' => 'FOR STOMACH'],
                    ['name' => 'VER2, F2, C10, BE, GE (SD4)', 'dosage' => null, 'quantity' => '100ML', 'instructions' => 'APPLY ON WHOLE STOMACH'],
                    ['name' => 'F1, C10, S10, VER1, WE', 'dosage' => null, 'quantity' => null, 'route' => 'INJECTION', 'instructions' => 'D4'],
                    ['name' => 'S10, C10, VER1, WE', 'dosage' => null, 'quantity' => null, 'route' => 'GULBULES'],
                    ['name' => 'L2, BE', 'dosage' => null, 'quantity' => null, 'instructions' => 'EVERY HALF HOUR TAKE 4 GULBULES'],
                ]
            ],
        ];

        foreach ($medicineGroups as $groupData) {
            $group = MedicineGroup::firstOrCreate(
                ['name' => $groupData['name']],
                [
                    'code' => $groupData['code'],
                    'description' => null,
                    'is_active' => true,
                ]
            );

            foreach ($groupData['medicines'] as $index => $medicineData) {
                Medicine::firstOrCreate(
                    [
                        'medicine_group_id' => $group->id,
                        'name' => $medicineData['name'],
                    ],
                    [
                        'sort_order' => $index + 1,
                        'dosage' => $medicineData['dosage'] ?? null,
                        'quantity' => $medicineData['quantity'] ?? null,
                        'instructions' => $medicineData['instructions'] ?? null,
                        'route' => $medicineData['route'] ?? null,
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('Medicine groups and medicines seeded successfully!');
    }
}