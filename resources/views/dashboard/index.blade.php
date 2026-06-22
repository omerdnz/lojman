@extends('layouts.app')
@section('breadcrumb')
    <li><a href="{{ route('panel.index') }}">Anasayfa</a></li>
    <li class="active">Gösterge Paneli</li>
@endsection
@section('header')
    <h1 class="lj-page-title">Gösterge Paneli</h1>
@endsection
@section('subtitle')
    Lojman doluluk ve personel istatistikleri
@endsection
@section('content')
<div class="lj-stat-grid mb-4">
    <div class="lj-stat-card">
        <div class="lj-stat-icon primary"><i class="bi bi-people"></i></div>
        <div>
            <div class="lj-stat-label">Toplam Personel</div>
            <div class="lj-stat-value">{{ number_format($stats['total_employees']) }}</div>
        </div>
    </div>
    <div class="lj-stat-card">
        <div class="lj-stat-icon info"><i class="bi bi-door-open"></i></div>
        <div>
            <div class="lj-stat-label">Toplam Oda</div>
            <div class="lj-stat-value">{{ number_format($stats['total_rooms']) }}</div>
        </div>
    </div>
    <div class="lj-stat-card">
        <div class="lj-stat-icon warning"><i class="bi bi-door-closed"></i></div>
        <div>
            <div class="lj-stat-label">Dolu / Boş Oda</div>
            <div class="lj-stat-value">{{ $stats['occupied_room_count'] }} / {{ $stats['empty_rooms'] }}</div>
        </div>
    </div>
    <div class="lj-stat-card">
        <div class="lj-stat-icon success"><i class="bi bi-percent"></i></div>
        <div>
            <div class="lj-stat-label">Doluluk</div>
            <div class="lj-stat-value">%{{ $stats['occupancy_rate'] }}</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="lj-card h-100">
            <div class="lj-card-header"><h2>Personel Durumu</h2></div>
            <div class="lj-card-body">
                <p>Atanan: <strong>{{ number_format($stats['assigned_employees']) }}</strong></p>
                <p>Atanmamış: <strong>{{ number_format($stats['unassigned_employees']) }}</strong></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="lj-card h-100">
            <div class="lj-card-header"><h2>Kapasite</h2></div>
            <div class="lj-card-body">
                <p>Toplam kapasite: <strong>{{ number_format($stats['total_capacity']) }}</strong></p>
                <p>Dolu yatak: <strong>{{ number_format($stats['total_occupied']) }}</strong></p>
                <p>Boş yatak: <strong>{{ number_format(max(0, $stats['total_capacity'] - $stats['total_occupied'])) }}</strong></p>
            </div>
        </div>
    </div>
</div>
@endsection
