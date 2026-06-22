@extends('layouts.app')
@section('breadcrumb')
    <li><a href="{{ route('panel.index') }}">Anasayfa</a></li>
    <li class="active">Personeller</li>
@endsection
@section('header')
    <h1 class="lj-page-title">Personeller</h1>
@endsection
@section('subtitle')
    {{ $employees->total() }} kayıt
@endsection
@section('actions')
    @can('employees.create')
    <a href="{{ route('employees.create') }}" class="btn btn-lj-primary btn-sm lj-3d-btn"><i class="bi bi-plus-lg"></i> Yeni Personel</a>
    @endcan
    @can('employees.import')
    <a href="{{ route('imports.index') }}" class="btn btn-lj-ghost btn-sm"><i class="bi bi-upload"></i> Excel İçe Aktar</a>
    @endcan
@endsection
@section('content')
<form method="GET" class="lj-filter-bar mb-3">
    <div class="lj-search">
        <i class="bi bi-search"></i>
        <input type="search" name="search" class="form-control" placeholder="Ad soyad ara..." value="{{ request('search') }}">
    </div>
    <select name="gender" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
        <option value="">Tüm cinsiyetler</option>
        <option value="male" @selected(request('gender')==='male')>Erkek</option>
        <option value="female" @selected(request('gender')==='female')>Kadın</option>
    </select>
    <select name="department_id" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
        <option value="">Tüm departmanlar</option>
        @foreach($departments as $dept)
            <option value="{{ $dept->id }}" @selected((int)request('department_id')===$dept->id)>{{ $dept->name }}</option>
        @endforeach
    </select>
</form>

<div class="lj-table-wrap">
    <div class="table-responsive">
        <table class="table lj-table mb-0">
            <thead>
                <tr>
                    <th>Sicil</th>
                    <th>Ad Soyad</th>
                    <th>Cinsiyet</th>
                    <th>Departman</th>
                    <th>Oda</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($employees as $employee)
                <tr>
                    <td><code>{{ $employee->personnel_number }}</code></td>
                    <td><a href="{{ route('employees.show', $employee) }}">{{ $employee->full_name }}</a></td>
                    <td>
                        @if($employee->gender)
                            <span class="lj-badge lj-badge-{{ $employee->gender->value === 'male' ? 'erkek' : 'kadin' }}">{{ $employee->gender->label() }}</span>
                        @else — @endif
                    </td>
                    <td>{{ $employee->department?->name ?? '—' }}</td>
                    <td>
                        @if($employee->activePlacement?->room)
                            {{ $employee->activePlacement->room->displayName() }}
                        @else
                            <span class="text-muted">Atanmamış</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @can('employees.edit')
                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-lj-ghost"><i class="bi bi-pencil"></i></a>
                        @endcan
                        @can('employees.delete')
                        <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline" onsubmit="return confirm('Silinsin mi?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Kayıt bulunamadı.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $employees->links() }}</div>
@endsection
