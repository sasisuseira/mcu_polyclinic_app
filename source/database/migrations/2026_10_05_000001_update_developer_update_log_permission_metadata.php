<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        DB::table(config('permission.table_names.permissions', 'permissions'))
            ->where('name', 'akses_update_log')
            ->where('guard_name', 'web')
            ->update([
                'group' => 'Developer Area',
                'description' => 'Mengakses dan mengelola log update aplikasi',
            ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        DB::table(config('permission.table_names.permissions', 'permissions'))
            ->where('name', 'akses_update_log')
            ->where('guard_name', 'web')
            ->update([
                'group' => null,
                'description' => null,
            ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};