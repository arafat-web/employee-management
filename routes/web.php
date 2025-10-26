<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;
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
Route::resource('employees', EmployeeController::class);

// Departments
Route::resource('departments', DepartmentController::class);

// Attendance
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkIn');
Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkOut');
Route::post('/attendance/{attendance}/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout');
Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');

// Leaves
Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
Route::get('/leaves/{leave}', [LeaveController::class, 'show'])->name('leaves.show');
Route::post('/leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
Route::post('/leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
Route::get('/leaves/balances', [LeaveController::class, 'balances'])->name('leaves.balances');

// Payroll
Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
Route::get('/payroll/create', [PayrollController::class, 'create'])->name('payroll.create');
Route::post('/payroll', [PayrollController::class, 'store'])->name('payroll.store');
Route::get('/payroll/{payroll}', [PayrollController::class, 'show'])->name('payroll.show');
Route::post('/payroll/{payroll}/process', [PayrollController::class, 'process'])->name('payroll.process');
Route::post('/payroll/{payroll}/mark-paid', [PayrollController::class, 'markAsPaid'])->name('payroll.markPaid');
Route::post('/payroll/generate-bulk', [PayrollController::class, 'generateBulk'])->name('payroll.generateBulk');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
