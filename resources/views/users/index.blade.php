@extends('layouts.app')
@section('breadcrumb')
    <li><a href="{{ route('panel.index') }}">Anasayfa</a></li>
    <li class="active">Kullanıcılar</li>
@endsection
@section('header')
    <h1 class="lj-page-title">Kullanıcılar</h1>
@endsection
@section('subtitle')
    Her kullanıcıya modül bazlı yetki atayın
@endsection
@section('actions')
    @can('users.create')
    <a href="{{ route('users.create') }}" class="btn btn-lj-primary btn-sm lj-3d-btn"><i class="bi bi-plus-lg"></i> Yeni Kullanıcı</a>
    @endcan
@endsection
@section('content')
<form method="GET" class="lj-filter-bar mb-3">
    <div class="lj-search">
        <i class="bi bi-search"></i>
        <input type="search" name="search" class="form-control" placeholder="Kullanıcı ara..." value="{{ request('search') }}">
    </div>
</form>

<div class="row g-3">
@foreach($users as $user)
    <div class="col-md-6 col-xl-4">
        <div class="lj-user-card lj-3d-card">
            <div class="lj-user-card-top">
                <div class="lj-user-card-avatar">{{ strtoupper(substr($user->username, 0, 2)) }}</div>
                <div>
                    <div class="lj-user-card-name">{{ $user->username }}</div>
                    <div class="lj-user-card-meta">{{ $user->name }} · {{ $user->getRoleNames()->first() }}</div>
                </div>
                <span class="lj-badge {{ $user->is_active ? 'lj-badge-success' : 'lj-badge-danger' }}">
                    {{ $user->is_active ? 'Aktif' : 'Pasif' }}
                </span>
            </div>
            <div class="lj-user-card-perms">
                <span class="small text-muted"><i class="bi bi-shield"></i> {{ $user->getDirectPermissions()->count() }} yetki</span>
                <div class="lj-perm-chips">
                    @foreach($user->getDirectPermissions()->take(4) as $perm)
                        <span class="lj-perm-chip">{{ \App\Support\PermissionCatalog::label($perm->name) }}</span>
                    @endforeach
                    @if($user->getDirectPermissions()->count() > 4)
                        <span class="lj-perm-chip">+{{ $user->getDirectPermissions()->count() - 4 }}</span>
                    @endif
                </div>
            </div>
            <div class="lj-user-card-actions">
                @can('users.edit')
                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-lj-primary lj-3d-btn">
                    <i class="bi bi-shield-check"></i> Yetkileri Düzenle
                </a>
                @endcan
                @can('users.delete')
                @if($user->id !== auth()->id() && !$user->hasRole('super_admin'))
                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Silinsin mi?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
                @endif
                @endcan
            </div>
        </div>
    </div>
@endforeach
</div>
<div class="mt-3">{{ $users->links() }}</div>
@endsection
