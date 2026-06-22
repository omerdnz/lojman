@extends('layouts.app')
@section('breadcrumb')
    <li><a href="{{ route('panel.index') }}">Anasayfa</a></li>
    <li class="active">Excel İçe Aktar</li>
@endsection
@section('header')
    <h1 class="lj-page-title">Personel İçe Aktar</h1>
@endsection
@section('subtitle')
    Excel (.xlsx) dosyasından toplu personel ekleme
@endsection
@section('content')
<div class="lj-card lj-form-card">
    <div class="lj-card-body">
        <p class="text-muted small">Beklenen sütunlar: <strong>Ad Soyad</strong> (zorunlu), Sicil, Cinsiyet, Departman, Telefon, E-posta, Görev</p>
        <form method="POST" action="{{ route('imports.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
            </div>
            <button class="btn btn-lj-primary"><i class="bi bi-upload me-1"></i> Yükle ve İçe Aktar</button>
        </form>
        @if(session('import_errors'))
            <div class="alert alert-warning mt-3 small">
                <ul class="mb-0">@foreach(session('import_errors') as $err)<li>{{ $err }}</li>@endforeach</ul>
            </div>
        @endif
    </div>
</div>
@endsection
