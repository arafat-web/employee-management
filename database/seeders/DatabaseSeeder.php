<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\Holiday;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RolesAndPermissionsSeeder::class);

        // Create Departments
        $departments = [
            ['name' => 'Human Resources', 'color' => '#e74c3c', 'description' => 'HR Department', 'active' => true],
            ['name' => 'IT Department', 'color' => '#3498db', 'description' => 'Information Technology', 'active' => true],
            ['name' => 'Sales', 'color' => '#2ecc71', 'description' => 'Sales Department', 'active' => true],
            ['name' => 'Marketing', 'color' => '#f39c12', 'description' => 'Marketing Department', 'active' => true],
            ['name' => 'Finance', 'color' => '#9b59b6', 'description' => 'Finance Department', 'active' => true],
            ['name' => 'Operations', 'color' => '#1abc9c', 'description' => 'Operations Department', 'active' => true],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // Create Positions
        $positions = [
            ['name' => 'CEO', 'department_id' => null, 'active' => true],
            ['name' => 'HR Manager', 'department_id' => 1, 'active' => true],
            ['name' => 'HR Executive', 'department_id' => 1, 'active' => true],
            ['name' => 'IT Manager', 'department_id' => 2, 'active' => true],
            ['name' => 'Software Developer', 'department_id' => 2, 'active' => true],
            ['name' => 'System Administrator', 'department_id' => 2, 'active' => true],
            ['name' => 'Sales Manager', 'department_id' => 3, 'active' => true],
            ['name' => 'Sales Executive', 'department_id' => 3, 'active' => true],
            ['name' => 'Marketing Manager', 'department_id' => 4, 'active' => true],
            ['name' => 'Marketing Executive', 'department_id' => 4, 'active' => true],
            ['name' => 'Finance Manager', 'department_id' => 5, 'active' => true],
            ['name' => 'Accountant', 'department_id' => 5, 'active' => true],
            ['name' => 'Operations Manager', 'department_id' => 6, 'active' => true],
        ];

        foreach ($positions as $pos) {
            Position::create($pos);
        }

        // Create Leave Types
        $leaveTypes = [
            ['name' => 'Annual Leave', 'days_allowed' => 20, 'is_paid' => true, 'color' => '#3498db', 'active' => true],
            ['name' => 'Sick Leave', 'days_allowed' => 10, 'is_paid' => true, 'color' => '#e74c3c', 'active' => true],
            ['name' => 'Casual Leave', 'days_allowed' => 5, 'is_paid' => true, 'color' => '#2ecc71', 'active' => true],
            ['name' => 'Maternity Leave', 'days_allowed' => 90, 'is_paid' => true, 'color' => '#f39c12', 'active' => true],
            ['name' => 'Paternity Leave', 'days_allowed' => 7, 'is_paid' => true, 'color' => '#9b59b6', 'active' => true],
            ['name' => 'Unpaid Leave', 'days_allowed' => 0, 'is_paid' => false, 'color' => '#95a5a6', 'active' => true],
        ];

        foreach ($leaveTypes as $type) {
            LeaveType::create($type);
        }

        // Create Holidays for 2024
        $holidays = [
            ['name' => 'New Year', 'date' => '2024-01-01', 'year' => 2024, 'active' => true],
            ['name' => 'Independence Day', 'date' => '2024-08-15', 'year' => 2024, 'active' => true],
            ['name' => 'Gandhi Jayanti', 'date' => '2024-10-02', 'year' => 2024, 'active' => true],
            ['name' => 'Christmas', 'date' => '2024-12-25', 'year' => 2024, 'active' => true],
        ];

        foreach ($holidays as $holiday) {
            Holiday::create($holiday);
        }

        // Create Admin User
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => 1, // Admin role
        ]);

        $adminEmployee = Employee::create([
            'user_id' => $adminUser->id,
            'employee_code' => 'EMP00001',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'phone' => '+1234567890',
            'mobile' => '+1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'marital_status' => 'single',
            'department_id' => 1,
            'position_id' => 1,
            'joining_date' => '2024-01-01',
            'work_email' => 'admin@company.com',
            'status' => 'active',
            'is_company_admin' => true,
        ]);

        // Create Sample Employees
        $sampleEmployees = [
            ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john.doe@example.com', 'department_id' => 2, 'position_id' => 5, 'role_id' => 5],
            ['first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'jane.smith@example.com', 'department_id' => 3, 'position_id' => 8, 'role_id' => 4],
            ['first_name' => 'Michael', 'last_name' => 'Johnson', 'email' => 'michael.j@example.com', 'department_id' => 4, 'position_id' => 10, 'role_id' => 2],
            ['first_name' => 'Emily', 'last_name' => 'Williams', 'email' => 'emily.w@example.com', 'department_id' => 5, 'position_id' => 12, 'role_id' => 3],
            ['first_name' => 'David', 'last_name' => 'Brown', 'email' => 'david.b@example.com', 'department_id' => 6, 'position_id' => 13, 'role_id' => 5],
        ];

        foreach ($sampleEmployees as $index => $empData) {
            $user = User::create([
                'name' => $empData['first_name'] . ' ' . $empData['last_name'],
                'email' => $empData['email'],
                'password' => Hash::make('password'),
                'role_id' => $empData['role_id'],
            ]);

            $employee = Employee::create([
                'user_id' => $user->id,
                'employee_code' => 'EMP' . str_pad($index + 2, 5, '0', STR_PAD_LEFT),
                'first_name' => $empData['first_name'],
                'last_name' => $empData['last_name'],
                'email' => $empData['email'],
                'phone' => '+1234567' . rand(100, 999),
                'mobile' => '+1234567' . rand(100, 999),
                'date_of_birth' => '199' . rand(0, 5) . '-0' . rand(1, 9) . '-' . rand(10, 28),
                'gender' => ['male', 'female'][rand(0, 1)],
                'marital_status' => ['single', 'married'][rand(0, 1)],
                'department_id' => $empData['department_id'],
                'position_id' => $empData['position_id'],
                'joining_date' => '2024-0' . rand(1, 9) . '-' . rand(10, 28),
                'work_email' => strtolower($empData['first_name']) . '@company.com',
                'status' => 'active',
            ]);

            // Create leave balances for each employee
            $leaveTypes = LeaveType::all();
            foreach ($leaveTypes as $leaveType) {
                LeaveBalance::create([
                    'employee_id' => $employee->id,
                    'leave_type_id' => $leaveType->id,
                    'year' => 2024,
                    'total_days' => $leaveType->days_allowed,
                    'used_days' => 0,
                    'remaining_days' => $leaveType->days_allowed,
                ]);
            }
        }

        // Create leave balances for admin
        $leaveTypes = LeaveType::all();
        foreach ($leaveTypes as $leaveType) {
            LeaveBalance::create([
                'employee_id' => $adminEmployee->id,
                'leave_type_id' => $leaveType->id,
                'year' => 2024,
                'total_days' => $leaveType->days_allowed,
                'used_days' => 0,
                'remaining_days' => $leaveType->days_allowed,
            ]);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin Login: admin@example.com / password');
    }
}
