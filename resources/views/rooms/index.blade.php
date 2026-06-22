@extends('layouts.app')
@php
    use App\Support\PlacementRules;
    $canAssignRoom = auth()->user()->can('placements.assign') || auth()->user()->can('rooms.edit');
    $canRemoveRoom = auth()->user()->can('placements.remove') || auth()->user()->can('rooms.edit');
@endphp
@section('breadcrumb')
    <li><a href="{{ route('panel.index') }}">Anasayfa</a></li>
    <li class="active">Odalar</li>
@endsection
@section('header')
    <h1 class="lj-page-title">Odalar</h1>
@endsection
@section('subtitle')
    {{ $rooms->total() }} oda — tek tek personel ekleyip çıkarabilirsiniz. Kızlar bloğuna yalnızca kadın; ana blokta boş odalara her iki cinsiyet de yerleşebilir.
@endsection
@section('actions')
    @can('rooms.create')
    <a href="{{ route('rooms.create') }}" class="btn btn-lj-primary btn-sm lj-3d-btn"><i class="bi bi-plus-lg"></i> Yeni Oda</a>
    @endcan
@endsection
@section('content')
<div class="row g-3">
@foreach($rooms as $room)
    @php
        $free = max(0, $room->capacity - $room->occupancy);
        $pct = $room->capacity > 0 ? round($room->occupancy / $room->capacity * 100) : 0;
        $allowedGenders = PlacementRules::allowedEmployeeGendersForRoom($room);
    @endphp
    <div class="col-md-6 col-xl-4">
        <div class="lj-room-card lj-3d-card {{ $free <= 0 ? 'status-danger' : ($free <= 1 ? 'status-warning' : '') }}">
            <div class="lj-room-card-body">
                <div class="lj-room-meta">{{ $room->floor?->block?->name }} · {{ $room->floor?->name }}</div>
                <div class="lj-room-number">Oda {{ $room->room_number }}</div>
                <div class="d-flex gap-1 flex-wrap mb-2">
                    <span class="lj-badge lj-badge-neutral">{{ $room->occupancy }}/{{ $room->capacity }} dolu</span>
                    @if($room->gender)
                        <span class="lj-badge lj-badge-{{ $room->gender->value === 'male' ? 'erkek' : 'kadin' }}">{{ $room->gender->label() }}</span>
                    @endif
                    @if($room->status)
                        <span class="lj-badge lj-badge-neutral">{{ $room->status->label() }}</span>
                    @endif
                </div>
                <div class="lj-occupancy-bar mb-2"><div class="lj-occupancy-fill" style="width:{{ $pct }}%"></div></div>

                @foreach($room->activePlacements as $placement)
                    <div class="d-flex justify-content-between align-items-center py-1 border-top small">
                        <span><i class="bi bi-person me-1"></i>{{ $placement->employee?->full_name }}</span>
                        @if($canRemoveRoom)
                        <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" onclick="removeEmployee({{ $placement->employee_id }})" title="Odadan çıkar">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        @endif
                    </div>
                @endforeach

                <div class="mt-2 d-flex gap-1 flex-wrap">
                    @if($canAssignRoom && $free > 0)
                    <button type="button" class="btn btn-sm btn-lj-primary lj-3d-btn open-add-modal-btn"
                        data-room-id="{{ $room->id }}"
                        data-allowed-genders='@json($allowedGenders)'
                        data-room-label='@json($room->displayName())'>
                        <i class="bi bi-person-plus"></i> Personel Ekle
                    </button>
                    @elseif($canAssignRoom)
                    <span class="small text-muted">Kapasite dolu</span>
                    @endif
                    @can('rooms.edit')
                    <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-lj-ghost">Düzenle</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endforeach
</div>
<div class="mt-3">{{ $rooms->links() }}</div>

@if($canAssignRoom)
<div id="addEmployeeModal" class="lj-assign-modal">
    <div class="lj-card lj-3d-card mx-auto" style="max-width:560px;margin-top:2rem">
        <div class="lj-card-header">
            <h2 class="h6 mb-0 fw-semibold">Personel Seç — <span id="modalRoomLabel"></span></h2>
            <button type="button" class="btn-close" onclick="closeAddModal()"></button>
        </div>
        <div class="lj-card-body">
            <div class="lj-search mb-3">
                <i class="bi bi-search"></i>
                <input type="search" id="employeeModalSearch" class="form-control form-control-sm" placeholder="Personel ara...">
            </div>
            <p id="noEmployeeMsg" class="text-muted small mb-2 d-none">Bu oda için uygun atanmamış personel bulunamadı.</p>
            <div id="employeeModalList" style="max-height:400px;overflow-y:auto">
            @forelse($unassignedEmployees as $employee)
                <div class="lj-person-row modal-employee-item d-flex justify-content-between align-items-center py-2 border-bottom"
                     data-gender="{{ $employee->gender?->value }}"
                     data-name="{{ mb_strtolower($employee->full_name) }}"
                     data-id="{{ $employee->id }}">
                    <div>
                        <strong>{{ $employee->full_name }}</strong>
                        <div class="small text-muted">
                            <span class="lj-badge lj-badge-{{ $employee->gender?->value === 'male' ? 'erkek' : 'kadin' }}">{{ $employee->gender?->label() ?? '—' }}</span>
                            @if($employee->department) · {{ $employee->department->name }} @endif
                        </div>
                    </div>
                    <button type="button" class="btn btn-lj-primary btn-sm assign-employee-btn" data-employee-id="{{ $employee->id }}">Ekle</button>
                </div>
            @empty
                <p class="text-muted small mb-0">Atanmamış personel kalmadı.</p>
            @endforelse
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
@if($canAssignRoom || $canRemoveRoom)
<script>
window.LojmanPlacement = window.LojmanPlacement || {
    csrf: document.querySelector('meta[name="csrf-token"]')?.content,
    store: @json(route('assignments.store')),
    remove: @json(route('assignments.remove-by-employee')),
};

let selectedRoomId = null;
let selectedAllowedGenders = [];

function parseDatasetJson(value, fallback = null) {
    if (value == null || value === '') return fallback;
    try { return JSON.parse(value); }
    catch { return value; }
}

document.addEventListener('click', e => {
    const btn = e.target.closest('.open-add-modal-btn');
    if (!btn) return;
    openAddModal(
        parseInt(btn.dataset.roomId),
        parseDatasetJson(btn.dataset.allowedGenders, []),
        parseDatasetJson(btn.dataset.roomLabel, ''),
    );
});

function openAddModal(roomId, allowedGenders, label) {
    selectedRoomId = roomId;
    selectedAllowedGenders = Array.isArray(allowedGenders) ? allowedGenders : [];
    document.getElementById('modalRoomLabel').textContent = label;
    document.getElementById('addEmployeeModal')?.classList.add('show');
    const search = document.getElementById('employeeModalSearch');
    if (search) search.value = '';
    filterModalEmployees();
}

function closeAddModal() {
    document.getElementById('addEmployeeModal')?.classList.remove('show');
    selectedRoomId = null;
    selectedAllowedGenders = [];
}

document.getElementById('addEmployeeModal')?.addEventListener('click', e => {
    if (e.target.id === 'addEmployeeModal') closeAddModal();
});
document.getElementById('employeeModalSearch')?.addEventListener('input', filterModalEmployees);

document.getElementById('employeeModalList')?.addEventListener('click', e => {
    const btn = e.target.closest('.assign-employee-btn');
    if (!btn) return;
    assignToRoom(parseInt(btn.dataset.employeeId));
});

function filterModalEmployees() {
    const q = (document.getElementById('employeeModalSearch')?.value || '').toLowerCase();
    let visibleCount = 0;
    document.querySelectorAll('.modal-employee-item').forEach(item => {
        const employeeGender = item.dataset.gender;
        const visible = employeeGender
            && selectedAllowedGenders.includes(employeeGender)
            && (item.dataset.name || '').includes(q);
        item.style.display = visible ? '' : 'none';
        if (visible) visibleCount++;
    });
    document.getElementById('noEmployeeMsg')?.classList.toggle('d-none', visibleCount > 0);
}

async function parseJsonResponse(response) {
    const text = await response.text();
    try { return JSON.parse(text); }
    catch {
        throw new Error(response.status === 403 ? 'Bu işlem için yetkiniz yok.' : 'Sunucu hatası (' + response.status + '). Sayfayı yenileyip tekrar deneyin.');
    }
}

function formatError(data) {
    if (data?.message) return data.message;
    if (data?.errors) return Object.values(data.errors).flat().join('\n');
    return 'Yerleştirme yapılamadı.';
}

async function assignToRoom(employeeId) {
    if (!selectedRoomId) return;
    try {
        const response = await fetch(window.LojmanPlacement.store, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': window.LojmanPlacement.csrf },
            body: JSON.stringify({ employee_id: employeeId, room_id: selectedRoomId }),
        });
        const data = await parseJsonResponse(response);
        if (response.ok && data.status === 'success') window.location.reload();
        else alert(formatError(data));
    } catch (e) { alert(e.message || 'Bağlantı hatası'); }
}

async function removeEmployee(employeeId) {
    if (!confirm('Personel bu odadan çıkarılsın mı?')) return;
    try {
        const response = await fetch(window.LojmanPlacement.remove, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': window.LojmanPlacement.csrf },
            body: JSON.stringify({ employee_id: employeeId }),
        });
        const data = await parseJsonResponse(response);
        if (response.ok && data.status === 'success') window.location.reload();
        else alert(formatError(data));
    } catch (e) { alert(e.message || 'Bağlantı hatası'); }
}
</script>
@endif
@endpush
