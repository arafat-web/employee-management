<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Report</title>
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
        <h1>Employee Report</h1>
        <p>{{ config('app.name') }}</p>
    </div>

    <div class="meta-info">
        <p><strong>Generated:</strong> {{ now()->format('F d, Y h:i A') }}</p>
        @if(request('department_id'))
        <p><strong>Department:</strong> {{ $departments->find(request('department_id'))->name ?? 'All' }}</p>
        @endif
        @if(request('status'))
        <p><strong>Status:</strong> {{ ucfirst(request('status')) }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Position</th>
                <th>Join Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->employee_code }}</td>
                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->department?->name }}</td>
                <td>{{ $employee->position?->title }}</td>
                <td>{{ $employee->join_date }}</td>
                <td>{{ ucfirst($employee->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This is a system-generated report. Generated on {{ now()->format('F d, Y h:i A') }}</p>
        <p>Total Employees: {{ $employees->count() }}</p>
    </div>
</body>
</html>
