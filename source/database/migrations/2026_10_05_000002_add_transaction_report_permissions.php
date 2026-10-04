<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    private array $newPermissions = [
        'akses_laporan_penjualan' => 'Mengakses laporan penjualan dan transaksi tindakan MCU.',
        'akses_laporan_insentif' => 'Mengakses laporan insentif pelayanan.',
    ];

    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $permissionsTable = $tableNames['permissions'];
        $rolesTable = $tableNames['roles'];
        $rolePermissionsTable = $tableNames['role_has_permissions'];

        foreach ($this->newPermissions as $name => $description) {
            DB::table($permissionsTable)->insertOrIgnore([
                'name' => $name,
                'guard_name' => 'web',
                'group' => 'Laporan',
                'description' => $description,
                'urutan' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $roleId = DB::table($rolesTable)
            ->where('name', 'admin_mcu')
            ->where('guard_name', 'web')
            ->value('id');

        if ($roleId !== null) {
            $permissionIds = DB::table($permissionsTable)
                ->where('guard_name', 'web')
                ->whereIn('name', array_merge(array_keys($this->newPermissions), ['akses_berkas_tindakan_kwitansi']))
                ->pluck('id');

            foreach ($permissionIds as $permissionId) {
                DB::table($rolePermissionsTable)->insertOrIgnore([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                ]);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        $permissionsTable = $tableNames['permissions'];
        $rolesTable = $tableNames['roles'];
        $rolePermissionsTable = $tableNames['role_has_permissions'];
        $permissionNames = array_merge(array_keys($this->newPermissions), ['akses_berkas_tindakan_kwitansi']);

        $roleId = DB::table($rolesTable)
            ->where('name', 'admin_mcu')
            ->where('guard_name', 'web')
            ->value('id');

        if ($roleId !== null) {
            $permissionIds = DB::table($permissionsTable)
                ->where('guard_name', 'web')
                ->whereIn('name', $permissionNames)
                ->pluck('id');

            DB::table($rolePermissionsTable)
                ->where('role_id', $roleId)
                ->whereIn('permission_id', $permissionIds)
                ->delete();
        }

        DB::table($permissionsTable)
            ->where('guard_name', 'web')
            ->whereIn('name', array_keys($this->newPermissions))
            ->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};