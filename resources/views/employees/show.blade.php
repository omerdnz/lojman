@extends('layouts.app')
@section('breadcrumb')
    <li><a href="{{ route('panel.index') }}">Anasayfa</a></li>
    <li><a href="{{ route('employees.index') }}">Personeller</a></li>
    <li class="active">{{ $employee->full_name }}</li>
@endsection
@section('header')
    <h1 class="lj-page-title">{{ $employee->full_name }}</h1>
@endsection
@section('subtitle')
    Sicil: {{ $employee->personnel_number }}
@endsection
@section('actions')
    @can('employees.edit')
    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-lj-primary btn-sm">Düzenle</a>
    @endcan
@endsection
@section('content')
<div class="row g-3">
    <div class="col-lg-5">
        <div class="lj-card">
            <div class="lj-card-header"><h2>Bilgiler</h2></div>
            <div class="lj-card-body">
                <dl class="row mb-0 small">
                    <dt class="col-4">Cinsiyet</dt><dd class="col-8">{{ $employee->gender?->label() ?? '—' }}</dd>
                    <dt class="col-4">Departman</dt><dd class="col-8">{{ $employee->department?->name ?? '—' }}</dd>
                    <dt class="col-4">Görev</dt><dd class="col-8">{{ $employee->job_title ?? '—' }}</dd>
                    <dt class="col-4">Telefon</dt><dd class="col-8">{{ $employee->phone ?? '—' }}</dd>
                    <dt class="col-4">E-posta</dt><dd class="col-8">{{ $employee->email ?? '—' }}</dd>
                    <dt class="col-4">Durum</dt><dd class="col-8">{{ $employee->status?->label() ?? '—' }}</dd>
                    <dt class="col-4">Oda</dt>
                    <dd class="col-8">
                        @if($employee->activePlacement?->room)
                            {{ $employee->activePlacement->room->displayName() }}
                        @else
                            <span class="text-muted">Atanmamış</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="lj-card">
            <div class="lj-card-header"><h2>Yerleşim Geçmişi</h2></div>
            <div class="lj-card-body p-0">
                <table class="table lj-table mb-0">
                    <thead><tr><th>Tarih</th><th>İşlem</th><th>Oda</th><th>Kullanıcı</th></tr></thead>
                    <tbody>
                    @forelse($employee->transferHistories->take(20) as $history)
                        <tr>
                            <td>{{ $history->created_at?->format('d.m.Y H:i') }}</td>
                            <td>{{ $history->action?->label() }}</td>
                            <td>
                                @if($history->toRoom)
                                    {{ $history->toRoom->displayName() }}
                                @elseif($history->fromRoom)
                                    {{ $history->fromRoom->displayName() }} (çıkış)
                                @else — @endif
                            </td>
                            <td>{{ $history->performedBy?->username ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-muted text-center py-3">Geçmiş kayıt yok</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
