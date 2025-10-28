<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PerformanceReviewController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Guest routes (authentication)
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated routes
Route::middleware([\App\Http\Middleware\Authenticate::class])->group(function () {

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Employees
Route::get('/employees/import', [EmployeeController::class, 'importView'])->name('employees.import.view');
Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');
Route::get('/employees/template', [EmployeeController::class, 'downloadTemplate'])->name('employees.template');
Route::get('/employees/export', [EmployeeController::class, 'export'])->name('employees.export');
Route::get('/employees/{employee}/documents', [EmployeeDocumentController::class, 'index'])->name('employees.documents.index');
Route::get('/employees/{employee}/documents/create', [EmployeeDocumentController::class, 'create'])->name('employees.documents.create');
Route::post('/employees/{employee}/documents', [EmployeeDocumentController::class, 'store'])->name('employees.documents.store');
Route::get('/employees/{employee}/documents/{document}/download', [EmployeeDocumentController::class, 'download'])->name('employees.documents.download');
Route::delete('/employees/{employee}/documents/{document}', [EmployeeDocumentController::class, 'destroy'])->name('employees.documents.destroy');
Route::resource('employees', EmployeeController::class);

// Departments
Route::resource('departments', DepartmentController::class);

// Attendance
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
Route::post('/attendance/employee/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.employee.checkIn');
Route::post('/attendance/employee/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.employee.checkOut');
Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkIn');
Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkOut');
Route::post('/attendance/{attendance}/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout');
Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');

// Leaves
Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
Route::get('/leaves/calendar', [LeaveController::class, 'calendar'])->name('leaves.calendar');
Route::get('/leaves/calendar/data', [LeaveController::class, 'calendarData'])->name('leaves.calendar.data');
Route::get('/leaves/balances', [LeaveController::class, 'balances'])->name('leaves.balances');
Route::get('/leaves/balance/{employee}', [LeaveController::class, 'getBalance'])->name('leaves.getBalance');
Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
Route::get('/leaves/{leaveRequest}', [LeaveController::class, 'show'])->name('leaves.show');
Route::post('/leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
Route::post('/leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
Route::post('/leaves/{leave}/cancel', [LeaveController::class, 'cancel'])->name('leaves.cancel');

// Payroll
Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
Route::get('/payroll/bulk', [PayrollController::class, 'bulk'])->name('payroll.bulk');
Route::post('/payroll/bulk', [PayrollController::class, 'bulkStore'])->name('payroll.bulkStore');
Route::get('/payroll/create', [PayrollController::class, 'create'])->name('payroll.create');
Route::post('/payroll', [PayrollController::class, 'store'])->name('payroll.store');
Route::get('/payroll/{payroll}', [PayrollController::class, 'show'])->name('payroll.show');
Route::post('/payroll/{payroll}/mark-paid', [PayrollController::class, 'markPaid'])->name('payroll.markPaid');
Route::delete('/payroll/{payroll}', [PayrollController::class, 'destroy'])->name('payroll.destroy');

// Performance Reviews
Route::resource('performance', PerformanceReviewController::class);

// Reports
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
Route::get('/reports/leave', [ReportController::class, 'leave'])->name('reports.leave');
Route::get('/reports/payroll', [ReportController::class, 'payroll'])->name('reports.payroll');
Route::get('/reports/employee', [ReportController::class, 'employee'])->name('reports.employee');
Route::get('/reports/attendance/pdf', [ReportController::class, 'exportAttendancePdf'])->name('reports.attendance.pdf');
Route::get('/reports/leave/pdf', [ReportController::class, 'exportLeavePdf'])->name('reports.leave.pdf');
Route::get('/reports/payroll/pdf', [ReportController::class, 'exportPayrollPdf'])->name('reports.payroll.pdf');
Route::get('/reports/employee/pdf', [ReportController::class, 'exportEmployeePdf'])->name('reports.employee.pdf');

// Settings
Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::get('/settings/company', [SettingsController::class, 'company'])->name('settings.company');
Route::post('/settings/company', [SettingsController::class, 'updateCompany'])->name('settings.updateCompany');
Route::get('/settings/attendance', [SettingsController::class, 'attendance'])->name('settings.attendance');
Route::post('/settings/attendance', [SettingsController::class, 'updateAttendance'])->name('settings.updateAttendance');
Route::get('/settings/leave-types', [SettingsController::class, 'leaveTypes'])->name('settings.leave-types');
Route::post('/settings/leave-types', [SettingsController::class, 'storeLeaveType'])->name('settings.storeLeaveType');
Route::put('/settings/leave-types/{leaveType}', [SettingsController::class, 'updateLeaveType'])->name('settings.updateLeaveType');
Route::delete('/settings/leave-types/{leaveType}', [SettingsController::class, 'deleteLeaveType'])->name('settings.deleteLeaveType');
Route::get('/settings/positions', [SettingsController::class, 'positions'])->name('settings.positions');
Route::post('/settings/positions', [SettingsController::class, 'storePosition'])->name('settings.storePosition');
Route::put('/settings/positions/{position}', [SettingsController::class, 'updatePosition'])->name('settings.updatePosition');
Route::delete('/settings/positions/{position}', [SettingsController::class, 'deletePosition'])->name('settings.deletePosition');
Route::get('/settings/holidays', [SettingsController::class, 'holidays'])->name('settings.holidays');
Route::post('/settings/holidays', [SettingsController::class, 'storeHoliday'])->name('settings.storeHoliday');
Route::put('/settings/holidays/{holiday}', [SettingsController::class, 'updateHoliday'])->name('settings.updateHoliday');
Route::delete('/settings/holidays/{holiday}', [SettingsController::class, 'deleteHoliday'])->name('settings.deleteHoliday');

// Roles & Permissions (Admin only or users with can_manage_roles permission)
Route::middleware(['auth'])->group(function () {
    Route::resource('roles', RoleController::class);
});

// User Management (Admin only or users with can_manage_roles permission)
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
    Route::put('/users/{user}/password', [UserController::class, 'updatePassword'])->name('users.updatePassword');
});

// Profile Management
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
