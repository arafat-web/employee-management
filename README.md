# Employee Management System (EMS)

A comprehensive employee management system inspired by Odoo, built with Laravel 12 and Bootstrap 5.

## 🎯 Key Features (Odoo-like)

### 1. **Employee Management**
- Complete employee profiles with personal & work information
- Employee hierarchy (Manager-Subordinate relationship)
- Employee status tracking (Active, Inactive, On Leave, Terminated)
- Employee code generation
- Photo upload and document management
- Emergency contact information
- Bank account details

### 2. **Department Management**
- Hierarchical department structure
- Department-wise employee grouping
- Department managers
- Color-coded departments
- Department statistics

### 3. **Position/Job Management**
- Position definitions
- Department-wise positions
- Required skills and qualifications
- Expected employee count per position

### 4. **Attendance Management**
- Daily attendance tracking
- Check-in/Check-out with IP logging
- Attendance status (Present, Absent, Late, Half-Day, On Leave, Holiday)
- Worked hours calculation
- Overtime tracking
- Monthly attendance reports
- Attendance statistics

### 5. **Leave Management**
- Multiple leave types (Annual, Sick, Casual, Maternity, Paternity, Unpaid)
- Leave request workflow (Submit → Approve/Reject)
- Leave balance tracking per employee
- Leave calendar
- Approval system with reasons
- Leave reports

### 6. **Payroll Management**
- Monthly payroll generation
- Salary components (Basic, Allowances, Bonuses, Overtime)
- Deductions (Tax, Insurance, Other)
- Attendance-based salary calculation
- Payroll status tracking
- Bulk payroll generation
- Payroll reports

### 7. **Contract Management**
- Multiple contract types (Permanent, Fixed-term, Internship, Freelance, Part-time)
- Contract terms and benefits
- Salary structure & working hours
- Contract status tracking

### 8. **Skills Management**
- Skill categories (Technical, Soft, Language, Certification)
- Employee skill mapping
- Proficiency levels (1-5 scale)

### 9. **Performance Reviews**
- Periodic performance evaluations
- Multi-criteria ratings
- Overall rating calculation
- Goal setting

### 10. **Additional Features**
- Dashboard with key metrics
- Announcements system
- Holiday management
- Document management
- Birthday reminders

## 🚀 Installation

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Node.js & NPM

### Setup Steps

1. **Install PHP dependencies**
```bash
composer install
```

2. **Install NPM dependencies**
```bash
npm install
```

3. **Create environment file**
```bash
copy .env.example .env
```

4. **Generate application key**
```bash
php artisan key:generate
```

5. **Configure database in .env**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=employee_management
DB_USERNAME=root
DB_PASSWORD=
```

6. **Run migrations**
```bash
php artisan migrate
```

7. **Seed the database**
```bash
php artisan db:seed
```

8. **Create storage link**
```bash
php artisan storage:link
```

9. **Build assets**
```bash
npm run build
```

10. **Start the development server**
```bash
php artisan serve
```

Visit: `http://localhost:8000`

## 👤 Default Login Credentials

**Admin Account:**
- Email: `admin@example.com`
- Password: `password`

## 📊 Database Schema

### Main Tables
1. **employees** - Employee information
2. **departments** - Department structure
3. **positions** - Job positions
4. **contracts** - Employment contracts
5. **attendances** - Daily attendance records
6. **leave_types** - Types of leaves
7. **leave_requests** - Leave applications
8. **leave_balances** - Employee leave balances
9. **payrolls** - Salary records
10. **skills** - Skill definitions
11. **performance_reviews** - Performance evaluations
12. **holidays** - Company holidays
13. **announcements** - Company announcements
14. **documents** - Employee documents

## 🎨 Technologies Used

- **Backend:** Laravel 12 (PHP 8.2)
- **Frontend:** Bootstrap 5, Bootstrap Icons
- **Database:** MySQL
- **Charts:** Chart.js

## 🎯 Odoo-Inspired Features

✅ **Hierarchical Structure** - Departments and positions with parent-child relationships
✅ **Status Tracking** - Active/Inactive status for all entities
✅ **Color Coding** - Visual organization with color-coded items
✅ **Workflow Management** - Approval flows for leaves and payroll
✅ **Multi-level Relationships** - Manager, Coach, Department hierarchies
✅ **Comprehensive Filtering** - Advanced search and filter options
✅ **Bulk Operations** - Batch processing for payroll
✅ **Dashboard Analytics** - Key metrics and trends
✅ **Document Management** - File attachments and document tracking
✅ **Audit Trail** - Timestamps and user tracking

## 📝 Usage Examples

### Creating an Employee
1. Navigate to Employees → Add Employee
2. Fill in personal information
3. Select department and position
4. Assign manager and save

### Processing Payroll
1. Navigate to Payroll → Create
2. Select month and year
3. Click "Generate Bulk" for all employees
4. Review and edit individual payrolls
5. Process payrolls and mark as paid

### Managing Attendance
1. Navigate to Attendance
2. Employees can check-in/out themselves
3. Or HR can mark attendance manually
4. View daily/monthly reports

## 🔐 Security Features

- Password hashing with bcrypt
- CSRF protection
- SQL injection prevention (Eloquent ORM)
- File upload validation
- User authentication

## 🚧 Future Enhancements

- [ ] Multi-tenant support
- [ ] Advanced reporting with exports (PDF, Excel)
- [ ] Email notifications
- [ ] Biometric integration
- [ ] Mobile app
- [ ] Project management integration
- [ ] Training management
- [ ] Recruitment module

## 📄 License

This project is open-sourced software licensed under the MIT license.

---

**Built with ❤️ using Laravel & Bootstrap**
