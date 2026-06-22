@extends('layouts.app')
@section('breadcrumb')
    <li><a href="{{ route('panel.index') }}">Anasayfa</a></li>
    <li class="active">Rol Şablonları</li>
@endsection
@section('header')
    <h1 class="lj-page-title">IK & Lojman Rol Şablonları</h1>
@endsection
@section('subtitle')
    Varsayılan şablonlar — bireysel kullanıcı yetkileri Kullanıcılar bölümünden tiklenir
@endsection
@section('content')
<form method="POST" action="{{ route('settings.permissions.update') }}">
    @csrf @method('PUT')
    <div class="row g-4">
        @foreach($roles as $roleKey => $roleLabel)
        <div class="col-lg-6">
            <div class="lj-3d-card h-100">
                <div class="lj-card-header">
                    <h2><i class="bi bi-shield-check"></i> {{ $roleLabel }}</h2>
                    <span class="lj-badge lj-badge-neutral">{{ $roleKey }}</span>
                </div>
                <div class="lj-card-body">
                    @foreach($groups as $group)
                        @if(!empty($group['permissions']))
                        <div class="mb-3">
                            <div class="fw-semibold small text-muted mb-2"><i class="bi {{ $group['icon'] }}"></i> {{ $group['label'] }}</div>
                            @foreach($group['permissions'] as $permKey => $permMeta)
                            <label class="lj-perm-item d-block mb-1 {{ in_array($permKey, $assigned[$roleKey] ?? [], true) ? 'is-checked' : '' }}">
                                <input type="checkbox" name="permissions[{{ $roleKey }}][]" value="{{ $permKey }}"
                                    class="lj-perm-checkbox me-2"
                                    @checked(in_array($permKey, $assigned[$roleKey] ?? [], true))>
                                <span class="lj-perm-type {{ \App\Support\PermissionCatalog::typeBadgeClass($permMeta['type']) }}">{{ \App\Support\PermissionCatalog::typeLabel($permMeta['type']) }}</span>
                                {{ $permMeta['label'] }}
                            </label>
                            @endforeach
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4">
        <button class="btn btn-lj-primary lj-3d-btn"><i class="bi bi-check-lg"></i> Şablonları Kaydet</button>
    </div>
</form>
@endsection
