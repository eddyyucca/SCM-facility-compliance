<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaundryArea;
use App\Models\LaundryMess;
use App\Models\LaundryTransaction;
use Illuminate\Support\Carbon;

class LaundryMessSeeder extends Seeder
{
    public function run(): void
    {
        // Get the BR area
        $area = LaundryArea::where('name', 'BR')->first();
        if (!$area) return;

        // Create Dummy Messes
        $messes = [
            LaundryMess::firstOrCreate(['area_id' => $area->id, 'name' => 'Mess Cendrawasih']),
            LaundryMess::firstOrCreate(['area_id' => $area->id, 'name' => 'Mess Merpati']),
            LaundryMess::firstOrCreate(['area_id' => $area->id, 'name' => 'Mess Rajawali']),
        ];

        // Seed transactions for the current month up to today
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now();

        $current = $startDate->copy();
        while ($current <= $endDate) {
            foreach ($messes as $mess) {
                // Base POB varies slightly per mess
                $basePob = 0;
                if ($mess->name === 'Mess Cendrawasih') $basePob = rand(45, 55);
                if ($mess->name === 'Mess Merpati') $basePob = rand(30, 38);
                if ($mess->name === 'Mess Rajawali') $basePob = rand(60, 75);

                // Laundry bag usually correlates with POB. Sometimes 100%, sometimes less.
                $bagIn = rand((int)($basePob * 0.8), (int)($basePob * 1.1)); 
                $kgIn = $bagIn * 2.5;

                // Outgoing is usually whatever came in 1-2 days ago, but let's just make it similar
                $bagOut = rand((int)($bagIn * 0.9), (int)($bagIn * 1.1));
                $kgOut = $bagOut * 2.5;

                LaundryTransaction::updateOrCreate(
                    [
                        'tanggal' => $current->format('Y-m-d'),
                        'mess_id' => $mess->id
                    ],
                    [
                        'pob'     => $basePob,
                        'bag_in'  => $bagIn,
                        'kg_in'   => $kgIn,
                        'bag_out' => $bagOut,
                        'kg_out'  => $kgOut,
                    ]
                );
            }
            $current->addDay();
        }
    }
}
