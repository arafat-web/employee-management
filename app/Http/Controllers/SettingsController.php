<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\Position;
use App\Models\Holiday;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    // Company Settings
    public function company()
    {
        return view('settings.company');
    }

    public function updateCompany(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email',
            'company_phone' => 'required|string',
            'company_address' => 'required|string',
            'company_website' => 'nullable|url',
        ]);

        // Store in config or database
        // For now, we'll use session
        session(['company_settings' => $validated]);

        return back()->with('success', 'Company settings updated successfully.');
    }

    // Leave Types Management
    public function leaveTypes()
    {
        $leaveTypes = LeaveType::orderBy('name')->get();
        return view('settings.leave-types', compact('leaveTypes'));
    }

    public function storeLeaveType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'days_allowed' => 'required|integer|min:0',
            'requires_approval' => 'nullable|boolean',
            'is_paid' => 'required|boolean',
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'active' => 'nullable|boolean',
        ]);

        LeaveType::create($validated);

        return back()->with('success', 'Leave type created successfully.');
    }

    public function updateLeaveType(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'days_allowed' => 'required|integer|min:0',
            'requires_approval' => 'nullable|boolean',
            'is_paid' => 'required|boolean',
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'active' => 'nullable|boolean',
        ]);

        $leaveType->update($validated);

        return back()->with('success', 'Leave type updated successfully.');
    }

    public function deleteLeaveType(LeaveType $leaveType)
    {
        $leaveType->delete();
        return back()->with('success', 'Leave type deleted successfully.');
    }

    // Positions Management
    public function positions()
    {
        $positions = Position::orderBy('name')->get();
        return view('settings.positions', compact('positions'));
    }

    public function storePosition(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'expected_employees' => 'nullable|integer|min:1',
            'active' => 'nullable|boolean',
        ]);

        Position::create($validated);

        return back()->with('success', 'Position created successfully.');
    }

    public function updatePosition(Request $request, Position $position)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'expected_employees' => 'nullable|integer|min:1',
            'active' => 'nullable|boolean',
        ]);

        $position->update($validated);

        return back()->with('success', 'Position updated successfully.');
    }

    public function deletePosition(Position $position)
    {
        $position->delete();
        return back()->with('success', 'Position deleted successfully.');
    }

    // Holidays Management
    public function holidays()
    {
        $holidays = Holiday::orderBy('date')->get();
        return view('settings.holidays', compact('holidays'));
    }

    public function storeHoliday(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:public,company',
            'description' => 'nullable|string',
        ]);

        Holiday::create($validated);

        return back()->with('success', 'Holiday created successfully.');
    }

    public function updateHoliday(Request $request, Holiday $holiday)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:public,company',
            'description' => 'nullable|string',
        ]);

        $holiday->update($validated);

        return back()->with('success', 'Holiday updated successfully.');
    }

    public function deleteHoliday(Holiday $holiday)
    {
        $holiday->delete();
        return back()->with('success', 'Holiday deleted successfully.');
    }

    // Attendance Settings
    public function attendance()
    {
        $settings = AttendanceSetting::getSettings();
        return view('settings.attendance', compact('settings'));
    }

    public function updateAttendance(Request $request)
    {
        $validated = $request->validate([
            'check_in_start' => 'required|date_format:H:i',
            'check_in_end' => 'required|date_format:H:i|after:check_in_start',
            'check_out_start' => 'required|date_format:H:i',
            'check_out_end' => 'required|date_format:H:i|after:check_out_start',
            'work_start_time' => 'required|date_format:H:i',
            'work_end_time' => 'required|date_format:H:i|after:work_start_time',
            'late_threshold_minutes' => 'required|integer|min:0|max:120',
            'early_leave_threshold_minutes' => 'required|integer|min:0|max:120',
            'half_day_hours' => 'required|integer|min:1|max:12',
            'full_day_hours' => 'required|integer|min:1|max:24',
            'allow_weekend_checkin' => 'boolean',
            'require_checkout' => 'boolean',
        ]);

        $settings = AttendanceSetting::first();
        $settings->update($validated);

        return back()->with('success', 'Attendance settings updated successfully.');
    }
}
