<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearReports extends Command
{
    protected $signature   = 'db:clear-reports {--force : Skip confirmation prompt}';
    protected $description = 'Hapus semua data laporan (complaints & hr_requests). Akun pengguna tetap aman.';

    public function handle(): int
    {
        $this->info('Tabel yang akan dikosongkan:');
        $this->line('  • complaints');
        $this->line('  • hr_requests');
        $this->newLine();
        $this->line('Tabel yang TIDAK disentuh:');
        $this->line('  • users, menu_permissions, sla_settings, push_subscriptions');
        $this->newLine();

        if (! $this->option('force') && ! $this->confirm('Yakin ingin menghapus semua data laporan? Aksi ini tidak bisa dibatalkan.')) {
            $this->warn('Dibatalkan.');
            return self::FAILURE;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $complaints = DB::table('complaints')->count();
        $hrRequests = DB::table('hr_requests')->count();

        DB::table('complaints')->truncate();
        DB::table('hr_requests')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->info("Selesai. {$complaints} complaints dan {$hrRequests} hr_requests dihapus.");
        $this->line('Akun pengguna, setting menu, dan SLA tetap utuh.');

        return self::SUCCESS;
    }
}
