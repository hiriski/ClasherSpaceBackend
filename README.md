# Clash of Clans Base Layouts - Admin Panel

Platform komunitas Clash of Clans untuk berbagi dan mengelola base layout dengan sistem admin panel yang komprehensif menggunakan Laravel, Inertia.js, React, dan TypeScript.

## 🚀 Fitur Utama

### Admin Panel
- **Dashboard Statistik Global**: Overview total users, base layouts, views, dan likes
- **User Management**: Kelola semua user (view, detail, delete)
- **Base Layout Management**: Kelola semua base layout dari semua user
- **Category & Tag Management**: Kelola kategori dan tag untuk base layouts

### General User Panel
- **Personal Dashboard**: Statistik pribadi (total layouts, views, likes)
- **Base Layout CRUD**: Create, Read, Update, Delete base layout milik sendiri
- **Browse & Share**: Lihat dan share base layouts ke komunitas

### Authentication & Authorization
- **Role-based Access Control**: Admin dan General User dengan permissions berbeda
- **Laravel Policies**: Authorization untuk setiap aksi
- **Email Verification**: Verifikasi email untuk General User
- **Secure Authentication**: Laravel Breeze dengan Inertia.js

## 🧱 Tech Stack

### Backend
- **Framework**: Laravel 12
- **Authentication**: Laravel Breeze + Sanctum
- **Database**: MySQL/PostgreSQL
- **API**: RESTful API untuk mobile app (terpisah)

### Frontend (Admin Panel)
- **Framework**: React 18 with TypeScript
- **UI Framework**: Inertia.js (SSR)
- **Component Library**: Shadcn UI + Tailwind CSS 4
- **Form Handling**: React Hook Form + Zod Validation
- **Icons**: Lucide React
- **State Management**: Inertia.js (server-driven)

### Development Tools
- **Build Tool**: Vite
- **Type Checking**: TypeScript
- **Code Quality**: Laravel Pint (PHP), ESLint (TypeScript)

## 📋 Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- NPM or Yarn
- MySQL >= 8.0 atau PostgreSQL >= 13

## 🔧 Installation

### 1. Clone Repository

```bash
git clone <repository-url>
cd csb
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Edit file `.env` dan sesuaikan dengan konfigurasi database Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=csb_database
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations & Seeders

```bash
# Run migrations and seed database with sample data
php artisan migrate:fresh --seed
```

Seeder akan membuat:
- 1 Admin User
- 3 General Users
- Sample Categories & Tags
- Sample Base Layouts

### 6. Build Frontend Assets

```bash
# Development build with watch
npm run dev

# Production build
npm run build
```

### 7. Start Development Server

```bash
# Start Laravel development server
php artisan serve

# In another terminal, start Vite dev server
npm run dev
```

Aplikasi akan berjalan di `http://localhost:8000`

## 👤 Default Accounts

### Admin Account
- **Email**: admin@cocbases.com
- **Password**: password

### General User Accounts
- **Email**: john@example.com | **Password**: password
- **Email**: jane@example.com | **Password**: password
- **Email**: mike@example.com | **Password**: password

## 📁 Project Structure

```
csb/
├── app/
│   ├── Enums/              # Enumerations (Role, Status)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Web/        # Inertia Controllers
│   │   │   │   ├── Admin/  # Admin Controllers
│   │   │   │   └── ...     # User Controllers
│   │   │   └── ...         # API Controllers
│   │   ├── Middleware/     # Custom Middleware (EnsureUserHasRole)
│   │   └── Requests/       # Form Requests
│   ├── Models/             # Eloquent Models
│   ├── Policies/           # Authorization Policies
│   └── Services/           # Business Logic Services
├── database/
│   ├── migrations/         # Database Migrations
│   └── seeders/            # Database Seeders
├── resources/
│   ├── js/
│   │   ├── components/
│   │   │   └── ui/         # Shadcn UI Components
│   │   ├── Layouts/        # Page Layouts (AppLayout, AuthLayout)
│   │   ├── Pages/          # Inertia Pages
│   │   │   ├── Admin/      # Admin Pages
│   │   │   ├── User/       # General User Pages
│   │   │   ├── Auth/       # Authentication Pages
│   │   │   └── ...
│   │   ├── types/          # TypeScript Type Definitions
│   │   └── lib/            # Utility Functions
│   └── views/              # Blade Templates (minimal, for Inertia root)
└── routes/
    ├── web.php             # Web Routes (Inertia)
    └── api.php             # API Routes (untuk mobile app)
```

## 🔐 Authorization System

### Roles
- **admin**: Full access ke semua fitur
- **general_user**: Akses terbatas ke fitur user

### Permissions
Menggunakan Laravel Policy:

#### BaseLayoutPolicy
- `viewAny`: Semua user bisa melihat list
- `view`: Semua user bisa melihat detail
- `create`: Admin dan General User
- `update`: Admin (semua) atau owner (milik sendiri)
- `delete`: Admin (semua) atau owner (milik sendiri)

#### UserPolicy
- `viewAny`: Hanya Admin
- `view`: Admin atau user itu sendiri
- `create`: Hanya Admin
- `update`: Admin atau user itu sendiri
- `delete`: Hanya Admin (tidak bisa delete diri sendiri)

## 🚦 Routes Overview

### Admin Routes (`/admin/*`)
```
GET    /admin/dashboard              # Admin Dashboard
GET    /admin/users                  # Users List
GET    /admin/users/{id}             # User Detail
DELETE /admin/users/{id}             # Delete User
GET    /admin/base-layouts           # Base Layouts List
GET    /admin/base-layouts/{id}      # Base Layout Detail
DELETE /admin/base-layouts/{id}      # Delete Base Layout
GET    /admin/categories             # Categories List
GET    /admin/tags                   # Tags List
```

### General User Routes
```
GET  /dashboard                      # User Dashboard
GET  /base-layouts                   # My Base Layouts
GET  /base-layouts/create            # Create Base Layout Form
GET  /base-layouts/{id}              # Base Layout Detail
POST /base-layouts                   # Store Base Layout (implement as needed)
PUT  /base-layouts/{id}              # Update Base Layout (implement as needed)
DELETE /base-layouts/{id}            # Delete Base Layout (implement as needed)
```

### Auth Routes
```
GET  /login                          # Login Page
POST /login                          # Login Action
POST /logout                         # Logout
GET  /register                       # Register Page
POST /register                       # Register Action
GET  /forgot-password                # Forgot Password
POST /forgot-password                # Send Reset Link
GET  /reset-password/{token}         # Reset Password Page
POST /reset-password                 # Reset Password Action
GET  /verify-email                   # Email Verification Notice
GET  /verify-email/{id}/{hash}       # Verify Email
POST /email/verification-notification # Resend Verification
```

## 📝 API Routes (untuk Mobile App)

API tersedia di `/api/v1/*` untuk integrasi dengan mobile app. Dokumentasi API terpisah.

## 🎨 Frontend Components

### Shadcn UI Components
- Button
- Card
- Badge
- Table
- Form Components (Input, Label, etc.)
- Dialog/Modal
- Dropdown Menu
- Toast Notifications (dapat ditambahkan)

### Custom Components
- AppLayout: Main layout dengan sidebar navigation
- AuthLayout: Layout untuk halaman authentication (dari Breeze)

## 🔄 Development Workflow

### Running in Development

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server (hot reload)
npm run dev

# Terminal 3: Queue worker (optional)
php artisan queue:work

# Terminal 4: Log monitoring (optional)
php artisan pail
```

### Building for Production

```bash
# Build optimized assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🧪 Testing

```bash
# Run PHP tests
php artisan test

# Run with coverage
php artisan test --coverage
```

## 📦 Next Steps / Future Enhancements

### Immediate (Implement as needed)
- [ ] Complete CRUD operations for Base Layouts (general user)
- [ ] Implement file upload for base layout screenshots
- [ ] Add image optimization and storage
- [ ] Implement Categories and Tags CRUD
- [ ] Add search and filtering functionality
- [ ] Implement sorting and advanced filtering

### Short-term
- [ ] Add toast notifications untuk feedback
- [ ] Implement bulk actions (delete multiple)
- [ ] Add export functionality (CSV, PDF)
- [ ] Implement base layout rating system
- [ ] Add comments system untuk base layouts

### Long-term
- [ ] Create public Next.js app untuk SEO-friendly browsing
- [ ] Mobile app integration (React Native/Flutter)
- [ ] Advanced analytics dashboard
- [ ] Real-time notifications
- [ ] Social features (follow users, save favorites)
- [ ] Integration dengan Clash of Clans API

## 🐛 Troubleshooting

### Build Errors
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild frontend
rm -rf node_modules
npm install
npm run build
```

### Database Issues
```bash
# Reset database
php artisan migrate:fresh --seed
```

### Permission Issues (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📧 Support

For support, email support@cocbases.com or join our Discord community.

---

**Built with ❤️ using Laravel, Inertia.js, React, and TypeScript**
