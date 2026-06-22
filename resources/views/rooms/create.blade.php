@extends('layouts.app')
@php use App\Enums\RoomStatus; @endphp
@section('breadcrumb')
    <li><a href="{{ route('panel.index') }}">Anasayfa</a></li>
    <li><a href="{{ route('rooms.index') }}">Odalar</a></li>
    <li class="active">Yeni Oda</li>
@endsection
@section('header')
    <h1 class="lj-page-title">Yeni Oda</h1>
@endsection
@section('content')
<div class="lj-card lj-3d-card lj-form-card">
    <div class="lj-card-body">
        <form method="POST" action="{{ route('rooms.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kat</label>
                    <select name="floor_id" class="form-select" required>
                        <option value="">Seçin</option>
                        @foreach($floors as $floor)
                            <option value="{{ $floor->id }}" @selected(old('floor_id')==$floor->id)>
                                {{ $floor->block?->name }} / {{ $floor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Oda No</label>
                    <input name="room_number" class="form-control" value="{{ old('room_number') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kapasite</label>
                    <input type="number" name="capacity" class="form-control" value="{{ old('capacity', 4) }}" min="1" max="20" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cinsiyet</label>
                    <select name="gender" class="form-select" required>
                        <option value="male" @selected(old('gender')==='male')>Erkek</option>
                        <option value="female" @selected(old('gender')==='female')>Kadın</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Durum</label>
                    <select name="status" class="form-select">
                        @foreach(RoomStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(old('status', 'available')===$status->value)>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-lj-primary lj-3d-btn">Kaydet</button>
                    <a href="{{ route('rooms.index') }}" class="btn btn-lj-ghost">İptal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
