@props([
    'selected' => [],
    'rolePresets' => [],
    'disabled' => false,
    'namePrefix' => 'permissions',
])

@php
    use App\Support\PermissionCatalog;
    $groups = PermissionCatalog::groups();
@endphp

<div class="lj-perm-matrix" data-perm-matrix {{ $attributes }}>
    @if(!empty($rolePresets))
    <div class="lj-perm-toolbar mb-3">
        <span class="text-muted small me-2"><i class="bi bi-magic"></i> Hızlı şablon:</span>
        @foreach($rolePresets as $roleKey => $preset)
            <button type="button" class="btn btn-sm btn-lj-ghost lj-3d-btn"
                    data-apply-role="{{ $roleKey }}"
                    data-role-perms="{{ json_encode($preset['permissions'] ?? []) }}">
                {{ $preset['label'] ?? $roleKey }}
            </button>
        @endforeach
        <button type="button" class="btn btn-sm btn-lj-ghost" data-perm-select-all>Tümünü Seç</button>
        <button type="button" class="btn btn-sm btn-lj-ghost" data-perm-clear-all>Temizle</button>
    </div>
    @endif

    <div class="row g-3">
        @foreach($groups as $group)
        <div class="col-md-6 col-xl-4">
            <div class="lj-perm-group-card lj-3d-card">
                <div class="lj-perm-group-header">
                    <i class="bi {{ $group['icon'] }}"></i>
                    <span>{{ $group['label'] }}</span>
                </div>
                <div class="lj-perm-group-body">
                    @foreach($group['permissions'] as $permKey => $permMeta)
                    <label class="lj-perm-item {{ in_array($permKey, $selected, true) ? 'is-checked' : '' }}">
                        <input type="checkbox"
                               name="{{ $namePrefix }}[]"
                               value="{{ $permKey }}"
                               class="lj-perm-checkbox"
                               @checked(in_array($permKey, $selected, true))
                               @disabled($disabled)>
                        <span class="lj-perm-item-content">
                            <span class="lj-perm-type {{ PermissionCatalog::typeBadgeClass($permMeta['type']) }}">
                                {{ PermissionCatalog::typeLabel($permMeta['type']) }}
                            </span>
                            <strong>{{ $permMeta['label'] }}</strong>
                            <code class="lj-perm-code">{{ $permKey }}</code>
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@once
@push('scripts')
<script>
document.querySelectorAll('[data-perm-matrix]').forEach(matrix => {
    const syncCheckedClass = () => {
        matrix.querySelectorAll('.lj-perm-item').forEach(item => {
            const cb = item.querySelector('input[type=checkbox]');
            item.classList.toggle('is-checked', cb?.checked);
        });
    };

    matrix.addEventListener('change', e => {
        if (e.target.matches('.lj-perm-checkbox')) syncCheckedClass();
    });

    matrix.querySelector('[data-perm-select-all]')?.addEventListener('click', () => {
        matrix.querySelectorAll('.lj-perm-checkbox:not(:disabled)').forEach(cb => { cb.checked = true; });
        syncCheckedClass();
    });

    matrix.querySelector('[data-perm-clear-all]')?.addEventListener('click', () => {
        matrix.querySelectorAll('.lj-perm-checkbox:not(:disabled)').forEach(cb => { cb.checked = false; });
        syncCheckedClass();
    });

    matrix.querySelectorAll('[data-apply-role]').forEach(btn => {
        btn.addEventListener('click', () => {
            const perms = JSON.parse(btn.dataset.rolePerms || '[]');
            matrix.querySelectorAll('.lj-perm-checkbox').forEach(cb => {
                cb.checked = perms.includes(cb.value);
            });
            syncCheckedClass();
        });
    });

    syncCheckedClass();
});
</script>
@endpush
@endonce
