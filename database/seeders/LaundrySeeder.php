<?php

namespace Database\Seeders;

use App\Models\LaundryArea;
use App\Models\LaundryEquipment;
use Illuminate\Database\Seeder;

class LaundrySeeder extends Seeder
{
    public function run(): void
    {
        $br = LaundryArea::firstOrCreate(
            ['name' => 'BR'],
            ['description' => 'Boiler Room / Laundry Utama', 'is_active' => true]
        );

        LaundryEquipment::where('area_id', $br->id)->delete();

        /*
         * Format: [model, capacity_kg (menggunakan Cap Exs agar akurat), cycle_min, status, remarks]
         */
        $washers = [
            // Speedqueen 15 kg: 3 Ready, 4 Breakdown (Remarks: Under MTC waiting part vbelt)
            ['Speedqueen 15 kg', 12, 55, 'ready', null],
            ['Speedqueen 15 kg', 12, 55, 'ready', null],
            ['Speedqueen 15 kg', 12, 55, 'ready', null],
            ['Speedqueen 15 kg', 12, 55, 'breakdown', 'Under MTC waiting part vbelt'],
            ['Speedqueen 15 kg', 12, 55, 'breakdown', 'Under MTC waiting part vbelt'],
            ['Speedqueen 15 kg', 12, 55, 'breakdown', 'Under MTC waiting part vbelt'],
            ['Speedqueen 15 kg', 12, 55, 'breakdown', 'Under MTC waiting part vbelt'],
            
            // Jetspeed 50 kg: 5 Ready, 1 Breakdown (Remarks: OL 03)
            ['Jetspeed 50 kg', 50, 55, 'ready', null],
            ['Jetspeed 50 kg', 50, 55, 'ready', null],
            ['Jetspeed 50 kg', 50, 55, 'ready', null],
            ['Jetspeed 50 kg', 50, 55, 'ready', null],
            ['Jetspeed 50 kg', 50, 55, 'ready', null],
            ['Jetspeed 50 kg', 50, 55, 'breakdown', 'OL 03'],
            
            // Electrolux 28 kg: 0 Ready, 1 Breakdown (Remarks: Mesin 28 kg baut rangka longgar)
            ['Electrolux 28 kg', 28, 40, 'breakdown', 'Mesin 28 kg baut rangka longgar'],
        ];

        $unitCounters = [];
        foreach ($washers as $i => $row) {
            [$model, $cap, $cycle, $status, $remarks] = $row;
            $unitCounters[$model] = ($unitCounters[$model] ?? 0) + 1;
            LaundryEquipment::create([
                'area_id'              => $br->id,
                'category'             => 'washer',
                'model_name'           => $model,
                'capacity_kg'          => $cap,
                'process_time_minutes' => $cycle,
                'unit_number'          => $unitCounters[$model],
                'status'               => $status,
                'remarks'              => $remarks,
                'sort_order'           => $i + 1,
            ]);
        }

        /*
         * DRYER: [model, capacity_kg (Cap Exs), cycle_min, qty]
         */
        $dryerData = [
            ['INOE 50 kg', 50, 70, 9],
            ['Speedqueen 15 kg', 12, 70, 7], // 7 units
            ['Maytag 15 kg', 12, 70, 6],
            ['Electrolux 30 kg', 30, 70, 1],
        ];

        $dryers = [];
        foreach ($dryerData as [$m, $cap, $cycle, $qty]) {
            for ($j = 1; $j <= $qty; $j++) {
                $dryers[] = [$m, $cap, $cycle, 'ready', null, $j];
            }
        }

        foreach ($dryers as $i => $row) {
            [$model, $cap, $cycle, $status, $remarks, $unitNum] = $row;
            LaundryEquipment::create([
                'area_id'              => $br->id,
                'category'             => 'dryer',
                'model_name'           => $model,
                'capacity_kg'          => $cap,
                'process_time_minutes' => $cycle,
                'unit_number'          => $unitNum,
                'status'               => $status,
                'remarks'              => $remarks,
                'sort_order'           => $i + 1,
            ]);
        }
    }
}
