<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushSubscriptionController extends Controller
{
    public function publicKey(): JsonResponse
    {
        return response()->json([
            'enabled' => filled(config('services.webpush.public_key')) && filled(config('services.webpush.private_key')),
            'publicKey' => config('services.webpush.public_key'),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'endpoint' => 'required|url|max:2000',
            'keys.p256dh' => 'required|string|max:255',
            'keys.auth' => 'required|string|max:255',
            'contentEncoding' => 'nullable|string|in:aesgcm,aes128gcm',
        ]);

        $user = Auth::user();
        $endpointHash = hash('sha256', $data['endpoint']);

        PushSubscription::updateOrCreate(
            [
                'endpoint_hash' => $endpointHash,
            ],
            [
                'user_id' => $user->id,
                'endpoint' => $data['endpoint'],
                'endpoint_hash' => $endpointHash,
                'public_key' => $data['keys']['p256dh'],
                'auth_token' => $data['keys']['auth'],
                'content_encoding' => $data['contentEncoding'] ?? 'aes128gcm',
                'user_agent' => (string) $request->userAgent(),
                'last_used_at' => now(),
            ]
        );

        return response()->json(['ok' => true]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $data = $request->validate([
            'endpoint' => 'required|url|max:2000',
        ]);

        PushSubscription::where('user_id', $request->user()->id)
            ->where('endpoint_hash', hash('sha256', $data['endpoint']))
            ->delete();

        return response()->json(['ok' => true]);
    }
}
