<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReporterController extends Controller
{
    public function index(Request $request)
    {
        $user      = Auth::user();
        $userTypes = $user->isSuperAdmin() ? ['receptionist','hk','laundry'] : [$user->role];

        // Date range
        $preset   = $request->get('preset', 'month');
        $dateFrom = match($preset) {
            'today'  => Carbon::today()->startOfDay(),
            'week'   => Carbon::now()->startOfWeek(),
            'month'  => Carbon::now()->startOfMonth(),
            'custom' => ($request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : Carbon::now()->startOfMonth()),
            default  => Carbon::now()->startOfMonth(),
        };
        $dateTo = ($preset === 'custom' && $request->filled('date_to'))
            ? Carbon::parse($request->date_to)->endOfDay()
            : Carbon::now()->endOfDay();

        // Aggregate reporters by reporter_wa (phone)
        $rawData = Complaint::whereIn('type', $userTypes)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get(['reporter_name', 'reporter_wa', 'type', 'status', 'building', 'created_at', 'ticket_number']);

        // Group by phone number
        $grouped = $rawData->groupBy(function ($c) {
            return $c->reporter_wa ?: ('__notel__' . $c->reporter_name);
        });

        $reporters = $grouped->map(function ($items, $key) {
            $latest = $items->sortByDesc('created_at')->first();
            return [
                'phone'        => $latest->reporter_wa ?? '—',
                'name'         => $latest->reporter_name,
                'total'        => $items->count(),
                'open'         => $items->where('status', 'open')->count(),
                'progress'     => $items->where('status', 'progress')->count(),
                'closed'       => $items->where('status', 'closed')->count(),
                'receptionist' => $items->where('type', 'receptionist')->count(),
                'hk'           => $items->where('type', 'hk')->count(),
                'laundry'      => $items->where('type', 'laundry')->count(),
                'buildings'    => $items->pluck('building')->filter()->unique()->take(3)->join(', '),
                'last_report'  => $latest->created_at,
                'last_ticket'  => $latest->ticket_number,
            ];
        })->sortByDesc('total')->values();

        // Summary stats
        $totalComplaints  = $rawData->count();
        $uniqueReporters  = $reporters->count();
        $topReporter      = $reporters->first();

        // For detail: if phone provided, show all complaints from that reporter
        $detailPhone     = $request->get('phone');
        $detailComplaints = null;
        if ($detailPhone) {
            $detailComplaints = Complaint::whereIn('type', $userTypes)
                ->where('reporter_wa', $detailPhone)
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->orderByDesc('created_at')
                ->get();
        }

        return view('reporters.index', compact(
            'reporters', 'totalComplaints', 'uniqueReporters', 'topReporter',
            'detailPhone', 'detailComplaints',
            'dateFrom', 'dateTo', 'preset', 'userTypes'
        ));
    }
}
