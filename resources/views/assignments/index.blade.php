@extends('layouts.app')
@php use App\Support\PlacementRules; @endphp
@section('breadcrumb')
    <li><a href="{{ route('panel.index') }}">Anasayfa</a></li>
    <li class="active">Yerleştirme</li>
@endsection
@section('header')
    <h1 class="lj-page-title">Personel Yerleştirme</h1>
@endsection
@section('subtitle')
    Tek tek veya toplu yerleştirme — kızlar bloğuna yalnızca kadın; ana blokta boş odalara her iki cinsiyet de yerleşebilir
@endsection
@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="lj-card h-100">
            <div class="lj-card-header">
                <h2><i class="bi bi-person-dash"></i> Boş Personeller</h2>
                <span class="lj-badge lj-badge-neutral">{{ $unassignedEmployees->count() }}</span>
            </div>
            <div class="lj-card-body">
                @can('placements.bulk')
                <div class="d-flex flex-wrap gap-2 mb-3 pb-3 border-bottom">
                    <button type="button" class="btn btn-sm btn-lj-ghost" onclick="toggleAllUnassigned(true)">Tümünü Seç</button>
                    <button type="button" class="btn btn-sm btn-lj-ghost" onclick="toggleAllUnassigned(false)">Seçimi Temizle</button>
                    <button type="button" class="btn btn-sm btn-lj-primary lj-3d-btn" onclick="bulkAssignAuto()">
                        <i class="bi bi-people-fill"></i> Toplu Yerleştir (<span id="bulkAssignCount">0</span>)
                    </button>
                    <span class="small text-muted align-self-center">Cinsiyete ve blok kurallarına göre otomatik yerleşir</span>
                </div>
                @endcan
                <div class="lj-search mb-3">
                    <i class="bi bi-search"></i>
                    <input type="search" id="employeeSearch" class="form-control form-control-sm" placeholder="Personel ara...">
                </div>
                <div id="unassignedList" style="max-height:520px;overflow-y:auto">
                @forelse($unassignedEmployees as $employee)
                    <div class="lj-person-row person-row d-flex align-items-center gap-2"
                         data-name="{{ mb_strtolower($employee->full_name) }}"
                         data-gender="{{ $employee->gender?->value }}">
                        @can('placements.bulk')
                        <input type="checkbox" class="form-check-input bulk-unassigned-cb flex-shrink-0"
                               value="{{ $employee->id }}" onchange="updateBulkCounts()">
                        @endcan
                        <div class="flex-grow-1">
                            <strong>{{ $employee->full_name }}</strong>
                            <div class="text-muted small">
                                <span class="lj-badge lj-badge-{{ $employee->gender?->value === 'male' ? 'erkek' : 'kadin' }}">{{ $employee->gender?->label() ?? '—' }}</span>
                                @if($employee->department) · {{ $employee->department->name }} @endif
                            </div>
                        </div>
                        @canany(['placements.assign', 'rooms.edit'])
                        <button type="button" class="btn btn-lj-primary btn-sm flex-shrink-0 open-room-modal-btn"
                            data-employee-id="{{ $employee->id }}"
                            data-employee-name="{{ e($employee->full_name) }}"
                            data-employee-gender="{{ $employee->gender?->value }}">
                            Odaya Ata
                        </button>
                        @endcanany
                    </div>
                @empty
                    <div class="lj-empty py-4"><i class="bi bi-check-circle"></i><p class="mb-0">Tüm personeller yerleşmiş</p></div>
                @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="lj-card h-100">
            <div class="lj-card-header">
                <h2><i class="bi bi-door-open"></i> Odalar</h2>
                <span class="lj-badge lj-badge-neutral">{{ $rooms->count() }}</span>
            </div>
            <div class="lj-card-body">
                @can('placements.bulk')
                <div class="d-flex flex-wrap gap-2 mb-3 pb-3 border-bottom">
                    <button type="button" class="btn btn-sm btn-lj-ghost" onclick="toggleAllPlaced(true)">Tümünü Seç</button>
                    <button type="button" class="btn btn-sm btn-lj-ghost" onclick="toggleAllPlaced(false)">Seçimi Temizle</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="bulkRemoveSelected()">
                        <i class="bi bi-person-x"></i> Toplu Çıkar (<span id="bulkRemoveCount">0</span>)
                    </button>
                </div>
                @endcan
                <div class="lj-search mb-3">
                    <i class="bi bi-search"></i>
                    <input type="search" id="roomSearch" class="form-control form-control-sm" placeholder="Oda veya kat ara...">
                </div>
                <div id="roomList" style="max-height:520px;overflow-y:auto">
                @foreach($rooms as $room)
                    @php $occupied = $room->occupancy ?? $room->activePlacements->count(); @endphp
                    <div class="lj-room-block room-row" data-search="{{ mb_strtolower(($room->floor?->block?->name ?? '').' '.($room->floor?->name ?? '').' '.$room->room_number) }}">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <strong>{{ $room->displayName() }}</strong>
                            <span class="lj-badge lj-badge-{{ $room->gender?->value === 'male' ? 'erkek' : 'kadin' }}">{{ $room->gender?->label() }}</span>
                            <span class="lj-badge lj-badge-neutral">{{ $occupied }}/{{ $room->capacity }}</span>
                        </div>
                        @forelse($room->activePlacements as $placement)
                            <div class="d-flex justify-content-between align-items-center py-1 border-top gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    @can('placements.bulk')
                                    <input type="checkbox" class="form-check-input bulk-placed-cb flex-shrink-0"
                                           value="{{ $placement->employee_id }}" onchange="updateBulkCounts()">
                                    @endcan
                                    <span class="small"><i class="bi bi-person me-1"></i>{{ $placement->employee->full_name }}</span>
                                </div>
                                @can('placements.remove')
                                <button type="button" class="btn btn-outline-danger btn-sm flex-shrink-0" onclick="removeEmployee({{ $placement->employee_id }})">Çıkar</button>
                                @endcan
                            </div>
                        @empty
                            <div class="text-muted small">Boş oda</div>
                        @endforelse
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div id="roomModal" class="lj-assign-modal">
    <div class="lj-card mx-auto" style="max-width:640px;margin-top:2rem">
        <div class="lj-card-header">
            <h2 class="h6 mb-0 fw-semibold">Oda Seç — <span id="selectedEmployeeName"></span></h2>
            <button type="button" class="btn-close" onclick="closeRoomModal()"></button>
        </div>
        <div class="lj-card-body">
            <div class="lj-search mb-3">
                <i class="bi bi-search"></i>
                <input type="search" id="modalRoomSearch" class="form-control form-control-sm" placeholder="Oda ara...">
            </div>
            <p id="noRoomMsg" class="text-muted small mb-2 d-none">Bu personel için uygun boş oda bulunamadı.</p>
            <div id="modalRoomList" style="max-height:400px;overflow-y:auto">
            @foreach($rooms as $room)
                @php
                    $occupied = $room->occupancy ?? $room->activePlacements->count();
                    $free = max(0, $room->capacity - $occupied);
                    $allowedGenders = PlacementRules::allowedEmployeeGendersForRoom($room);
                @endphp
                <div class="lj-room-block modal-room-item"
                     data-allowed-genders='@json($allowedGenders)'
                     data-free="{{ $free }}"
                     data-search="{{ mb_strtolower(($room->floor?->block?->name ?? '').' '.($room->floor?->name ?? '').' '.$room->room_number) }}"
                     data-room-id="{{ $room->id }}">
                    <strong>{{ $room->displayName() }}</strong>
                    <span class="lj-badge lj-badge-neutral ms-1">{{ $occupied }}/{{ $room->capacity }} ({{ $free }} boş)</span>
                    @canany(['placements.assign', 'rooms.edit'])
                    <button type="button" class="btn btn-lj-primary btn-sm mt-2 assign-room-btn" data-room-id="{{ $room->id }}" @disabled($free <= 0)>Bu Odaya Ata</button>
                    @endcanany
                </div>
            @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.LojmanPlacement = {
    csrf: document.querySelector('meta[name="csrf-token"]')?.content,
    store: @json(route('assignments.store')),
    remove: @json(route('assignments.remove-by-employee')),
    bulkAssign: @json(route('assignments.bulk-assign')),
    bulkRemove: @json(route('assignments.bulk-remove')),
};

let selectedEmployeeId = null;
let selectedEmployeeGender = null;

document.addEventListener('click', e => {
    const btn = e.target.closest('.open-room-modal-btn');
    if (!btn) return;
    openRoomModal(
        parseInt(btn.dataset.employeeId),
        btn.dataset.employeeName || '',
        btn.dataset.employeeGender || null,
    );
});

document.getElementById('employeeSearch')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.person-row').forEach(el => {
        el.style.display = el.dataset.name.includes(q) ? '' : 'none';
    });
});

document.getElementById('roomSearch')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.room-row').forEach(el => {
        el.style.display = el.dataset.search.includes(q) ? '' : 'none';
    });
});

function getSelectedUnassignedIds() {
    return [...document.querySelectorAll('.bulk-unassigned-cb:checked')].map(cb => parseInt(cb.value));
}

function getSelectedPlacedIds() {
    return [...document.querySelectorAll('.bulk-placed-cb:checked')].map(cb => parseInt(cb.value));
}

function updateBulkCounts() {
    const assignCount = getSelectedUnassignedIds().length;
    const removeCount = getSelectedPlacedIds().length;
    const assignEl = document.getElementById('bulkAssignCount');
    const removeEl = document.getElementById('bulkRemoveCount');
    if (assignEl) assignEl.textContent = assignCount;
    if (removeEl) removeEl.textContent = removeCount;
}

function toggleAllUnassigned(checked) {
    document.querySelectorAll('.person-row').forEach(row => {
        if (row.style.display === 'none') return;
        const cb = row.querySelector('.bulk-unassigned-cb');
        if (cb) cb.checked = checked;
    });
    updateBulkCounts();
}

function toggleAllPlaced(checked) {
    document.querySelectorAll('.room-row').forEach(row => {
        if (row.style.display === 'none') return;
        row.querySelectorAll('.bulk-placed-cb').forEach(cb => { cb.checked = checked; });
    });
    updateBulkCounts();
}

function openRoomModal(id, name, gender) {
    if (!gender) {
        alert('Bu personelin cinsiyet bilgisi tanımlı değil. Önce personel kaydını düzenleyin.');
        return;
    }
    selectedEmployeeId = id;
    selectedEmployeeGender = gender;
    document.getElementById('selectedEmployeeName').textContent = name;
    document.getElementById('roomModal').classList.add('show');
    document.getElementById('modalRoomSearch').value = '';
    filterModalRooms();
}

function closeRoomModal() {
    document.getElementById('roomModal').classList.remove('show');
    selectedEmployeeId = null;
    selectedEmployeeGender = null;
}

document.getElementById('roomModal')?.addEventListener('click', e => {
    if (e.target.id === 'roomModal') closeRoomModal();
});

document.getElementById('modalRoomSearch')?.addEventListener('input', filterModalRooms);

document.getElementById('modalRoomList')?.addEventListener('click', e => {
    const btn = e.target.closest('.assign-room-btn');
    if (!btn || btn.disabled) return;
    assignRoom(parseInt(btn.dataset.roomId));
});

function filterModalRooms() {
    const q = (document.getElementById('modalRoomSearch')?.value || '').toLowerCase();
    let visibleCount = 0;
    document.querySelectorAll('.modal-room-item').forEach(item => {
        let allowedGenders = [];
        try { allowedGenders = JSON.parse(item.dataset.allowedGenders || '[]'); } catch (e) { allowedGenders = []; }
        const visible = selectedEmployeeGender
            && allowedGenders.includes(selectedEmployeeGender)
            && parseInt(item.dataset.free) > 0
            && item.dataset.search.includes(q);
        item.style.display = visible ? '' : 'none';
        if (visible) visibleCount++;
    });
    document.getElementById('noRoomMsg')?.classList.toggle('d-none', visibleCount > 0);
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
    return 'İşlem başarısız.';
}

async function assignRoom(roomId) {
    if (!selectedEmployeeId) return;
    try {
        const response = await fetch(window.LojmanPlacement.store, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': window.LojmanPlacement.csrf },
            body: JSON.stringify({ employee_id: selectedEmployeeId, room_id: roomId }),
        });
        const data = await parseJsonResponse(response);
        if (response.ok && data.status === 'success') window.location.reload();
        else alert(formatError(data));
    } catch (e) { alert(e.message || 'Bağlantı hatası'); }
}

async function bulkAssignAuto() {
    const ids = getSelectedUnassignedIds();
    if (!ids.length) { alert('Lütfen en az bir personel seçin.'); return; }
    if (!confirm(ids.length + ' personel uygun boş odalara otomatik yerleştirilecek. Devam edilsin mi?')) return;
    try {
        const response = await fetch(window.LojmanPlacement.bulkAssign, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': window.LojmanPlacement.csrf },
            body: JSON.stringify({ employee_ids: ids }),
        });
        const data = await parseJsonResponse(response);
        if (response.ok && data.status === 'success') {
            if (data.failed?.length) alert(data.message + '\n\nBaşarısız:\n' + data.failed.map(f => '- ID '+f.employee_id+': '+f.message).join('\n'));
            window.location.reload();
        } else alert(formatError(data));
    } catch (e) { alert(e.message || 'Bağlantı hatası'); }
}

async function removeEmployee(employeeId) {
    if (!confirm('Odadan çıkarılsın mı?')) return;
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

async function bulkRemoveSelected() {
    const ids = getSelectedPlacedIds();
    if (!ids.length) { alert('Lütfen en az bir personel seçin.'); return; }
    if (!confirm(ids.length + ' personel odadan çıkarılsın mı?')) return;
    try {
        const response = await fetch(window.LojmanPlacement.bulkRemove, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': window.LojmanPlacement.csrf },
            body: JSON.stringify({ employee_ids: ids }),
        });
        const data = await parseJsonResponse(response);
        if (response.ok && data.status === 'success') {
            if (data.failed?.length) alert(data.message + '\n\nBaşarısız:\n' + data.failed.map(f => '- ID '+f.employee_id+': '+f.message).join('\n'));
            window.location.reload();
        } else alert(formatError(data));
    } catch (e) { alert(e.message || 'Bağlantı hatası'); }
}

updateBulkCounts();
</script>
@endpush
