<?php

namespace App\Http\Controllers;

use App\Models\LaundryArea;
use App\Models\LaundryMess;
use App\Models\LaundryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LaundryTransactionController extends Controller
{
    public function index(Request $request)
    {
        // By default, filter by month/year or just show a default view
        $month = $request->get('month', date('m'));
        $year  = $request->get('year', date('Y'));
        
        $areas = LaundryArea::with('messes')->get();
        $messes = LaundryMess::all();
        
        // Let's get data for the selected month to build the charts
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate   = $startDate->copy()->endOfMonth();
        
        $transactions = LaundryTransaction::with('mess.area')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'desc')
            ->get();

        // Data for Chart: Group by date
        $chartDates = [];
        $chartData  = []; // [mess_id => [data]]

        $current = $startDate->copy();
        while ($current <= $endDate) {
            if ($current > now()) break; // Only show up to today if current month
            $dateStr = $current->format('Y-m-d');
            $chartDates[] = $dateStr;
            $current->addDay();
        }

        foreach ($messes as $mess) {
            $chartData[$mess->id] = [
                'name' => $mess->name,
                'data' => array_fill(0, count($chartDates), 0)
            ];
        }

        // Fill chart data (People / Orang Laundry per day based on POB or Bag Masuk)
        // Let's use Bag Masuk as the "aktual orang laundry" indicator, and POB as target.
        // The prompt asked: "chart yang membaca kenaikan dan penurunan orang laundry per mess"
        // We'll plot Bag In (Actual people washing)
        foreach ($transactions as $t) {
            $dateStr = $t->tanggal->format('Y-m-d');
            $idx = array_search($dateStr, $chartDates);
            if ($idx !== false && isset($chartData[$t->mess_id])) {
                $chartData[$t->mess_id]['data'][$idx] = $t->bag_in;
            }
        }

        // Summary for current month
        $totalPob   = $transactions->sum('pob');
        $totalBagIn = $transactions->sum('bag_in');
        $totalKgIn  = $transactions->sum('kg_in');
        $totalKgOut = $transactions->sum('kg_out');
        $targetKg   = $totalPob * 2.5;

        return view('laundry.transactions.index', compact(
            'areas', 'messes', 'transactions', 'month', 'year',
            'chartDates', 'chartData',
            'totalPob', 'totalBagIn', 'totalKgIn', 'totalKgOut', 'targetKg'
        ));
    }

    public function storeMess(Request $request)
    {
        $data = $request->validate([
            'area_id'     => 'required|exists:laundry_areas,id',
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);
        LaundryMess::create($data);
        return redirect()->back()->with('success', 'Mess berhasil ditambahkan.');
    }

    public function storeTransaction(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'entries' => 'required|array',
            'entries.*.mess_id' => 'required|exists:laundry_messes,id',
            'entries.*.pob'     => 'required|integer|min:0',
            'entries.*.bag_in'  => 'required|integer|min:0',
            'entries.*.kg_in'   => 'required|numeric|min:0',
            'entries.*.bag_out' => 'required|integer|min:0',
            'entries.*.kg_out'  => 'required|numeric|min:0',
        ]);

        $tanggal = $request->tanggal;

        foreach ($request->entries as $entry) {
            // Update or create based on date and mess_id
            LaundryTransaction::updateOrCreate(
                ['tanggal' => $tanggal, 'mess_id' => $entry['mess_id']],
                [
                    'pob'     => $entry['pob'],
                    'bag_in'  => $entry['bag_in'],
                    'kg_in'   => $entry['kg_in'],
                    'bag_out' => $entry['bag_out'],
                    'kg_out'  => $entry['kg_out'],
                ]
            );
        }

        return redirect()->back()->with('success', 'Data pergerakan laundry berhasil disimpan.');
    }

    public function updateTransaction(Request $request, LaundryTransaction $transaction)
    {
        $data = $request->validate([
            'pob'     => 'required|integer|min:0',
            'bag_in'  => 'required|integer|min:0',
            'kg_in'   => 'required|numeric|min:0',
            'bag_out' => 'required|integer|min:0',
            'kg_out'  => 'required|numeric|min:0',
        ]);
        $transaction->update($data);
        return redirect()->back()->with('success', 'Data transaksi berhasil diperbarui.');
    }
}
