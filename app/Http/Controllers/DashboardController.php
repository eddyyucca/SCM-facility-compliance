<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user      = Auth::user();
        $allTypes  = ['receptionist', 'hk', 'laundry'];
        $userTypes = $user->isSuperAdmin() ? $allTypes : [$user->role];

        // ── Date range (default: current month) ──
        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : Carbon::now()->startOfMonth();
        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : Carbon::now()->endOfDay();

        // ── Summary (all user's types, date-filtered) ──
        $base    = Complaint::whereIn('type', $userTypes)->whereBetween('created_at', [$dateFrom, $dateTo]);
        $summary = [
            'total'    => (clone $base)->count(),
            'open'     => (clone $base)->where('status', 'open')->count(),
            'progress' => (clone $base)->where('status', 'progress')->count(),
            'closed'   => (clone $base)->where('status', 'closed')->count(),
            'overdue'  => (clone $base)->whereIn('status', ['open','progress'])
                                       ->where('sla_deadline', '<', now())->count(),
        ];

        // ── Per-type stats ──
        $typeStats = [];
        foreach ($userTypes as $type) {
            $q = Complaint::where('type', $type)->whereBetween('created_at', [$dateFrom, $dateTo]);
            $typeStats[$type] = [
                'total'    => (clone $q)->count(),
                'open'     => (clone $q)->where('status', 'open')->count(),
                'progress' => (clone $q)->where('status', 'progress')->count(),
                'closed'   => (clone $q)->where('status', 'closed')->count(),
                'overdue'  => (clone $q)->whereIn('status', ['open','progress'])
                                        ->where('sla_deadline', '<', now())->count(),
                'recent'      => Complaint::where('type', $type)
                                    ->orderByDesc('created_at')
                                    ->limit(6)->get(),
            ];
        }

        // ── Chart data ──
        $chartData = $this->buildChartData($userTypes, $dateFrom, $dateTo);

        // ── Outstanding SLA (not date-filtered, always current) ──
        $outstanding = Complaint::whereIn('type', $userTypes)
            ->whereIn('status', ['open', 'progress'])
            ->where('sla_deadline', '<', now())
            ->orderBy('sla_deadline')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'summary', 'typeStats', 'chartData', 'outstanding',
            'userTypes', 'dateFrom', 'dateTo'
        ));
    }

    /** AJAX endpoint — new complaints since timestamp */
    public function newComplaints(Request $request)
    {
        $user      = Auth::user();
        $types     = $user->isSuperAdmin() ? ['receptionist','hk','laundry'] : [$user->role];
        $sinceRaw  = $request->input('since', now()->subMinutes(1)->toISOString());

        try {
            $since = Carbon::parse($sinceRaw);
        } catch (\Throwable $e) {
            $since = now()->subMinutes(1);
        }

        $count = Complaint::whereIn('type', $types)
            ->where('created_at', '>', $since)
            ->count();

        $complaints = Complaint::whereIn('type', $types)
            ->where('created_at', '>', $since)
            ->latest()
            ->take(5)
            ->get(['id', 'ticket_number', 'type', 'reporter_name', 'created_at'])
            ->map(function ($c) {
                $c->age = $c->created_at->diffForHumans();
                return $c;
            });

        return response()->json([
            'count'      => $count,
            'complaints' => $complaints,
            'timestamp'  => now()->toISOString(),
        ]);
    }

    private function buildChartData(array $types, Carbon $from, Carbon $to): array
    {
        $diffDays = (int) $from->diffInDays($to);
        $labels   = [];
        $points   = [];

        $colors = [
            'receptionist' => ['bg' => 'rgba(13,110,253,.65)',  'border' => '#0d6efd'],
            'hk'           => ['bg' => 'rgba(25,135,84,.65)',   'border' => '#198754'],
            'laundry'      => ['bg' => 'rgba(255,193,7,.75)',   'border' => '#e6a800'],
        ];
        $typeLabels = [
            'receptionist' => 'Resepsionis',
            'hk'           => 'Housekeeping',
            'laundry'      => 'Laundry',
        ];

        // Choose granularity
        if ($diffDays <= 31) {
            $cursor = $from->copy()->startOfDay();
            while ($cursor <= $to) {
                $labels[]     = $cursor->format('d M');
                $points[$cursor->toDateString()] = [];
                $cursor->addDay();
            }
            foreach ($types as $type) {
                $data = [];
                foreach (array_keys($points) as $date) {
                    $data[] = Complaint::where('type', $type)
                        ->whereDate('created_at', $date)->count();
                }
                $points[$type] = $data;
            }
            $datasets = [];
            foreach ($types as $type) {
                $datasets[] = [
                    'label'           => $typeLabels[$type] ?? $type,
                    'data'            => $points[$type],
                    'backgroundColor' => $colors[$type]['bg'],
                    'borderColor'     => $colors[$type]['border'],
                    'borderWidth'     => 2,
                    'fill'            => false,
                    'tension'         => 0.35,
                ];
            }
        } else {
            $cursor = $from->copy()->startOfMonth();
            while ($cursor <= $to) {
                $labels[] = $cursor->format('M Y');
                $cursor->addMonth();
            }
            $datasets = [];
            foreach ($types as $type) {
                $data   = [];
                $cursor = $from->copy()->startOfMonth();
                while ($cursor <= $to) {
                    $data[] = Complaint::where('type', $type)
                        ->whereYear('created_at', $cursor->year)
                        ->whereMonth('created_at', $cursor->month)
                        ->count();
                    $cursor->addMonth();
                }
                $datasets[] = [
                    'label'           => $typeLabels[$type] ?? $type,
                    'data'            => $data,
                    'backgroundColor' => $colors[$type]['bg'],
                    'borderColor'     => $colors[$type]['border'],
                    'borderWidth'     => 2,
                    'fill'            => false,
                    'tension'         => 0.35,
                ];
            }
        }

        return ['labels' => $labels, 'datasets' => $datasets];
    }
}
