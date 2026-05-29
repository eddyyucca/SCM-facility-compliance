<?php

namespace App\Http\Controllers;

use App\Models\LaundryArea;
use App\Models\LaundryEquipment;
use Illuminate\Http\Request;

class LaundryEquipmentController extends Controller
{
    /* ═══════════════════════════════════
     *  DASHBOARD
     * ═══════════════════════════════════ */

    public function dashboard(Request $request)
    {
        $areas = LaundryArea::with('equipment')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $activeAreaId = $request->get('area', $areas->first()?->id);
        $activeArea   = $areas->firstWhere('id', $activeAreaId) ?? $areas->first();

        // Jam kerja default 20 jam
        $workingHours = (int) $request->get('wh', 20);
        $workingHours = max(1, min(24, $workingHours));

        return view('laundry.dashboard', compact('areas', 'activeArea', 'workingHours'));
    }

    /* ═══════════════════════════════════
     *  AREA CRUD
     * ═══════════════════════════════════ */

    public function storeArea(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);
        $area = LaundryArea::create(['name' => $data['name'], 'description' => $data['description'] ?? null, 'is_active' => true]);
        return redirect()->route('laundry.dashboard', ['area' => $area->id])->with('success', "Area \"{$area->name}\" ditambahkan.");
    }

    public function updateArea(Request $request, LaundryArea $area)
    {
        $data = $request->validate(['name' => 'required|string|max:100', 'description' => 'nullable|string|max:255']);
        $area->update($data);
        return redirect()->route('laundry.dashboard', ['area' => $area->id])->with('success', 'Area diperbarui.');
    }

    public function destroyArea(LaundryArea $area)
    {
        $area->delete();
        return redirect()->route('laundry.dashboard')->with('success', "Area \"{$area->name}\" dihapus.");
    }

    /* ═══════════════════════════════════
     *  EQUIPMENT CRUD
     * ═══════════════════════════════════ */

    public function storeEquipment(Request $request)
    {
        $data = $request->validate([
            'area_id'              => 'required|exists:laundry_areas,id',
            'category'             => 'required|in:washer,dryer',
            'model_name'           => 'required|string|max:100',
            'capacity_kg'          => 'required|numeric|min:0.1|max:9999',
            'process_time_minutes' => 'required|integer|min:1|max:9999',
            'unit_qty'             => 'required|integer|min:1|max:50',
            'status'               => 'required|in:ready,breakdown',
            'remarks'              => 'nullable|string|max:500',
        ]);

        $qty = (int) $data['unit_qty'];
        unset($data['unit_qty']);

        // Cari nomor unit berikutnya
        $nextNumber = LaundryEquipment::nextUnitNumber($data['area_id'], $data['category'], $data['model_name']);

        for ($i = 0; $i < $qty; $i++) {
            LaundryEquipment::create([
                ...$data,
                'unit_number' => $nextNumber + $i,
                'sort_order'  => $nextNumber + $i,
            ]);
        }

        $label = $qty > 1 ? "{$qty} unit {$data['model_name']}" : "{$data['model_name']} #{$nextNumber}";
        return redirect()->route('laundry.dashboard', ['area' => $data['area_id']])->with('success', "{$label} berhasil ditambahkan.");
    }

    public function updateEquipment(Request $request, LaundryEquipment $equipment)
    {
        $data = $request->validate([
            'model_name'           => 'required|string|max:100',
            'capacity_kg'          => 'required|numeric|min:0.1|max:9999',
            'process_time_minutes' => 'required|integer|min:1|max:9999',
            'status'               => 'required|in:ready,breakdown',
            'remarks'              => 'nullable|string|max:500',
        ]);

        $equipment->update($data);
        return redirect()->route('laundry.dashboard', ['area' => $equipment->area_id])->with('success', "{$equipment->display_name} diperbarui.");
    }

    public function destroyEquipment(LaundryEquipment $equipment)
    {
        $areaId = $equipment->area_id;
        $name   = $equipment->display_name;
        $equipment->delete();
        return redirect()->route('laundry.dashboard', ['area' => $areaId])->with('success', "{$name} dihapus.");
    }

    /* ═══════════════════════════════════
     *  API Stats
     * ═══════════════════════════════════ */

    public function apiStats(Request $request)
    {
        $wh   = max(1, min(24, (int) $request->get('wh', 20)));
        $wMin = $wh * 60;
        $areas = LaundryArea::with('equipment')->where('is_active', true)->get();

        return response()->json([
            'working_hours' => $wh,
            'areas' => $areas->map(fn($a) => [
                'id'                => $a->id,
                'name'              => $a->name,
                'washer_ready'      => $a->washerReady(),
                'washer_breakdown'  => $a->washerBreakdown(),
                'washer_total'      => $a->washerCount(),
                'washer_pa'         => $a->washerPa(),
                'washer_output_kg'  => $a->washerOutputKg($wMin),
                'dryer_ready'       => $a->dryerReady(),
                'dryer_breakdown'   => $a->dryerBreakdown(),
                'dryer_total'       => $a->dryerCount(),
                'dryer_pa'          => $a->dryerPa(),
                'dryer_output_kg'   => $a->dryerOutputKg($wMin),
                'overall_pa'        => $a->overallPa(),
            ]),
        ]);
    }
}
