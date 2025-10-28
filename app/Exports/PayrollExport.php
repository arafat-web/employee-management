<?php

namespace App\Exports;

use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PayrollExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query;
    }

    public function collection()
    {
        if ($this->query) {
            return $this->query->with(['employee.department'])->get();
        }
        return Payroll::with(['employee.department'])->get();
    }

    public function headings(): array
    {
        return [
            'Employee Code',
            'Employee Name',
            'Department',
            'Month',
            'Year',
            'Basic Salary',
            'Allowances',
            'Deductions',
            'Tax',
            'Net Salary',
            'Status',
        ];
    }

    public function map($payroll): array
    {
        return [
            $payroll->employee->employee_code,
            $payroll->employee->first_name . ' ' . $payroll->employee->last_name,
            $payroll->employee->department?->name,
            $payroll->month,
            $payroll->year,
            $payroll->basic_salary,
            $payroll->allowances,
            $payroll->deductions,
            $payroll->tax,
            $payroll->net_salary,
            $payroll->status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
