<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Lojman Durum Raporu — {{ $summary['generated_at'] }}</title>
    <style>
        @page { margin: 40px 36px 50px 36px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9.5pt;
            color: #0f172a;
            line-height: 1.45;
        }

        /* Header band */
        .header-band {
            background: #0f172a;
            color: #ffffff;
            padding: 18px 22px;
            margin-bottom: 20px;
        }
        .header-band .eyebrow {
            font-size: 7.5pt;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #94a3b8;
            margin-bottom: 4px;
        }
        .header-band h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 6px;
        }
        .header-band .meta {
            font-size: 8pt;
            color: #cbd5e1;
        }
        .header-band .meta span { margin-right: 16px; }

        /* KPI row */
        .kpi-row {
            width: 100%;
            margin-bottom: 18px;
            border-collapse: collapse;
        }
        .kpi-row td {
            width: 25%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            text-align: center;
            vertical-align: top;
        }
        .kpi-row .kpi-label {
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 3px;
        }
        .kpi-row .kpi-value {
            font-size: 14pt;
            font-weight: bold;
            color: #0f172a;
        }
        .kpi-row .kpi-sub {
            font-size: 7pt;
            color: #94a3b8;
        }

        /* Section title */
        .section-title {
            font-size: 10pt;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 5px;
            margin: 18px 0 10px;
        }

        /* Tables */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 8.5pt;
        }
        table.data thead th {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
            font-size: 7.5pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #475569;
            font-weight: bold;
        }
        table.data tbody td {
            border: 1px solid #e2e8f0;
            padding: 5px 8px;
            vertical-align: top;
        }
        table.data tbody tr:nth-child(even) { background: #f8fafc; }

        /* Summary table */
        table.summary {
            width: 48%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 16px;
        }
        table.summary td {
            padding: 5px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        table.summary td:last-child {
            text-align: right;
            font-weight: bold;
        }

        /* Status */
        .status-full { color: #dc2626; font-weight: bold; }
        .status-warn { color: #d97706; font-weight: bold; }
        .status-empty { color: #059669; font-weight: bold; }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7pt;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
        }

        .page-break { page-break-before: always; }

        .two-col { width: 100%; }
        .two-col td { vertical-align: top; }
    </style>
</head>
<body>

{{-- Kapak başlık --}}
<div class="header-band">
    <div class="eyebrow">Resmi Durum Raporu</div>
    <h1>Lojman Genel Durum Raporu</h1>
    <div class="meta">
        <span>Oluşturulma: {{ $summary['generated_at'] }}</span>
        <span>Toplam Oda: {{ $summary['total_rooms'] }}</span>
        <span>Toplam Personel: {{ $summary['total_employees'] }}</span>
        <span>Doluluk: %{{ $summary['occupancy_rate'] }}</span>
    </div>
</div>

{{-- KPI --}}
@php
    $fullRooms = collect($occupancy)->filter(fn($r) => $r['available'] <= 0 && $r['occupied'] > 0)->count();
    $emptyRooms = collect($occupancy)->filter(fn($r) => $r['occupied'] === 0)->count();
    $warningRooms = collect($occupancy)->filter(fn($r) => $r['available'] === 1)->count();
@endphp
<table class="kpi-row">
    <tr>
        <td>
            <div class="kpi-label">Toplam Kapasite</div>
            <div class="kpi-value">{{ number_format($summary['total_capacity']) }}</div>
            <div class="kpi-sub">yatak</div>
        </td>
        <td>
            <div class="kpi-label">Dolu Yatak</div>
            <div class="kpi-value">{{ number_format($summary['total_occupied']) }}</div>
            <div class="kpi-sub">atanmış</div>
        </td>
        <td>
            <div class="kpi-label">Boş Yatak</div>
            <div class="kpi-value">{{ number_format(max(0, $summary['total_capacity'] - $summary['total_occupied'])) }}</div>
            <div class="kpi-sub">müsait</div>
        </td>
        <td>
            <div class="kpi-label">Doluluk Oranı</div>
            <div class="kpi-value">%{{ $summary['occupancy_rate'] }}</div>
            <div class="kpi-sub">genel</div>
        </td>
    </tr>
</table>

<table class="two-col">
    <tr>
        <td style="width:48%;padding-right:12px">
            <div class="section-title">Özet İstatistikler</div>
            <table class="summary">
                <tr><td>Toplam Oda</td><td>{{ number_format($summary['total_rooms']) }}</td></tr>
                <tr><td>Toplam Personel</td><td>{{ number_format($summary['total_employees']) }}</td></tr>
                <tr><td>Atanan Personel</td><td>{{ number_format($summary['assigned']) }}</td></tr>
                <tr><td>Atanmamış Personel</td><td>{{ number_format($summary['unassigned']) }}</td></tr>
                <tr><td>Tam Dolu Oda</td><td class="status-full">{{ $fullRooms }}</td></tr>
                <tr><td>Son 1 Yatak Kalan</td><td class="status-warn">{{ $warningRooms }}</td></tr>
                <tr><td>Tamamen Boş Oda</td><td class="status-empty">{{ $emptyRooms }}</td></tr>
            </table>
        </td>
        <td style="width:52%">
            <div class="section-title">Oda Durum Dağılımı</div>
            <table class="summary">
                <tr><td>Tam Dolu Oda</td><td class="status-full">{{ $fullRooms }} (%{{ $summary['total_rooms'] > 0 ? round($fullRooms/$summary['total_rooms']*100,1) : 0 }})</td></tr>
                <tr><td>Son 1 Yatak</td><td class="status-warn">{{ $warningRooms }}</td></tr>
                <tr><td>Boş Oda</td><td class="status-empty">{{ $emptyRooms }}</td></tr>
                <tr><td>Kısmen Dolu</td><td>{{ $summary['total_rooms'] - $fullRooms - $emptyRooms - $warningRooms }}</td></tr>
            </table>
        </td>
    </tr>
</table>

{{-- Oda tablosu --}}
<div class="section-title">Oda Bazlı Doluluk Listesi ({{ count($occupancy) }} oda)</div>
<table class="data">
    <thead>
        <tr>
            <th style="width:7%">Oda</th>
            <th style="width:14%">Bina/Kat</th>
            <th style="width:8%">Cinsiyet</th>
            <th style="width:6%">Kap.</th>
            <th style="width:6%">Dolu</th>
            <th style="width:6%">Boş</th>
            <th style="width:8%">Durum</th>
            <th>Sakinler</th>
        </tr>
    </thead>
    <tbody>
    @foreach($occupancy as $room)
        @php
            if ($room['occupied'] === 0) {
                $status = 'Boş'; $statusClass = 'status-empty';
            } elseif ($room['available'] <= 0) {
                $status = 'Dolu'; $statusClass = 'status-full';
            } elseif ($room['available'] === 1) {
                $status = 'Kritik'; $statusClass = 'status-warn';
            } else {
                $status = 'Müsait'; $statusClass = '';
            }
        @endphp
        <tr>
            <td><strong>{{ $room['room_number'] }}</strong></td>
            <td>{{ ($room['block'] ?? '—') }} / {{ $room['floor'] ?? '—' }}</td>
            <td>{{ $room['gender'] }}</td>
            <td style="text-align:center">{{ $room['capacity'] }}</td>
            <td style="text-align:center">{{ $room['occupied'] }}</td>
            <td style="text-align:center">{{ $room['available'] }}</td>
            <td class="{{ $statusClass }}">{{ $status }}</td>
            <td>{{ collect($room['occupants'])->pluck('full_name')->implode(', ') ?: '—' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@if(count($unassigned) > 0)
<div class="page-break"></div>
<div class="section-title">Atanmamış Personeller ({{ count($unassigned) }} kişi)</div>
<table class="data">
    <thead>
        <tr>
            <th style="width:12%">Sicil No</th>
            <th style="width:30%">Ad Soyad</th>
            <th style="width:12%">Cinsiyet</th>
            <th>Departman</th>
        </tr>
    </thead>
    <tbody>
    @foreach($unassigned as $emp)
        <tr>
            <td>{{ $emp['personnel_number'] }}</td>
            <td><strong>{{ $emp['full_name'] }}</strong></td>
            <td>{{ $emp['gender'] }}</td>
            <td>{{ $emp['department'] ?? '—' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif

<div class="footer">
    {{ config('app.name') }} — Lojman Yönetim Sistemi | {{ $summary['generated_at'] }} | Gizli — Dahili Kullanım
</div>

</body>
</html>
