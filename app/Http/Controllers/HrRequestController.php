<?php

namespace App\Http\Controllers;

use App\Models\HrRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HrRequestController extends Controller
{
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
            Storage::disk('public')->makeDirectory('hr-requests');
            $attachments = collect($request->file('hr_attachments'))
                ->map(fn ($file) => $file->store('hr-requests', 'public'))
                ->values()
                ->all();
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

        return redirect()->route('ticket.success')
            ->with('submitted_ticket', $hrRequest->ticket_number);
    }

    public function dashboard(Request $request)
    {
        $this->authorizeSuperAdmin();

        [$dateFrom, $dateTo] = $this->dateRange($request);
        $summary = $this->summary();
        $serviceStats = $this->serviceStats();
        $outstanding = HrRequest::whereIn('status', ['open', 'progress'])
            ->where('sla_deadline', '<', now())
            ->orderBy('sla_deadline')
            ->limit(10)
            ->get();
        $recent = HrRequest::latest()->limit(8)->get();
        $chartData = $this->chartData($dateFrom, $dateTo);
        $resolutionChart = $this->resolutionChart();

        return view('hr.dashboard', compact(
            'summary',
            'serviceStats',
            'outstanding',
            'recent',
            'chartData',
            'resolutionChart',
            'dateFrom',
            'dateTo'
        ));
    }

    public function index(Request $request)
    {
        $this->authorizeSuperAdmin();

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
        $this->authorizeSuperAdmin();

        return view('hr.show', compact('hrRequest'));
    }

    public function updateStatus(Request $request, HrRequest $hrRequest)
    {
        $this->authorizeSuperAdmin();

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

    private function authorizeSuperAdmin(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
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

    private function summary(): array
    {
        $base = HrRequest::query();
        $open = (clone $base)->where('status', 'open')->count();
        $progress = (clone $base)->where('status', 'progress')->count();
        $closed = (clone $base)->where('status', 'closed')->count();
        $rejected = (clone $base)->where('status', 'rejected')->count();

        return [
            'total' => $open + $progress + $closed + $rejected,
            'open' => $open,
            'progress' => $progress,
            'closed' => $closed,
            'rejected' => $rejected,
            'overdue' => (clone $base)->whereIn('status', ['open', 'progress'])
                ->where('sla_deadline', '<', now())
                ->count(),
            'resolved_on_time' => HrRequest::where('status', 'closed')
                ->whereColumn('resolved_at', '<=', 'sla_deadline')
                ->count(),
        ];
    }

    private function serviceStats(): array
    {
        return HrRequest::query()
            ->selectRaw('service_type, COUNT(*) as total')
            ->groupBy('service_type')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(fn ($row) => ['label' => $row->service_type, 'total' => $row->total])
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

    private function resolutionChart(): array
    {
        return [
            'labels' => ['Tepat SLA', 'Overdue', 'Belum Selesai', 'Ditolak'],
            'datasets' => [[
                'data' => [
                    HrRequest::where('status', 'closed')->whereColumn('resolved_at', '<=', 'sla_deadline')->count(),
                    HrRequest::where('status', 'closed')->whereColumn('resolved_at', '>', 'sla_deadline')->count(),
                    HrRequest::whereIn('status', ['open', 'progress'])->count(),
                    HrRequest::where('status', 'rejected')->count(),
                ],
                'backgroundColor' => ['#198754', '#dc3545', '#ffc107', '#adb5bd'],
                'borderWidth' => 2,
                'borderColor' => '#fff',
            ]],
        ];
    }
}
