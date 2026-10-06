<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kanban\KanbanArea;
use App\Models\Kanban\KanbanDepartment;
use App\Models\Kanban\KanbanUser;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class EnsureKanbanTestEnvironmentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $adminRole      = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $staffRole      = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $qaRole         = Role::firstOrCreate(['name' => 'qa', 'guard_name' => 'web']);
        $ppicRole       = Role::firstOrCreate(['name' => 'ppic', 'guard_name' => 'web']);

        // 2. Kanban Permissions
        $permissions = [
            'view-kanban-jobs',
            'create-kanban-job',
            'manage-kanban-jobs',
            'view-kanban-areas',
            'manage-kanban-areas',
            'view-kanban-departments',
            'manage-kanban-departments',
            'view-kanban-users',
            'manage-kanban-users',
            'view-kanban-reports',
            'export-kanban-reports',
            'view-kanban-activity-logs',
        ];

        foreach ($permissions as $permName) {
            $perm = Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
            $superAdminRole->givePermissionTo($perm);
        }

        $qaRole->givePermissionTo(['view-kanban-jobs', 'create-kanban-job', 'view-kanban-activity-logs']);
        $ppicRole->givePermissionTo(['view-kanban-jobs', 'view-kanban-activity-logs']);

        // 3. Departments in mysql_kanban
        $qaDept = KanbanDepartment::firstOrCreate(['department_name' => 'Quality Assurance']);
        $ppicDept = KanbanDepartment::firstOrCreate(['department_name' => 'PPIC']);
        $engDept = KanbanDepartment::firstOrCreate(['department_name' => 'Engineering']);

        // 4. Areas in mysql_kanban
        KanbanArea::firstOrCreate(['name' => 'Laboratorium Pengujian Kimia'], ['description' => 'Lab uji kimia']);
        KanbanArea::firstOrCreate(['name' => 'Laboratorium Fisika & Sensori'], ['description' => 'Lab uji fisika']);
        KanbanArea::firstOrCreate(['name' => 'Area Karantina Mutu (Hold RM)'], ['description' => 'Gudang karantina']);

        // 5. Test Users in mysql
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@test.local'],
            [
                'name' => 'Super Admin',
                'username' => 'super',
                'nik' => 'AG1111',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->syncRoles([$superAdminRole]);

        $regular = User::updateOrCreate(
            ['email' => 'regular@test.local'],
            [
                'name' => 'Regular Staff',
                'username' => 'regular',
                'nik' => 'REG991',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $regular->syncRoles([$staffRole]);

        $qa = User::updateOrCreate(
            ['email' => 'qa@test.local'],
            [
                'name' => 'QA Officer',
                'username' => 'qa_officer',
                'nik' => 'QA991',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $qa->syncRoles([$qaRole]);
        KanbanUser::updateOrCreate(['user_id' => $qa->id], ['kanban_department_id' => $qaDept->id]);

        $ppic = User::updateOrCreate(
            ['email' => 'ppic@test.local'],
            [
                'name' => 'PPIC Officer',
                'username' => 'ppic_officer',
                'nik' => 'PPIC991',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $ppic->syncRoles([$ppicRole]);
        KanbanUser::updateOrCreate(['user_id' => $ppic->id], ['kanban_department_id' => $ppicDept->id]);
    }
}
