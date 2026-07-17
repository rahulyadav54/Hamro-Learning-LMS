<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\Role;

class RolePermissionController extends Controller
{
    public function instructor_permission(Request $request)
    {
        return $this->syncRolePermissions(2, $request);
    }

    public function student_permission(Request $request)
    {
        return $this->syncRolePermissions(3, $request);
    }

    protected function syncRolePermissions($roleId, Request $request)
    {
        try {
            $role = Role::findOrFail($roleId);

            $permissions = $request->permissions ?? [];

            DB::table('role_permission')->where('role_id', $roleId)->delete();

            foreach ($permissions as $permissionId) {
                DB::table('role_permission')->insert([
                    'role_id'      => $roleId,
                    'permission_id' => $permissionId,
                    'status'       => 1,
                    'created_by'   => Auth::id() ?? 1,
                    'updated_by'   => Auth::id() ?? 1,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => $role->name . ' permissions updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function permissions()
    {
        $permissions = Permission::all();

        return response()->json($permissions);
    }
}
