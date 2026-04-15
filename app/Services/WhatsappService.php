<?php

namespace App\Services;

use App\Models\Complaint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    private string $token = 'gLwdMNznAQxXc4TuRDb9';
    private string $apiUrl = 'https://api.fonnte.com/send';
    private string $groupId = '120363408048802780@g.us';

    public function sendComplaintToGroup(Complaint $complaint): array
    {
        $message = $this->buildComplaintMessage($complaint);

        try {
            $response = Http::asForm()
                ->withHeaders([
                    'Authorization' => $this->token,
                ])
                ->timeout(30)
                ->post($this->apiUrl, [
                    'target' => $this->groupId,
                    'message' => $message,
                ]);

            return [
                'success' => $response->successful(),
                'response' => $response->body(),
            ];
        } catch (\Throwable $e) {
            Log::warning('Failed to send complaint notification to WhatsApp group.', [
                'ticket_number' => $complaint->ticket_number,
                'error' => $e->getMessage(),
            ]);

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
        $footer = "\n\n_Pesan ini dikirim otomatis oleh sistem GA Facility Complaint Management._";

        return
            "*PEMBERITAHUAN LAPORAN BARU*\n\n" .
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
}
