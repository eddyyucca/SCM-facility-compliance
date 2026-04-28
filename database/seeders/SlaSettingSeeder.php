<?php

namespace Database\Seeders;

use App\Models\SlaSetting;
use Illuminate\Database\Seeder;

class SlaSettingSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // ── GA: Receptionist ──
            ['module' => 'ga', 'type' => 'receptionist', 'priority' => 'rendah', 'hours' => 72],
            ['module' => 'ga', 'type' => 'receptionist', 'priority' => 'sedang', 'hours' => 24],
            ['module' => 'ga', 'type' => 'receptionist', 'priority' => 'tinggi', 'hours' => 8],
            ['module' => 'ga', 'type' => 'receptionist', 'priority' => 'urgent', 'hours' => 2],

            // ── GA: Housekeeping ──
            ['module' => 'ga', 'type' => 'hk', 'priority' => 'rendah', 'hours' => 24],
            ['module' => 'ga', 'type' => 'hk', 'priority' => 'sedang', 'hours' => 8],
            ['module' => 'ga', 'type' => 'hk', 'priority' => 'tinggi', 'hours' => 4],
            ['module' => 'ga', 'type' => 'hk', 'priority' => 'urgent', 'hours' => 1],

            // ── GA: Laundry ──
            ['module' => 'ga', 'type' => 'laundry', 'priority' => 'rendah', 'hours' => 120],
            ['module' => 'ga', 'type' => 'laundry', 'priority' => 'sedang', 'hours' => 48],
            ['module' => 'ga', 'type' => 'laundry', 'priority' => 'tinggi', 'hours' => 24],
            ['module' => 'ga', 'type' => 'laundry', 'priority' => 'urgent', 'hours' => 8],

            // ── HR Request ──
            ['module' => 'hr', 'type' => 'hr_request', 'priority' => 'normal',   'hours' => 72],
            ['module' => 'hr', 'type' => 'hr_request', 'priority' => 'penting',  'hours' => 24],
            ['module' => 'hr', 'type' => 'hr_request', 'priority' => 'mendesak', 'hours' => 8],
        ];

        foreach ($rows as $row) {
            SlaSetting::updateOrCreate(
                ['module' => $row['module'], 'type' => $row['type'], 'priority' => $row['priority']],
                ['hours' => $row['hours']]
            );
        }
    }
}
