@php use App\Enums\EmployeeStatus; @endphp
@extends('layouts.app')
@section('breadcrumb')
    <li><a href="{{ route('panel.index') }}">Anasayfa</a></li>
    <li><a href="{{ route('employees.index') }}">Personeller</a></li>
    <li class="active">Düzenle</li>
@endsection
@section('header')
    <h1 class="lj-page-title">{{ $employee->full_name }}</h1>
@endsection
@section('content')
<div class="lj-card lj-form-card">
    <div class="lj-card-body">
        <form method="POST" action="{{ route('employees.update', $employee) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Sicil No</label>
                    <input name="personnel_number" class="form-control" value="{{ old('personnel_number', $employee->personnel_number) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Departman</label>
                    <select name="department_id" class="form-select">
                        <option value="">Seçin</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" @selected(old('department_id', $employee->department_id)==$dept->id)>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Ad Soyad</label>
                    <input name="full_name" class="form-control" value="{{ old('full_name', $employee->full_name) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cinsiyet</label>
                    <select name="gender" class="form-select" required>
                        <option value="male" @selected(old('gender', $employee->gender?->value)==='male')>Erkek</option>
                        <option value="female" @selected(old('gender', $employee->gender?->value)==='female')>Kadın</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Durum</label>
                    <select name="status" class="form-select">
                        @foreach(EmployeeStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(old('status', $employee->status?->value)===$status->value)>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Görev</label>
                    <input name="job_title" class="form-control" value="{{ old('job_title', $employee->job_title) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telefon</label>
                    <input name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">E-posta</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Notlar</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $employee->notes) }}</textarea>
                </div>
                <div class="col-12 d-flex gap-2 pt-2">
                    <button class="btn btn-lj-primary">Güncelle</button>
                    <a href="{{ route('employees.index') }}" class="btn btn-lj-ghost">İptal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
