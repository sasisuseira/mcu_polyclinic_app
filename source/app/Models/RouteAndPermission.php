<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RouteAndPermission extends Model
{
    protected $table = 'permissions';
    protected $fillable = [
        'name',
        'guard_name',
        'description',
        'group',
        'urutan'
    ];

    public static function listPermissionTabel($req, $perHalaman, $offset)
    {
        $parameterpencarian = $req->parameter_pencarian;
        $query = DB::table((new self())->getTable());
        if (!empty($parameterpencarian)) {
            $query->where('name', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('group', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('group', 'ASC')
            ->orderBy('name', 'ASC')
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
    public static function listRoleTabel($req, $perHalaman, $offset)
    {
        $parameterpencarian = $req->parameter_pencarian;
        $query = DB::table('roles')
            ->select('roles.*');

        if (!empty($parameterpencarian)) {
            $query->where(function($q) use ($parameterpencarian) {
                $q->where('roles.name', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('roles.description', 'LIKE', '%' . $parameterpencarian . '%');
            });
        }

        $roleIds = $query->pluck('roles.id')->all();

        $permissionRows = DB::table('role_has_permissions as rhp')
            ->join('permissions as p', 'rhp.permission_id', '=', 'p.id')
            ->select('rhp.role_id', 'p.name')
            ->whereIn('rhp.role_id', $roleIds)
            ->orderBy('p.name')
            ->get();

        $permissionMap = [];
        foreach ($permissionRows as $row) {
            $permissionMap[(int) $row->role_id][] = $row->name;
        }

        $permissionMap = array_map(function ($permissions) {
            return implode(',', array_unique($permissions));
        }, $permissionMap);

        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('roles.id', 'ASC')
            ->get()
            ->map(function ($role) use ($permissionMap) {
                $role->permissions = $permissionMap[$role->id] ?? null;
                return $role;
            });

        return [
            'data' => $result,
            'total' => count($roleIds),
        ];
    }
    
}

