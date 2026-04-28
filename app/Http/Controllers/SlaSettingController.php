<?php

namespace App\Http\Controllers;

use App\Models\SlaSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SlaSettingController extends Controller
{
    public function index(): View
    {
        $gaTypes      = ['receptionist', 'hk', 'laundry'];
        $gaPriorities = ['rendah', 'sedang', 'tinggi', 'urgent'];
        $hrPriorities = ['normal', 'penting', 'mendesak'];

        // Keyed: "module|type|priority" => SlaSetting model
        $all = SlaSetting::all()->keyBy(fn ($r) => "{$r->module}|{$r->type}|{$r->priority}");

        return view('sla.index', compact('gaTypes', 'gaPriorities', 'hrPriorities', 'all'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sla'   => 'required|array',
            'sla.*' => 'required|integer|min:1|max:8760', // maks 1 tahun
        ]);

        foreach ($validated['sla'] as $id => $hours) {
            SlaSetting::where('id', (int) $id)->update(['hours' => (int) $hours]);
        }

        SlaSetting::clearCache();

        return redirect()->route('sla.index')
            ->with('success', 'Pengaturan SLA berhasil disimpan. Berlaku untuk tiket baru.');
    }
}
