<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\Position;
use App\Models\Holiday;
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
            'code' => 'required|string|max:10|unique:leave_types,code',
            'days_per_year' => 'required|integer|min:0',
            'max_consecutive_days' => 'nullable|integer|min:1',
            'is_paid' => 'required|boolean',
            'description' => 'nullable|string',
        ]);

        LeaveType::create($validated);

        return back()->with('success', 'Leave type created successfully.');
    }

    public function updateLeaveType(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:leave_types,code,' . $leaveType->id,
            'days_per_year' => 'required|integer|min:0',
            'max_consecutive_days' => 'nullable|integer|min:1',
            'is_paid' => 'required|boolean',
            'description' => 'nullable|string',
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
        $positions = Position::orderBy('title')->get();
        return view('settings.positions', compact('positions'));
    }

    public function storePosition(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|min:0',
        ]);

        Position::create($validated);

        return back()->with('success', 'Position created successfully.');
    }

    public function updatePosition(Request $request, Position $position)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|min:0',
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
}
