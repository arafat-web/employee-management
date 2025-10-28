<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payroll Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #714b67;
        }
        .meta-info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #714b67;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            background-color: #f0f0f0 !important;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Payroll Report</h1>
        <p>{{ config('app.name') }}</p>
    </div>

    <div class="meta-info">
        <p><strong>Generated:</strong> {{ now()->format('F d, Y h:i A') }}</p>
        @if(request('month') && request('year'))
        <p><strong>Period:</strong> {{ date('F', mktime(0, 0, 0, request('month'), 1)) }} {{ request('year') }}</p>
        @endif
        @if(request('department_id'))
        <p><strong>Department:</strong> {{ $departments->find(request('department_id'))->name ?? 'All' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Department</th>
                <th class="text-right">Basic Salary</th>
                <th class="text-right">Allowances</th>
                <th class="text-right">Deductions</th>
                <th class="text-right">Tax</th>
                <th class="text-right">Net Salary</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalBasic = 0;
                $totalAllowances = 0;
                $totalDeductions = 0;
                $totalTax = 0;
                $totalNet = 0;
            @endphp
            @foreach($payrolls as $payroll)
            @php
                $totalBasic += $payroll->basic_salary;
                $totalAllowances += $payroll->allowances;
                $totalDeductions += $payroll->deductions;
                $totalTax += $payroll->tax;
                $totalNet += $payroll->net_salary;
            @endphp
            <tr>
                <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                <td>{{ $payroll->employee->department?->name }}</td>
                <td class="text-right">${{ number_format($payroll->basic_salary, 2) }}</td>
                <td class="text-right">${{ number_format($payroll->allowances, 2) }}</td>
                <td class="text-right">${{ number_format($payroll->deductions, 2) }}</td>
                <td class="text-right">${{ number_format($payroll->tax, 2) }}</td>
                <td class="text-right">${{ number_format($payroll->net_salary, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2"><strong>TOTAL</strong></td>
                <td class="text-right"><strong>${{ number_format($totalBasic, 2) }}</strong></td>
                <td class="text-right"><strong>${{ number_format($totalAllowances, 2) }}</strong></td>
                <td class="text-right"><strong>${{ number_format($totalDeductions, 2) }}</strong></td>
                <td class="text-right"><strong>${{ number_format($totalTax, 2) }}</strong></td>
                <td class="text-right"><strong>${{ number_format($totalNet, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>This is a system-generated report. Generated on {{ now()->format('F d, Y h:i A') }}</p>
    </div>
</body>
</html>
