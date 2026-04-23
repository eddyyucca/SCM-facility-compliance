<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\HrRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /** Public ticket status check page */
    public function show(string $ticket)
    {
        $complaint = $this->findTicket(strtoupper(trim($ticket)));
        abort_unless($complaint, 404);

        return view('ticket.status', compact('complaint'));
    }

    /** AJAX: check ticket status */
    public function check(Request $request)
    {
        $ticket = strtoupper(trim($request->input('ticket', '')));

        if (empty($ticket)) {
            return response()->json(['found' => false, 'message' => 'Nomor tiket tidak boleh kosong.'], 422);
        }

        $complaint = $this->findTicket($ticket);

        if (!$complaint) {
            return response()->json(['found' => false, 'message' => 'Tiket tidak ditemukan. Periksa kembali nomor tiket Anda.']);
        }

        return response()->json([
            'found'        => true,
            'ticket'       => $complaint->ticket_number,
            'type'         => $complaint->typeLabel(),
            'status'       => $complaint->status,
            'status_label' => $complaint->statusLabel(),
            'status_color' => $complaint->statusColor(),
            'reporter'     => $complaint instanceof HrRequest ? $complaint->employee_name : $complaint->reporter_name,
            'building'     => $complaint instanceof HrRequest ? $complaint->department : $complaint->building,
            'room'         => $complaint instanceof HrRequest ? null : $complaint->room_number,
            'description'  => Str::limit($complaint->description, 100),
            'admin_notes'  => $complaint->admin_notes,
            'created_at'   => $complaint->created_at->format('d M Y H:i'),
            'sla_deadline' => $complaint->sla_deadline?->format('d M Y H:i'),
            'resolved_at'  => $complaint->resolved_at?->format('d M Y H:i'),
            'is_overdue'   => $complaint->isOverdue(),
            'url'          => route('ticket.show', $complaint->ticket_number),
        ]);
    }

    /** Success page after complaint submitted */
    public function success(Request $request)
    {
        $ticket = $request->session()->get('submitted_ticket');
        if (!$ticket) {
            return redirect()->route('home');
        }
        $complaint = $this->findTicket($ticket);
        abort_unless($complaint, 404);

        return view('ticket.success', compact('complaint'));
    }

    private function findTicket(string $ticket): Complaint|HrRequest|null
    {
        return Complaint::where('ticket_number', $ticket)->first()
            ?? HrRequest::where('ticket_number', $ticket)->first();
    }
}
