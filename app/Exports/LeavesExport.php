<?php

namespace App\Exports;

use App\Models\Leave;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeavesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query;
    }

    public function collection()
    {
        if ($this->query) {
            return $this->query->with(['employee.department', 'leaveType'])->get();
        }
        return Leave::with(['employee.department', 'leaveType'])->get();
    }

    public function headings(): array
    {
        return [
            'Employee Code',
            'Employee Name',
            'Department',
            'Leave Type',
            'Start Date',
            'End Date',
            'Days',
            'Reason',
            'Status',
            'Applied Date',
        ];
    }

    public function map($leave): array
    {
        return [
            $leave->employee->employee_code,
            $leave->employee->first_name . ' ' . $leave->employee->last_name,
            $leave->employee->department?->name,
            $leave->leaveType->name,
            $leave->start_date,
            $leave->end_date,
            $leave->days,
            $leave->reason,
            $leave->status,
            $leave->created_at->format('Y-m-d'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
