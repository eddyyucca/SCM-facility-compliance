@extends('layouts.app')
@section('title', 'Pergerakan Laundry Mess')
@section('page_title', 'Pergerakan Laundry Mess')
@section('breadcrumb', 'Laundry Mess')

@push('styles')
<style>
.metric-card {
    background: #fff; border-radius: 14px; padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: none;
    display: flex; align-items: center; gap: 15px;
}
.metric-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
}
.icon-pob { background: #e0f2fe; color: #0284c7; }
.icon-bag { background: #fef3c7; color: #d97706; }
.icon-kg { background: #dcfce7; color: #16a34a; }
.icon-target { background: #f3e8ff; color: #9333ea; }
.metric-info h5 { margin: 0; font-size: .8rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.metric-info h3 { margin: 5px 0 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; }

.section-card {
    background: #fff; border-radius: 14px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border: none; margin-bottom: 24px; overflow: hidden;
}
.section-header {
    background: #f8fafc; padding: 16px 20px; border-bottom: 1px solid #e2e8f0;
    display: flex; justify-content: space-between; align-items: center;
}
.section-title { font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 8px; }

.table-input th { font-size: .8rem; color: #475569; background: #f1f5f9; text-align: center; }
.table-input td { vertical-align: middle; }
.form-input-number { width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 6px 12px; text-align: right; }
.form-input-number:focus { border-color: #3b82f6; outline: none; box-shadow: 0 0 0 2px rgba(59,130,246,0.2); }
</style>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="metric-card">
            <div class="metric-icon icon-pob"><i class="fas fa-users"></i></div>
            <div class="metric-info">
                <h5>Total POB</h5>
                <h3>{{ number_format($totalPob) }} <span style="font-size:.9rem;color:#94a3b8;">Orang</span></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="metric-card">
            <div class="metric-icon icon-bag"><i class="fas fa-shopping-bag"></i></div>
            <div class="metric-info">
                <h5>Total Bag (In)</h5>
                <h3>{{ number_format($totalBagIn) }} <span style="font-size:.9rem;color:#94a3b8;">Bag</span></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="metric-card">
            <div class="metric-icon icon-kg"><i class="fas fa-weight"></i></div>
            <div class="metric-info">
                <h5>Total Aktual (In)</h5>
                <h3>{{ number_format($totalKgIn, 1) }} <span style="font-size:.9rem;color:#94a3b8;">Kg</span></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="metric-card">
            <div class="metric-icon icon-target"><i class="fas fa-bullseye"></i></div>
            <div class="metric-info">
                <h5>Target Beban</h5>
                <h3>{{ number_format($targetKg, 1) }} <span style="font-size:.9rem;color:#94a3b8;">Kg</span></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart Section -->
    <div class="col-md-12">
        <div class="section-card">
            <div class="section-header">
                <h4 class="section-title"><i class="fas fa-chart-line text-primary"></i> Tren Orang Laundry (Bag In) per Mess</h4>
                <div>
                    <form method="GET" action="{{ route('laundry.transactions.index') }}" class="d-flex align-items-center gap-2">
                        <select name="month" class="form-control form-control-sm" style="width:120px;border-radius:8px;">
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                            @endfor
                        </select>
                        <select name="year" class="form-control form-control-sm" style="width:100px;border-radius:8px;">
                            @for($y=date('Y')-1; $y<=date('Y'); $y++)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px;">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Input Form Section -->
    <div class="col-md-12">
        <div class="section-card">
            <div class="section-header">
                <h4 class="section-title"><i class="fas fa-edit text-success"></i> Input Pergerakan Laundry Harian</h4>
                <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#addMessModal" style="border-radius:8px;">
                    <i class="fas fa-plus"></i> Tambah Master Mess
                </button>
            </div>
            <div class="card-body">
                @if($messes->count() == 0)
                    <div class="alert alert-warning text-center">Belum ada data Master Mess. Silakan tambah Mess terlebih dahulu.</div>
                @else
                    <form method="POST" action="{{ route('laundry.transactions.store') }}">
                        @csrf
                        <div class="form-group mb-4" style="max-width: 250px;">
                            <label style="font-weight:700;">Pilih Tanggal Transaksi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="inputTanggal" class="form-control" value="{{ date('Y-m-d') }}" required style="border-radius:8px; border-width: 2px;">
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-input">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="text-align:left; width: 200px;">Nama Mess</th>
                                        <th rowspan="2" style="width: 100px;">POB</th>
                                        <th colspan="2">Laundry IN (Masuk)</th>
                                        <th colspan="2">Laundry OUT (Keluar)</th>
                                    </tr>
                                    <tr>
                                        <th style="width: 120px;">Jml Bag</th>
                                        <th style="width: 120px;">Total Kg</th>
                                        <th style="width: 120px;">Jml Bag</th>
                                        <th style="width: 120px;">Total Kg</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($messes as $index => $mess)
                                    <tr>
                                        <td style="font-weight:600;">
                                            <input type="hidden" name="entries[{{ $index }}][mess_id]" value="{{ $mess->id }}">
                                            {{ $mess->name }}
                                            <div style="font-size:.7rem; color:#94a3b8; font-weight:normal;">Area: {{ $mess->area->name ?? '-' }}</div>
                                        </td>
                                        <td><input type="number" name="entries[{{ $index }}][pob]" class="form-input-number" min="0" value="0"></td>
                                        <td><input type="number" name="entries[{{ $index }}][bag_in]" class="form-input-number bag-in" min="0" value="0"></td>
                                        <td><input type="number" name="entries[{{ $index }}][kg_in]" class="form-input-number kg-in" min="0" step="any" value="0"></td>
                                        <td><input type="number" name="entries[{{ $index }}][bag_out]" class="form-input-number bag-out" min="0" value="0"></td>
                                        <td><input type="number" name="entries[{{ $index }}][kg_out]" class="form-input-number kg-out" min="0" step="any" value="0"></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div style="font-size: .85rem; color: #64748b;">
                                <i class="fas fa-info-circle mr-1"></i> Hint: Otomatis menghitung Kg = Bag × 2.5 kg jika Kg dikosongkan. Data tanggal yang sama akan ditimpa (update).
                            </div>
                            <button type="submit" class="btn btn-primary" style="border-radius:10px; padding: 8px 24px; font-weight:600;">
                                <i class="fas fa-save mr-2"></i> Simpan Data Harian
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Master Mess -->
<div class="modal fade" id="addMessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:16px; border:0;">
            <div class="modal-header" style="background:#0f172a; border:0;">
                <h5 class="modal-title text-white"><i class="fas fa-building mr-2"></i>Tambah Mess</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="{{ route('laundry.mess.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label>Area <span class="text-danger">*</span></label>
                        <select name="area_id" class="form-control" required style="border-radius:8px;">
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Nama Mess <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required style="border-radius:8px;" placeholder="cth: Mess Anggrek">
                    </div>
                    <div class="form-group mb-0">
                        <label>Deskripsi</label>
                        <input type="text" name="description" class="form-control" style="border-radius:8px;">
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Auto-calculate Kg based on Bag (1 bag = 2.5 kg)
    document.querySelectorAll('.bag-in').forEach(function(el) {
        el.addEventListener('input', function() {
            let row = this.closest('tr');
            let kgIn = row.querySelector('.kg-in');
            if (this.value !== '') {
                kgIn.value = (parseFloat(this.value) * 2.5).toFixed(1);
            }
        });
    });

    document.querySelectorAll('.bag-out').forEach(function(el) {
        el.addEventListener('input', function() {
            let row = this.closest('tr');
            let kgOut = row.querySelector('.kg-out');
            if (this.value !== '') {
                kgOut.value = (parseFloat(this.value) * 2.5).toFixed(1);
            }
        });
    });

    // Chart.js Setup
    const chartLabels = {!! json_encode($chartDates) !!};
    const rawData = {!! json_encode($chartData) !!};
    
    // Generate distinct colors for each mess
    const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'];
    let datasets = [];
    let colorIndex = 0;

    for (const [messId, dataObj] of Object.entries(rawData)) {
        datasets.push({
            label: dataObj.name,
            data: dataObj.data,
            borderColor: colors[colorIndex % colors.length],
            backgroundColor: colors[colorIndex % colors.length] + '20', // Add transparency
            borderWidth: 2,
            tension: 0.3,
            fill: true
        });
        colorIndex++;
    }

    const ctx = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels.map(d => {
                let date = new Date(d);
                return date.getDate() + ' ' + date.toLocaleString('default', { month: 'short' });
            }),
            datasets: datasets
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' Orang / Bag';
                        }
                    }
                },
                legend: { position: 'bottom' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Jumlah Bag (Masuk)' },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush
