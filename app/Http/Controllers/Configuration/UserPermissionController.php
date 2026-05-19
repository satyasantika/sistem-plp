<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\RolePermissionUi;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class UserPermissionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:roles-update|users-update', ['only' => ['edit', 'update']]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $blockedIdsViaRoles = $user->getPermissionsViaRoles()->pluck('id')->unique()->values()
            ->map(fn ($permissionId) => (int) $permissionId)->all();

        $assignablePermissions = Permission::query()
            ->when($blockedIdsViaRoles !== [], fn ($q) => $q->whereNotIn('id', $blockedIdsViaRoles))
            ->orderBy('name')
            ->get(['id', 'name']);

        $assignableLookup = array_fill_keys(
            $assignablePermissions->pluck('id')->map(fn ($permissionId) => (int) $permissionId)->all(),
            true,
        );

        $userPermissions = [];
        foreach ($user->permissions as $permissionRow) {
            $pid = (int) $permissionRow->id;
            if (isset($assignableLookup[$pid])) {
                $userPermissions[$pid] = $pid;
            }
        }

        $permissionUi = RolePermissionUi::build($assignablePermissions, $userPermissions);

        $userRoleGrantedPermissionCount = count($blockedIdsViaRoles);

        return view('konfigurasi.userpermission-action', compact(
            'user',
            'permissionUi',
            'userPermissions',
            'userRoleGrantedPermissionCount',
        ));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $requested = $request->input('permission', []);
        $requested = is_array($requested)
            ? array_values(array_unique(array_map('intval', array_filter($requested))))
            : [];

        $blockedIdsViaRoles = $user->getPermissionsViaRoles()->pluck('id')->unique()->values()
            ->map(fn ($permissionId) => (int) $permissionId)->all();
        $requested = array_values(array_diff($requested, $blockedIdsViaRoles));

        $user->syncPermissions($requested);

        return response()->json([
            'success' => true,
            'message' => 'Permission untuk <strong>'.$request->name.'</strong> telah diperbarui',
        ]);
    }
}
