<?php

namespace App\Http\Controllers;

use App\Models\HrRequest;
use App\Services\WhatsappService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HrRequestController extends Controller
{
    public function __construct(
        private WhatsappService $whatsappService,
    )
    {
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hr_name' => 'required|string|max:120',
            'hr_employee_id' => 'nullable|string|max:80',
            'hr_company' => 'required|string|max:150',
            'hr_department' => 'required|string|max:120',
            'hr_position' => 'nullable|string|max:120',
            'hr_phone' => 'required|string|max:30',
            'hr_email' => 'nullable|email|max:150',
            'hr_service' => 'required|string|max:120',
            'hr_priority' => 'required|in:normal,penting,mendesak',
            'hr_period' => 'nullable|string|max:150',
            'hr_description' => 'required|string|max:3000',
            'hr_attachments' => 'nullable|array|max:6',
            'hr_attachments.*' => 'file|mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $attachments = [];
        if ($request->hasFile('hr_attachments')) {
            try {
                Storage::disk('public')->makeDirectory('hr-requests');
                $attachments = collect($request->file('hr_attachments'))
                    ->map(fn ($file) => $file->store('hr-requests', 'public'))
                    ->values()
                    ->all();
            } catch (\Throwable $e) {
                Log::error('HR attachment upload failed.', [
                    'error' => $e->getMessage(),
                    'disk' => 'public',
                    'target' => 'hr-requests',
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'Upload lampiran gagal. Pastikan folder storage hosting writable.');
            }
        }

        $hrRequest = HrRequest::create([
            'ticket_number' => HrRequest::generateTicket(),
            'employee_name' => $data['hr_name'],
            'employee_id' => $data['hr_employee_id'] ?? null,
            'company_name' => $data['hr_company'],
            'department' => $data['hr_department'],
            'position' => $data['hr_position'] ?? null,
            'phone' => $data['hr_phone'],
            'email' => $data['hr_email'] ?? null,
            'service_type' => $data['hr_service'],
            'priority' => $data['hr_priority'],
            'period' => $data['hr_period'] ?? null,
            'description' => $data['hr_description'],
            'attachments' => $attachments,
            'sla_deadline' => HrRequest::computeSlaDeadline($data['hr_priority']),
            'status' => 'open',
        ]);

        $waResult = $this->whatsappService->sendHrRequestToPersonal($hrRequest);
        if (! $waResult['success']) {
            Log::warning('HR request created but WhatsApp notification failed.', [
                'ticket_number' => $hrRequest->ticket_number,
                'response' => $waResult['response'],
            ]);
        }

        return redirect()->route('ticket.success')
            ->with('submitted_ticket', $hrRequest->ticket_number);
    }

    public function dashboard(Request $request)
    {
        $this->authorizeHrAccess();

        [$dateFrom, $dateTo] = $this->dateRange($request);

        $summary         = $this->buildSummary($dateFrom, $dateTo);
        $serviceStats    = $this->buildServiceStats($dateFrom, $dateTo);
        $chartData       = $this->chartData($dateFrom, $dateTo);
        $resolutionChart = $this->buildResolutionChart($dateFrom, $dateTo);
        $recent          = $this->buildRecent($dateFrom, $dateTo);
        $outstanding     = HrRequest::whereIn('status', ['open', 'progress'])
            ->where('sla_deadline', '<', now())
            ->orderBy('sla_deadline')
            ->limit(10)
            ->get();

        return view('hr.dashboard', compact(
            'summary', 'serviceStats', 'outstanding',
            'recent', 'chartData', 'resolutionChart',
            'dateFrom', 'dateTo'
        ));
    }

    public function dashboardStats(Request $request)
    {
        $this->authorizeHrAccess();

        [$dateFrom, $dateTo] = $this->dateRange($request);

        return response()->json([
            'summary'         => $this->buildSummary($dateFrom, $dateTo),
            'serviceStats'    => $this->buildServiceStats($dateFrom, $dateTo),
            'chartData'       => $this->chartData($dateFrom, $dateTo),
            'resolutionChart' => $this->buildResolutionChart($dateFrom, $dateTo),
            'recent'          => $this->buildRecent($dateFrom, $dateTo),
        ]);
    }

    public function index(Request $request)
    {
        $this->authorizeHrAccess();

        $query = HrRequest::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        if ($request->boolean('overdue')) {
            $query->whereIn('status', ['open', 'progress'])
                ->where('sla_deadline', '<', now());
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('employee_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('service_type', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $serviceTypes = HrRequest::query()
            ->select('service_type')
            ->distinct()
            ->orderBy('service_type')
            ->pluck('service_type');
        $hrRequests = $query->paginate(15)->withQueryString();

        return view('hr.index', compact('hrRequests', 'serviceTypes'));
    }

    public function show(HrRequest $hrRequest)
    {
        $this->authorizeHrAccess();

        return view('hr.show', compact('hrRequest'));
    }

    public function updateStatus(Request $request, HrRequest $hrRequest)
    {
        $this->authorizeHrAccess();

        $data = $request->validate([
            'status' => 'required|in:open,progress,closed,rejected',
            'admin_notes' => 'nullable|string|max:1500',
        ]);

        if (in_array($data['status'], ['closed', 'rejected'], true) && !$hrRequest->resolved_at) {
            $data['resolved_at'] = now();
        }

        if (!in_array($data['status'], ['closed', 'rejected'], true)) {
            $data['resolved_at'] = null;
        }

        $hrRequest->update($data);

        return back()->with('success', 'Status laporan Human Resources berhasil diperbarui.');
    }

    private function authorizeHrAccess(): void
    {
        $user = auth()->user();
        abort_unless($user && ($user->isSuperAdmin() || $user->isHr()), 403);
    }

    private function dateRange(Request $request): array
    {
        $tz = config('app.timezone');
        $dateFrom = $request->filled('date_from')
            ? Carbon::createFromFormat('Y-m-d', $request->date_from, $tz)->startOfDay()
            : Carbon::now($tz)->startOfMonth()->startOfDay();
        $dateTo = $request->filled('date_to')
            ? Carbon::createFromFormat('Y-m-d', $request->date_to, $tz)->endOfDay()
            : Carbon::now($tz)->endOfDay();

        return [$dateFrom, $dateTo];
    }

    private function buildSummary(Carbon $from, Carbon $to): array
    {
        $base     = HrRequest::whereBetween('created_at', [$from, $to]);
        $open     = (clone $base)->where('status', 'open')->count();
        $progress = (clone $base)->where('status', 'progress')->count();
        $closed   = (clone $base)->where('status', 'closed')->count();
        $rejected = (clone $base)->where('status', 'rejected')->count();
        $total    = $open + $progress + $closed + $rejected;

        $overdue = (clone $base)
            ->whereIn('status', ['open', 'progress'])
            ->where('sla_deadline', '<', now())
            ->count();

        $resolvedOnTime = (clone $base)
            ->where('status', 'closed')
            ->whereColumn('resolved_at', '<=', 'sla_deadline')
            ->count();

        $resolvedLate = (clone $base)
            ->where('status', 'closed')
            ->whereColumn('resolved_at', '>', 'sla_deadline')
            ->count();

        $slaRate = $closed > 0 ? round($resolvedOnTime / $closed * 100, 1) : null;

        $avgHours = (clone $base)
            ->where('status', 'closed')
            ->whereNotNull('resolved_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours')
            ->value('avg_hours');

        return [
            'total'             => $total,
            'open'              => $open,
            'progress'          => $progress,
            'closed'            => $closed,
            'rejected'          => $rejected,
            'overdue'           => $overdue,
            'resolved_on_time'  => $resolvedOnTime,
            'resolved_late'     => $resolvedLate,
            'sla_rate'          => $slaRate,
            'avg_hours'         => $avgHours !== null ? round((float) $avgHours, 1) : null,
            'priority_mendesak' => (clone $base)->where('priority', 'mendesak')->count(),
            'priority_penting'  => (clone $base)->where('priority', 'penting')->count(),
            'priority_normal'   => (clone $base)->where('priority', 'normal')->count(),
        ];
    }

    private function buildServiceStats(Carbon $from, Carbon $to): array
    {
        return HrRequest::whereBetween('created_at', [$from, $to])
            ->selectRaw('service_type, COUNT(*) as total')
            ->groupBy('service_type')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(fn ($row) => ['label' => $row->service_type, 'total' => $row->total])
            ->all();
    }

    private function buildRecent(Carbon $from, Carbon $to): array
    {
        return HrRequest::whereBetween('created_at', [$from, $to])
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn ($r) => [
                'id'             => $r->id,
                'ticket_number'  => $r->ticket_number,
                'employee_name'  => $r->employee_name,
                'service_type'   => $r->service_type,
                'priority_label' => $r->priorityLabel(),
                'priority_class' => $r->priorityBadgeClass(),
                'status_label'   => $r->statusLabel(),
                'status_class'   => $r->statusBadgeClass(),
                'url'            => route('hr.requests.show', $r),
            ])
            ->all();
    }

    private function chartData(Carbon $from, Carbon $to): array
    {
        $labels = [];
        $data = [];

        if ($from->diffInDays($to) <= 31) {
            $cursor = $from->copy()->startOfDay();
            while ($cursor <= $to) {
                $labels[] = $cursor->format('d M');
                $data[] = HrRequest::whereDate('created_at', $cursor->toDateString())->count();
                $cursor->addDay();
            }
        } else {
            $cursor = $from->copy()->startOfMonth();
            while ($cursor <= $to) {
                $labels[] = $cursor->format('M Y');
                $data[] = HrRequest::whereYear('created_at', $cursor->year)
                    ->whereMonth('created_at', $cursor->month)
                    ->count();
                $cursor->addMonth();
            }
        }

        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Laporan Human Resources',
                'data' => $data,
                'backgroundColor' => 'rgba(15,118,110,.18)',
                'borderColor' => '#0f766e',
                'borderWidth' => 2,
                'fill' => true,
                'tension' => 0.35,
            ]],
        ];
    }

    private function buildResolutionChart(Carbon $from, Carbon $to): array
    {
        $base = HrRequest::whereBetween('created_at', [$from, $to]);

        return [
            'labels'   => ['Tepat SLA', 'Terlambat', 'Belum Selesai', 'Ditolak'],
            'datasets' => [[
                'data' => [
                    (clone $base)->where('status', 'closed')->whereColumn('resolved_at', '<=', 'sla_deadline')->count(),
                    (clone $base)->where('status', 'closed')->whereColumn('resolved_at', '>', 'sla_deadline')->count(),
                    (clone $base)->whereIn('status', ['open', 'progress'])->count(),
                    (clone $base)->where('status', 'rejected')->count(),
                ],
                'backgroundColor' => ['#198754', '#dc3545', '#ffc107', '#adb5bd'],
                'borderWidth'     => 2,
                'borderColor'     => '#fff',
            ]],
        ];
    }
}
