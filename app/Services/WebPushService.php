<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\PushSubscription;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class WebPushService
{
    public function isConfigured(): bool
    {
        return filled(config('services.webpush.public_key'))
            && filled(config('services.webpush.private_key'))
            && filled(config('services.webpush.subject'));
    }

    public function sendComplaintCreated(Complaint $complaint): void
    {
        if (!$this->isConfigured()) {
            return;
        }

        $subscriptions = PushSubscription::query()
            ->whereHas('user', fn ($query) => $query->where('role', 'superadmin')->orWhere('role', $complaint->type))
            ->get();

        if ($subscriptions->isEmpty()) {
            return;
        }

        $payload = json_encode([
            'title' => 'Laporan Baru',
            'body' => sprintf(
                '%s • %s • %s',
                $complaint->ticket_number,
                $complaint->typeLabel(),
                $complaint->reporter_name
            ),
            'icon' => asset('icons/icon-192.png'),
            'badge' => asset('icons/icon-192.png'),
            'tag' => 'complaint-' . $complaint->id,
            'url' => route('complaints.show', $complaint),
            'data' => [
                'complaint_id' => $complaint->id,
                'ticket_number' => $complaint->ticket_number,
                'type' => $complaint->type,
            ],
        ], JSON_UNESCAPED_SLASHES);

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => config('services.webpush.subject'),
                'publicKey' => config('services.webpush.public_key'),
                'privateKey' => config('services.webpush.private_key'),
            ],
        ]);

        $webPush->setReuseVAPIDHeaders(true);

        foreach ($subscriptions as $subscription) {
            $webPush->queueNotification(
                Subscription::create([
                    'endpoint' => $subscription->endpoint,
                    'publicKey' => $subscription->public_key,
                    'authToken' => $subscription->auth_token,
                    'contentEncoding' => $subscription->content_encoding,
                ]),
                $payload,
                ['urgency' => 'high']
            );
        }

        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();
            $storedSubscription = $subscriptions->firstWhere('endpoint', $endpoint);

            if ($report->isSuccess()) {
                if ($storedSubscription) {
                    $storedSubscription->forceFill(['last_used_at' => now()])->save();
                }

                continue;
            }

            Log::warning('Web push notification failed.', [
                'endpoint' => $endpoint,
                'reason' => $report->getReason(),
            ]);

            if ($storedSubscription && in_array($report->getResponse()?->getStatusCode(), [404, 410], true)) {
                $storedSubscription->delete();
            }
        }
    }
}
