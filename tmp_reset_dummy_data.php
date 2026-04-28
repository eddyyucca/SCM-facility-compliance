<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$now = Carbon::now();

$complaints = [
    [
        'ticket_number' => 'RCP-0001',
        'type' => 'receptionist',
        'reporter_name' => 'Andi Saputra',
        'reporter_wa' => '081234567801',
        'company_name' => 'PT Sulawesi Cahaya Mineral',
        'job_title' => 'Supervisor Camp',
        'department' => 'General Affair',
        'building' => 'Mess A',
        'room_number' => 'A-12',
        'location' => null,
        'category' => 'Fasilitas Kamar',
        'priority' => 'sedang',
        'status' => 'open',
        'description' => 'AC kamar tidak dingin sejak tadi malam dan perlu pengecekan teknisi.',
        'photos' => null,
        'admin_notes' => null,
        'sla_deadline' => $now->copy()->addHours(18),
        'resolved_at' => null,
        'rating' => null,
        'feedback_text' => null,
        'feedback_at' => null,
        'feedback_auto' => false,
        'created_at' => $now->copy()->subHours(6),
        'updated_at' => $now->copy()->subHours(6),
    ],
    [
        'ticket_number' => 'HKP-0001',
        'type' => 'hk',
        'reporter_name' => 'Budi Hartono',
        'reporter_wa' => '081234567802',
        'company_name' => 'PT Sulawesi Cahaya Mineral',
        'job_title' => 'Operator',
        'department' => 'Produksi',
        'building' => 'Workshop 2',
        'room_number' => null,
        'location' => 'Area toilet belakang workshop',
        'category' => 'Kebersihan',
        'priority' => 'tinggi',
        'status' => 'progress',
        'description' => 'Saluran air toilet tersumbat dan area menjadi becek.',
        'photos' => null,
        'admin_notes' => 'Tim housekeeping sudah diarahkan ke lokasi untuk penanganan.',
        'sla_deadline' => $now->copy()->addHours(2),
        'resolved_at' => null,
        'rating' => null,
        'feedback_text' => null,
        'feedback_at' => null,
        'feedback_auto' => false,
        'created_at' => $now->copy()->subHours(2),
        'updated_at' => $now->copy()->subHour(),
    ],
    [
        'ticket_number' => 'LDY-0001',
        'type' => 'laundry',
        'reporter_name' => 'Citra Lestari',
        'reporter_wa' => '081234567803',
        'company_name' => 'PT Sulawesi Cahaya Mineral',
        'job_title' => 'Staff Admin',
        'department' => 'Finance',
        'building' => 'Mess B',
        'room_number' => 'B-07',
        'location' => null,
        'category' => 'Laundry',
        'priority' => 'sedang',
        'status' => 'closed',
        'description' => 'Seragam kerja tertukar dengan penghuni lain saat pengambilan laundry.',
        'photos' => null,
        'admin_notes' => 'Seragam sudah ditemukan dan dikembalikan ke pelapor.',
        'sla_deadline' => $now->copy()->subHours(20),
        'resolved_at' => $now->copy()->subHours(22),
        'rating' => 5,
        'feedback_text' => 'Penanganan cepat dan informatif.',
        'feedback_at' => $now->copy()->subHours(20),
        'feedback_auto' => false,
        'created_at' => $now->copy()->subDays(2),
        'updated_at' => $now->copy()->subHours(20),
    ],
    [
        'ticket_number' => 'RCP-0002',
        'type' => 'receptionist',
        'reporter_name' => 'Dewi Anggraini',
        'reporter_wa' => '081234567804',
        'company_name' => 'PT SCM Support',
        'job_title' => 'Coordinator',
        'department' => 'Support',
        'building' => 'Mess C',
        'room_number' => 'C-03',
        'location' => null,
        'category' => 'Kunci Kamar',
        'priority' => 'urgent',
        'status' => 'closed',
        'description' => 'Kartu akses kamar tidak bisa digunakan setelah pergantian shift.',
        'photos' => null,
        'admin_notes' => 'Akses kartu diperbarui dan sudah normal kembali.',
        'sla_deadline' => $now->copy()->subHours(28),
        'resolved_at' => $now->copy()->subHours(29),
        'rating' => 4,
        'feedback_text' => 'Sudah baik, hanya sempat menunggu beberapa menit.',
        'feedback_at' => $now->copy()->subHours(27),
        'feedback_auto' => false,
        'created_at' => $now->copy()->subDays(1)->subHours(8),
        'updated_at' => $now->copy()->subHours(27),
    ],
    [
        'ticket_number' => 'HKP-0002',
        'type' => 'hk',
        'reporter_name' => 'Eko Prasetyo',
        'reporter_wa' => '081234567805',
        'company_name' => 'PT Sulawesi Cahaya Mineral',
        'job_title' => 'Mechanic',
        'department' => 'Maintenance',
        'building' => 'Gudang Utama',
        'room_number' => null,
        'location' => 'Lorong barat dekat rak sparepart',
        'category' => 'Sampah',
        'priority' => 'rendah',
        'status' => 'rejected',
        'description' => 'Permintaan pembersihan area yang ternyata sudah masuk jadwal rutin mingguan.',
        'photos' => null,
        'admin_notes' => 'Permintaan ditutup karena sudah tercakup di jadwal housekeeping reguler.',
        'sla_deadline' => $now->copy()->subDays(1),
        'resolved_at' => $now->copy()->subHours(16),
        'rating' => null,
        'feedback_text' => null,
        'feedback_at' => null,
        'feedback_auto' => false,
        'created_at' => $now->copy()->subDays(2)->subHours(4),
        'updated_at' => $now->copy()->subHours(16),
    ],
    [
        'ticket_number' => 'LDY-0002',
        'type' => 'laundry',
        'reporter_name' => 'Farhan Maulana',
        'reporter_wa' => '081234567806',
        'company_name' => 'PT SCM Support',
        'job_title' => 'Driver',
        'department' => 'Logistik',
        'building' => 'Mess D',
        'room_number' => 'D-21',
        'location' => null,
        'category' => 'Laundry',
        'priority' => 'tinggi',
        'status' => 'progress',
        'description' => 'Laundry seragam belum kembali padahal dibutuhkan untuk shift malam ini.',
        'photos' => null,
        'admin_notes' => 'Sedang ditelusuri bersama vendor laundry.',
        'sla_deadline' => $now->copy()->subHours(1),
        'resolved_at' => null,
        'rating' => null,
        'feedback_text' => null,
        'feedback_at' => null,
        'feedback_auto' => false,
        'created_at' => $now->copy()->subHours(10),
        'updated_at' => $now->copy()->subMinutes(40),
    ],
];

$hrRequests = [
    [
        'ticket_number' => 'HR-0001',
        'employee_name' => 'Rina Oktavia',
        'employee_id' => 'SCM-00101',
        'company_name' => 'PT Sulawesi Cahaya Mineral',
        'department' => 'Finance',
        'position' => 'Staff Finance',
        'phone' => '081234560101',
        'email' => 'rina.oktavia@scm.local',
        'service_type' => 'Payroll / slip gaji',
        'priority' => 'normal',
        'period' => 'April 2026',
        'status' => 'open',
        'description' => 'Memerlukan salinan slip gaji untuk keperluan administrasi bank.',
        'attachments' => null,
        'admin_notes' => null,
        'sla_deadline' => $now->copy()->addHours(48),
        'resolved_at' => null,
        'rating' => null,
        'feedback_text' => null,
        'feedback_at' => null,
        'feedback_auto' => false,
        'created_at' => $now->copy()->subHours(5),
        'updated_at' => $now->copy()->subHours(5),
    ],
    [
        'ticket_number' => 'HR-0002',
        'employee_name' => 'Slamet Riyadi',
        'employee_id' => 'SCM-00102',
        'company_name' => 'PT Sulawesi Cahaya Mineral',
        'department' => 'Operasional',
        'position' => 'Supervisor',
        'phone' => '081234560102',
        'email' => 'slamet.riyadi@scm.local',
        'service_type' => 'Absensi, cuti, atau izin',
        'priority' => 'penting',
        'period' => '22-24 April 2026',
        'status' => 'progress',
        'description' => 'Perlu koreksi data cuti yang belum tercatat pada sistem absensi.',
        'attachments' => null,
        'admin_notes' => 'Sedang diverifikasi dengan tim payroll dan absensi.',
        'sla_deadline' => $now->copy()->addHours(10),
        'resolved_at' => null,
        'rating' => null,
        'feedback_text' => null,
        'feedback_at' => null,
        'feedback_auto' => false,
        'created_at' => $now->copy()->subHours(4),
        'updated_at' => $now->copy()->subHours(2),
    ],
    [
        'ticket_number' => 'HR-0003',
        'employee_name' => 'Mega Puspita',
        'employee_id' => 'SCM-00103',
        'company_name' => 'PT SCM Support',
        'department' => 'HSE',
        'position' => 'Officer',
        'phone' => '081234560103',
        'email' => 'mega.puspita@scm.local',
        'service_type' => 'BPJS, asuransi, atau benefit',
        'priority' => 'mendesak',
        'period' => 'April 2026',
        'status' => 'closed',
        'description' => 'Memerlukan klarifikasi aktivasi BPJS kesehatan untuk anggota keluarga.',
        'attachments' => null,
        'admin_notes' => 'Data benefit sudah diperbarui dan bukti aktivasi dikirim ke pelapor.',
        'sla_deadline' => $now->copy()->subHours(30),
        'resolved_at' => $now->copy()->subHours(31),
        'rating' => 5,
        'feedback_text' => 'Sangat membantu dan cepat.',
        'feedback_at' => $now->copy()->subHours(29),
        'feedback_auto' => false,
        'created_at' => $now->copy()->subDays(2),
        'updated_at' => $now->copy()->subHours(29),
    ],
    [
        'ticket_number' => 'HR-0004',
        'employee_name' => 'Taufik Hidayat',
        'employee_id' => 'SCM-00104',
        'company_name' => 'PT Sulawesi Cahaya Mineral',
        'department' => 'Engineering',
        'position' => 'Technician',
        'phone' => '081234560104',
        'email' => 'taufik.hidayat@scm.local',
        'service_type' => 'Surat keterangan kerja',
        'priority' => 'normal',
        'period' => null,
        'status' => 'closed',
        'description' => 'Mengajukan surat keterangan kerja untuk pengurusan dokumen pribadi.',
        'attachments' => null,
        'admin_notes' => 'Surat selesai dibuat dan telah dikirim melalui email perusahaan.',
        'sla_deadline' => $now->copy()->subHours(12),
        'resolved_at' => $now->copy()->subHours(14),
        'rating' => 4,
        'feedback_text' => 'Proses lancar dan hasil sesuai kebutuhan.',
        'feedback_at' => $now->copy()->subHours(12),
        'feedback_auto' => false,
        'created_at' => $now->copy()->subDays(1)->subHours(5),
        'updated_at' => $now->copy()->subHours(12),
    ],
    [
        'ticket_number' => 'HR-0005',
        'employee_name' => 'Yuni Kartika',
        'employee_id' => 'SCM-00105',
        'company_name' => 'PT SCM Support',
        'department' => 'Procurement',
        'position' => 'Buyer',
        'phone' => '081234560105',
        'email' => 'yuni.kartika@scm.local',
        'service_type' => 'Konsultasi hubungan kerja',
        'priority' => 'penting',
        'period' => 'April 2026',
        'status' => 'rejected',
        'description' => 'Mengajukan konsultasi yang ternyata perlu diarahkan ke atasan langsung terlebih dahulu.',
        'attachments' => null,
        'admin_notes' => 'Permintaan ditolak dan diarahkan untuk mengikuti jalur koordinasi internal unit kerja.',
        'sla_deadline' => $now->copy()->subHours(6),
        'resolved_at' => $now->copy()->subHours(8),
        'rating' => null,
        'feedback_text' => null,
        'feedback_at' => null,
        'feedback_auto' => false,
        'created_at' => $now->copy()->subDays(1),
        'updated_at' => $now->copy()->subHours(8),
    ],
];

$beforeComplaintCount = DB::table('complaints')->count();
$beforeHrCount = DB::table('hr_requests')->count();

DB::beginTransaction();

try {
    Storage::disk('public')->deleteDirectory('complaints');
    Storage::disk('public')->deleteDirectory('hr-requests');
    Storage::disk('public')->makeDirectory('complaints');
    Storage::disk('public')->makeDirectory('hr-requests');

    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    DB::table('complaints')->truncate();
    DB::table('hr_requests')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1');

    DB::table('complaints')->insert($complaints);
    DB::table('hr_requests')->insert($hrRequests);

    DB::commit();

    echo json_encode([
        'ok' => true,
        'before' => [
            'complaints' => $beforeComplaintCount,
            'hr_requests' => $beforeHrCount,
        ],
        'after' => [
            'complaints' => DB::table('complaints')->count(),
            'hr_requests' => DB::table('hr_requests')->count(),
        ],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
} catch (Throwable $e) {
    DB::rollBack();

    try {
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    } catch (Throwable $inner) {
    }

    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
}
