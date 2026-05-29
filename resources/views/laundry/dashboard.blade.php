@extends('layouts.app')
@section('title', 'Equipment Laundry')
@section('page_title', 'Equipment Laundry')
@section('breadcrumb', 'Dashboard')

@push('styles')
<style>
/* ─── Area Tabs ─── */
.area-tabs { display:flex; gap:6px; flex-wrap:wrap; margin-bottom:18px; }
.area-tab-btn {
    padding:8px 18px; border-radius:10px; font-size:.83rem; font-weight:600;
    border:2px solid #dee2e6; background:#fff; color:#6c757d;
    cursor:pointer; text-decoration:none; transition:all .15s;
    display:inline-flex; align-items:center; gap:6px;
}
.area-tab-btn:hover  { border-color:#0d6efd; color:#0d6efd; background:#f0f5ff; text-decoration:none; }
.area-tab-btn.active { border-color:#0a2e6e; background:#0a2e6e; color:#fff; }
.pa-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
.btn-add-area {
    padding:8px 14px; border-radius:10px; font-size:.8rem; font-weight:600;
    border:2px dashed #adb5bd; background:transparent; color:#6c757d;
    cursor:pointer; transition:all .15s; display:inline-flex; align-items:center; gap:5px;
}
.btn-add-area:hover { border-color:#0d6efd; color:#0d6efd; }

/* ─── Working Hours Bar ─── */
.wh-bar {
    display:flex; align-items:center; gap:12px; flex-wrap:wrap;
    padding:12px 18px; background:#fff; border-radius:12px;
    box-shadow:0 2px 10px rgba(0,0,0,.07); margin-bottom:18px;
}
.wh-bar label { font-size:.84rem; font-weight:700; color:#0a2e6e; margin:0; white-space:nowrap; }
.wh-input {
    width:70px; padding:6px 10px; border-radius:8px;
    border:2px solid #0a2e6e; font-size:.9rem; font-weight:700;
    color:#0a2e6e; text-align:center;
}
.wh-output-pill {
    display:inline-flex; align-items:center; gap:6px;
    padding:6px 14px; border-radius:999px; font-size:.82rem; font-weight:700;
}
.pill-washer { background:#dbeafe; color:#1e3a8a; }
.pill-dryer  { background:#ccfbf1; color:#134e4a; }
.pill-total  { background:#f0f9ff; color:#0c4a6e; border:1.5px solid #bae6fd; }

/* ─── Summary Chips ─── */
.area-chips { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:16px; align-items:center; }
.area-chip {
    display:flex; align-items:center; gap:8px;
    padding:8px 16px; border-radius:12px; font-size:.82rem; font-weight:600;
}
.chip-washer { background:#dbeafe; color:#1e40af; }
.chip-dryer  { background:#ccfbf1; color:#0f766e; }
.chip-num { font-size:1.3rem; font-weight:800; line-height:1; }

/* ─── Section Header ─── */
.ldy-section-hdr {
    display:flex; align-items:center; justify-content:space-between;
    padding:11px 16px; background:#0a2e6e; color:#fff;
}
.ldy-section-hdr .title { font-size:.84rem; font-weight:800; letter-spacing:.05em; display:flex; align-items:center; gap:8px; }
.ldy-section-hdr .summary-right { display:flex; gap:16px; font-size:.78rem; align-items:center; }
.ldy-section-hdr .sum-item { display:flex; flex-direction:column; align-items:center; line-height:1.2; }
.ldy-section-hdr .sum-val  { font-size:1rem; font-weight:800; }
.ldy-section-hdr .sum-lbl  { font-size:.62rem; opacity:.75; }

/* ─── Equipment Table ─── */
.ldy-table { width:100%; border-collapse:collapse; font-size:.84rem; }
.ldy-table thead th {
    background:#0a2e6e; color:#fff; padding:8px 12px;
    font-size:.74rem; font-weight:700; white-space:nowrap;
    border-right:1px solid rgba(255,255,255,.12);
    text-align:center;
}
.ldy-table thead th:first-child { text-align:left; border-radius:0; }
.ldy-table thead th:last-child  { border-right:none; }
.ldy-table thead tr:first-child th.group-hdr {
    background:#132f6b; border-bottom:1px solid rgba(255,255,255,.15);
}
.ldy-table tbody td {
    padding:9px 12px; border-bottom:1px solid #eef0f4;
    vertical-align:middle; text-align:center;
}
.ldy-table tbody td:first-child { text-align:left; }
.ldy-table tbody tr:hover { background:#f7f9ff; }
.ldy-table tbody tr.row-breakdown { background:#fff8f8; }
.ldy-table tbody tr.row-breakdown:hover { background:#fff3f3; }
.ldy-table tfoot td {
    padding:10px 12px; font-weight:800; font-size:.85rem;
    background:#fffde7; border-top:2px solid #fbbf24; text-align:center;
    color:#78350f;
}
.ldy-table tfoot td:first-child { text-align:left; color:#92400e; }

/* ─── Status Badge ─── */
.status-ready     { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:999px; background:#d1fae5; color:#065f46; font-size:.74rem; font-weight:700; }
.status-breakdown { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:999px; background:#fee2e2; color:#991b1b; font-size:.74rem; font-weight:700; }
.status-ready i     { font-size:.5rem; }
.status-breakdown i { font-size:.6rem; }

/* ─── Remarks ─── */
.remark-text { display:inline-block; padding:3px 8px; border-radius:6px; background:#fffbeb; color:#92400e; font-size:.74rem; font-weight:600; border:1px solid #fde68a; text-align:left; }

/* ─── Output badge ─── */
.output-badge { display:inline-block; padding:3px 10px; border-radius:8px; background:#e0f2fe; color:#0369a1; font-size:.8rem; font-weight:700; }
.output-zero  { color:#d1d5db; font-size:.78rem; }

/* ─── Action buttons ─── */
.tbl-actions { display:flex; gap:4px; justify-content:center; }
.tbl-btn {
    width:26px; height:26px; border-radius:6px; border:1px solid;
    display:flex; align-items:center; justify-content:center;
    font-size:.68rem; cursor:pointer; transition:all .12s; background:transparent;
}
.tbl-btn-edit   { border-color:#0d6efd; color:#0d6efd; }
.tbl-btn-edit:hover   { background:#0d6efd; color:#fff; }
.tbl-btn-delete { border-color:#dc3545; color:#dc3545; }
.tbl-btn-delete:hover { background:#dc3545; color:#fff; }

/* ─── Add row button ─── */
.btn-add-row {
    padding:6px 14px; border-radius:8px; font-size:.78rem; font-weight:600;
    border:1.5px solid; cursor:pointer;
    display:inline-flex; align-items:center; gap:5px; background:transparent; transition:all .12s;
}
.btn-add-row.washer { border-color:#1d4ed8; color:#1d4ed8; }
.btn-add-row.washer:hover { background:#1d4ed8; color:#fff; }
.btn-add-row.dryer  { border-color:#0d9488; color:#0d9488; }
.btn-add-row.dryer:hover  { background:#0d9488; color:#fff; }

/* ─── Card ─── */
.ldy-card { border-radius:14px!important; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,.09)!important; border:none!important; margin-bottom:20px; }

/* ─── Unit number badge ─── */
.unit-num {
    display:inline-flex; align-items:center; justify-content:center;
    width:22px; height:22px; border-radius:6px; background:#e2e8f0;
    color:#475569; font-size:.7rem; font-weight:800; margin-right:2px; flex-shrink:0;
}
.model-name { font-weight:600; font-size:.84rem; }

/* ─── PA bar ─── */
.pa-mini-bar { height:5px; background:#e9ecef; border-radius:999px; overflow:hidden; min-width:50px; margin-top:3px; }
.pa-mini-fill { height:100%; border-radius:999px; }
</style>
@endpush

@section('content')

{{-- ══ Area Tabs ══ --}}
<div class="area-tabs">
    @forelse($areas as $area)
        @php
            $isActive = $activeArea && $activeArea->id === $area->id;
            $pa       = $area->overallPa();
            $bdTotal  = $area->washerBreakdown() + $area->dryerBreakdown();
            $dotColor = $pa >= 90 ? '#22c55e' : ($pa >= 75 ? '#eab308' : '#ef4444');
        @endphp
        <a href="{{ route('laundry.dashboard', ['area' => $area->id, 'wh' => $workingHours]) }}"
           class="area-tab-btn {{ $isActive ? 'active' : '' }}">
            <span class="pa-dot" style="background:{{ $isActive ? 'rgba(255,255,255,.6)' : $dotColor }};"></span>
            {{ $area->name }}
            @if($bdTotal > 0)
                <span style="background:{{ $isActive ? 'rgba(255,255,255,.2)' : '#fee2e2' }};color:{{ $isActive ? '#fff' : '#b91c1c' }};border-radius:999px;padding:1px 7px;font-size:.68rem;">
                    {{ $bdTotal }} BD
                </span>
            @endif
        </a>
    @empty
    @endforelse
    <button class="btn-add-area" data-toggle="modal" data-target="#addAreaModal">
        <i class="fas fa-plus"></i> Tambah Area
    </button>
</div>

@if($activeArea)

    {{-- ══ Working Hours Bar ══ --}}
    @php
        $wMin      = $workingHours * 60;
        $washerOut = $activeArea->equipment->where('category','washer')->sum(fn($e) => $e->outputKg($wMin));
        $dryerOut  = $activeArea->equipment->where('category','dryer')->sum(fn($e) => $e->outputKg($wMin));
    @endphp
    <form method="GET" action="{{ route('laundry.dashboard') }}" class="wh-bar">
        <input type="hidden" name="area" value="{{ $activeArea->id }}">
        <i class="fas fa-clock text-primary"></i>
        <label for="wh-input">Jam Kerja / Hari:</label>
        <input type="number" name="wh" id="wh-input" class="wh-input"
            value="{{ $workingHours }}" min="1" max="24">
        <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px;">
            <i class="fas fa-calculator mr-1"></i> Hitung
        </button>

        <div style="height:28px;border-left:1.5px solid #e2e8f0;margin:0 4px;"></div>

        <span style="font-size:.8rem;color:#6c757d;">Output Area ({{ $workingHours }} jam @ 85% PA):</span>
        <span class="wh-output-pill pill-washer">
            <i class="fas fa-soap"></i>
            Washer: <strong>{{ number_format($washerOut, 0) }} kg</strong>
        </span>
        <span class="wh-output-pill pill-dryer">
            <i class="fas fa-wind"></i>
            Dryer: <strong>{{ number_format($dryerOut, 0) }} kg</strong>
        </span>
        <span class="wh-output-pill pill-total">
            <i class="fas fa-layer-group"></i>
            Total: <strong>{{ number_format($washerOut + $dryerOut, 0) }} kg</strong>
        </span>
    </form>

    {{-- ══ Summary Chips ══ --}}
    <div class="area-chips">
        {{-- Washer chip --}}
        <div class="area-chip chip-washer">
            <i class="fas fa-soap" style="font-size:1.1rem;"></i>
            <div>
                <div class="chip-num">{{ $activeArea->washerCount() }}</div>
                <div style="font-size:.68rem;opacity:.8;">Unit Washer</div>
            </div>
            <div style="border-left:1.5px solid rgba(30,64,175,.2);margin:0 6px;height:30px;"></div>
            <div>
                <div style="font-size:.78rem;"><span style="color:#15803d;font-weight:700;">{{ $activeArea->washerReady() }}</span> Ready</div>
                <div style="font-size:.78rem;"><span style="color:#b91c1c;font-weight:700;">{{ $activeArea->washerBreakdown() }}</span> Breakdown</div>
            </div>
            <div style="margin-left:6px;">
                <div style="font-size:.68rem;opacity:.7;">PA</div>
                <div style="font-size:.9rem;font-weight:800;">{{ $activeArea->washerPa() }}%</div>
                <div class="pa-mini-bar" style="width:60px;">
                    <div class="pa-mini-fill" style="width:{{ $activeArea->washerPa() }}%;background:{{ $activeArea->washerPa() >= 90 ? '#22c55e' : ($activeArea->washerPa() >= 75 ? '#eab308' : '#ef4444') }};"></div>
                </div>
            </div>
        </div>

        {{-- Dryer chip --}}
        <div class="area-chip chip-dryer">
            <i class="fas fa-wind" style="font-size:1.1rem;"></i>
            <div>
                <div class="chip-num">{{ $activeArea->dryerCount() }}</div>
                <div style="font-size:.68rem;opacity:.8;">Unit Dryer</div>
            </div>
            <div style="border-left:1.5px solid rgba(15,118,110,.2);margin:0 6px;height:30px;"></div>
            <div>
                <div style="font-size:.78rem;"><span style="color:#15803d;font-weight:700;">{{ $activeArea->dryerReady() }}</span> Ready</div>
                <div style="font-size:.78rem;"><span style="color:#b91c1c;font-weight:700;">{{ $activeArea->dryerBreakdown() }}</span> Breakdown</div>
            </div>
            <div style="margin-left:6px;">
                <div style="font-size:.68rem;opacity:.7;">PA</div>
                <div style="font-size:.9rem;font-weight:800;">{{ $activeArea->dryerPa() }}%</div>
                <div class="pa-mini-bar" style="width:60px;">
                    <div class="pa-mini-fill" style="width:{{ $activeArea->dryerPa() }}%;background:{{ $activeArea->dryerPa() >= 90 ? '#22c55e' : ($activeArea->dryerPa() >= 75 ? '#eab308' : '#ef4444') }};"></div>
                </div>
            </div>
        </div>

        {{-- Overall PA --}}
        <div class="area-chip" style="background:#f8fafc;border:1.5px solid #e2e8f0;margin-left:auto;">
            <div>
                <div style="font-size:.68rem;color:#6c757d;">Overall PA</div>
                @php $opa = $activeArea->overallPa(); @endphp
                <div class="chip-num" style="color:{{ $opa >= 90 ? '#15803d' : ($opa >= 75 ? '#b45309' : '#b91c1c') }}">{{ $opa }}%</div>
            </div>
        </div>

        {{-- Edit/Delete area --}}
        <button class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-size:.78rem;"
            data-toggle="modal" data-target="#editAreaModal"
            data-id="{{ $activeArea->id }}" data-name="{{ $activeArea->name }}"
            data-description="{{ $activeArea->description }}">
            <i class="fas fa-edit mr-1"></i> Edit Area
        </button>
        <form method="POST" action="{{ route('laundry.areas.destroy', $activeArea->id) }}"
            onsubmit="return confirm('Hapus area {{ $activeArea->name }} beserta semua datanya?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-size:.78rem;">
                <i class="fas fa-trash mr-1"></i> Hapus Area
            </button>
        </form>
    </div>

    {{-- ═══════════════════════ WASHER TABLE ═══════════════════════ --}}
    @php
        $washers     = $activeArea->equipment->where('category','washer');
        $wTotalReady = $washers->where('status','ready')->count();
        $wTotalBD    = $washers->where('status','breakdown')->count();
    @endphp
    <div class="ldy-card card">
        <div class="ldy-section-hdr">
            <div class="title"><i class="fas fa-soap"></i> WASHER</div>
            <div class="summary-right">
                <div class="sum-item">
                    <span class="sum-val">{{ $washers->count() }}</span>
                    <span class="sum-lbl">Total Unit</span>
                </div>
                <div class="sum-item">
                    <span class="sum-val" style="color:#86efac;">{{ $wTotalReady }}</span>
                    <span class="sum-lbl">Ready</span>
                </div>
                <div class="sum-item">
                    <span class="sum-val" style="color:#fca5a5;">{{ $wTotalBD }}</span>
                    <span class="sum-lbl">Breakdown</span>
                </div>
                <div style="border-left:1px solid rgba(255,255,255,.2);height:30px;margin:0 4px;"></div>
                <div class="sum-item">
                    <span class="sum-val" style="color:#93c5fd;">{{ number_format($washerOut, 0) }} kg</span>
                    <span class="sum-lbl">Output/{{ $workingHours }}h</span>
                </div>
            </div>
        </div>

        <div class="table-responsive">
        <table class="ldy-table">
            <thead>
                <tr>
                    <th rowspan="2" style="text-align:left;min-width:200px;">Model / Tipe</th>
                    <th rowspan="2" style="width:60px;">Kap. (kg)</th>
                    <th rowspan="2" style="width:70px;">Cycle (mnt)</th>
                    <th class="group-hdr" colspan="2">Per {{ $workingHours }} Jam Kerja (@ 85% PA)</th>
                    <th rowspan="2" style="width:90px;">Status</th>
                    <th rowspan="2" style="min-width:150px;">Remarks</th>
                    <th rowspan="2" style="width:60px;"></th>
                </tr>
                <tr>
                    <th style="width:70px;">Cycles</th>
                    <th style="width:90px;">Output (kg)</th>
                </tr>
            </thead>
            <tbody>
            @forelse($washers->sortBy([['model_name','asc'],['unit_number','asc']]) as $eq)
                @php
                    $cycles = $eq->cyclesInPeriod($wMin);
                    $outKg  = $eq->outputKg($wMin);
                @endphp
                <tr class="{{ $eq->isBreakdown() ? 'row-breakdown' : '' }}">
                    <td>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <span class="unit-num">#{{ $eq->unit_number }}</span>
                            <span class="model-name">{{ $eq->model_name }}</span>
                        </div>
                    </td>
                    <td style="font-weight:700;">{{ $eq->capacity_kg + 0 }}</td>
                    <td>{{ $eq->process_time_minutes }}</td>
                    <td>
                        @if($eq->isReady())
                            <span style="font-weight:700;color:#0369a1;">{{ $cycles }}x</span>
                        @else
                            <span class="output-zero">–</span>
                        @endif
                    </td>
                    <td>
                        @if($eq->isReady())
                            <span class="output-badge">{{ number_format($outKg, 1) }} kg</span>
                        @else
                            <span class="output-zero">0 kg</span>
                        @endif
                    </td>
                    <td>
                        @if($eq->isReady())
                            <span class="status-ready"><i class="fas fa-circle"></i> Ready</span>
                        @else
                            <span class="status-breakdown"><i class="fas fa-tools"></i> BD</span>
                        @endif
                    </td>
                    <td style="text-align:left;">
                        @if($eq->remarks)
                            <span class="remark-text">{{ $eq->remarks }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="tbl-actions">
                            <button class="tbl-btn tbl-btn-edit btn-edit-eq"
                                data-id="{{ $eq->id }}"
                                data-model="{{ $eq->model_name }}"
                                data-capacity="{{ $eq->capacity_kg }}"
                                data-cycle="{{ $eq->process_time_minutes }}"
                                data-status="{{ $eq->status }}"
                                data-remarks="{{ $eq->remarks }}"
                                title="Edit"><i class="fas fa-edit"></i></button>
                            <form method="POST" action="{{ route('laundry.equipment.destroy', $eq->id) }}" style="margin:0;" onsubmit="return confirm('Hapus {{ $eq->display_name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="tbl-btn tbl-btn-delete" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;color:#ccc;padding:20px;">Belum ada data washer</td></tr>
            @endforelse
            </tbody>
            @if($washers->count() > 0)
            <tfoot>
                <tr>
                    <td><i class="fas fa-sigma mr-1"></i> TOTAL WASHER</td>
                    <td>–</td>
                    <td>–</td>
                    <td>–</td>
                    <td style="color:#0369a1;">{{ number_format($washerOut, 1) }} kg</td>
                    <td colspan="3">
                        <span style="color:#15803d;font-weight:600;font-size:.8rem;">{{ $wTotalReady }} Ready</span>
                        &nbsp;/&nbsp;
                        <span style="color:#b91c1c;font-weight:600;font-size:.8rem;">{{ $wTotalBD }} BD</span>
                        &nbsp;·&nbsp;
                        <span style="font-size:.8rem;">PA {{ $activeArea->washerPa() }}%</span>
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
        </div>
        <div style="padding:10px 14px;background:#f8f9fc;border-top:1px solid #eef0f4;">
            <button class="btn-add-row washer" data-toggle="modal" data-target="#addEqModal"
                data-category="washer" data-area="{{ $activeArea->id }}">
                <i class="fas fa-plus"></i> Tambah Unit Washer
            </button>
        </div>
    </div>

    {{-- ═══════════════════════ DRYER TABLE ═══════════════════════ --}}
    @php
        $dryers      = $activeArea->equipment->where('category','dryer');
        $dTotalReady = $dryers->where('status','ready')->count();
        $dTotalBD    = $dryers->where('status','breakdown')->count();
    @endphp
    <div class="ldy-card card">
        <div class="ldy-section-hdr" style="background:#0d5c54;">
            <div class="title"><i class="fas fa-wind"></i> DRYER</div>
            <div class="summary-right">
                <div class="sum-item">
                    <span class="sum-val">{{ $dryers->count() }}</span>
                    <span class="sum-lbl">Total Unit</span>
                </div>
                <div class="sum-item">
                    <span class="sum-val" style="color:#86efac;">{{ $dTotalReady }}</span>
                    <span class="sum-lbl">Ready</span>
                </div>
                <div class="sum-item">
                    <span class="sum-val" style="color:#fca5a5;">{{ $dTotalBD }}</span>
                    <span class="sum-lbl">Breakdown</span>
                </div>
                <div style="border-left:1px solid rgba(255,255,255,.2);height:30px;margin:0 4px;"></div>
                <div class="sum-item">
                    <span class="sum-val" style="color:#6ee7b7;">{{ number_format($dryerOut, 0) }} kg</span>
                    <span class="sum-lbl">Output/{{ $workingHours }}h</span>
                </div>
            </div>
        </div>

        <div class="table-responsive">
        <table class="ldy-table">
            <thead>
                <tr>
                    <th rowspan="2" style="text-align:left;min-width:200px;">Model / Tipe</th>
                    <th rowspan="2" style="width:60px;">Kap. (kg)</th>
                    <th rowspan="2" style="width:70px;">Cycle (mnt)</th>
                    <th class="group-hdr" colspan="2">Per {{ $workingHours }} Jam Kerja (@ 85% PA)</th>
                    <th rowspan="2" style="width:90px;">Status</th>
                    <th rowspan="2" style="min-width:150px;">Remarks</th>
                    <th rowspan="2" style="width:60px;"></th>
                </tr>
                <tr>
                    <th style="width:70px;">Cycles</th>
                    <th style="width:90px;">Output (kg)</th>
                </tr>
            </thead>
            <tbody>
            @forelse($dryers->sortBy([['model_name','asc'],['unit_number','asc']]) as $eq)
                @php
                    $cycles = $eq->cyclesInPeriod($wMin);
                    $outKg  = $eq->outputKg($wMin);
                @endphp
                <tr class="{{ $eq->isBreakdown() ? 'row-breakdown' : '' }}">
                    <td>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <span class="unit-num">#{{ $eq->unit_number }}</span>
                            <span class="model-name">{{ $eq->model_name }}</span>
                        </div>
                    </td>
                    <td style="font-weight:700;">{{ $eq->capacity_kg + 0 }}</td>
                    <td>{{ $eq->process_time_minutes }}</td>
                    <td>
                        @if($eq->isReady())
                            <span style="font-weight:700;color:#0f766e;">{{ $cycles }}x</span>
                        @else
                            <span class="output-zero">–</span>
                        @endif
                    </td>
                    <td>
                        @if($eq->isReady())
                            <span class="output-badge" style="background:#ccfbf1;color:#0f766e;">{{ number_format($outKg, 1) }} kg</span>
                        @else
                            <span class="output-zero">0 kg</span>
                        @endif
                    </td>
                    <td>
                        @if($eq->isReady())
                            <span class="status-ready"><i class="fas fa-circle"></i> Ready</span>
                        @else
                            <span class="status-breakdown"><i class="fas fa-tools"></i> BD</span>
                        @endif
                    </td>
                    <td style="text-align:left;">
                        @if($eq->remarks)
                            <span class="remark-text">{{ $eq->remarks }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="tbl-actions">
                            <button class="tbl-btn tbl-btn-edit btn-edit-eq"
                                data-id="{{ $eq->id }}"
                                data-model="{{ $eq->model_name }}"
                                data-capacity="{{ $eq->capacity_kg }}"
                                data-cycle="{{ $eq->process_time_minutes }}"
                                data-status="{{ $eq->status }}"
                                data-remarks="{{ $eq->remarks }}"
                                title="Edit"><i class="fas fa-edit"></i></button>
                            <form method="POST" action="{{ route('laundry.equipment.destroy', $eq->id) }}" style="margin:0;" onsubmit="return confirm('Hapus {{ $eq->display_name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="tbl-btn tbl-btn-delete" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;color:#ccc;padding:20px;">Belum ada data dryer</td></tr>
            @endforelse
            </tbody>
            @if($dryers->count() > 0)
            <tfoot>
                <tr>
                    <td><i class="fas fa-sigma mr-1"></i> TOTAL DRYER</td>
                    <td>–</td>
                    <td>–</td>
                    <td>–</td>
                    <td style="color:#0f766e;">{{ number_format($dryerOut, 1) }} kg</td>
                    <td colspan="3">
                        <span style="color:#15803d;font-weight:600;font-size:.8rem;">{{ $dTotalReady }} Ready</span>
                        &nbsp;/&nbsp;
                        <span style="color:#b91c1c;font-weight:600;font-size:.8rem;">{{ $dTotalBD }} BD</span>
                        &nbsp;·&nbsp;
                        <span style="font-size:.8rem;">PA {{ $activeArea->dryerPa() }}%</span>
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
        </div>
        <div style="padding:10px 14px;background:#f8f9fc;border-top:1px solid #eef0f4;">
            <button class="btn-add-row dryer" data-toggle="modal" data-target="#addEqModal"
                data-category="dryer" data-area="{{ $activeArea->id }}">
                <i class="fas fa-plus"></i> Tambah Unit Dryer
            </button>
        </div>
    </div>

    {{-- ═══ GRAND TOTAL CARD ═══ --}}
    <div class="card" style="border-radius:14px!important;border:none!important;box-shadow:0 4px 20px rgba(0,0,0,.09)!important;background:linear-gradient(135deg,#0a2e6e,#1d4ed8);">
        <div class="card-body" style="padding:20px 24px;">
            <div style="font-size:.72rem;font-weight:700;letter-spacing:.08em;color:rgba(255,255,255,.6);text-transform:uppercase;margin-bottom:10px;">
                TOTAL KAPASITAS AREA {{ strtoupper($activeArea->name) }} (@ 85% PA) – {{ $workingHours }} JAM KERJA
            </div>
            <div style="display:flex;gap:32px;flex-wrap:wrap;align-items:center;">
                <div>
                    <div style="font-size:.78rem;color:rgba(255,255,255,.7);">🧼 Washer</div>
                    <div style="font-size:2rem;font-weight:800;color:#93c5fd;line-height:1.1;">
                        {{ number_format($washerOut, 0) }} <span style="font-size:1rem;font-weight:600;">kg</span>
                    </div>
                    <div style="font-size:.72rem;color:rgba(255,255,255,.6);">{{ $wTotalReady }} unit aktif</div>
                </div>
                <div style="color:rgba(255,255,255,.3);font-size:1.5rem;">+</div>
                <div>
                    <div style="font-size:.78rem;color:rgba(255,255,255,.7);">💨 Dryer</div>
                    <div style="font-size:2rem;font-weight:800;color:#6ee7b7;line-height:1.1;">
                        {{ number_format($dryerOut, 0) }} <span style="font-size:1rem;font-weight:600;">kg</span>
                    </div>
                    <div style="font-size:.72rem;color:rgba(255,255,255,.6);">{{ $dTotalReady }} unit aktif</div>
                </div>
                <div style="color:rgba(255,255,255,.3);font-size:1.5rem;">=</div>
                <div>
                    <div style="font-size:.78rem;color:rgba(255,255,255,.7);">Total Output</div>
                    <div style="font-size:2.4rem;font-weight:800;color:#fff;line-height:1.1;">
                        {{ number_format($washerOut + $dryerOut, 0) }} <span style="font-size:1.1rem;font-weight:600;">kg</span>
                    </div>
                    <div style="font-size:.72rem;color:rgba(255,255,255,.6);">per {{ $workingHours }} jam kerja</div>
                </div>
            </div>
        </div>
    </div>

@else
    <div class="card" style="border-radius:14px!important;text-align:center;padding:60px;border:none!important;">
        <i class="fas fa-tools fa-3x" style="color:#dee2e6;margin-bottom:12px;"></i>
        <p style="color:#adb5bd;font-size:.95rem;font-weight:600;">Belum ada area terdaftar.</p>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addAreaModal" style="border-radius:10px;">
            <i class="fas fa-plus mr-1"></i> Tambah Area Pertama
        </button>
    </div>
@endif


{{-- ════ MODAL: TAMBAH AREA ════ --}}
<div class="modal fade" id="addAreaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:16px;border:0;overflow:hidden;">
            <div class="modal-header" style="background:#0a2e6e;border:0;">
                <h5 class="modal-title text-white" style="font-size:.95rem;"><i class="fas fa-map-marker-alt mr-2"></i>Tambah Area</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form method="POST" action="{{ route('laundry.areas.store') }}">
                @csrf
                <div class="modal-body" style="padding:20px;">
                    <div class="form-group mb-3">
                        <label style="font-size:.84rem;font-weight:600;">Nama Area <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="cth: BR, Kitchen, Staff" required
                            style="border-radius:8px;" autofocus>
                    </div>
                    <div class="form-group mb-0">
                        <label style="font-size:.84rem;font-weight:600;">Deskripsi</label>
                        <input type="text" name="description" class="form-control" placeholder="Keterangan area..."
                            style="border-radius:8px;">
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #eef0f4;padding:12px 20px;">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="border-radius:8px;">Batal</button>
                    <button type="submit" class="btn btn-sm" style="border-radius:8px;background:#0a2e6e;border-color:#0a2e6e;color:#fff;">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════ MODAL: EDIT AREA ════ --}}
<div class="modal fade" id="editAreaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:16px;border:0;overflow:hidden;">
            <div class="modal-header" style="background:#475569;border:0;">
                <h5 class="modal-title text-white" style="font-size:.95rem;"><i class="fas fa-edit mr-2"></i>Edit Area</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="editAreaForm" method="POST" action="">
                @csrf @method('PUT')
                <div class="modal-body" style="padding:20px;">
                    <div class="form-group mb-3">
                        <label style="font-size:.84rem;font-weight:600;">Nama Area <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editAreaName" class="form-control" required style="border-radius:8px;">
                    </div>
                    <div class="form-group mb-0">
                        <label style="font-size:.84rem;font-weight:600;">Deskripsi</label>
                        <input type="text" name="description" id="editAreaDesc" class="form-control" style="border-radius:8px;">
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #eef0f4;padding:12px 20px;">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="border-radius:8px;">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm" style="border-radius:8px;"><i class="fas fa-save mr-1"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════ MODAL: TAMBAH UNIT EQUIPMENT ════ --}}
<div class="modal fade" id="addEqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:0;overflow:hidden;">
            <div class="modal-header" id="addEqHeader" style="background:#0a2e6e;border:0;">
                <h5 class="modal-title text-white" style="font-size:.95rem;" id="addEqTitle">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Unit
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form method="POST" action="{{ route('laundry.equipment.store') }}">
                @csrf
                <input type="hidden" name="area_id"  id="addEqAreaId" value="{{ $activeArea?->id }}">
                <input type="hidden" name="category" id="addEqCategory" value="washer">
                <div class="modal-body" style="padding:24px;">
                    <div class="form-row">
                        <div class="form-group col-8">
                            <label style="font-size:.85rem;font-weight:600;">Model / Nama Tipe <span class="text-danger">*</span></label>
                            <input type="text" name="model_name" class="form-control" required
                                placeholder="cth: Speedqueen 15 kg" style="border-radius:8px;">
                        </div>
                        <div class="form-group col-4">
                            <label style="font-size:.85rem;font-weight:600;">Jumlah Unit <span class="text-danger">*</span></label>
                            <input type="number" name="unit_qty" class="form-control" required
                                value="1" min="1" max="50" style="border-radius:8px;" id="addEqQty">
                            <small class="text-muted" style="font-size:.72rem;">Unit akan dinomori otomatis</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label style="font-size:.85rem;font-weight:600;">Kapasitas / Siklus (kg) <span class="text-danger">*</span></label>
                            <input type="number" name="capacity_kg" class="form-control" required
                                placeholder="15" min="0.1" step="any" style="border-radius:8px;">
                        </div>
                        <div class="form-group col-6">
                            <label style="font-size:.85rem;font-weight:600;">Durasi 1 Siklus (menit) <span class="text-danger">*</span></label>
                            <input type="number" name="process_time_minutes" class="form-control" required
                                placeholder="45" min="1" step="any" style="border-radius:8px;" id="addEqCycle">
                            <small class="text-muted" style="font-size:.72rem;">
                                Output/jam: <span id="addEqCalcResult" style="font-weight:700;color:#0d6efd;">–</span>
                            </small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-4">
                            <label style="font-size:.85rem;font-weight:600;">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" style="border-radius:8px;" required>
                                <option value="ready">✅ Ready</option>
                                <option value="breakdown">🔧 Breakdown</option>
                            </select>
                        </div>
                        <div class="form-group col-8">
                            <label style="font-size:.85rem;font-weight:600;">Remarks / Keterangan</label>
                            <input type="text" name="remarks" class="form-control"
                                placeholder="cth: Under MTC waiting part" style="border-radius:8px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #eef0f4;padding:14px 20px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:8px;">Batal</button>
                    <button type="submit" class="btn btn-primary" id="addEqSubmitBtn"
                        style="border-radius:8px;background:#0a2e6e;border-color:#0a2e6e;">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════ MODAL: EDIT UNIT EQUIPMENT ════ --}}
<div class="modal fade" id="editEqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:0;overflow:hidden;">
            <div class="modal-header" style="background:#1e40af;border:0;">
                <h5 class="modal-title text-white" style="font-size:.95rem;"><i class="fas fa-edit mr-2"></i>Edit Unit Equipment</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="editEqForm" method="POST" action="">
                @csrf @method('PUT')
                <div class="modal-body" style="padding:24px;">
                    <div class="form-group">
                        <label style="font-size:.85rem;font-weight:600;">Model / Nama Tipe <span class="text-danger">*</span></label>
                        <input type="text" name="model_name" id="editEqModel" class="form-control" required style="border-radius:8px;">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label style="font-size:.85rem;font-weight:600;">Kapasitas / Siklus (kg) <span class="text-danger">*</span></label>
                            <input type="number" name="capacity_kg" id="editEqCap" class="form-control" required min="0.1" step="any" style="border-radius:8px;">
                        </div>
                        <div class="form-group col-6">
                            <label style="font-size:.85rem;font-weight:600;">Durasi 1 Siklus (menit) <span class="text-danger">*</span></label>
                            <input type="number" name="process_time_minutes" id="editEqCycle" class="form-control" required min="1" step="any" style="border-radius:8px;">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-4">
                            <label style="font-size:.85rem;font-weight:600;">Status <span class="text-danger">*</span></label>
                            <select name="status" id="editEqStatus" class="form-control" style="border-radius:8px;" required>
                                <option value="ready">✅ Ready</option>
                                <option value="breakdown">🔧 Breakdown</option>
                            </select>
                        </div>
                        <div class="form-group col-8">
                            <label style="font-size:.85rem;font-weight:600;">Remarks / Keterangan</label>
                            <input type="text" name="remarks" id="editEqRemarks" class="form-control" style="border-radius:8px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #eef0f4;padding:14px 20px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:8px;">Batal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius:8px;background:#1e40af;border-color:#1e40af;">
                        <i class="fas fa-save mr-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Edit Area ──
document.querySelectorAll('[data-target="#editAreaModal"]').forEach(btn => {
    btn.addEventListener('click', function () {
        document.getElementById('editAreaName').value  = this.dataset.name;
        document.getElementById('editAreaDesc').value  = this.dataset.description || '';
        document.getElementById('editAreaForm').action = `/laundry-equipment/areas/${this.dataset.id}`;
    });
});

// ── Add Equipment Modal (washer / dryer color) ──
document.querySelectorAll('[data-target="#addEqModal"]').forEach(btn => {
    btn.addEventListener('click', function () {
        const cat     = this.dataset.category;
        const areaId  = this.dataset.area;
        const isW     = cat === 'washer';
        document.getElementById('addEqCategory').value = cat;
        document.getElementById('addEqAreaId').value   = areaId;
        document.getElementById('addEqTitle').innerHTML = isW
            ? '<i class="fas fa-soap mr-2"></i>Tambah Unit WASHER'
            : '<i class="fas fa-wind mr-2"></i>Tambah Unit DRYER';
        const hdrBg = isW ? '#1d4ed8' : '#0d9488';
        document.getElementById('addEqHeader').style.background   = hdrBg;
        document.getElementById('addEqSubmitBtn').style.background = hdrBg;
        document.getElementById('addEqSubmitBtn').style.borderColor = hdrBg;
    });
});

// ── Live calc: output per jam di Add modal ──
function recalcAddOutput() {
    const cycleMin = parseFloat(document.getElementById('addEqCycle').value) || 0;
    const cap      = parseFloat(document.querySelector('#addEqModal [name="capacity_kg"]')?.value) || 0;
    const qty      = parseFloat(document.getElementById('addEqQty').value) || 1;
    const el       = document.getElementById('addEqCalcResult');
    if (cycleMin > 0 && cap > 0) {
        const cyclesPerHour = 60 / cycleMin;
        // PA 85% is baked into final calculation, we can just show theoretical output per hour here, or with 85%
        el.textContent = (cyclesPerHour * cap * qty * 0.85).toFixed(1) + ' kg/jam/unit (@85% PA)';
    } else {
        el.textContent = '–';
    }
}
document.getElementById('addEqCycle')?.addEventListener('input', recalcAddOutput);
document.getElementById('addEqQty')?.addEventListener('input', recalcAddOutput);
document.querySelector('#addEqModal [name="capacity_kg"]')?.addEventListener('input', recalcAddOutput);

// ── Edit Equipment Modal ──
document.querySelectorAll('.btn-edit-eq').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        document.getElementById('editEqModel').value   = this.dataset.model;
        document.getElementById('editEqCap').value     = this.dataset.capacity;
        document.getElementById('editEqCycle').value   = this.dataset.cycle;
        document.getElementById('editEqStatus').value  = this.dataset.status;
        document.getElementById('editEqRemarks').value = this.dataset.remarks || '';
        document.getElementById('editEqForm').action   = `/laundry-equipment/equipment/${id}`;
        $('#editEqModal').modal('show');
    });
});
</script>
@endpush
