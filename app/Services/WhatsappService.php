<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\HrRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    public function isConfigured(): bool
    {
        $token = (string) config('services.whatsapp.token');

        return filled($token)
            && $token !== 'ISI_TOKEN_FONNTE'
            && filled(config('services.whatsapp.api_url'));
    }

    public function sendComplaintToGroup(Complaint $complaint): array
    {
        $target = (string) config('services.whatsapp.group_id');

        if (! $this->isConfigured() || blank($target)) {
            Log::warning('WhatsApp gateway is not configured.', [
                'ticket_number' => $complaint->ticket_number,
                'driver' => config('services.whatsapp.driver'),
                'has_token' => filled(config('services.whatsapp.token')),
                'has_group_id' => filled($target),
                'has_api_url' => filled(config('services.whatsapp.api_url')),
            ]);

            return [
                'success' => false,
                'response' => 'WhatsApp gateway is not configured.',
            ];
        }

        $message = $this->buildComplaintMessage($complaint);
        return $this->sendMessage($target, $message, [
            'ticket_number' => $complaint->ticket_number,
            'channel' => 'ga_group',
        ]);
    }

    public function sendHrRequestToPersonal(HrRequest $hrRequest): array
    {
        $targets = $this->parseTargets((string) config('services.whatsapp.hr_target'));

        if (! $this->isConfigured() || empty($targets)) {
            Log::warning('WhatsApp HR target is not configured.', [
                'ticket_number' => $hrRequest->ticket_number,
                'driver' => config('services.whatsapp.driver'),
                'has_token' => filled(config('services.whatsapp.token')),
                'has_hr_target' => ! empty($targets),
                'has_api_url' => filled(config('services.whatsapp.api_url')),
            ]);

            return [
                'success' => false,
                'response' => 'WhatsApp HR target is not configured.',
            ];
        }

        $message = $this->buildHrRequestMessage($hrRequest);

        $results = [];
        foreach ($targets as $target) {
            $results[] = $this->sendMessage($target, $message, [
                'ticket_number' => $hrRequest->ticket_number,
                'channel' => 'hr_personal',
            ]);
        }

        $failed = collect($results)->where('success', false)->values()->all();

        return [
            'success' => empty($failed),
            'response' => empty($failed)
                ? 'Sent to all HR targets.'
                : json_encode($failed, JSON_UNESCAPED_SLASHES),
        ];
    }

    private function parseTargets(string $rawTargets): array
    {
        return collect(explode(',', $rawTargets))
            ->map(fn ($target) => trim($target))
            ->filter()
            ->map(fn ($target) => $this->normalizeTarget($target))
            ->filter()
            ->values()
            ->all();
    }

    private function normalizeTarget(string $target): ?string
    {
        if (str_contains($target, '@g.us')) {
            return $target;
        }

        $clean = preg_replace('/[^0-9]/', '', $target);

        if (! $clean) {
            return null;
        }

        if (str_starts_with($clean, '0')) {
            return '62' . substr($clean, 1);
        }

        if (str_starts_with($clean, '62')) {
            return $clean;
        }

        return $clean;
    }

    private function sendMessage(string $target, string $message, array $context = []): array
    {
        $apiUrl = (string) config('services.whatsapp.api_url');
        $token = (string) config('services.whatsapp.token');

        try {
            $response = Http::asForm()
                ->withHeaders([
                    'Authorization' => $token,
                ])
                ->timeout(30)
                ->post($apiUrl, [
                    'target' => $target,
                    'message' => $message,
                ]);

            if (! $response->successful()) {
                Log::warning('WhatsApp gateway returned a non-success response.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'target' => $target,
                    'api_url' => $apiUrl,
                ] + $context);
            }

            return [
                'success' => $response->successful(),
                'response' => $response->body(),
            ];
        } catch (\Throwable $e) {
            Log::warning('Failed to send complaint notification to WhatsApp group.', [
                'error' => $e->getMessage(),
                'target' => $target,
                'api_url' => $apiUrl,
            ] + $context);

            return [
                'success' => false,
                'response' => $e->getMessage(),
            ];
        }
    }

    private function buildComplaintMessage(Complaint $complaint): string
    {
        $typeLabel = $complaint->typeLabel();
        $submittedAt = $complaint->created_at?->timezone(config('app.timezone'))->format('d-m-Y H:i') ?? now()->format('d-m-Y H:i');
        $room = $complaint->room_number ? "\nNo. Kamar: {$complaint->room_number}" : '';
        $reporterWa = $complaint->reporter_wa ? $complaint->reporter_wa : '-';
        $company = $complaint->company_name ? "Perusahaan: *{$complaint->company_name}*\n" : '';
        $jobTitle = $complaint->job_title ? "Jabatan: *{$complaint->job_title}*\n" : '';
        $footer = "\n\n_Pesan ini dikirim otomatis oleh sistem SEDIA._";

        return
            "*SEDIA - LAPORAN GA BARU*\n\n" .
            "No. Tiket: *{$complaint->ticket_number}*\n" .
            "Tipe Laporan: *{$typeLabel}*\n" .
            "Bangunan / Area: *{$complaint->building}*{$room}\n" .
            "Nama Pelapor: *{$complaint->reporter_name}*\n" .
            $company .
            $jobTitle .
            "No. WhatsApp: *{$reporterWa}*\n" .
            "Waktu Laporan: *{$submittedAt}*\n\n" .
            "*Uraian Keluhan:*\n{$complaint->description}" .
            $footer;
    }

    private function buildHrRequestMessage(HrRequest $hrRequest): string
    {
        $submittedAt = $hrRequest->created_at?->timezone(config('app.timezone'))->format('d-m-Y H:i') ?? now()->format('d-m-Y H:i');
        $employeeId = $hrRequest->employee_id ? "NIK / ID: *{$hrRequest->employee_id}*\n" : '';
        $position = $hrRequest->position ? "Jabatan: *{$hrRequest->position}*\n" : '';
        $period = $hrRequest->period ? "Periode Terkait: *{$hrRequest->period}*\n" : '';
        $email = $hrRequest->email ? "Email: *{$hrRequest->email}*\n" : '';
        $footer = "\n\n_Pesan ini dikirim otomatis oleh sistem SEDIA._";

        return
            "*SEDIA - LAPORAN HR BARU*\n\n" .
            "No. Tiket: *{$hrRequest->ticket_number}*\n" .
            "Jenis Layanan: *{$hrRequest->service_type}*\n" .
            "Prioritas: *{$hrRequest->priorityLabel()}*\n" .
            "Nama Karyawan: *{$hrRequest->employee_name}*\n" .
            $employeeId .
            "Perusahaan: *{$hrRequest->company_name}*\n" .
            "Departemen: *{$hrRequest->department}*\n" .
            $position .
            "No. WhatsApp: *{$hrRequest->phone}*\n" .
            $email .
            $period .
            "Waktu Laporan: *{$submittedAt}*\n\n" .
            "*Detail Permintaan:*\n{$hrRequest->description}" .
            $footer;
    }
}
