<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report</title>
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
        <h1>Attendance Report</h1>
        <p>{{ config('app.name') }}</p>
    </div>

    <div class="meta-info">
        <p><strong>Generated:</strong> {{ now()->format('F d, Y h:i A') }}</p>
        @if(request('start_date') && request('end_date'))
        <p><strong>Period:</strong> {{ request('start_date') }} to {{ request('end_date') }}</p>
        @endif
        @if(request('department_id'))
        <p><strong>Department:</strong> {{ $departments->find(request('department_id'))->name ?? 'All' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Employee</th>
                <th>Department</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Hours</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{ $attendance->date }}</td>
                <td>{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}</td>
                <td>{{ $attendance->employee->department?->name }}</td>
                <td>{{ $attendance->check_in }}</td>
                <td>{{ $attendance->check_out }}</td>
                <td>{{ $attendance->work_hours }}</td>
                <td>{{ ucfirst($attendance->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This is a system-generated report. Generated on {{ now()->format('F d, Y h:i A') }}</p>
    </div>
</body>
</html>
