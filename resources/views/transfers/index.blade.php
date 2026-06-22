@extends('layouts.app')
@section('breadcrumb')
    <li><a href="{{ route('panel.index') }}">Anasayfa</a></li>
    <li class="active">Transfer Geçmişi</li>
@endsection
@section('header')
    <h1 class="lj-page-title">Yerleşim Geçmişi</h1>
@endsection
@section('content')
<form method="GET" class="lj-filter-bar mb-3">
    <div class="lj-search">
        <i class="bi bi-search"></i>
        <input type="search" name="search" class="form-control" placeholder="Personel ara..." value="{{ request('search') }}">
    </div>
</form>
<div class="lj-table-wrap">
    <table class="table lj-table mb-0">
        <thead>
            <tr><th>Tarih</th><th>Personel</th><th>İşlem</th><th>Kaynak</th><th>Hedef</th><th>Kullanıcı</th></tr>
        </thead>
        <tbody>
        @forelse($histories as $history)
            <tr>
                <td>{{ $history->created_at?->format('d.m.Y H:i') }}</td>
                <td>{{ $history->employee?->full_name }}</td>
                <td>{{ $history->action?->label() }}</td>
                <td>{{ $history->fromRoom?->displayName() ?? '—' }}</td>
                <td>{{ $history->toRoom?->displayName() ?? '—' }}</td>
                <td>{{ $history->performedBy?->username ?? '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-4">Kayıt yok</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $histories->links() }}</div>
@endsection
