<?php

namespace App\Providers;

use App\Http\Controllers\PushSubscriptionController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->ensureHostingDirectories();

        Route::middleware('web')->group(function () {
            Route::get('/api/push/public-key', [PushSubscriptionController::class, 'publicKey'])
                ->name('api.push.public-key');

            Route::middleware('auth')->group(function () {
                Route::post('/api/push/subscribe', [PushSubscriptionController::class, 'store'])
                    ->name('api.push.subscribe');
                Route::delete('/api/push/unsubscribe', [PushSubscriptionController::class, 'destroy'])
                    ->name('api.push.unsubscribe');
            });
        });
    }

    private function ensureHostingDirectories(): void
    {
        $directories = [
            storage_path('app'),
            storage_path('app/private'),
            storage_path('app/public'),
            storage_path('app/public/complaints'),
            storage_path('framework'),
            storage_path('framework/cache'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
        ];

        foreach ($directories as $directory) {
            try {
                File::ensureDirectoryExists($directory);
            } catch (\Throwable $e) {
                Log::warning('Failed to ensure hosting directory exists.', [
                    'directory' => $directory,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
