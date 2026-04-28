<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PrepareHostingStorage extends Command
{
    protected $signature = 'hosting:prepare-storage';

    protected $description = 'Create required writable directories and the public/storage link for hosting';

    public function handle(): int
    {
        $directories = [
            storage_path('app'),
            storage_path('app/private'),
            storage_path('app/public'),
            storage_path('app/public/complaints'),
            storage_path('app/public/hr-requests'),
            storage_path('framework/cache'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
        ];

        foreach ($directories as $directory) {
            File::ensureDirectoryExists($directory);
            $this->line("ready: {$directory}");
        }

        $publicStorage = public_path('storage');
        $targetStorage = storage_path('app/public');

        if (!file_exists($publicStorage)) {
            $this->call('storage:link');
        } else {
            $this->line("ready: {$publicStorage}");
        }

        $this->newLine();
        $this->info('Hosting storage preparation completed.');

        return self::SUCCESS;
    }
}
