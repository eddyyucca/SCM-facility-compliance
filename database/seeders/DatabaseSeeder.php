<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Complaint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 4 Accounts ──
        $users = [
            ['name' => 'Super Admin GA',    'email' => 'superadmin@ga.com',   'role' => 'superadmin'],
            ['name' => 'Staff Receptionist', 'email' => 'receptionist@ga.com', 'role' => 'receptionist'],
            ['name' => 'Staff Housekeeping','email' => 'hk@ga.com',           'role' => 'hk'],
            ['name' => 'Staff Laundry',     'email' => 'laundry@ga.com',      'role' => 'laundry'],
        ];

        foreach ($users as $u) {
            User::firstOrCreate(['email' => $u['email']], array_merge($u, [
                'password' => Hash::make('password123'),
            ]));
        }

        // ── Dummy Complaints ──
        $this->seedComplaints();
    }

    private function seedComplaints(): void
    {
        $daysBack = 45;

        $receptionist = [
            ['desc' => 'AC di kamar tidak dingin sama sekali, sudah dipasang tapi tidak terasa anginnya.'],
            ['desc' => 'TV kamar mati, remote sudah diganti baterai tapi tetap tidak nyala.'],
            ['desc' => 'Air panas tidak keluar di shower, sudah dicoba beberapa kali.'],
            ['desc' => 'Kartu kunci kamar tidak bisa membuka pintu, sudah dicoba berkali-kali.'],
            ['desc' => 'Lampu di kamar mati semua, perlu penggantian segera.'],
            ['desc' => 'Toilet tersumbat dan air tidak mau mengalir dengan baik.'],
            ['desc' => 'Bau tidak sedap dari saluran kamar mandi, sudah berlangsung 2 hari.'],
        ];

        $hk = [
            ['desc' => 'Kamar sudah check-in tapi kondisi belum dibersihkan dari tamu sebelumnya.'],
            ['desc' => 'Handuk tidak diganti selama 3 hari, sudah minta ke front desk.'],
            ['desc' => 'Lantai koridor sangat kotor, ada tumpahan minuman yang mengering.'],
            ['desc' => 'Tempat sampah sudah penuh sejak kemarin tapi belum diambil.'],
            ['desc' => 'Toilet di area mess sangat kotor dan berbau tidak sedap.'],
            ['desc' => 'Sprei di kamar ada noda yang mencolok, perlu diganti.'],
            ['desc' => 'Debu menumpuk di ventilasi kamar, perlu dibersihkan.'],
        ];

        $laundry = [
            ['desc' => 'Cucian sudah 3 hari tapi belum dikembalikan, padahal dijanjikan 1 hari.'],
            ['desc' => 'Pakaian yang dikembalikan bukan milik saya, ada pakaian orang lain.'],
            ['desc' => 'Kemeja putih kembali dengan noda yang tidak bisa hilang setelah dicuci.'],
            ['desc' => 'Mengirim 10 item tapi yang kembali hanya 8 item, 2 item hilang.'],
            ['desc' => 'Pakaian masih bau dan belum bersih meski sudah dicuci.'],
            ['desc' => 'Pakaian mengkerut setelah dicuci, seperti terkena panas berlebih.'],
        ];

        // New 3-value statuses: open, progress, closed
        $statuses = ['open', 'open', 'open', 'progress', 'progress', 'closed', 'closed'];
        $rooms    = ['101', '102', '103', '201', '202', '203', '301', '302', '401', '402'];
        $reporters = [
            ['name' => 'Budi Santoso',   'wa' => '08123456789'],
            ['name' => 'Siti Rahayu',    'wa' => '08234567890'],
            ['name' => 'Ahmad Fauzi',    'wa' => '08345678901'],
            ['name' => 'Dewi Kusuma',    'wa' => '08456789012'],
            ['name' => 'Rizal Anwar',    'wa' => '08567890123'],
            ['name' => 'Nur Fitri',      'wa' => '08678901234'],
            ['name' => 'Joko Wibowo',    'wa' => '08789012345'],
            ['name' => 'Sri Mulyani',    'wa' => '08890123456'],
        ];

        // Flat list of all buildings from config
        $allBuildings = [];
        foreach (config('buildings', []) as $group => $buildings) {
            foreach ($buildings as $b) {
                $allBuildings[] = $b;
            }
        }
        // Fallback if config not loaded during seeding
        if (empty($allBuildings)) {
            $allBuildings = ['GARNERIT A','GARNERIT B','SAPROLITE A','LIMONITE A','FLAT PACK 1','FLAT PACK 2','FLAT PACK 3','CONTAINER','NEW CAMP FEMALE','CAMP PB SCM'];
        }

        $priorities = ['rendah', 'sedang', 'sedang', 'tinggi', 'urgent'];
        $counter    = ['receptionist' => 1, 'hk' => 1, 'laundry' => 1];

        for ($day = $daysBack; $day >= 0; $day--) {
            $date     = Carbon::now()->subDays($day);
            $numToday = rand(1, 4);

            for ($j = 0; $j < $numToday; $j++) {
                $typeRand = rand(0, 2);
                $type     = ['receptionist', 'hk', 'laundry'][$typeRand];

                $items    = match($type) {
                    'receptionist' => $receptionist,
                    'hk'           => $hk,
                    default        => $laundry,
                };
                $item     = $items[array_rand($items)];
                $priority = $priorities[array_rand($priorities)];
                $status   = $statuses[array_rand($statuses)];

                // Older complaints should be mostly closed
                if ($day > 14 && in_array($status, ['open', 'progress'])) {
                    $status = 'closed';
                }

                $prefix = match($type) {
                    'receptionist' => 'RCP',
                    'hk'           => 'HKP',
                    default        => 'LDY',
                };
                $ticketNumber = sprintf('%s-%s-%04d', $prefix, $date->format('Ymd'), $counter[$type]++);

                $slaHours    = Complaint::$slaHours[$type][$priority];
                $slaDeadline = (clone $date)->addHours($slaHours);
                $resolvedAt  = ($status === 'closed')
                    ? (clone $date)->addHours(rand(1, $slaHours + 4))
                    : null;

                $reporter = $reporters[array_rand($reporters)];
                $building = $allBuildings[array_rand($allBuildings)];

                $data = [
                    'ticket_number' => $ticketNumber,
                    'type'          => $type,
                    'reporter_name' => $reporter['name'],
                    'reporter_wa'   => $reporter['wa'],
                    'priority'      => $priority,
                    'status'        => $status,
                    'description'   => $item['desc'],
                    'building'      => $building,
                    'sla_deadline'  => $slaDeadline,
                    'resolved_at'   => $resolvedAt,
                    'created_at'    => $date,
                    'updated_at'    => $date,
                ];

                if ($type === 'receptionist') {
                    $data['room_number'] = $rooms[array_rand($rooms)];
                } elseif ($type === 'hk') {
                    $data['room_number'] = rand(0, 1) ? $rooms[array_rand($rooms)] : null;
                } else {
                    $data['room_number'] = $rooms[array_rand($rooms)];
                }

                Complaint::create($data);
            }
        }
    }
}
