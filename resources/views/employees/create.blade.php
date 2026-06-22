@extends('layouts.app')
@section('breadcrumb')
    <li><a href="{{ route('panel.index') }}">Anasayfa</a></li>
    <li><a href="{{ route('employees.index') }}">Personeller</a></li>
    <li class="active">Yeni Personel</li>
@endsection
@section('header')
    <h1 class="lj-page-title">Yeni Personel</h1>
@endsection
@section('content')
<div class="lj-card lj-form-card">
    <div class="lj-card-body">
        <form method="POST" action="{{ route('employees.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Sicil No</label>
                    <input name="personnel_number" class="form-control @error('personnel_number') is-invalid @enderror" value="{{ old('personnel_number', $nextPersonnelNumber) }}" required>
                    @error('personnel_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Departman</label>
                    <select name="department_id" class="form-select">
                        <option value="">Seçin</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" @selected(old('department_id')==$dept->id)>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Ad Soyad</label>
                    <input name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required>
                    @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Cinsiyet</label>
                    <select name="gender" class="form-select" required>
                        <option value="">Seçin</option>
                        <option value="male" @selected(old('gender')==='male')>Erkek</option>
                        <option value="female" @selected(old('gender')==='female')>Kadın</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Görev</label>
                    <input name="job_title" class="form-control" value="{{ old('job_title') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telefon</label>
                    <input name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">E-posta</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-12 d-flex gap-2 pt-2">
                    <button class="btn btn-lj-primary"><i class="bi bi-check-lg me-1"></i> Kaydet</button>
                    <a href="{{ route('employees.index') }}" class="btn btn-lj-ghost">İptal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
