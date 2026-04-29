<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user      = Auth::user();
        $userTypes = $user->gaTypes();

        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : Carbon::now()->startOfMonth();
        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : Carbon::now()->endOfDay();

        $base = Complaint::whereIn('type', $userTypes)
                         ->whereBetween('created_at', [$dateFrom, $dateTo]);

        // ── 1. By type ──
        $byType = [];
        foreach ($userTypes as $t) {
            $byType[$t] = (clone $base)->where('type', $t)->count();
        }

        // ── 2. By status ──
        $byStatus = [
            'open'     => (clone $base)->where('status', 'open')->count(),
            'progress' => (clone $base)->where('status', 'progress')->count(),
            'closed'   => (clone $base)->where('status', 'closed')->count(),
        ];

        // ── 3. Top buildings ──
        $topBuildings = (clone $base)
            ->whereNotNull('building')
            ->where('building', '!=', '')
            ->selectRaw('building, COUNT(*) as cnt')
            ->groupBy('building')
            ->orderByDesc('cnt')
            ->limit(15)
            ->get();

        // ── 4. SLA compliance ──
        $closedQuery  = (clone $base)->where('status', 'closed');
        $totalClosed  = (clone $closedQuery)->count();
        $onTime       = (clone $closedQuery)->whereNotNull('resolved_at')
                                            ->whereColumn('resolved_at', '<=', 'sla_deadline')
                                            ->count();
        $lateClose    = $totalClosed - $onTime;
        $slaRate      = $totalClosed > 0 ? round($onTime / $totalClosed * 100, 1) : 0;

        // ── 5. Overdue currently ──
        $overdue = (clone $base)
            ->whereIn('status', ['open', 'progress'])
            ->where('sla_deadline', '<', now())
            ->count();

        // ── 6. Day-of-week distribution (fetch small dataset) ──
        $dowData = array_fill(0, 7, 0); // Mon-Sun (0=Mon)
        $dowLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        (clone $base)->get(['created_at'])->each(function ($c) use (&$dowData) {
            // Carbon: 0=Sunday, 1=Monday ... 6=Saturday  → remap to Mon-first
            $dow = ($c->created_at->dayOfWeek + 6) % 7;
            $dowData[$dow]++;
        });

        // ── 7. Hourly distribution ──
        $hourData = array_fill(0, 24, 0);
        (clone $base)->get(['created_at'])->each(function ($c) use (&$hourData) {
            $hourData[$c->created_at->hour]++;
        });

        // ── 8. Trend (daily if <=31 days, else monthly) ──
        $trendLabels  = [];
        $trendDataset = [];
        $diffDays = (int) $dateFrom->diffInDays($dateTo);
        $typeColors = [
            'receptionist' => '#0d6efd',
            'hk'           => '#198754',
            'laundry'      => '#ffc107',
        ];
        if ($diffDays <= 31) {
            $cur = $dateFrom->copy()->startOfDay();
            while ($cur <= $dateTo) {
                $trendLabels[] = $cur->format('d M');
                $cur->addDay();
            }
            foreach ($userTypes as $t) {
                $data = [];
                $cur  = $dateFrom->copy()->startOfDay();
                while ($cur <= $dateTo) {
                    $data[] = Complaint::where('type', $t)
                        ->whereDate('created_at', $cur->toDateString())->count();
                    $cur->addDay();
                }
                $trendDataset[] = ['label' => (new Complaint)->typeLabel() ?: $t, 'type_key' => $t, 'data' => $data, 'color' => $typeColors[$t]];
            }
        } else {
            $cur = $dateFrom->copy()->startOfMonth();
            while ($cur <= $dateTo) {
                $trendLabels[] = $cur->format('M Y');
                $cur->addMonth();
            }
            foreach ($userTypes as $t) {
                $data = [];
                $cur  = $dateFrom->copy()->startOfMonth();
                while ($cur <= $dateTo) {
                    $data[] = Complaint::where('type', $t)
                        ->whereYear('created_at', $cur->year)
                        ->whereMonth('created_at', $cur->month)->count();
                    $cur->addMonth();
                }
                $trendDataset[] = ['label' => $t, 'type_key' => $t, 'data' => $data, 'color' => $typeColors[$t]];
            }
        }

        // ── 9. Feedback / rating stats ──
        $ratedQuery   = (clone $base)->where('status', 'closed')->whereNotNull('rating');
        $ratedCount   = (clone $ratedQuery)->count();
        $autoRated    = (clone $ratedQuery)->where('feedback_auto', true)->count();
        $avgRating    = $ratedCount > 0
            ? round((clone $ratedQuery)->avg('rating'), 2)
            : null;
        $ratingDist   = [];
        for ($s = 1; $s <= 5; $s++) {
            $ratingDist[$s] = (clone $ratedQuery)->where('rating', $s)->count();
        }
        $participationRate = $totalClosed > 0 ? round($ratedCount / $totalClosed * 100, 1) : 0;
        $recentFeedback = (clone $base)
            ->where('status', 'closed')
            ->whereNotNull('rating')
            ->where('feedback_auto', false)
            ->whereNotNull('feedback_text')
            ->orderByDesc('feedback_at')
            ->limit(5)
            ->get(['ticket_number', 'type', 'reporter_name', 'rating', 'feedback_text', 'feedback_at']);

        // ── 10. Building detail table ──
        $buildingDetail = (clone $base)
            ->whereNotNull('building')
            ->where('building', '!=', '')
            ->selectRaw('building, type, COUNT(*) as cnt,
                SUM(CASE WHEN status="open" THEN 1 ELSE 0 END) as open_cnt,
                SUM(CASE WHEN status="progress" THEN 1 ELSE 0 END) as prog_cnt,
                SUM(CASE WHEN status="closed" THEN 1 ELSE 0 END) as closed_cnt')
            ->groupBy('building', 'type')
            ->orderByDesc('cnt')
            ->get()
            ->groupBy('building');

        return view('analytics.index', compact(
            'byType', 'byStatus', 'topBuildings', 'totalClosed',
            'onTime', 'lateClose', 'slaRate', 'overdue',
            'dowData', 'dowLabels', 'hourData',
            'trendLabels', 'trendDataset',
            'buildingDetail', 'dateFrom', 'dateTo', 'userTypes',
            'avgRating', 'ratingDist', 'ratedCount', 'autoRated',
            'participationRate', 'recentFeedback'
        ));
    }
}
