<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Hash;

class EmployeesImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Find or create department
        $department = Department::firstOrCreate(
            ['name' => $row['department']],
            ['active' => true]
        );

        // Find or create position
        $position = Position::firstOrCreate(
            ['title' => $row['position']],
            ['description' => 'Imported position']
        );

        // Create user account
        $user = User::create([
            'name' => $row['first_name'] . ' ' . $row['last_name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password'] ?? 'password123'),
            'role_id' => 5, // Employee role
        ]);

        // Create employee
        return new Employee([
            'user_id' => $user->id,
            'employee_code' => $row['employee_code'] ?? 'EMP' . str_pad(Employee::max('id') + 1, 4, '0', STR_PAD_LEFT),
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
            'phone' => $row['phone'] ?? null,
            'address' => $row['address'] ?? null,
            'city' => $row['city'] ?? null,
            'state' => $row['state'] ?? null,
            'zip_code' => $row['zip_code'] ?? null,
            'date_of_birth' => $row['date_of_birth'] ?? null,
            'gender' => $row['gender'] ?? 'male',
            'marital_status' => $row['marital_status'] ?? 'single',
            'nationality' => $row['nationality'] ?? null,
            'national_id' => $row['national_id'] ?? null,
            'department_id' => $department->id,
            'position_id' => $position->id,
            'join_date' => $row['join_date'] ?? now()->format('Y-m-d'),
            'employment_type' => $row['employment_type'] ?? 'full_time',
            'salary' => $row['salary'] ?? 0,
            'status' => $row['status'] ?? 'active',
            'emergency_contact' => $row['emergency_contact'] ?? null,
            'emergency_phone' => $row['emergency_phone'] ?? null,
            'bank_name' => $row['bank_name'] ?? null,
            'bank_account' => $row['bank_account'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'department' => 'required|string',
            'position' => 'required|string',
        ];
    }
}
