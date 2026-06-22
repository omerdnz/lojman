<?php

namespace App\Support;

class PermissionCatalog
{
    /**
     * @return array<string, array{label: string, icon: string, permissions: array<string, array{label: string, type: string, hint?: string}>}>
     */
    public static function groups(): array
    {
        return [
            'dashboard' => [
                'label' => 'Dashboard',
                'icon' => 'bi-speedometer2',
                'permissions' => [
                    'dashboard.view' => ['label' => 'Dashboard Görüntüleme', 'type' => 'view'],
                ],
            ],
            'employees' => [
                'label' => 'Personeller',
                'icon' => 'bi-people',
                'permissions' => [
                    'employees.view' => ['label' => 'Listeleme & Detay', 'type' => 'view'],
                    'employees.create' => ['label' => 'Yeni Personel Ekleme', 'type' => 'create'],
                    'employees.edit' => ['label' => 'Personel Düzenleme', 'type' => 'edit'],
                    'employees.delete' => ['label' => 'Personel Silme', 'type' => 'delete'],
                    'employees.import' => ['label' => 'Excel İçe Aktarma', 'type' => 'import'],
                ],
            ],
            'rooms' => [
                'label' => 'Odalar',
                'icon' => 'bi-door-open',
                'permissions' => [
                    'rooms.view' => ['label' => 'Oda Listesi & Doluluk', 'type' => 'view'],
                    'rooms.create' => ['label' => 'Yeni Oda Ekleme', 'type' => 'create'],
                    'rooms.edit' => ['label' => 'Oda Düzenleme', 'type' => 'edit'],
                    'rooms.delete' => ['label' => 'Oda Silme', 'type' => 'delete'],
                ],
            ],
            'placements' => [
                'label' => 'Yerleştirme',
                'icon' => 'bi-pin-map',
                'permissions' => [
                    'placements.view' => ['label' => 'Yerleştirme Ekranı', 'type' => 'view'],
                    'placements.assign' => ['label' => 'Odaya Personel Atama', 'type' => 'create'],
                    'placements.remove' => ['label' => 'Odadan Personel Çıkarma', 'type' => 'delete'],
                    'placements.transfer' => ['label' => 'Oda Değiştirme (Transfer)', 'type' => 'edit'],
                    'placements.bulk' => ['label' => 'Toplu Yerleştirme & Çıkarma', 'type' => 'import'],
                ],
            ],
            'transfers' => [
                'label' => 'Geçmiş',
                'icon' => 'bi-clock-history',
                'permissions' => [
                    'transfers.view' => ['label' => 'Yerleşim Geçmişi', 'type' => 'view'],
                ],
            ],
            'reports' => [
                'label' => 'Raporlar',
                'icon' => 'bi-file-earmark-bar-graph',
                'permissions' => [
                    'reports.view' => ['label' => 'Rapor Görüntüleme', 'type' => 'view'],
                    'reports.export' => ['label' => 'Excel / PDF İndirme', 'type' => 'export'],
                ],
            ],
            'structure' => [
                'label' => 'Yapı & Birimler',
                'icon' => 'bi-buildings',
                'permissions' => [
                    'departments.manage' => ['label' => 'Departman Düzenleme', 'type' => 'edit'],
                    'blocks.manage' => ['label' => 'Blok Düzenleme', 'type' => 'edit'],
                    'floors.manage' => ['label' => 'Kat Düzenleme', 'type' => 'edit'],
                ],
            ],
            'documents' => [
                'label' => 'Evraklar',
                'icon' => 'bi-folder2-open',
                'permissions' => [
                    'documents.view' => ['label' => 'Evrak Görüntüleme', 'type' => 'view'],
                    'documents.manage' => ['label' => 'Evrak Yükleme / Düzenleme', 'type' => 'edit'],
                ],
            ],
            'maintenance' => [
                'label' => 'Arıza & Bakım',
                'icon' => 'bi-tools',
                'permissions' => [
                    'maintenance.view' => ['label' => 'Kayıt Görüntüleme', 'type' => 'view'],
                    'maintenance.manage' => ['label' => 'Kayıt Oluşturma / Düzenleme', 'type' => 'edit'],
                ],
            ],
            'users' => [
                'label' => 'Kullanıcılar',
                'icon' => 'bi-person-gear',
                'permissions' => [
                    'users.view' => ['label' => 'Kullanıcı Listesi', 'type' => 'view'],
                    'users.create' => ['label' => 'Kullanıcı Ekleme', 'type' => 'create'],
                    'users.edit' => ['label' => 'Kullanıcı Düzenleme', 'type' => 'edit'],
                    'users.delete' => ['label' => 'Kullanıcı Silme', 'type' => 'delete'],
                    'users.permissions' => ['label' => 'Yetki Atama (Tikleme)', 'type' => 'admin'],
                ],
            ],
            'system' => [
                'label' => 'Sistem',
                'icon' => 'bi-gear',
                'permissions' => [
                    'permissions.manage' => ['label' => 'Rol Şablonları (IK / Lojman)', 'type' => 'admin'],
                    'settings.manage' => ['label' => 'Kapasite & Sistem Ayarları', 'type' => 'admin'],
                    'notifications.view' => ['label' => 'Bildirimler', 'type' => 'view'],
                ],
            ],
        ];
    }

    /** @return list<string> */
    public static function all(): array
    {
        $names = [];
        foreach (self::groups() as $group) {
            foreach ($group['permissions'] as $key => $_) {
                $names[] = $key;
            }
        }

        return $names;
    }

    /** @return list<string> */
    public static function assignableToUsers(): array
    {
        return array_values(array_filter(
            self::all(),
            fn (string $p) => ! in_array($p, ['permissions.manage'], true)
                || true // permissions.manage only for super_admin via gate
        ));
    }

    /** Rol şablonu sayfasında atanabilir (sistem admin izinleri hariç). */
    /** @return list<string> */
    public static function assignableToRoles(): array
    {
        return array_values(array_filter(
            self::all(),
            fn (string $p) => ! in_array($p, ['permissions.manage', 'users.permissions', 'users.delete', 'users.create', 'users.edit', 'users.view'], true)
        ));
    }

    public static function label(string $permission): string
    {
        foreach (self::groups() as $group) {
            if (isset($group['permissions'][$permission])) {
                return $group['permissions'][$permission]['label'];
            }
        }

        return $permission;
    }

    public static function typeBadgeClass(string $type): string
    {
        return match ($type) {
            'view' => 'perm-view',
            'create' => 'perm-create',
            'edit' => 'perm-edit',
            'delete' => 'perm-delete',
            'import', 'export' => 'perm-export',
            'admin' => 'perm-admin',
            default => 'perm-view',
        };
    }

    public static function typeLabel(string $type): string
    {
        return match ($type) {
            'view' => 'Görüntüle',
            'create' => 'Ekle',
            'edit' => 'Düzenle',
            'delete' => 'Sil',
            'import' => 'İçe Aktar',
            'export' => 'Dışa Aktar',
            'admin' => 'Yönetim',
            default => $type,
        };
    }

    /** Eski izin adları → yeni granüler izinler */
    public static function legacyMap(): array
    {
        return [
            'employees.manage' => ['employees.create', 'employees.edit'],
            'rooms.manage' => ['rooms.view', 'rooms.create', 'rooms.edit', 'rooms.delete'],
            'users.manage' => ['users.view', 'users.create', 'users.edit', 'users.delete', 'users.permissions'],
            'placements.assign' => ['placements.view', 'placements.assign', 'placements.remove', 'placements.transfer'],
        ];
    }
}
