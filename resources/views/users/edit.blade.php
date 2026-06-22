@extends('layouts.app')
@section('breadcrumb')
    <li><a href="{{ route('panel.index') }}">Anasayfa</a></li>
    <li><a href="{{ route('users.index') }}">Kullanıcılar</a></li>
    <li class="active">{{ $user->username }}</li>
@endsection
@section('header')
    <h1 class="lj-page-title">Kullanıcı Düzenle</h1>
@endsection
@section('subtitle')
    {{ $user->username }} — yetkileri modül modül tikleyerek yönetin
@endsection
@section('content')
<form method="POST" action="{{ route('users.update', $user) }}">
    @csrf @method('PUT')

    <div class="lj-3d-card mb-4">
        <div class="lj-card-header"><h2><i class="bi bi-person-badge"></i> Hesap Bilgileri</h2></div>
        <div class="lj-card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kullanıcı Adı</label>
                    <input name="username" class="form-control" value="{{ old('username', $user->username) }}" required @disabled($isSuperAdmin)>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ad Soyad</label>
                    <input name="name" class="form-control" value="{{ old('name', $user->name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">E-posta</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Yeni Şifre</label>
                    <input type="password" name="password" class="form-control" placeholder="Değiştirmek için doldurun">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Rol Etiketi</label>
                    @if($isSuperAdmin)
                        <input class="form-control" value="super_admin" disabled>
                        <input type="hidden" name="role" value="super_admin">
                    @else
                        <select name="role" class="form-select" required>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" @selected(old('role', $user->getRoleNames()->first())===$role)>{{ $role }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $user->is_active)) @disabled($isSuperAdmin)>
                        <label class="form-check-label" for="is_active">Aktif</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lj-3d-card mb-4">
        <div class="lj-card-header">
            <h2><i class="bi bi-shield-check"></i> Modül Yetkileri</h2>
            @if($isSuperAdmin)
                <span class="lj-badge lj-badge-warning">Süper admin — tüm yetkiler</span>
            @else
                <span class="lj-badge lj-badge-neutral">{{ count($selectedPermissions) }} izin aktif</span>
            @endif
        </div>
        <div class="lj-card-body">
            @if($isSuperAdmin)
                <p class="text-muted mb-0">Süper admin kullanıcısının yetkileri değiştirilemez.</p>
            @else
                <x-permission-matrix :selected="$selectedPermissions" :role-presets="$rolePresets" />
            @endif
        </div>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-lj-primary lj-3d-btn" @disabled($isSuperAdmin && false)>Güncelle</button>
        <a href="{{ route('users.index') }}" class="btn btn-lj-ghost">İptal</a>
    </div>
</form>
@endsection
