<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Employee permissions
            ['name' => 'view_employees', 'display_name' => 'View Employees', 'group' => 'employees'],
            ['name' => 'create_employees', 'display_name' => 'Create Employees', 'group' => 'employees'],
            ['name' => 'edit_employees', 'display_name' => 'Edit Employees', 'group' => 'employees'],
            ['name' => 'delete_employees', 'display_name' => 'Delete Employees', 'group' => 'employees'],

            // Department permissions
            ['name' => 'view_departments', 'display_name' => 'View Departments', 'group' => 'departments'],
            ['name' => 'create_departments', 'display_name' => 'Create Departments', 'group' => 'departments'],
            ['name' => 'edit_departments', 'display_name' => 'Edit Departments', 'group' => 'departments'],
            ['name' => 'delete_departments', 'display_name' => 'Delete Departments', 'group' => 'departments'],

            // Attendance permissions
            ['name' => 'view_attendance', 'display_name' => 'View Attendance', 'group' => 'attendance'],
            ['name' => 'create_attendance', 'display_name' => 'Create Attendance', 'group' => 'attendance'],
            ['name' => 'edit_attendance', 'display_name' => 'Edit Attendance', 'group' => 'attendance'],
            ['name' => 'delete_attendance', 'display_name' => 'Delete Attendance', 'group' => 'attendance'],

            // Leave permissions
            ['name' => 'view_leaves', 'display_name' => 'View Leaves', 'group' => 'leaves'],
            ['name' => 'create_leaves', 'display_name' => 'Create Leaves', 'group' => 'leaves'],
            ['name' => 'edit_leaves', 'display_name' => 'Edit Leaves', 'group' => 'leaves'],
            ['name' => 'delete_leaves', 'display_name' => 'Delete Leaves', 'group' => 'leaves'],
            ['name' => 'approve_leaves', 'display_name' => 'Approve Leaves', 'group' => 'leaves'],

            // Payroll permissions
            ['name' => 'view_payroll', 'display_name' => 'View Payroll', 'group' => 'payroll'],
            ['name' => 'create_payroll', 'display_name' => 'Create Payroll', 'group' => 'payroll'],
            ['name' => 'edit_payroll', 'display_name' => 'Edit Payroll', 'group' => 'payroll'],
            ['name' => 'delete_payroll', 'display_name' => 'Delete Payroll', 'group' => 'payroll'],

            // Performance permissions
            ['name' => 'view_performance', 'display_name' => 'View Performance', 'group' => 'performance'],
            ['name' => 'create_performance', 'display_name' => 'Create Performance', 'group' => 'performance'],
            ['name' => 'edit_performance', 'display_name' => 'Edit Performance', 'group' => 'performance'],
            ['name' => 'delete_performance', 'display_name' => 'Delete Performance', 'group' => 'performance'],

            // Report permissions
            ['name' => 'view_reports', 'display_name' => 'View Reports', 'group' => 'reports'],

            // Settings permissions
            ['name' => 'view_settings', 'display_name' => 'View Settings', 'group' => 'settings'],
            ['name' => 'edit_settings', 'display_name' => 'Edit Settings', 'group' => 'settings'],

            // Role permissions
            ['name' => 'view_roles', 'display_name' => 'View Roles', 'group' => 'roles'],
            ['name' => 'create_roles', 'display_name' => 'Create Roles', 'group' => 'roles'],
            ['name' => 'edit_roles', 'display_name' => 'Edit Roles', 'group' => 'roles'],
            ['name' => 'delete_roles', 'display_name' => 'Delete Roles', 'group' => 'roles'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Create roles
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Full system access with all permissions',
            'can_manage_roles' => true,
            'active' => true,
        ]);

        $hrManager = Role::create([
            'name' => 'hr_manager',
            'display_name' => 'HR Manager',
            'description' => 'Manage employees, attendance, leaves, and performance',
            'can_manage_roles' => false,
            'active' => true,
        ]);

        $accountant = Role::create([
            'name' => 'accountant',
            'display_name' => 'Accountant',
            'description' => 'Manage payroll and view financial reports',
            'can_manage_roles' => false,
            'active' => true,
        ]);

        $manager = Role::create([
            'name' => 'manager',
            'display_name' => 'Department Manager',
            'description' => 'Manage department employees and approve leaves',
            'can_manage_roles' => false,
            'active' => true,
        ]);

        $employee = Role::create([
            'name' => 'employee',
            'display_name' => 'Employee',
            'description' => 'Basic employee access to view own information',
            'can_manage_roles' => false,
            'active' => true,
        ]);

        // Assign permissions to Admin (all permissions)
        $admin->permissions()->attach(Permission::all());

        // Assign permissions to HR Manager
        $hrManager->permissions()->attach(Permission::whereIn('group', [
            'employees', 'departments', 'attendance', 'leaves', 'performance', 'reports', 'settings'
        ])->get());

        // Assign permissions to Accountant
        $accountant->permissions()->attach(Permission::whereIn('group', [
            'payroll', 'reports'
        ])->get());

        // Assign permissions to Manager
        $manager->permissions()->attach(Permission::whereIn('name', [
            'view_employees', 'view_departments', 'view_attendance',
            'view_leaves', 'approve_leaves', 'view_performance', 'view_reports'
        ])->get());

        // Assign permissions to Employee
        $employee->permissions()->attach(Permission::whereIn('name', [
            'view_employees', 'view_leaves', 'create_leaves', 'view_attendance'
        ])->get());
    }
}
