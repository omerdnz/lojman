@extends('layouts.app')
@section('breadcrumb')
    <li><a href="{{ route('panel.index') }}">Anasayfa</a></li>
    <li class="active">Bildirimler</li>
@endsection
@section('header')
    <h1 class="lj-page-title">Bildirimler</h1>
@endsection
@section('actions')
    <form action="{{ route('notifications.read-all') }}" method="POST">
        @csrf
        <button class="btn btn-lj-ghost btn-sm">Tümünü Okundu İşaretle</button>
    </form>
@endsection
@section('content')
<div class="lj-card">
    <div class="lj-card-body p-0">
        @forelse($notifications as $notification)
            <div class="d-flex justify-content-between align-items-start p-3 border-bottom {{ $notification->read_at ? '' : 'bg-light' }}">
                <div>
                    <strong>{{ $notification->title }}</strong>
                    <div class="small text-muted">{{ $notification->created_at?->diffForHumans() }}</div>
                    <p class="mb-0 small">{{ $notification->message }}</p>
                </div>
                @unless($notification->read_at)
                <form action="{{ route('notifications.read', $notification) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-lj-ghost">Okundu</button>
                </form>
                @endunless
            </div>
        @empty
            <div class="text-center text-muted py-5">Bildirim yok</div>
        @endforelse
    </div>
</div>
<div class="mt-3">{{ $notifications->links() }}</div>
@endsection
