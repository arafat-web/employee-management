<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payroll_number',
        'month',
        'year',
        'basic_salary',
        'allowances',
        'bonuses',
        'overtime_pay',
        'gross_salary',
        'tax_deduction',
        'insurance_deduction',
        'other_deductions',
        'total_deductions',
        'net_salary',
        'working_days',
        'present_days',
        'absent_days',
        'leave_days',
        'notes',
        'status',
        'payment_date',
        'payment_method',
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'bonuses' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'tax_deduction' => 'decimal:2',
        'insurance_deduction' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'working_days' => 'integer',
        'present_days' => 'integer',
        'absent_days' => 'integer',
        'leave_days' => 'integer',
        'payment_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function calculateGrossSalary()
    {
        $this->gross_salary = $this->basic_salary + $this->allowances + $this->bonuses + $this->overtime_pay;
        return $this->gross_salary;
    }

    public function calculateTotalDeductions()
    {
        $this->total_deductions = $this->tax_deduction + $this->insurance_deduction + $this->other_deductions;
        return $this->total_deductions;
    }

    public function calculateNetSalary()
    {
        $this->calculateGrossSalary();
        $this->calculateTotalDeductions();
        $this->net_salary = $this->gross_salary - $this->total_deductions;
        return $this->net_salary;
    }

    public function scopeByMonth($query, $year, $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
