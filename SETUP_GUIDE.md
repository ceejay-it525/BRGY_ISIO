# BRGY_ISIO - Barangay Management Information System (BMIS)

## Setup and Installation Guide

### Prerequisites
- XAMPP (Apache, MySQL, PHP 7.4+)
- Composer (for PHP dependencies)
- Modern web browser (Chrome, Firefox, Edge)

### Installation Steps

#### 1. Database Setup
1. Start XAMPP (Apache and MySQL)
2. Open phpMyAdmin (http://localhost/phpmyadmin)
3. Create a new database named `crud_db`
4. Import the SQL file: `db/crud_db.sql`
5. The database will be populated with sample data

#### 2. Application Configuration
1. Copy the project folder to XAMPP's htdocs directory:
   ```
   C:\xampp\htdocs\BRGY_ISIO
   ```

2. Configure the database connection in `.env` file:
   ```
   database.default.hostname = localhost
   database.default.database = crud_db
   database.default.username = root
   database.default.password = 
   database.default.DBDriver = MySQLi
   ```

3. Install PHP dependencies using Composer:
   ```bash
   cd C:\xampp\htdocs\BRGY_ISIO
   composer install
   ```

#### 3. Set Permissions (Windows)
Ensure the `writable` directory is writable by the web server.

#### 4. Access the Application
Open your browser and navigate to:
```
http://localhost/BRGY_ISIO
```

#### 5. Default Login Credentials
- **Email:** ceejay05022005@gmail.com
- **Password:** (Check the database or use password reset)

Or create a new account using the registration link.

### Features Overview

#### Core Modules
1. **Dashboard** - Overview with statistics, recent activities, and alerts
2. **Residents** - Complete resident population registry
3. **Barangay Officials** - Manage elected officials
4. **Households** - Family profile management
5. **Blotter System** - Complaint and case logging
6. **Clearances** - Certificate issuance system
7. **Business Permits** - Permit management (BPLS)
8. **Indigents** - Social services management
9. **Reports** - Analytics and data visualization
10. **User Management** - Admin user accounts
11. **Activity Logs** - System audit trail

#### Key Features
- ✅ Modern, responsive UI with dark/light theme toggle
- ✅ Real-time data tables with search, sort, and pagination
- ✅ CRUD operations for all modules
- ✅ Form validation and error handling
- ✅ Toast notifications for user feedback
- ✅ CSRF protection for security
- ✅ Activity logging for accountability
- ✅ Export capabilities (DataTables)
- ✅ Mobile-friendly responsive design

### Troubleshooting

#### Common Issues

**1. Database Connection Error**
- Verify MySQL is running in XAMPP
- Check database credentials in `.env` file
- Ensure database `crud_db` exists

**2. 404 Errors / Page Not Found**
- Check that `.htaccess` files are present
- Verify Apache's `mod_rewrite` is enabled
- Ensure `AllowOverride All` is set in Apache config

**3. Permission Denied Errors**
- Make sure `writable/` directory has write permissions
- On Windows, right-click folder > Properties > Security > Edit

**4. Blank White Page**
- Check `writable/logs/` for error messages
- Enable debug mode in `.env`: `CI_ENVIRONMENT = development`
- Check PHP error log in XAMPP

**5. CSS/JS Not Loading**
- Clear browser cache (Ctrl+Shift+Delete)
- Check browser console for errors
- Verify base URL in `app/Config/App.php`

#### Enable Debug Mode
Edit `.env` file:
```
CI_ENVIRONMENT = development
```

#### View Logs
Check log files at:
```
writable/logs/log-YYYY-MM-DD.php
```

### Recent Fixes and Improvements

#### Backend Fixes
- ✅ Fixed Dashboard controller - added missing data (activities, events, alerts)
- ✅ Added `reportStats()` method to Reports controller
- ✅ Fixed route definitions for all modules
- ✅ Standardized status values (Active/Inactive with capital A/I)

#### Frontend Fixes
- ✅ Removed duplicate theme toggle scripts
- ✅ Created global `app.js` with helper functions
- ✅ Fixed `refreshReportStats()` function
- ✅ Added CSRF token handling helpers
- ✅ Improved error handling and user feedback

#### UI Improvements
- ✅ Consistent theme toggle functionality
- ✅ Better toast notifications
- ✅ Improved modal dialogs
- ✅ Enhanced data table rendering
- ✅ Better responsive design

### Development Notes

#### Project Structure
```
BRGY_ISIO/
├── app/
│   ├── Config/          # Configuration files
│   ├── Controllers/     # Request handlers
│   ├── Helpers/         # Custom helper functions
│   ├── Models/          # Data models
│   ├── Views/           # Template files
│   │   ├── theme/       # Main layout templates
│   │   └── [module]/    # Module-specific views
│   └── Filters/         # Request filters
├── public/
│   ├── assets/          # CSS, JS libraries
│   ├── js/              # Custom JavaScript
│   └── uploads/         # User uploads
├── db/                  # Database files
├── writable/            # Writable directories
└── tests/               # Test files
```

#### Database Tables
- `residents` - Resident information
- `households` - Household/family data
- `barangay_officials` - Officials information
- `blotter` - Complaint/case records
- `clearances` - Certificate records
- `clearance_types` - Types of clearances
- `permits` - Business permits
- `indigents` - Indigent residents
- `users` - System users
- `tbl_logs` - Activity logs
- `barangay_events` - Upcoming events
- `login_attempts` - Login security

### Support and Contact

For issues, questions, or feature requests, please contact the development team.

### Version Information
- **Version:** CI4.v1
- **Framework:** CodeIgniter 4
- **Last Updated:** May 8, 2026
- **Developer:** Cymone IT yarn

---

**Note:** This system is designed for Barangay ISIO but can be customized for other barangays by modifying the configuration and branding.