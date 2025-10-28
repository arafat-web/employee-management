<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Leave Report</title>
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
        .status-approved {
            color: green;
            font-weight: bold;
        }
        .status-pending {
            color: orange;
            font-weight: bold;
        }
        .status-rejected {
            color: red;
            font-weight: bold;
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
        <h1>Leave Report</h1>
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
                <th>Employee</th>
                <th>Department</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Days</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaves as $leave)
            <tr>
                <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                <td>{{ $leave->employee->department?->name }}</td>
                <td>{{ $leave->leaveType->name }}</td>
                <td>{{ $leave->start_date }}</td>
                <td>{{ $leave->end_date }}</td>
                <td>{{ $leave->days }}</td>
                <td class="status-{{ $leave->status }}">{{ ucfirst($leave->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This is a system-generated report. Generated on {{ now()->format('F d, Y h:i A') }}</p>
    </div>
</body>
</html>
