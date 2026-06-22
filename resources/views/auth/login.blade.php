@extends('layouts.guest')
@section('title', 'Giriş')
@section('content')
<div class="lj-login-page">
    <div class="lj-login-left">
        <div class="lj-login-brand">
            <div class="mb-4"><i class="bi bi-buildings" style="font-size:3rem;opacity:.9"></i></div>
            <h1>Lojman Yönetim Sistemi</h1>
            <p>Personel yerleştirme, oda takibi ve doluluk raporlarını tek panelden yönetin.</p>
        </div>
    </div>
    <div class="lj-login-right">
        <div class="lj-login-form">
            <h2>Hoş geldiniz</h2>
            <p class="subtitle">Devam etmek için hesabınıza giriş yapın</p>

            @if($errors->any())
                <div class="alert alert-danger lj-alert py-2">
                    <i class="bi bi-exclamation-circle"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="lj-input-group">
                    <i class="bi bi-person"></i>
                    <input type="text" name="username" class="form-control" placeholder="Kullanıcı adı" value="{{ old('username') }}" required autofocus>
                </div>
                <div class="lj-input-group">
                    <i class="bi bi-lock"></i>
                    <input type="password" name="password" class="form-control" placeholder="Şifre" required>
                </div>
                <div class="mb-4 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label text-muted" for="remember">Beni hatırla</label>
                </div>
                <button type="submit" class="btn btn-lj-primary w-100 py-2">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Giriş Yap
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
