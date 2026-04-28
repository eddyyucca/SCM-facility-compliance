<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // HR tidak punya akses ke GA dashboard, arahkan ke HR dashboard
        if ($user->isHr()) {
            return redirect()->route('hr.dashboard');
        }

        $allTypes  = ['receptionist', 'hk', 'laundry'];
        $userTypes = $user->gaTypes();

        // ── Date range (default: current month) ──
        $tz = config('app.timezone');
        $dateFrom = $request->filled('date_from')
            ? Carbon::createFromFormat('Y-m-d', $request->date_from, $tz)->startOfDay()
            : Carbon::now($tz)->startOfMonth()->startOfDay();
        $dateTo = $request->filled('date_to')
            ? Carbon::createFromFormat('Y-m-d', $request->date_to, $tz)->endOfDay()
            : Carbon::now($tz)->endOfDay();

        // ── Summary — TANPA date filter (kondisi saat ini) ──
        $base = Complaint::whereIn('type', $userTypes);
        $open     = (clone $base)->where('status', 'open')->count();
        $progress = (clone $base)->where('status', 'progress')->count();
        $closed   = (clone $base)->where('status', 'closed')->count();
        $rejected = (clone $base)->where('status', 'rejected')->count();
        $summary  = [
            'total'    => $open + $progress + $closed + $rejected,
            'open'     => $open,
            'progress' => $progress,
            'closed'   => $closed,
            'rejected' => $rejected,
            'overdue'  => (clone $base)->whereIn('status', ['open','progress'])
                                       ->where('sla_deadline', '<', now())->count(),
        ];

        // ── Per-type stats — TANPA date filter (kondisi saat ini) ──
        $typeStats = [];
        foreach ($userTypes as $type) {
            $q        = Complaint::where('type', $type);
            $tOpen    = (clone $q)->where('status', 'open')->count();
            $tProg    = (clone $q)->where('status', 'progress')->count();
            $tClosed  = (clone $q)->where('status', 'closed')->count();
            $tRej     = (clone $q)->where('status', 'rejected')->count();
            $typeStats[$type] = [
                'total'    => $tOpen + $tProg + $tClosed + $tRej,
                'tab_total' => $tOpen + $tProg + $tClosed,
                'open'     => $tOpen,
                'progress' => $tProg,
                'closed'   => $tClosed,
                'rejected' => $tRej,
                'overdue'  => (clone $q)->whereIn('status', ['open','progress'])
                                        ->where('sla_deadline', '<', now())->count(),
                'recent'   => Complaint::where('type', $type)
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

    /** AJAX endpoint — live dashboard stats */
    public function stats(Request $request)
    {
        $user      = Auth::user();
        $allTypes  = ['receptionist', 'hk', 'laundry'];
        $userTypes = $user->gaTypes();

        $tz = config('app.timezone');
        $dateFrom = $request->filled('date_from')
            ? Carbon::createFromFormat('Y-m-d', $request->date_from, $tz)->startOfDay()
            : Carbon::now($tz)->startOfMonth()->startOfDay();
        $dateTo = $request->filled('date_to')
            ? Carbon::createFromFormat('Y-m-d', $request->date_to, $tz)->endOfDay()
            : Carbon::now($tz)->endOfDay();

        // Summary — TANPA date filter (kondisi saat ini)
        $base     = Complaint::whereIn('type', $userTypes);
        $open     = (clone $base)->where('status', 'open')->count();
        $progress = (clone $base)->where('status', 'progress')->count();
        $closed   = (clone $base)->where('status', 'closed')->count();
        $rejected = (clone $base)->where('status', 'rejected')->count();
        $summary  = [
            'total'    => $open + $progress + $closed + $rejected,
            'open'     => $open,
            'progress' => $progress,
            'closed'   => $closed,
            'rejected' => $rejected,
            'overdue'  => (clone $base)->whereIn('status', ['open','progress'])
                                       ->where('sla_deadline', '<', now())->count(),
        ];

        $typeStats = [];
        foreach ($userTypes as $type) {
            $q       = Complaint::where('type', $type);
            $tOpen   = (clone $q)->where('status', 'open')->count();
            $tProg   = (clone $q)->where('status', 'progress')->count();
            $tClosed = (clone $q)->where('status', 'closed')->count();
            $tRej    = (clone $q)->where('status', 'rejected')->count();
            $typeStats[$type] = [
                'total'    => $tOpen + $tProg + $tClosed + $tRej,
                'tab_total' => $tOpen + $tProg + $tClosed,
                'open'     => $tOpen,
                'progress' => $tProg,
                'closed'   => $tClosed,
                'rejected' => $tRej,
                'overdue'  => (clone $q)->whereIn('status', ['open','progress'])
                                        ->where('sla_deadline', '<', now())->count(),
            ];
        }

        $outstanding = Complaint::whereIn('type', $userTypes)
            ->whereIn('status', ['open', 'progress'])
            ->where('sla_deadline', '<', now())
            ->orderBy('sla_deadline')
            ->limit(10)
            ->get()
            ->map(fn ($c) => [
                'ticket'       => $c->ticket_number,
                'type_label'   => $c->typeLabel(),
                'type_badge'   => $c->type === 'receptionist' ? 'type-rec' : ($c->type === 'hk' ? 'type-hk' : 'type-ldy'),
                'reporter'     => $c->reporter_name,
                'status_label' => $c->statusLabel(),
                'status_badge' => $c->statusBadgeClass(),
                'sla_deadline' => $c->sla_deadline?->format('d M Y H:i'),
                'late_hours'   => abs(round($c->sla_deadline?->diffInHours(now()), 1)),
                'url'          => route('complaints.show', $c),
            ]);

        $chartData = $this->buildChartData($userTypes, $dateFrom, $dateTo);

        return response()->json(compact('summary', 'typeStats', 'outstanding', 'chartData'));
    }

    /** AJAX endpoint — new complaints since timestamp */
    public function newComplaints(Request $request)
    {
        $pushAction = $request->input('push_action');
        if ($pushAction === 'public_key') {
            return response()->json([
                'enabled' => filled(config('services.webpush.public_key')) && filled(config('services.webpush.private_key')),
                'publicKey' => config('services.webpush.public_key'),
            ]);
        }

        if ($pushAction === 'subscribe') {
            $data = $request->validate([
                'endpoint' => 'required|url|max:2000',
                'p256dh' => 'required|string|max:255',
                'auth' => 'required|string|max:255',
                'content_encoding' => 'nullable|string|in:aesgcm,aes128gcm',
            ]);
            $user = Auth::user();

            PushSubscription::updateOrCreate(
                ['endpoint_hash' => hash('sha256', $data['endpoint'])],
                [
                    'user_id' => $user->id,
                    'endpoint' => $data['endpoint'],
                    'endpoint_hash' => hash('sha256', $data['endpoint']),
                    'public_key' => $data['p256dh'],
                    'auth_token' => $data['auth'],
                    'content_encoding' => $data['content_encoding'] ?? 'aes128gcm',
                    'user_agent' => (string) $request->userAgent(),
                    'last_used_at' => now(),
                ]
            );

            return response()->json(['ok' => true]);
        }

        if ($pushAction === 'unsubscribe') {
            $data = $request->validate([
                'endpoint' => 'required|url|max:2000',
            ]);
            $user = Auth::user();

            PushSubscription::where('user_id', $user->id)
                ->where('endpoint_hash', hash('sha256', $data['endpoint']))
                ->delete();

            return response()->json(['ok' => true]);
        }

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
            'receptionist' => 'Receptionist',
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
