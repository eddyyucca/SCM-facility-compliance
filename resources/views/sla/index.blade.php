@extends('layouts.app')

@section('title', 'Pengaturan SLA')
@section('page_title', 'Pengaturan SLA')
@section('breadcrumb', 'Pengaturan SLA')

@push('styles')
<style>
    .sla-input {
        width: 80px;
        text-align: center;
        font-weight: 700;
        font-size: .9rem;
        border: 1.5px solid #dee2e6;
        border-radius: 8px;
        padding: 5px 8px;
        transition: border-color .15s, box-shadow .15s;
    }
    .sla-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,.12);
        outline: none;
    }
    .sla-input.changed {
        border-color: #fd7e14;
        background: #fffbf2;
    }
    .sla-human {
        font-size: .68rem;
        color: #6c757d;
        margin-top: 3px;
        min-height: 14px;
        text-align: center;
    }
    .priority-col { font-size: .78rem; font-weight: 700; }
    .priority-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 700;
    }
    .sla-section-title {
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: 8px 16px;
        border-bottom: 1px solid #e9ecef;
    }
    .type-header {
        font-size: .78rem;
        font-weight: 700;
        color: #1a2a4a;
    }
    .info-note {
        background: #fffbf0;
        border: 1px solid #fde68a;
        border-radius: 10px;
        padding: 10px 16px;
        font-size: .82rem;
        color: #92400e;
    }
</style>
@endpush

@section('content')

<form method="POST" action="{{ route('sla.update') }}" id="sla-form">
@csrf
@method('PUT')

<div class="row">
    <div class="col-12 col-xl-9">

        {{-- Catatan --}}
        <div class="info-note mb-3">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>Catatan:</strong> Perubahan SLA hanya berlaku untuk <strong>tiket baru</strong> yang dibuat setelah disimpan.
            Tiket yang sudah ada tidak terpengaruh.
        </div>

        {{-- ── GA Section ── --}}
        <div class="card mb-4">
            <div class="card-header" style="background:#fff;">
                <h3 class="card-title mb-0" style="font-size:.95rem;font-weight:700;">
                    <i class="fas fa-building text-primary mr-2"></i>
                    General Affairs — Batas Waktu Penyelesaian (jam)
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" style="min-width:520px;">
                        <thead>
                            <tr style="background:#f8f9fa;">
                                <th style="min-width:140px;">Tipe Layanan</th>
                                @foreach($gaPriorities as $p)
                                <th class="text-center priority-col">
                                    <span class="priority-badge
                                        @if($p==='urgent') badge-urgent
                                        @elseif($p==='tinggi') badge-high
                                        @elseif($p==='sedang') badge-medium
                                        @else badge-low @endif">
                                        {{ \App\Models\SlaSetting::priorityLabel($p) }}
                                    </span>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gaTypes as $type)
                            <tr>
                                <td>
                                    <div class="type-header">
                                        @if($type === 'receptionist')
                                            <i class="fas fa-concierge-bell text-primary mr-1"></i>
                                        @elseif($type === 'hk')
                                            <i class="fas fa-broom text-success mr-1"></i>
                                        @else
                                            <i class="fas fa-tshirt text-warning mr-1"></i>
                                        @endif
                                        {{ \App\Models\SlaSetting::gaTypeLabel($type) }}
                                    </div>
                                </td>
                                @foreach($gaPriorities as $p)
                                @php
                                    $setting = $all->get("ga|{$type}|{$p}");
                                    $hours   = $setting?->hours ?? 24;
                                @endphp
                                <td class="text-center" style="padding: 10px 8px;">
                                    @if($setting)
                                    <input type="number"
                                           name="sla[{{ $setting->id }}]"
                                           value="{{ $hours }}"
                                           min="1" max="8760"
                                           class="sla-input"
                                           data-original="{{ $hours }}"
                                           data-human-id="human-ga-{{ $type }}-{{ $p }}">
                                    <div class="sla-human" id="human-ga-{{ $type }}-{{ $p }}">
                                        {{ \App\Models\SlaSetting::hoursToHuman($hours) }}
                                    </div>
                                    @else
                                    <span class="text-muted" style="font-size:.75rem;">—</span>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── HR Section ── --}}
        <div class="card mb-4">
            <div class="card-header" style="background:#fff;">
                <h3 class="card-title mb-0" style="font-size:.95rem;font-weight:700;">
                    <i class="fas fa-user-tie text-success mr-2"></i>
                    Human Resources — Batas Waktu Penyelesaian (jam)
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" style="max-width:520px;">
                        <thead>
                            <tr style="background:#f8f9fa;">
                                <th style="min-width:160px;">Prioritas</th>
                                <th class="text-center" style="min-width:160px;">Batas Waktu (jam)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hrPriorities as $p)
                            @php
                                $setting = $all->get("hr|hr_request|{$p}");
                                $hours   = $setting?->hours ?? 24;
                            @endphp
                            <tr>
                                <td>
                                    <span class="priority-badge
                                        @if($p==='mendesak') badge-urgent
                                        @elseif($p==='penting') badge-high
                                        @else badge-medium @endif">
                                        {{ \App\Models\SlaSetting::priorityLabel($p) }}
                                    </span>
                                </td>
                                <td class="text-center" style="padding:10px 8px;">
                                    @if($setting)
                                    <input type="number"
                                           name="sla[{{ $setting->id }}]"
                                           value="{{ $hours }}"
                                           min="1" max="8760"
                                           class="sla-input"
                                           data-original="{{ $hours }}"
                                           data-human-id="human-hr-{{ $p }}">
                                    <div class="sla-human" id="human-hr-{{ $p }}">
                                        {{ \App\Models\SlaSetting::hoursToHuman($hours) }}
                                    </div>
                                    @else
                                    <span class="text-muted" style="font-size:.75rem;">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Side Panel ── --}}
    <div class="col-12 col-xl-3">
        <div class="card mb-3 sticky-top" style="top:72px;">
            <div class="card-body">
                <h6 class="font-weight-700 mb-3" style="font-size:.85rem;">
                    <i class="fas fa-save text-primary mr-1"></i> Simpan Perubahan
                </h6>
                <p class="text-muted mb-3" style="font-size:.78rem;">
                    Klik tombol di bawah untuk menyimpan semua perubahan SLA sekaligus.
                </p>
                <button type="submit" class="btn btn-primary btn-block" id="btn-save">
                    <i class="fas fa-save mr-1"></i> Simpan Semua
                </button>
                <button type="button" class="btn btn-outline-secondary btn-block btn-sm mt-2" id="btn-reset">
                    <i class="fas fa-undo mr-1"></i> Reset Perubahan
                </button>

                <hr>

                <div id="change-summary" style="display:none;">
                    <p class="text-warning font-weight-bold mb-1" style="font-size:.78rem;">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span id="change-count">0</span> nilai diubah
                    </p>
                </div>

                <div style="font-size:.72rem;color:#6c757d;margin-top:8px;">
                    <div class="mb-1"><span class="priority-badge badge-urgent">Urgent/Mendesak</span> — respons sangat cepat</div>
                    <div class="mb-1"><span class="priority-badge badge-high">Tinggi/Penting</span> — respons cepat</div>
                    <div class="mb-1"><span class="priority-badge badge-medium">Sedang/Normal</span> — respons normal</div>
                    <div><span class="priority-badge badge-low">Rendah</span> — respons lambat</div>
                </div>
            </div>
        </div>
    </div>

</div>
</form>

@endsection

@push('scripts')
<script>
function hoursToHuman(h) {
    h = parseInt(h, 10);
    if (!h || h < 1) return '—';
    if (h < 24) return h + ' jam';
    const days = Math.floor(h / 24);
    const rem  = h % 24;
    return rem > 0
        ? `${h} jam (${days} hari ${rem} jam)`
        : `${h} jam (${days} hari)`;
}

let changedCount = 0;

function updateChangeCount() {
    const summary = document.getElementById('change-summary');
    document.getElementById('change-count').textContent = changedCount;
    summary.style.display = changedCount > 0 ? 'block' : 'none';

    const btn = document.getElementById('btn-save');
    btn.className = changedCount > 0
        ? 'btn btn-warning btn-block'
        : 'btn btn-primary btn-block';
    btn.innerHTML = changedCount > 0
        ? `<i class="fas fa-save mr-1"></i> Simpan ${changedCount} Perubahan`
        : '<i class="fas fa-save mr-1"></i> Simpan Semua';
}

document.querySelectorAll('.sla-input').forEach(input => {
    const original  = input.dataset.original;
    const humanId   = input.dataset.humanId;

    input.addEventListener('input', () => {
        const val = parseInt(input.value, 10);

        // Update human-readable text
        document.getElementById(humanId).textContent = hoursToHuman(val);

        // Mark as changed
        const isChanged = input.value !== original;
        if (isChanged) {
            input.classList.add('changed');
        } else {
            input.classList.remove('changed');
        }

        // Recount
        changedCount = document.querySelectorAll('.sla-input.changed').length;
        updateChangeCount();
    });
});

// Reset button
document.getElementById('btn-reset').addEventListener('click', () => {
    document.querySelectorAll('.sla-input').forEach(input => {
        input.value = input.dataset.original;
        input.classList.remove('changed');
        document.getElementById(input.dataset.humanId).textContent =
            hoursToHuman(input.dataset.original);
    });
    changedCount = 0;
    updateChangeCount();
});

// Confirm before save
document.getElementById('sla-form').addEventListener('submit', e => {
    if (changedCount === 0) {
        e.preventDefault();
        alert('Tidak ada perubahan yang perlu disimpan.');
        return;
    }
    if (!confirm(`Simpan ${changedCount} perubahan SLA? Akan berlaku untuk tiket baru.`)) {
        e.preventDefault();
    }
});
</script>
@endpush
