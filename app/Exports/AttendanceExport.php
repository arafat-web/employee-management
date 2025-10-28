<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
        return Attendance::with(['employee.department'])->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Employee Code',
            'Employee Name',
            'Department',
            'Check In',
            'Check Out',
            'Work Hours',
            'Status',
            'Notes',
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->date,
            $attendance->employee->employee_code,
            $attendance->employee->first_name . ' ' . $attendance->employee->last_name,
            $attendance->employee->department?->name,
            $attendance->check_in,
            $attendance->check_out,
            $attendance->work_hours,
            $attendance->status,
            $attendance->notes,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
