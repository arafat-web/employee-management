# Employee Management System (EMS)

A comprehensive employee management system inspired by Odoo, built with Laravel 12 and Bootstrap 5.

## 🎯 Key Features

### 👥 Employee Management
- Complete employee profiles with personal & work information
- Employee hierarchy (Manager-Subordinate relationship)
- Employee status tracking (Active, Inactive, On Leave, Terminated)
- Photo upload and profile management
- Emergency contact information
- Bank account details
- **Excel/CSV Import/Export** - Bulk employee data management
- **Group By** - View employees grouped by Department, Position, or Status
- **Document Management** - Upload and manage employee documents (ID, Contract, Certificate, Other)

### 🏢 Department & Position Management
- Hierarchical department structure
- Department-wise employee grouping
- Position definitions with required skills
- Color-coded organization

### ⏰ Attendance Management
- **Employee Self-Service Check-in/Check-out** with time validation
- **Admin-Configurable Time Windows:**
  - Set earliest/latest check-in times (default: 6:00 AM - 12:00 PM)
  - Set earliest/latest check-out times (default: 3:00 PM - 11:59 PM)
  - Configure official work hours (default: 9:00 AM - 5:00 PM)
  - Set late arrival threshold (default: 15 minutes)
  - Set early leave threshold (default: 15 minutes)
  - Define half-day and full-day hours
  - Weekend check-in toggle
  - Required check-out option
- **Auto Status Detection:**
  - Marks "Late" if check-in exceeds threshold
  - Determines "Half Day" or "Present" based on worked hours
  - Detects early leave
- IP address logging for security
- Monthly attendance reports
- Dashboard quick check-in/check-out

### 🏖️ Leave Management
- Multiple leave types (Annual, Sick, Casual, Maternity, Paternity, Unpaid)
- Leave request workflow (Submit → Approve/Reject)
- Leave balance tracking per employee
- **Leave Calendar View** - Visual calendar with color-coded leave types using FullCalendar
- Approval system with reasons
- Leave reports

### 💰 Payroll Management
- Monthly payroll generation
- Salary components (Basic, Allowances, Bonuses, Overtime)
- Deductions (Tax, Insurance, Other)
- Attendance-based salary calculation
- Bulk payroll generation
- **Export Options** - Download payroll reports

### 📊 Reports & Analytics
- **PDF Export** - Generate PDF reports for:
  - Attendance reports
  - Leave reports
  - Payroll reports
  - Employee directory
- **Excel/CSV Export** - Export data for:
  - Employees
  - Attendance records
  - Leave requests
  - Payroll data
- Dashboard with key metrics and statistics

### 🔐 Roles & Permissions System
- **5 Default Roles:**
  - **Administrator** - Full system access
  - **HR Manager** - Employee, attendance, leave, performance management
  - **Accountant** - Payroll and financial reports
  - **Department Manager** - Team management, leave approvals
  - **Employee** - Self-service access only
- **Permission-Based UI** - Menu items and buttons hidden based on user permissions
- **32 Granular Permissions** across all modules
- **User Management** - Assign roles to users, change passwords

### ⚙️ Settings & Configuration
- Company settings management
- Leave types configuration
- Position management
- Holiday calendar
- **Attendance Settings** - Configure check-in/check-out rules
- Roles & permissions management
- User management with role assignment

### 📱 Employee Self-Service
- Dashboard with attendance status
- Quick check-in/check-out buttons
- View personal attendance history
- Apply for leaves
- View leave balances
- Download payslips
- Update profile information

### 📄 Document Management
- Upload employee documents (ID Card, Contract, Certificates, Other)
- Download documents
- Organize by document type
- Delete documents with confirmation

### 🎨 User Interface
- Clean, modern Bootstrap 5 design
- Odoo purple theme (#714b67)
- Responsive layout
- Bootstrap Icons
- Real-time time display
- Color-coded status badges
- Interactive calendar views

## 🚀 Installation

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Node.js & NPM

### Setup Steps

1. **Install dependencies**
```bash
composer install
npm install
```

2. **Environment setup**
```bash
copy .env.example .env
php artisan key:generate
```

3. **Configure database in .env**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=employee_management
DB_USERNAME=root
DB_PASSWORD=
```

4. **Database setup**
```bash
php artisan migrate
php artisan db:seed
```

5. **Storage and assets**
```bash
php artisan storage:link
npm run build
```

6. **Start server**
```bash
php artisan serve
```

Visit: `http://localhost:8000`

## 👤 Default Login Credentials

**Admin Account:**
- Email: `admin@example.com`
- Password: `password`

## � Required Packages

- `maatwebsite/excel` - Excel/CSV import/export
- `barryvdh/laravel-dompdf` - PDF generation
- Laravel 12 framework

## 🎯 Key Highlights

✅ **Odoo-Inspired UI/UX** - Professional enterprise look and feel
✅ **Role-Based Access Control** - Granular permissions system
✅ **Employee Self-Service** - Empower employees with self-check-in and profile management
✅ **Time-Based Validation** - Admin-configurable attendance time windows
✅ **Bulk Operations** - Import/Export employees, bulk payroll generation
✅ **Visual Calendar** - Leave calendar with color-coded events
✅ **Document Management** - Centralized employee document storage
✅ **Comprehensive Reporting** - PDF and Excel exports
✅ **Auto-Calculations** - Worked hours, late detection, leave balance
✅ **Dashboard Analytics** - Real-time metrics and quick actions

## 🔧 Core Technologies

- **Backend:** Laravel 12 (PHP 8.2)
- **Frontend:** Bootstrap 5, Bootstrap Icons
- **Database:** MySQL
- **Libraries:** 
  - FullCalendar - Calendar views
  - Maatwebsite Excel - Data export/import
  - DomPDF - PDF generation

## 📝 Usage

### For Employees
1. Login with employee credentials
2. Check-in/out from dashboard or attendance page
3. View personal attendance history
4. Apply for leaves
5. Download payslips
6. Update profile

### For HR/Admins
1. Manage employees (Create, Edit, Delete)
2. Import employees from Excel/CSV
3. Configure attendance time windows
4. Mark attendance manually
5. Approve/reject leave requests
6. Generate and process payroll
7. Generate reports (PDF/Excel)
8. Assign roles to users

### For Managers
1. View team attendance
2. Approve team leave requests
3. Access team reports

## 🔐 Security Features

- Password hashing with bcrypt
- CSRF protection
- Role-based access control
- Permission-based UI rendering
- Backend authorization checks
- SQL injection prevention (Eloquent ORM)
- File upload validation
- IP logging for attendance

## 📄 License

This project is open-sourced software licensed under the MIT license.

---

**Built with ❤️ using Laravel & Bootstrap**
