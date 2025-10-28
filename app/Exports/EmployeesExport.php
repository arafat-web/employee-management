<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query;
    }

    public function collection()
    {
        if ($this->query) {
            return $this->query->with(['department', 'position'])->get();
        }
        return Employee::with(['department', 'position'])->get();
    }

    public function headings(): array
    {
        return [
            'Employee Code',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Department',
            'Position',
            'Join Date',
            'Status',
            'Manager',
            'Emergency Contact',
            'Emergency Phone',
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->employee_code,
            $employee->first_name,
            $employee->last_name,
            $employee->email,
            $employee->phone,
            $employee->department?->name,
            $employee->position?->title,
            $employee->join_date,
            $employee->status,
            $employee->manager?->first_name . ' ' . $employee->manager?->last_name,
            $employee->emergency_contact,
            $employee->emergency_phone,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
