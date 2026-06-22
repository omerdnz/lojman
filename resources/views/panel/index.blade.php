@extends('layouts.app')
@section('breadcrumb')
    <li class="active">Anasayfa</li>
@endsection
@section('header')
    <h1 class="lj-page-title">Merhaba, {{ auth()->user()->username }}</h1>
@endsection
@section('subtitle')
    Lojman yönetim paneline hoş geldiniz.
@endsection
@section('content')
<div class="lj-stat-grid mb-4">
    <div class="lj-stat-card lj-3d-card">
        <div class="lj-stat-icon primary"><i class="bi bi-people"></i></div>
        <div>
            <div class="lj-stat-label">Toplam Personel</div>
            <div class="lj-stat-value">{{ number_format($stats['total_employees']) }}</div>
        </div>
    </div>
    <div class="lj-stat-card lj-3d-card">
        <div class="lj-stat-icon info"><i class="bi bi-building"></i></div>
        <div>
            <div class="lj-stat-label">Blok / Kat</div>
            <div class="lj-stat-value">{{ $stats['total_blocks'] }} / {{ $stats['total_floors'] }}</div>
        </div>
    </div>
    <div class="lj-stat-card lj-3d-card">
        <div class="lj-stat-icon info"><i class="bi bi-door-open"></i></div>
        <div>
            <div class="lj-stat-label">Toplam Oda</div>
            <div class="lj-stat-value">{{ number_format($stats['total_rooms']) }}</div>
        </div>
    </div>
    <div class="lj-stat-card lj-3d-card">
        <div class="lj-stat-icon success"><i class="bi bi-graph-up-arrow"></i></div>
        <div>
            <div class="lj-stat-label">Doluluk Oranı</div>
            <div class="lj-stat-value">%{{ $stats['occupancy_rate'] }}</div>
        </div>
    </div>
</div>

<div class="lj-section-title">Hızlı Erişim</div>
<div class="lj-quick-grid">
    <a href="{{ route('employees.index') }}" class="lj-quick-link lj-3d-card">
        <i class="bi bi-people"></i>
        <strong>Personeller</strong>
        <span class="text-muted small">{{ number_format($stats['total_employees']) }} kayıt</span>
    </a>
    <a href="{{ route('rooms.index') }}" class="lj-quick-link lj-3d-card">
        <i class="bi bi-door-open"></i>
        <strong>Odalar</strong>
        <span class="text-muted small">{{ number_format($stats['empty_rooms']) }} boş oda</span>
    </a>
    <a href="{{ route('assignments.index') }}" class="lj-quick-link lj-3d-card">
        <i class="bi bi-pin-map"></i>
        <strong>Yerleştirme</strong>
        <span class="text-muted small">{{ number_format($stats['unassigned_employees']) }} boş personel</span>
    </a>
    <a href="{{ route('dashboard.index') }}" class="lj-quick-link lj-3d-card">
        <i class="bi bi-bar-chart-line"></i>
        <strong>Gösterge Paneli</strong>
        <span class="text-muted small">Detaylı istatistikler</span>
    </a>
    @can('reports.view')
    <a href="{{ route('reports.index') }}" class="lj-quick-link">
        <i class="bi bi-file-earmark-bar-graph"></i>
        <strong>Raporlar</strong>
        <span class="text-muted small">Excel / PDF</span>
    </a>
    @endcan
    @can('permissions.manage')
    <a href="{{ route('settings.permissions') }}" class="lj-quick-link">
        <i class="bi bi-shield-lock"></i>
        <strong>Yetkilendirme</strong>
        <span class="text-muted small">IK & Lojman izinleri</span>
    </a>
    @endcan
</div>
@endsection
