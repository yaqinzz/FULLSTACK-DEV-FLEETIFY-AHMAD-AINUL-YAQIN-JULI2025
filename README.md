# 📋 Employee Attendance Management System

A modern, full-featured employee attendance management system built with Laravel and enhanced with beautiful Tailwind CSS UI components.

## 🚀 Features

### 🏢 Department Management

-   **Create, Read, Update, Delete (CRUD)** departments
-   **Working Hours Configuration** - Set max clock-in and clock-out times per department
-   **Employee Count Tracking** - View how many employees are in each department
-   **Modern UI** with gradient cards and responsive design

### 👥 Employee Management

-   **Complete Employee Profiles** - Name, ID, department assignment, and address
-   **Department Association** - Link employees to specific departments
-   **Employee Avatar System** - Automatic avatar generation with initials
-   **Search and Filter** capabilities
-   **Responsive Table Design** with hover effects

### ⏰ Attendance Tracking

-   **Real-time Clock In/Out** functionality
-   **Attendance History** - Complete log of all attendance records
-   **Status Monitoring** - Track Present, Late, Absent statuses
-   **Time Validation** - Automatic status calculation based on department rules
-   **Daily Attendance Reports** with filtering options

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

-   PHP 8.2 or higher
-   Composer
-   Node.js & NPM
-   MySQL/MariaDB
-   Git

## 🔧 Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd absensi
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Database Configuration

Edit your `.env` file with database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Database Migration

```bash
# Create database tables
php artisan migrate

# (Optional) Seed sample data
php artisan db:seed
```

### 7. Build Assets

```bash
# For development
npm run dev

# For production
npm run build
```

### 8. Start the Application

```bash
# Start Laravel development server
composer run dev
```

Visit `http://localhost:8000` in your browser.

## 📚 Database Schema

### Tables Overview

#### 1. `departments`

-   `id` - Primary key
-   `department_name` - Department name
-   `max_clock_in_time` - Maximum allowed clock-in time
-   `max_clock_out_time` - Maximum allowed clock-out time
-   `created_at`, `updated_at` - Timestamps

#### 2. `employees`

-   `employee_id` - Primary key (string)
-   `name` - Employee name
-   `department_id` - Foreign key to departments
-   `address` - Employee address
-   `created_at`, `updated_at` - Timestamps

#### 3. `attendances`

-   `id` - Primary key
-   `employee_id` - Foreign key to employees
-   `clock_in_time` - Clock-in timestamp
-   `clock_out_time` - Clock-out timestamp (nullable)
-   `status` - Attendance status (Present, Late, Absent)
-   `created_at`, `updated_at` - Timestamps

#### 4. `attendance_histories`

-   `id` - Primary key
-   `employee_id` - Foreign key to employees
-   `clock_in_time` - Historical clock-in time
-   `clock_out_time` - Historical clock-out time
-   `status` - Historical status
-   `created_at`, `updated_at` - Timestamps

## 🎯 Usage Guide

### Department Management

#### Creating a Department

1. Navigate to **Departments** from the main menu
2. Click **"Add Department"** button
3. Fill in the form:
    - **Department Name**: Enter the department name
    - **Max Clock In Time**: Set the latest allowed clock-in time
    - **Max Clock Out Time**: Set the earliest allowed clock-out time
4. Click **"Save Department"**

#### Managing Departments

-   **View**: All departments are listed in a modern table format
-   **Edit**: Click the blue "Edit" button to modify department details
-   **Delete**: Click the red "Delete" button (with SweetAlert confirmation)

### Employee Management

#### Adding Employees

1. Go to **Employees** section
2. Click **"Add Employee"**
3. Complete the form:
    - **Employee Name**: Full name
    - **Department**: Select from dropdown
    - **Address**: Complete address
4. Submit the form

#### Employee Features

-   **Avatar System**: Automatic avatar generation with employee initials
-   **Department Badges**: Visual department assignment
-   **Search & Filter**: Easy employee lookup

### Attendance Tracking

#### Clock In/Out Process

1. Navigate to **Attendance** section
2. Use the **Clock In** card to start work day
3. Use the **Clock Out** card to end work day
4. System automatically calculates status based on department rules

#### Status Calculation

-   **Present**: Clocked in within department's max clock-in time
-   **Late**: Clocked in after department's max clock-in time
-   **Absent**: No clock-in record for the day

#### Viewing Records

-   **Today's Attendance**: Real-time view of current day
-   **History**: Complete attendance records with filtering
-   **Status Indicators**: Color-coded status badges

## 📝 Development Notes

### Code Structure

-   **Controllers**: Located in `app/Http/Controllers/`
-   **Models**: Located in `app/Models/`
-   **Views**: Located in `resources/views/`
-   **Routes**: Defined in `routes/web.php`
-   **Migrations**: Located in `database/migrations/`

### Key Files

-   `AttendanceController.php`: Main attendance logic
-   `EmployeeController.php`: Employee CRUD operations
-   `DepartmentController.php`: Department management
-   `app.blade.php`: Main layout template
-   `app.css`: Custom Tailwind styles

## 🔄 API Endpoints

### Web Routes

-   `GET /` - Dashboard
-   `GET /departments` - Department list
-   `POST /departments` - Create department
-   `PUT /departments/{id}` - Update department
-   `DELETE /departments/{id}` - Delete department
-   `GET /employees` - Employee list
-   `POST /employees` - Create employee
-   `PUT /employees/{id}` - Update employee
-   `DELETE /employees/{id}` - Delete employee
-   `GET /attendance` - Attendance page
-   `POST /attendance/clock-in` - Clock in
-   `POST /attendance/clock-out` - Clock out

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

Created with ❤️ for modern employee attendance management.

---

## 📞 Support

For support and questions:

-   Create an issue in the repository
-   Check the troubleshooting section above
-   Review the Laravel documentation for framework-specific questions

---

**Happy Attendance Tracking! 🎉**
