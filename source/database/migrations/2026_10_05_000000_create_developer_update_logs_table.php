<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('developer_update_logs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 160);
            $table->string('version', 40)->nullable();
            $table->string('category', 30);
            $table->string('summary', 500);
            $table->longText('details');
            $table->date('released_at')->index();
            $table->string('visibility', 20)->default('internal');
            $table->string('author_name', 100);
            $table->timestamps();
        });

        Permission::findOrCreate('akses_update_log', 'web');
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Schema::dropIfExists('developer_update_logs');

        $permission = Permission::query()
            ->where('name', 'akses_update_log')
            ->where('guard_name', 'web')
            ->first();

        if ($permission) {
            $permission->roles()->detach();
            $permission->users()->detach();
            $permission->delete();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};