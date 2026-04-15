<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ComplaintController extends Controller
{
    public function __construct(private WhatsappService $whatsappService)
    {
    }

    // Public: store a new complaint (no auth required)
    public function store(Request $request)
    {
        if ($request->input('building') === '__other__') {
            $request->merge([
                'building' => trim((string) $request->input('building_other')),
            ]);
        }

        $type = $request->input('type');

        $rules = [
            'type'          => 'required|in:receptionist,hk,laundry',
            'building'      => 'required|string|max:100',
            'building_other'=> 'nullable|string|max:100',
            'reporter_name' => 'required|string|max:100',
            'reporter_wa'   => 'nullable|string|max:20',
            'description'   => 'required|string|max:2000',
        ];

        if ($type === 'receptionist') {
            $rules['room_number'] = 'required|string|max:20';
        } elseif ($type === 'laundry') {
            $rules['room_number'] = 'nullable|string|max:20';
        }

        $data = $request->validate($rules);

        $data['priority']      = 'sedang';
        $data['ticket_number'] = Complaint::generateTicket($type);
        $data['sla_deadline']  = Complaint::computeSlaDeadline($type, 'sedang');
        $data['status']        = 'open';
        unset($data['building_other']);

        $complaint = Complaint::create($data);

        $waResult = $this->whatsappService->sendComplaintToGroup($complaint);
        if (!$waResult['success']) {
            Log::warning('Complaint created but WhatsApp group notification failed.', [
                'ticket_number' => $complaint->ticket_number,
                'response' => $waResult['response'],
            ]);
        }

        // Redirect to QR success page (ticket stored in session)
        return redirect()->route('ticket.success')
            ->with('submitted_ticket', $data['ticket_number']);
    }

    // Admin: list complaints
    public function index(Request $request)
    {
        $user  = Auth::user();
        $types = $user->isSuperAdmin() ? ['receptionist', 'hk', 'laundry'] : [$user->role];
        $query = Complaint::whereIn('type', $types)->orderByDesc('created_at');

        if ($request->filled('type') && in_array($request->type, $types)) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('building')) {
            $query->where('building', 'like', '%' . $request->building . '%');
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('ticket_number', 'like', "%$s%")
                  ->orWhere('reporter_name', 'like', "%$s%")
                  ->orWhere('reporter_wa', 'like', "%$s%")
                  ->orWhere('building', 'like', "%$s%")
                  ->orWhere('description', 'like', "%$s%");
            });
        }

        $complaints = $query->paginate(15)->withQueryString();

        return view('complaints.index', compact('complaints', 'types'));
    }

    // Admin: show single complaint
    public function show(Complaint $complaint)
    {
        $user = Auth::user();
        abort_unless($user->canView($complaint->type), 403);
        return view('complaints.show', compact('complaint'));
    }

    // Admin: update status
    public function updateStatus(Request $request, Complaint $complaint)
    {
        $user = Auth::user();
        abort_unless($user->canView($complaint->type), 403);

        $data = $request->validate([
            'status'      => 'required|in:open,progress,closed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($data['status'] === 'closed' && !$complaint->resolved_at) {
            $data['resolved_at'] = now();
        }

        $complaint->update($data);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}
