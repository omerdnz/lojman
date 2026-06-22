<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lojman Yönetim') — {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/lojman.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lojman-corp.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="lj-body lj-corp-theme">
<div class="lj-app">
    <aside class="lj-sidebar" id="ljSidebar">
        <div class="lj-brand">
            <div class="lj-brand-icon"><i class="bi bi-buildings"></i></div>
            <div>
                <div class="lj-brand-text">Lojman</div>
                <div class="lj-brand-sub">Yönetim Sistemi</div>
            </div>
        </div>

        <nav class="lj-nav">
            <div class="lj-nav-label">Ana Menü</div>
            <a class="lj-nav-link {{ request()->routeIs('panel.*') ? 'active' : '' }}" href="{{ route('panel.index') }}">
                <i class="bi bi-grid-1x2"></i> Anasayfa
            </a>
            <a class="lj-nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}" href="{{ route('dashboard.index') }}">
                <i class="bi bi-bar-chart-line"></i> Gösterge Paneli
            </a>
            @can('employees.view')
            <a class="lj-nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                <i class="bi bi-people"></i> Personeller
            </a>
            @endcan
            @can('rooms.view')
            <a class="lj-nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}" href="{{ route('rooms.index') }}">
                <i class="bi bi-door-open"></i> Odalar
            </a>
            @endcan
            @canany(['placements.view', 'placements.assign'])
            <a class="lj-nav-link {{ request()->routeIs('assignments.*') ? 'active' : '' }}" href="{{ route('assignments.index') }}">
                <i class="bi bi-pin-map"></i> Yerleştirme
            </a>
            @endcanany
            @can('transfers.view')
            <a class="lj-nav-link {{ request()->routeIs('transfers.*') ? 'active' : '' }}" href="{{ route('transfers.index') }}">
                <i class="bi bi-clock-history"></i> Geçmiş
            </a>
            @endcan
            @can('reports.view')
            <a class="lj-nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                <i class="bi bi-file-earmark-bar-graph"></i> Raporlar
            </a>
            @endcan

            @can('employees.import')
            <div class="lj-nav-label">Veri</div>
            <a class="lj-nav-link {{ request()->routeIs('imports.*') ? 'active' : '' }}" href="{{ route('imports.index') }}">
                <i class="bi bi-upload"></i> Excel İçe Aktar
            </a>
            @endcan

            @can('users.view')
            <div class="lj-nav-label">Yönetim</div>
            <a class="lj-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="bi bi-person-gear"></i> Kullanıcılar
            </a>
            @endcan

            @can('notifications.view')
            <a class="lj-nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                <i class="bi bi-bell"></i> Bildirimler
            </a>
            @endcan

            @can('permissions.manage')
            <a class="lj-nav-link {{ request()->routeIs('settings.permissions*') ? 'active' : '' }}" href="{{ route('settings.permissions') }}">
                <i class="bi bi-shield-lock"></i> Yetkilendirme
            </a>
            @endcan
            @can('settings.manage')
            <div class="lj-nav-label">Sistem</div>
            <a class="lj-nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.capacity') }}">
                <i class="bi bi-sliders"></i> Kapasite Ayarları
            </a>
            @endcan
        </nav>

        <div class="lj-sidebar-footer">
            <div class="lj-user-chip">
                <div class="lj-user-avatar">{{ strtoupper(substr(auth()->user()->username ?? auth()->user()->name, 0, 2)) }}</div>
                <div>
                    <div class="lj-user-name">{{ auth()->user()->username ?? auth()->user()->name }}</div>
                    <div class="lj-user-role">{{ auth()->user()->getRoleNames()->first() }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100 rounded-3">
                    <i class="bi bi-box-arrow-right me-1"></i> Çıkış Yap
                </button>
            </form>
        </div>
    </aside>

    <div class="lj-main">
        <header class="lj-topbar d-none d-lg-block">
            @hasSection('breadcrumb')
                <ol class="lj-breadcrumb">@yield('breadcrumb')</ol>
            @endif
        </header>

        <div class="lj-content">
            @if(session('success') || session('status'))
                <div class="alert alert-success lj-alert alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') ?? session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="lj-page-header">
                <div>
                    @yield('header')
                    @hasSection('subtitle')
                        <p class="lj-page-subtitle">@yield('subtitle')</p>
                    @endif
                </div>
                <div class="d-flex gap-2 flex-wrap">@yield('actions')</div>
            </div>

            @yield('content')
        </div>
    </div>
</div>

<button class="lj-mobile-toggle d-lg-none" type="button" onclick="document.getElementById('ljSidebar').classList.toggle('open')" aria-label="Menü">
    <i class="bi bi-list"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
