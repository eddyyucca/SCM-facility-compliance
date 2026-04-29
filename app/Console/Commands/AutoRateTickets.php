<?php

namespace App\Console\Commands;

use App\Models\Complaint;
use App\Models\HrRequest;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoRateTickets extends Command
{
    protected $signature   = 'tickets:auto-rate';
    protected $description = 'Auto-rate closed tickets with no feedback after 224 hours';

    public function handle(): void
    {
        $cutoff = Carbon::now()->subHours(224);

        $complaints = Complaint::where('status', 'closed')
            ->whereNull('rating')
            ->whereNotNull('resolved_at')
            ->where('resolved_at', '<=', $cutoff)
            ->get();

        foreach ($complaints as $c) {
            $c->update([
                'rating'        => 5,
                'feedback_auto' => true,
                'feedback_at'   => now(),
            ]);
        }

        $hrRequests = HrRequest::where('status', 'closed')
            ->whereNull('rating')
            ->whereNotNull('resolved_at')
            ->where('resolved_at', '<=', $cutoff)
            ->get();

        foreach ($hrRequests as $r) {
            $r->update([
                'rating'        => 5,
                'feedback_auto' => true,
                'feedback_at'   => now(),
            ]);
        }

        $total = $complaints->count() + $hrRequests->count();
        $this->info("Auto-rated {$total} tickets.");
    }
}
