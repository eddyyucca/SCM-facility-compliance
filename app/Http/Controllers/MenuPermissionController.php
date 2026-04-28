<?php

namespace App\Http\Controllers;

use App\Models\MenuPermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuPermissionController extends Controller
{
    public function index(): View
    {
        $permissions = MenuPermission::orderBy('sort_order')->get()->groupBy('menu_section');
        $roles       = MenuPermission::allRoles();
        $roleLabels  = MenuPermission::roleLabels();

        return view('menu-permissions.index', compact('permissions', 'roles', 'roleLabels'));
    }

    public function update(Request $request): RedirectResponse
    {
        $perm      = $request->input('perm', []);
        $allRoles  = MenuPermission::allRoles();
        $nonAdmin  = array_filter($allRoles, fn ($r) => $r !== 'superadmin');

        foreach (MenuPermission::all() as $menu) {
            $allowed = ['superadmin']; // superadmin selalu punya akses

            foreach ($nonAdmin as $role) {
                if (($perm[$menu->menu_key][$role] ?? '0') === '1') {
                    $allowed[] = $role;
                }
            }

            $menu->update(['allowed_roles' => $allowed]);
        }

        MenuPermission::clearCache();

        return redirect()->route('menu-permissions.index')
            ->with('success', 'Izin akses menu berhasil disimpan.');
    }
}
