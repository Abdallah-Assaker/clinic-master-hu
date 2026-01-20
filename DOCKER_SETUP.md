# 🏥 Clinic Management System - Docker Setup Guide

A comprehensive guide to running the Medical Clinic Management System using Docker.

---

## 📋 Table of Contents

1. [Prerequisites](#-prerequisites)
2. [Quick Start](#-quick-start)
3. [Detailed Setup](#-detailed-setup)
4. [Accessing the Application](#-accessing-the-application)
5. [Authentication & Roles](#-authentication--roles)
6. [Services Overview](#-services-overview)
7. [Configuration](#-configuration)
8. [Common Commands](#-common-commands)
9. [Troubleshooting](#-troubleshooting)
10. [Development Tips](#-development-tips)

---

## 📦 Prerequisites

Before you begin, ensure you have the following installed on your machine:

| Requirement | Minimum Version | Check Command |
|-------------|-----------------|---------------|
| **Docker** | 20.10+ | `docker --version` |
| **Docker Compose** | 2.0+ | `docker-compose --version` |
| **Git** | 2.0+ | `git --version` |

### System Requirements

- **RAM**: Minimum 4GB (8GB recommended)
- **Disk Space**: At least 5GB free space
- **OS**: Windows 10/11, macOS 10.15+, or Linux

---

## 🚀 Quick Start

Get the application running in 3 simple steps:

```bash
# 1. Clone the repository (if not already done)
git clone https://github.com/Abdallah-Assaker/clinic-master-hu.git
cd clinic-master-hu

# 2. Copy environment file (optional - defaults work out of the box)
cp .env.docker .env

# 3. Build and start the containers
docker-compose up -d --build
```

Wait approximately **2-3 minutes** for the first build, then access:

| Service | URL |
|---------|-----|
| **Application** | http://localhost:8000 |
| **phpMyAdmin** | http://localhost:8080 |
| **Mailpit** (Email Testing) | http://localhost:8025 |

---

## 📖 Detailed Setup

### Step 1: Clone the Repository

```bash
git clone https://github.com/Abdallah-Assaker/clinic-master-hu.git
cd clinic-master-hu
```

### Step 2: Configure Environment (Optional)

The application works with default settings. For customization, copy the Docker environment template:

```bash
cp .env.docker .env
```

Edit `.env` to customize:
- Database credentials
- Application URL
- Mail settings
- Stripe payment keys (for payment functionality)

### Step 3: Build and Start

```bash
# Build and start all services
docker-compose up -d --build
```

**First-time build** takes 2-5 minutes depending on your internet connection and hardware.

### Step 4: Verify Installation

```bash
# Check all containers are running
docker-compose ps

# View application logs
docker logs clinic-app

# Check health status
curl http://localhost:8000/health
```

You should see:
- `clinic-app` - Running (healthy)
- `clinic-db` - Running (healthy)
- `clinic-redis` - Running (healthy)

---

## 🌐 Accessing the Application

### Main Application

| URL | Description |
|-----|-------------|
| http://localhost:8000 | Homepage (Arabic RTL) |
| http://localhost:8000/login | Login Page |
| http://localhost:8000/register | Patient Registration |
| http://localhost:8000/dashboard | Admin Dashboard (Admin only) |
| http://localhost:8000/doctors/profile | Doctor Profile (Doctor only) |
| http://localhost:8000/patients/profile | Patient Profile |

> ⚠️ **Note**: There is NO `/admin` route. The admin dashboard is at `/dashboard`.

### Development Tools

| Service | URL | Credentials |
|---------|-----|-------------|
| **phpMyAdmin** | http://localhost:8080 | Server: `db`, User: `clinic_user`, Password: `clinic_secret` |
| **Mailpit** | http://localhost:8025 | No credentials needed |
| **Laravel Telescope** | http://localhost:8000/telescope | Admin access required |

---

## 🔐 Authentication & Roles

### Default Test Accounts

The system comes with test accounts for each role:

| Role | Email | Password | Access |
|------|-------|----------|--------|
| **Admin** | `admin@clinic.com` | `password` | Full system access, Dashboard at `/dashboard` |
| **Doctor** | `doctor@clinic.com` | `password` | Doctor profile at `/doctors/profile`, Manage appointments |
| **Patient** | `patient@clinic.com` | `password` | Patient profile at `/patients/profile`, Book appointments |

> 📝 **Note**: To create test Doctor and Patient accounts, run:
> ```bash
> docker exec clinic-app php artisan db:seed --class=TestUsersSeeder
> ```

### User Roles

The system has three user roles with different permissions:

| Role | Description | Capabilities |
|------|-------------|--------------|
| **Admin** | System Administrator | Full access: Dashboard, manage doctors, patients, appointments, users |
| **Doctor** | Medical Professional | Own profile, view/manage assigned appointments |
| **Patient** | Healthcare Consumer | Own profile, book appointments, view booking history |

### Role-Based Route Access

| Route | Admin | Doctor | Patient |
|-------|-------|--------|---------|
| `/dashboard` | ✅ | ❌ | ❌ |
| `/doctors` (list) | ✅ | ❌ | ❌ |
| `/patients` (list) | ✅ | ❌ | ❌ |
| `/appointments` (list) | ✅ | ❌ | ❌ |
| `/doctors/profile` | ❌ | ✅ | ❌ |
| `/doctors/profile/appointments` | ❌ | ✅ | ❌ |
| `/patients/profile` | ✅ | ✅ | ✅ |
| `/appointments/book/{doctor}` | ✅ | ✅ | ✅ |

### Creating New Users

1. **Admin Users**: Login as admin → Users → Create User → Assign "Admin" role
2. **Doctors**: Login as admin → Doctors → Create Doctor (automatically gets Doctor role)
3. **Patients**: Register via http://localhost:8000/register (automatically gets Patient role)

---

## 🔧 Services Overview

The Docker setup includes 5 services:

### 1. Application (`clinic-app`)
- **Image**: PHP 8.2 FPM + Nginx + Supervisor
- **Port**: 8000
- **Contains**: Laravel application, queue workers, scheduler

### 2. Database (`clinic-db`)
- **Image**: MySQL 8.0
- **Port**: 3306
- **Data**: Persisted in `clinic-db-data` volume

### 3. Cache (`clinic-redis`)
- **Image**: Redis 7 Alpine
- **Port**: 6379
- **Used for**: Sessions, cache, queue

### 4. phpMyAdmin (`phpmyadmin`)
- **Image**: phpMyAdmin latest
- **Port**: 8080
- **Purpose**: Database management UI

### 5. Mail Testing (`mailpit`)
- **Ports**: 8025 (UI), 1025 (SMTP)
- **Purpose**: Catch and view all outgoing emails

---

## ⚙️ Configuration

### Environment Variables

Key environment variables you can customize:

```env
# Application
APP_NAME=ClinicMaster
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_LOCALE=ar

# Database
DB_DATABASE=clinic_master
DB_USERNAME=clinic_user
DB_PASSWORD=clinic_secret

# Ports (change if conflicts occur)
APP_PORT=8000
DB_EXTERNAL_PORT=3306
REDIS_EXTERNAL_PORT=6379
PHPMYADMIN_PORT=8080
MAILPIT_HTTP_PORT=8025

# Stripe (for payment functionality)
STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key
```

### Changing Ports

If you have port conflicts, modify in `.env`:

```env
APP_PORT=8080           # Change app from 8000 to 8080
PHPMYADMIN_PORT=8081    # Change phpMyAdmin from 8080 to 8081
```

Then restart: `docker-compose up -d`

---

## 🛠️ Common Commands

### Container Management

```bash
# Start all containers
docker-compose up -d

# Stop all containers
docker-compose down

# Restart all containers
docker-compose restart

# View running containers
docker-compose ps

# View logs (all services)
docker-compose logs -f

# View logs (specific service)
docker logs clinic-app -f
```

### Laravel Commands

```bash
# Run artisan commands
docker exec clinic-app php artisan <command>

# Examples:
docker exec clinic-app php artisan migrate              # Run migrations
docker exec clinic-app php artisan db:seed              # Seed database
docker exec clinic-app php artisan cache:clear          # Clear cache
docker exec clinic-app php artisan config:clear         # Clear config cache
docker exec clinic-app php artisan queue:restart        # Restart queue workers
docker exec clinic-app php artisan tinker               # Interactive REPL
```

### Database Operations

```bash
# Access MySQL CLI
docker exec -it clinic-db mysql -u clinic_user -pclinic_secret clinic_master

# Backup database
docker exec clinic-db mysqldump -u clinic_user -pclinic_secret clinic_master > backup.sql

# Restore database
docker exec -i clinic-db mysql -u clinic_user -pclinic_secret clinic_master < backup.sql

# Fresh database (WARNING: deletes all data)
docker exec clinic-app php artisan migrate:fresh --seed
```

### Development Commands

```bash
# Enter app container shell
docker exec -it clinic-app sh

# Clear all caches
docker exec clinic-app php artisan optimize:clear

# Rebuild frontend assets (if needed)
docker exec clinic-app npm run build
```
### Email Testing with Mailpit

Mailpit captures all emails sent by the application for testing purposes. No emails are sent to real addresses.

```bash
# View all sent emails at:
http://localhost:8025

# Test email sending from CLI
docker exec clinic-app php artisan tinker
# Then in tinker:
Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

**When Emails Are Sent**:
- Patient books an appointment → Doctor receives notification
- Appointment completed → Patient receives notification
- User registers → Welcome email sent
- Password reset requests

**Viewing Emails**:
1. Open http://localhost:8025 in your browser
2. All emails appear in real-time
3. Click any email to view content, headers, and HTML/text versions
---

## 🔍 Troubleshooting

### Issue: Port Already in Use

**Error**: `Bind for 0.0.0.0:8000 failed: port is already allocated`

**Solution**:
```bash
# Find what's using the port
# Windows:
netstat -ano | findstr :8000

# Linux/Mac:
lsof -i :8000

# Option 1: Stop the conflicting process
# Option 2: Change the port in .env file:
APP_PORT=8080
```

### Issue: Container Keeps Restarting

**Diagnosis**:
```bash
docker logs clinic-app --tail 100
```

**Common Causes**:
1. **Database not ready**: Wait 30 seconds and check again
2. **Missing .env**: Ensure `.env` file exists
3. **Permission issues**: Run `docker-compose down -v` and restart

### Issue: 500 Internal Server Error

**Solution**:
```bash
# Clear all caches
docker exec clinic-app php artisan optimize:clear

# Check Laravel logs
docker exec clinic-app cat storage/logs/laravel.log

# Regenerate key if needed
docker exec clinic-app php artisan key:generate
```

### Issue: Database Connection Refused

**Solution**:
```bash
# Ensure database is running
docker-compose ps

# Wait for database to be healthy
docker-compose up -d --wait

# Check database logs
docker logs clinic-db
```

### Issue: Assets Not Loading (CSS/JS)

**Solution**:
```bash
# Rebuild frontend assets
docker exec clinic-app npm run build

# Clear view cache
docker exec clinic-app php artisan view:clear
```

### Issue: Email Connection Error (mailpit)

**Error**: `Connection could not be established with host "mailpit:1025"`

**Solution**:
```bash
# Ensure mailpit is running
docker ps | grep mailpit

# If not running, start it
docker-compose up -d mailpit

# Restart app to pick up changes
docker-compose restart app

# Verify mailpit is accessible
curl http://localhost:8025
```

### Complete Reset

If all else fails, perform a complete reset:

```bash
# Stop and remove everything (including volumes)
docker-compose down -v

# Remove all related images
docker rmi clinic-master-hu-app

# Fresh start
docker-compose up -d --build
```

---

## 💡 Development Tips

### Viewing Emails

All emails sent by the application are captured by Mailpit:

1. Open http://localhost:8025
2. See all sent emails in a nice UI
3. Great for testing password resets, appointment notifications, etc.

### Database Management

phpMyAdmin provides a web interface for database operations:

1. Open http://localhost:8080
2. Login with:
   - Server: `db`
   - Username: `clinic_user`
   - Password: `clinic_secret`

### Debugging

Laravel Telescope is enabled for debugging:

1. Login as admin
2. Visit http://localhost:8000/telescope
3. View requests, exceptions, queries, jobs, etc.

### Running Tests

```bash
# Run all tests
docker exec clinic-app php artisan test

# Run specific test
docker exec clinic-app php artisan test --filter=UserTest
```

### File Permissions Issues (Linux)

If you encounter permission issues:

```bash
docker exec clinic-app chown -R www-data:www-data storage bootstrap/cache
docker exec clinic-app chmod -R 775 storage bootstrap/cache
```

---

## 📁 Project Structure

```
clinic-master-hu/
├── docker/                    # Docker configuration files
│   ├── entrypoint.sh         # Container startup script
│   ├── nginx/                # Nginx configuration
│   ├── php/                  # PHP configuration
│   ├── mysql/                # MySQL initialization
│   └── supervisor/           # Process management
├── Dockerfile                # Main application image
├── docker-compose.yml        # Services orchestration
├── .env.docker              # Docker environment template
├── Modules/                  # Laravel modules
│   ├── Appointments/        # Appointment management
│   ├── Auth/                # Authentication
│   ├── Dashboard/           # Admin dashboard
│   ├── Doctors/             # Doctor management
│   ├── Patients/            # Patient management
│   ├── Payments/            # Payment processing
│   └── ...
├── app/                     # Core Laravel application
├── resources/               # Views, assets
└── storage/                 # Logs, uploads, cache
```

---

## ⚠️ Known Constraints & Limitations

### Routing
- **No `/admin` route exists** - The admin dashboard is at `/dashboard`
- The original README.md references `/admin` but this route was never implemented

### Default Seed Data
- By default, only **Admin** users are seeded (via `AdminSeeder`)
- **Doctor** and **Patient** test accounts must be created by running `TestUsersSeeder`
- The original README lists credentials with `@clinic.local` domain, but actual seeded email is `@clinic.com`

### Payments
- Stripe integration requires valid API keys in `.env`
- Without Stripe keys, payment features will not work

### Language
- The system is primarily in **Arabic (RTL)**
- English translations may be incomplete in some areas

### Features Requiring Additional Setup
1. **Email Notifications**: Configure SMTP or use Mailpit for testing
2. **Stripe Payments**: Add `STRIPE_KEY` and `STRIPE_SECRET` to `.env`
3. **Large-scale Test Data**: Run `php artisan db:seed --class=LargeScaleDataSeeder`

---

## 📞 Support

If you encounter issues not covered in this guide:

1. Check the [GitHub Issues](https://github.com/Abdallah-Assaker/clinic-master-hu/issues)
2. Review Laravel logs: `docker exec clinic-app cat storage/logs/laravel.log`
3. Review container logs: `docker-compose logs`

---

## 📄 License

This project is open-source software licensed under the MIT license.

---

**Happy Coding! 🚀**
