<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lojman Yönetim Raporu — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/report.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body class="rp-body rp-show-body">

@php
    $exportParams = http_build_query(array_merge($exportQuery ?? [], ['generate' => 1]));
@endphp

<div class="rp-toolbar no-print">
    <div class="rp-toolbar-brand">
        <div class="icon"><i class="bi bi-buildings"></i></div>
        <span>Lojman Yönetim — Kurumsal Rapor</span>
    </div>
    <div class="rp-toolbar-actions">
        <button onclick="window.print()" class="rp-btn rp-btn-primary">
            <i class="bi bi-printer"></i> Yazdır
        </button>
        @can('reports.export')
        <a href="{{ route('reports.excel') }}" class="rp-btn rp-btn-success">
            <i class="bi bi-file-earmark-excel"></i> Excel
        </a>
        <a href="{{ route('reports.pdf') }}" class="rp-btn rp-btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
        @endcan
        <a href="{{ route('reports.index') }}" class="rp-btn rp-btn-outline">
            <i class="bi bi-sliders"></i> Yeni Rapor
        </a>
        <a href="{{ route('panel.index') }}" class="rp-btn rp-btn-outline">
            <i class="bi bi-house"></i> Ana Sayfa
        </a>
    </div>
</div>

<div class="rp-container">

    <header class="rp-report-header rp-report-header-executive">
        <div class="rp-report-header-grid">
            <div>
                <div class="eyebrow">Kurumsal Durum Raporu</div>
                <h1>Lojman Yönetim<br><span>Performans Özeti</span></h1>
                <div class="meta">
                    <span><i class="bi bi-calendar3"></i> {{ $summary['generated_at'] }}</span>
                    <span><i class="bi bi-building"></i> {{ $summary['total_rooms'] }} Oda</span>
                    <span><i class="bi bi-people"></i> {{ $summary['total_employees'] }} Personel</span>
                </div>
            </div>
            <div class="rp-report-header-stat">
                <div class="rp-header-stat-label">Genel Doluluk</div>
                <div class="rp-header-stat-value">%{{ $summary['occupancy_rate'] }}</div>
                <div class="rp-header-stat-ring" style="--pct: {{ $summary['occupancy_rate'] }}"></div>
            </div>
        </div>
    </header>

    @if(in_array('kpi', $sections, true))
    <div class="rp-kpi-grid rp-kpi-grid-executive">
        <div class="rp-kpi accent">
            <div class="label">Toplam Kapasite</div>
            <div class="value">{{ number_format($summary['total_capacity']) }}</div>
            <div class="sub">yatak</div>
        </div>
        <div class="rp-kpi success">
            <div class="label">Dolu Yatak</div>
            <div class="value">{{ number_format($summary['total_occupied']) }}</div>
            <div class="sub">atanmış</div>
        </div>
        <div class="rp-kpi warning">
            <div class="label">Boş Yatak</div>
            <div class="value">{{ number_format(max(0, $summary['total_capacity'] - $summary['total_occupied'])) }}</div>
            <div class="sub">müsait</div>
        </div>
        <div class="rp-kpi accent">
            <div class="label">Yerleşme Oranı</div>
            <div class="value">%{{ $executive['assignment_rate'] }}</div>
            <div class="sub">aktif personel</div>
        </div>
        <div class="rp-kpi danger">
            <div class="label">Tam Dolu Oda</div>
            <div class="value">{{ $fullRooms }}</div>
            <div class="sub">kapasite doldu</div>
        </div>
        <div class="rp-kpi warning">
            <div class="label">Kritik Oda</div>
            <div class="value">{{ $warningRooms }}</div>
            <div class="sub">1 yatak kaldı</div>
        </div>
        <div class="rp-kpi success">
            <div class="label">Boş Oda</div>
            <div class="value">{{ $emptyRooms }}</div>
            <div class="sub">tamamen boş</div>
        </div>
        <div class="rp-kpi accent">
            <div class="label">Atanmamış</div>
            <div class="value">{{ number_format($summary['unassigned']) }}</div>
            <div class="sub">personel</div>
        </div>
    </div>
    @endif

    @if(in_array('executive', $sections, true))
    <div class="rp-executive-card">
        <div class="rp-executive-card-head">
            <i class="bi bi-briefcase-fill"></i>
            <div>
                <h2>Yönetici Özeti</h2>
                <p>Üst yönetim için stratejik değerlendirme</p>
            </div>
        </div>
        <ul class="rp-executive-list">
            @foreach($executive['highlights'] as $highlight)
            <li><i class="bi bi-check-circle-fill"></i> {{ $highlight }}</li>
            @endforeach
        </ul>
        <div class="rp-executive-metrics">
            <div><span>Doluluk</span><strong>%{{ $summary['occupancy_rate'] }}</strong></div>
            <div><span>Yerleşme</span><strong>%{{ $executive['assignment_rate'] }}</strong></div>
            <div><span>Boş Yatak</span><strong>{{ number_format($executive['available_beds']) }}</strong></div>
            <div><span>Kritik Oda</span><strong>{{ $warningRooms }}</strong></div>
        </div>
    </div>
    @endif

    @if(in_array('charts', $sections, true))
    <div class="rp-charts-grid">
        <div class="rp-chart-card">
            <div class="rp-chart-card-head">
                <h3><i class="bi bi-pie-chart"></i> Oda Durum Dağılımı</h3>
                <span class="rp-chart-type-badge">{{ ucfirst($charts['room_status']) }}</span>
            </div>
            <div class="rp-chart-canvas-wrap"><canvas id="chartRoomStatus"></canvas></div>
        </div>
        <div class="rp-chart-card">
            <div class="rp-chart-card-head">
                <h3><i class="bi bi-layers"></i> Kapasite Kullanımı</h3>
                <span class="rp-chart-type-badge">{{ ucfirst($charts['capacity']) }}</span>
            </div>
            <div class="rp-chart-canvas-wrap"><canvas id="chartCapacity"></canvas></div>
        </div>
        <div class="rp-chart-card rp-chart-card-wide">
            <div class="rp-chart-card-head">
                <h3><i class="bi bi-building"></i> Blok Bazlı Doluluk</h3>
                <span class="rp-chart-type-badge">{{ ucfirst($charts['block']) }}</span>
            </div>
            <div class="rp-chart-canvas-wrap rp-chart-canvas-tall"><canvas id="chartBlock"></canvas></div>
        </div>
        <div class="rp-chart-card">
            <div class="rp-chart-card-head">
                <h3><i class="bi bi-people"></i> Cinsiyet Dağılımı</h3>
                <span class="rp-chart-type-badge">{{ ucfirst($charts['gender']) }}</span>
            </div>
            <div class="rp-chart-canvas-wrap"><canvas id="chartGender"></canvas></div>
        </div>
    </div>
    @endif

    @if(in_array('table', $sections, true))
    <div class="rp-section">
        <div class="rp-section-header">
            <h2><i class="bi bi-door-open"></i> Oda Bazlı Doluluk Listesi</h2>
            <span class="badge-count">{{ count($occupancy) }} oda</span>
        </div>
        <div class="rp-section-header no-print" style="border-top:1px solid var(--rp-border); background:#fff">
            <div class="rp-search">
                <i class="bi bi-search"></i>
                <input type="search" id="roomFilter" placeholder="Oda, bina veya personel ara...">
            </div>
        </div>
        <div class="rp-table-wrap">
            <table class="rp-table" id="roomTable">
                <thead>
                    <tr>
                        <th>Oda No</th>
                        <th>Bina / Kat</th>
                        <th>Cinsiyet</th>
                        <th>Kapasite</th>
                        <th>Dolu</th>
                        <th>Boş</th>
                        <th>Doluluk</th>
                        <th>Durum</th>
                        <th>Sakinler</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($occupancy as $room)
                    @php
                        $pct = $room['capacity'] > 0 ? round($room['occupied'] / $room['capacity'] * 100) : 0;
                        if ($room['occupied'] === 0) {
                            $statusClass = 'rp-status-empty'; $statusLabel = 'Boş';
                            $barColor = '#059669';
                        } elseif ($room['available'] <= 0) {
                            $statusClass = 'rp-status-full'; $statusLabel = 'Dolu';
                            $barColor = '#dc2626';
                        } elseif ($room['available'] === 1) {
                            $statusClass = 'rp-status-warning'; $statusLabel = 'Kritik';
                            $barColor = '#d97706';
                        } else {
                            $statusClass = 'rp-status-partial'; $statusLabel = 'Müsait';
                            $barColor = '#4f46e5';
                        }
                        $occupantNames = collect($room['occupants'])->pluck('full_name')->implode(' ');
                    @endphp
                    <tr data-search="{{ mb_strtolower($room['room_number'].' '.($room['block']??'').' '.($room['floor']??'').' '.$occupantNames) }}">
                        <td><strong>{{ $room['room_number'] }}</strong></td>
                        <td>{{ ($room['block'] ?? '—') }} / {{ $room['floor'] ?? '—' }}</td>
                        <td>
                            <span class="rp-gender rp-gender-{{ $room['gender'] === 'Erkek' ? 'erkek' : 'kadin' }}">
                                {{ $room['gender'] }}
                            </span>
                        </td>
                        <td>{{ $room['capacity'] }}</td>
                        <td>{{ $room['occupied'] }}</td>
                        <td>{{ $room['available'] }}</td>
                        <td class="rp-bar-cell">
                            %{{ $pct }}
                            <div class="rp-mini-bar">
                                <div class="rp-mini-bar-fill" style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
                            </div>
                        </td>
                        <td><span class="rp-status {{ $statusClass }}">{{ $statusLabel }}</span></td>
                        <td style="max-width:220px">
                            @if(empty($room['occupants']))
                                <span style="color:var(--rp-muted)">—</span>
                            @else
                                {{ collect($room['occupants'])->pluck('full_name')->implode(', ') }}
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if(in_array('cards', $sections, true))
    <div class="rp-section">
        <div class="rp-section-header">
            <h2><i class="bi bi-grid-3x3-gap"></i> Oda Kartları Görünümü</h2>
            <span class="badge-count">{{ count($occupancy) }} oda</span>
        </div>
        <div class="rp-section-body">
            <div class="rp-room-grid">
                @foreach($occupancy as $room)
                    @php
                        $pct = $room['capacity'] > 0 ? round($room['occupied'] / $room['capacity'] * 100) : 0;
                        $cardClass = $room['occupied'] === 0 ? 'rp-room-card-empty' : ($room['available'] <= 0 ? 'rp-room-card-full' : 'rp-room-card-partial');
                    @endphp
                    <div class="rp-room-card {{ $cardClass }}">
                        <div class="rp-room-card-header">
                            <div>
                                <strong>Oda {{ $room['room_number'] }}</strong>
                                <div class="small" style="color:var(--rp-muted)">{{ $room['block'] }} · {{ $room['floor'] }}</div>
                            </div>
                            <span class="rp-status {{ $room['available'] <= 0 && $room['occupied'] > 0 ? 'rp-status-full' : ($room['occupied'] === 0 ? 'rp-status-empty' : 'rp-status-partial') }}">
                                {{ $room['occupied'] }}/{{ $room['capacity'] }}
                            </span>
                        </div>
                        <div class="rp-room-card-body">
                            <div class="rp-mini-bar mb-2">
                                <div class="rp-mini-bar-fill" style="width:{{ $pct }}%"></div>
                            </div>
                            @forelse($room['occupants'] as $occupant)
                                <div class="occupant"><i class="bi bi-person"></i> {{ $occupant['full_name'] }}</div>
                            @empty
                                <div class="occupant text-muted">Boş oda</div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if(in_array('unassigned', $sections, true) && count($unassigned) > 0)
    <div class="rp-section">
        <div class="rp-section-header">
            <h2><i class="bi bi-person-dash"></i> Atanmamış Personeller</h2>
            <span class="badge-count">{{ count($unassigned) }} kişi</span>
        </div>
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th>Sicil No</th>
                        <th>Ad Soyad</th>
                        <th>Cinsiyet</th>
                        <th>Departman</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($unassigned as $emp)
                    <tr>
                        <td><code>{{ $emp['personnel_number'] }}</code></td>
                        <td><strong>{{ $emp['full_name'] }}</strong></td>
                        <td>
                            <span class="rp-gender rp-gender-{{ $emp['gender'] === 'Erkek' ? 'erkek' : 'kadin' }}">
                                {{ $emp['gender'] }}
                            </span>
                        </td>
                        <td>{{ $emp['department'] ?? '—' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <footer class="rp-footer rp-footer-executive">
        <div class="rp-footer-seal"><i class="bi bi-shield-check"></i></div>
        <div>
            <strong>{{ config('app.name') }}</strong> — Lojman Yönetim Sistemi<br>
            Bu rapor {{ $summary['generated_at'] }} tarihinde oluşturulmuştur. Kurumsal kullanım içindir — gizlidir.
        </div>
    </footer>
</div>

@if(in_array('charts', $sections, true))
<script>
window.RpChartConfig = {
    types: @json($charts),
    data: @json($chartData),
    palette: {
        primary: ['#0f172a', '#4f46e5', '#7c3aed', '#059669', '#d97706', '#dc2626', '#0891b2', '#be185d'],
        soft: ['#4f46e5', '#059669', '#d97706', '#dc2626'],
        gender: ['#2563eb', '#db2777'],
        capacity: ['#4f46e5', '#cbd5e1'],
    },
    dpi: Math.min(3, Math.max(2, window.devicePixelRatio || 2)),
};

Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
Chart.defaults.devicePixelRatio = window.RpChartConfig.dpi;
Chart.defaults.color = '#334155';
Chart.defaults.plugins.legend.labels.usePointStyle = true;
Chart.defaults.plugins.legend.labels.padding = 18;
Chart.defaults.plugins.legend.labels.font = { size: 13, weight: '600' };

function rpBaseOptions(type) {
    const isCircular = ['doughnut', 'pie', 'polarArea'].includes(type);
    const isRadar = type === 'radar';
    return {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 900, easing: 'easeOutQuart' },
        plugins: {
            legend: { position: isCircular ? 'bottom' : 'top' },
            tooltip: {
                backgroundColor: '#0f172a',
                titleFont: { size: 14, weight: '700' },
                bodyFont: { size: 13 },
                padding: 14,
                cornerRadius: 10,
            },
        },
        scales: (type === 'bar' || type === 'line') ? {
            x: { grid: { display: false }, ticks: { font: { weight: '600' } } },
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { weight: '600' } } },
        } : (isRadar ? {
            r: { beginAtZero: true, grid: { color: '#e2e8f0' }, ticks: { backdropColor: 'transparent' } },
        } : {}),
    };
}

function rpCreateChart(canvasId, type, config) {
    const el = document.getElementById(canvasId);
    if (!el) return null;
    return new Chart(el, { type, data: config.data, options: { ...rpBaseOptions(type), ...config.options } });
}

const d = window.RpChartConfig.data;
const p = window.RpChartConfig.palette;
const t = window.RpChartConfig.types;

rpCreateChart('chartRoomStatus', t.room_status, {
    data: {
        labels: d.room_status.labels,
        datasets: [{ data: d.room_status.values, backgroundColor: p.soft, borderWidth: 0, hoverOffset: 10 }],
    },
    options: { cutout: t.room_status === 'doughnut' ? '62%' : 0 },
});

rpCreateChart('chartCapacity', t.capacity, {
    data: {
        labels: d.capacity.labels,
        datasets: [{
            label: 'Yatak',
            data: d.capacity.values,
            backgroundColor: p.capacity,
            borderRadius: 8,
            borderSkipped: false,
            fill: t.capacity === 'line',
            tension: 0.35,
        }],
    },
});

const blockType = t.block;
const blockDatasets = blockType === 'line'
    ? [{ label: 'Doluluk %', data: d.by_block.rates, borderColor: '#4f46e5', backgroundColor: 'rgba(79,70,229,.12)', fill: true, tension: 0.35, pointRadius: 5 }]
    : blockType === 'radar'
    ? [{ label: 'Doluluk %', data: d.by_block.rates, backgroundColor: 'rgba(79,70,229,.2)', borderColor: '#4f46e5', pointBackgroundColor: '#4f46e5' }]
    : [
        { label: 'Dolu', data: d.by_block.occupied, backgroundColor: '#4f46e5', borderRadius: 6 },
        { label: 'Kapasite', data: d.by_block.capacity, backgroundColor: '#cbd5e1', borderRadius: 6 },
    ];

rpCreateChart('chartBlock', blockType, {
    data: { labels: d.by_block.labels, datasets: blockDatasets },
    options: blockType === 'bar' ? { scales: { x: { stacked: false }, y: { stacked: false } } } : {},
});

const genderType = t.gender;
const genderDatasets = ['doughnut', 'pie', 'polarArea'].includes(genderType)
    ? [{ data: d.gender.assigned.map((v, i) => v + d.gender.unassigned[i]), backgroundColor: p.gender, borderWidth: 0 }]
    : genderType === 'radar'
    ? [
        { label: 'Yerleşmiş', data: d.gender.assigned, backgroundColor: 'rgba(37,99,235,.2)', borderColor: '#2563eb' },
        { label: 'Atanmamış', data: d.gender.unassigned, backgroundColor: 'rgba(219,39,119,.2)', borderColor: '#db2777' },
    ]
    : [
        { label: 'Yerleşmiş', data: d.gender.assigned, backgroundColor: '#2563eb', borderRadius: 6 },
        { label: 'Atanmamış', data: d.gender.unassigned, backgroundColor: '#db2777', borderRadius: 6 },
    ];

rpCreateChart('chartGender', genderType, {
    data: { labels: d.gender.labels, datasets: genderDatasets },
    options: { cutout: genderType === 'doughnut' ? '58%' : 0 },
});
</script>
@endif

<script>
document.getElementById('roomFilter')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#roomTable tbody tr').forEach(row => {
        row.style.display = (row.dataset.search || '').includes(q) ? '' : 'none';
    });
});
</script>
</body>
</html>
