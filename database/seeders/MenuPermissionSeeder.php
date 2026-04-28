<?php

namespace Database\Seeders;

use App\Models\MenuPermission;
use Illuminate\Database\Seeder;

class MenuPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // ── GA Section ──
            [
                'menu_key'      => 'dashboard_ga',
                'menu_label'    => 'Dashboard GA',
                'menu_section'  => 'ga',
                'sort_order'    => 1,
                'allowed_roles' => ['superadmin', 'ga', 'receptionist', 'hk', 'laundry'],
            ],
            [
                'menu_key'      => 'complaints_all',
                'menu_label'    => 'Semua Laporan GA',
                'menu_section'  => 'ga',
                'sort_order'    => 2,
                'allowed_roles' => ['superadmin', 'ga', 'receptionist', 'hk', 'laundry'],
            ],
            [
                'menu_key'      => 'complaints_open',
                'menu_label'    => 'GA Open',
                'menu_section'  => 'ga',
                'sort_order'    => 3,
                'allowed_roles' => ['superadmin', 'ga', 'receptionist', 'hk', 'laundry'],
            ],
            [
                'menu_key'      => 'complaints_progress',
                'menu_label'    => 'GA Progress',
                'menu_section'  => 'ga',
                'sort_order'    => 4,
                'allowed_roles' => ['superadmin', 'ga', 'receptionist', 'hk', 'laundry'],
            ],
            [
                'menu_key'      => 'complaints_overdue',
                'menu_label'    => 'GA Overdue SLA',
                'menu_section'  => 'ga',
                'sort_order'    => 5,
                'allowed_roles' => ['superadmin', 'ga', 'receptionist', 'hk', 'laundry'],
            ],
            [
                'menu_key'      => 'complaints_receptionist',
                'menu_label'    => 'GA Receptionist',
                'menu_section'  => 'ga',
                'sort_order'    => 6,
                'allowed_roles' => ['superadmin', 'ga', 'receptionist'],
            ],
            [
                'menu_key'      => 'complaints_hk',
                'menu_label'    => 'GA Housekeeping',
                'menu_section'  => 'ga',
                'sort_order'    => 7,
                'allowed_roles' => ['superadmin', 'ga', 'hk'],
            ],
            [
                'menu_key'      => 'complaints_laundry',
                'menu_label'    => 'GA Laundry',
                'menu_section'  => 'ga',
                'sort_order'    => 8,
                'allowed_roles' => ['superadmin', 'ga', 'laundry'],
            ],
            [
                'menu_key'      => 'analytics_ga',
                'menu_label'    => 'Analitik GA',
                'menu_section'  => 'ga',
                'sort_order'    => 9,
                'allowed_roles' => ['superadmin', 'ga'],
            ],
            [
                'menu_key'      => 'reporters_ga',
                'menu_label'    => 'Pelapor GA',
                'menu_section'  => 'ga',
                'sort_order'    => 10,
                'allowed_roles' => ['superadmin', 'ga'],
            ],

            // ── HR Section ──
            [
                'menu_key'     => 'hr_dashboard',
                'menu_label'   => 'Dashboard HR',
                'menu_section' => 'hr',
                'sort_order'   => 11,
                'allowed_roles' => ['superadmin', 'hr'],
            ],
            [
                'menu_key'     => 'hr_all',
                'menu_label'   => 'Semua Laporan HR',
                'menu_section' => 'hr',
                'sort_order'   => 12,
                'allowed_roles' => ['superadmin', 'hr'],
            ],
            [
                'menu_key'     => 'hr_open',
                'menu_label'   => 'HR Open',
                'menu_section' => 'hr',
                'sort_order'   => 13,
                'allowed_roles' => ['superadmin', 'hr'],
            ],
            [
                'menu_key'     => 'hr_progress',
                'menu_label'   => 'HR Progress',
                'menu_section' => 'hr',
                'sort_order'   => 14,
                'allowed_roles' => ['superadmin', 'hr'],
            ],
            [
                'menu_key'     => 'hr_closed',
                'menu_label'   => 'HR Penyelesaian',
                'menu_section' => 'hr',
                'sort_order'   => 15,
                'allowed_roles' => ['superadmin', 'hr'],
            ],
            [
                'menu_key'     => 'hr_overdue',
                'menu_label'   => 'HR Overdue SLA',
                'menu_section' => 'hr',
                'sort_order'   => 16,
                'allowed_roles' => ['superadmin', 'hr'],
            ],

            // ── Admin Section ──
            [
                'menu_key'     => 'users_manage',
                'menu_label'   => 'Kelola Akun',
                'menu_section' => 'admin',
                'sort_order'   => 17,
                'allowed_roles' => ['superadmin'],
            ],
            [
                'menu_key'      => 'menu_permissions',
                'menu_label'    => 'Izin Akses Menu',
                'menu_section'  => 'admin',
                'sort_order'    => 18,
                'allowed_roles' => ['superadmin'],
            ],
            [
                'menu_key'      => 'sla_settings',
                'menu_label'    => 'Pengaturan SLA',
                'menu_section'  => 'admin',
                'sort_order'    => 19,
                'allowed_roles' => ['superadmin'],
            ],
        ];

        foreach ($menus as $menu) {
            MenuPermission::updateOrCreate(
                ['menu_key' => $menu['menu_key']],
                $menu
            );
        }
    }
}
