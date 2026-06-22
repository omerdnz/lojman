@extends('layouts.app')
@section('breadcrumb')
    <li><a href="{{ route('panel.index') }}">Anasayfa</a></li>
    <li><a href="{{ route('users.index') }}">Kullanıcılar</a></li>
    <li class="active">Yeni Kullanıcı</li>
@endsection
@section('header')
    <h1 class="lj-page-title">Yeni Kullanıcı</h1>
@endsection
@section('subtitle')
    Hesap bilgileri ve modül yetkilerini tikleyerek tanımlayın
@endsection
@section('content')
<form method="POST" action="{{ route('users.store') }}">
    @csrf
    <div class="lj-3d-card mb-4">
        <div class="lj-card-header"><h2><i class="bi bi-person-badge"></i> Hesap Bilgileri</h2></div>
        <div class="lj-card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kullanıcı Adı</label>
                    <input name="username" class="form-control" value="{{ old('username') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ad Soyad</label>
                    <input name="name" class="form-control" value="{{ old('name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">E-posta</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Şifre</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Varsayılan Rol</label>
                    <select name="role" class="form-select" required>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" @selected(old('role')===$role)>{{ $role }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Rol etiketi içindir; asıl erişim aşağıdaki tiklerle belirlenir.</div>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', true))>
                        <label class="form-check-label" for="is_active">Aktif kullanıcı</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lj-3d-card mb-4">
        <div class="lj-card-header">
            <h2><i class="bi bi-shield-check"></i> Modül Yetkileri</h2>
            <span class="lj-badge lj-badge-neutral">Görüntüle · Ekle · Düzenle · Sil</span>
        </div>
        <div class="lj-card-body">
            <x-permission-matrix :selected="$selectedPermissions" :role-presets="$rolePresets" />
        </div>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-lj-primary lj-3d-btn"><i class="bi bi-check-lg me-1"></i> Kaydet</button>
        <a href="{{ route('users.index') }}" class="btn btn-lj-ghost">İptal</a>
    </div>
</form>
@endsection
