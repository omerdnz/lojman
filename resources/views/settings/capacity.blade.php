@extends('layouts.app')
@section('breadcrumb')
    <li><a href="{{ route('panel.index') }}">Anasayfa</a></li>
    <li class="active">Kapasite Ayarları</li>
@endsection
@section('header')
    <h1 class="lj-page-title">Kapasite Ayarları</h1>
@endsection
@section('content')
<div class="lj-card" style="max-width:520px">
    <div class="lj-card-body">
        <p class="text-muted">Kapasitesi 0 olan odalar import sırasında bu değerle kaydedilir.</p>
        <form method="POST" action="{{ route('settings.capacity.update') }}" class="mt-3">
            @csrf
            @method('PUT')
            <label class="form-label">Varsayılan Oda Kapasitesi</label>
            <input type="number" name="default_room_capacity" class="form-control mb-3" min="1" max="20" value="{{ old('default_room_capacity', $defaultCapacity) }}" required>
            @error('default_room_capacity')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
            <button class="btn btn-lj-primary">Kaydet</button>
        </form>
    </div>
</div>
@endsection
