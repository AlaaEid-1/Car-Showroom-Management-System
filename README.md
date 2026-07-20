<div align="center">

# 🚗 Alaa Car Explore

### A Modern, Multi-Role Car Showroom Platform

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com/)
[![Gemini AI](https://img.shields.io/badge/Gemini_2.5_Flash-AI_Powered-4285F4?style=for-the-badge&logo=google&logoColor=white)](https://deepmind.google/gemini/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.0-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com/)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-77C1D2?style=for-the-badge&logo=alpine.js&logoColor=white)](https://alpinejs.dev/)
[![Vite](https://img.shields.io/badge/Vite-8.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vite.dev/)

<br/>

> A full-featured car showroom platform where **customers** discover and inquire about cars,
> **dealers** manage their inventory powered by **Gemini AI**, and **admins** control the entire ecosystem —
> all connected through a real-time messaging & notification system.

![Status](https://img.shields.io/badge/Status-Active-brightgreen?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-blue?style=flat-square)
![PRs Welcome](https://img.shields.io/badge/PRs-Welcome-orange?style=flat-square)

</div>

---

## 📋 Table of Contents

- [🌟 Highlight Features](#-highlight-features)
- [✨ Full Feature List](#-full-feature-list)
- [🛠️ Tech Stack](#️-tech-stack)
- [🏗️ Project Architecture](#️-project-architecture)
- [🗄️ Database Schema](#️-database-schema)
- [🔐 Roles & Permissions](#-roles--permissions)
- [🌐 Routes Reference](#-routes-reference)
- [🚀 Getting Started](#-getting-started)
- [⚙️ Environment Configuration](#️-environment-configuration)
- [🧪 Running Tests](#-running-tests)
- [🤝 Contributing](#-contributing)
- [📄 License](#-license)

---

## 🌟 Highlight Features

> The three flagship systems that make this platform stand out.

---

### 🤖 Gemini AI — Smart Car Listing Assistant

Dealers don't write car descriptions manually anymore. **Google Gemini 2.5 Flash is embedded directly inside the car creation and edit form** — not a separate page, not an external tool — right there as a button while filling in the listing.

**How it works inside the create/edit form:**

```
Dealer opens the "Add Car" or "Edit Car" form
         ↓
Fills in basic details: brand, model, year, price
         ↓
Clicks the "Generate with AI" button inside the form
         ↓
Gemini 2.5 Flash instantly returns:
  ✅ Professional marketing title
  ✅ Full showroom-quality description
  ✅ 3 polished highlight bullet points
         ↓
Fields are auto-filled in the form
         ↓
Dealer reviews, tweaks if needed, and publishes
```

**Two AI actions available — both triggered from within the form:**
- **Generate** — Dealer fills brand/model/year/price → AI writes the full listing from scratch
- **Improve** — Already have a listing? AI reads the existing content and rewrites it better

> If the Gemini API is unreachable, the system gracefully falls back to a smart mock generator — zero crashes, zero empty fields, always a result.

---

### 💬 Real-Time Threaded Messaging System

Every car has an **Inquire** button. When a customer clicks it, a private threaded conversation opens between them and the dealer — like a private inbox, per car.

```
Customer clicks "Inquire" on a car
         ↓
Inquiry thread is created (linked to car + dealer)
         ↓
Customer sends first message
         ↓
Dealer receives ✉️ email + 🔔 in-app notification instantly
         ↓
Dealer replies → Customer gets notified
         ↓
Either party can close the thread when done
```

**Key behaviors:**
- A dealer **cannot** inquire about their own car
- Inquiry status auto-updates: `open` → `pending` → `answered` → `closed`
- `last_message_at` timestamp is tracked for sorting active threads
- Policy-guarded: only the buyer and dealer of that inquiry can read or reply

---

### 🔔 Dual-Channel Notification System

Every important action fires a notification through **two channels simultaneously**:

| Event | Who Gets Notified | Channels |
|-------|-------------------|----------|
| New inquiry on your car | Dealer | 📧 Email + 🔔 Database |
| New reply in a thread | Other party | 📧 Email + 🔔 Database |
| Test drive request received | Dealer | 📧 Email + 🔔 Database |
| Test drive status updated | Customer | 📧 Email + 🔔 Database |
| Dealer application submitted | Admin | 📧 Email + 🔔 Database |
| Dealer application approved | Applicant | 📧 Email + 🔔 Database |
| Dealer application rejected | Applicant | 📧 Email + 🔔 Database |

All notifications are **queued** (not blocking) via Laravel's database queue driver — the user's request completes instantly while the notification is sent in the background.

**Notification center features:**
- Mark a single notification as read
- Mark all as read at once
- Unread count badge in the navigation

---

### 🔒 Laravel Policies — Fine-Grained Authorization

Every sensitive action in the platform is protected by a **Laravel Policy** — not just middleware, but model-level authorization enforced inside the controller via `Gate::authorize()`.

```
CarPolicy         → Only the car's owner dealer can edit, delete, or restore it
                    A dealer cannot touch another dealer's listings

InquiryPolicy     → Only the exact buyer + dealer of that thread can view or reply
                    No other user — including admins browsing — can peek in

ShowroomPolicy    → A dealer can only modify their own showroom profile
                    Creating a second showroom is blocked at the policy level

TestDrivePolicy   → A dealer can only accept/decline test drives on their own cars
                    Prevents cross-dealer booking manipulation
```

**Why this matters:** Even if a user guesses a URL like `/dashboarddealer/cars/999/edit`, the Policy checks ownership at the model level and returns `403 Forbidden` — not just a redirect.

---

## ✨ Full Feature List

### 🏠 For Customers
| Feature | Description |
|---------|-------------|
| 🔍 **Advanced Search & Filter** | Filter by brand, model, type, year, and price range |
| 🚗 **Rich Car Profiles** | Full details page with multi-image gallery |
| ❤️ **Favorites System** | Save and manage your favorite cars |
| 🗓️ **Test Drive Booking** | Request a test drive directly from any car page |
| 💬 **Threaded Inquiries** | Private conversations with dealers per car |
| 🔔 **Smart Notifications** | Email + in-app alerts for every update |
| 👤 **Personal Dashboard** | View all your inquiries, favorites, and bookings |

### 🏪 For Dealers
| Feature | Description |
|---------|-------------|
| 🤖 **Gemini AI Assistant** | Auto-generate or improve car descriptions with AI |
| 🚘 **Full Car Management** | Create, edit, soft-delete, and restore listings |
| 🖼️ **Multi-Image Upload** | Upload multiple photos, set a primary cover image |
| 🏢 **Showroom Profile** | Customize your showroom (name, location, contact) |
| 💬 **Inquiry Management** | Respond to customers in a clean threaded interface |
| 🗓️ **Test Drive Requests** | Accept or decline incoming booking requests |
| 🔔 **Instant Notifications** | Get alerted the moment a customer inquires |

### 🛡️ For Admins
| Feature | Description |
|---------|-------------|
| 👥 **User Management** | View, activate, deactivate, or delete any user |
| 🏪 **Dealer Approval System** | Review and approve or reject dealer applications |
| 🚗 **Platform Car Oversight** | Monitor and remove any listing on the platform |
| 🏢 **Showroom Control** | Manage the status of all registered showrooms |
| 💬 **Inquiry Monitoring** | Read-only view of all conversations on the platform |

---

## 🛠️ Tech Stack

<table>
<tr>
<td valign="top" width="50%">

### ⚙️ Backend
| Technology | Role |
|-----------|------|
| **Laravel 13.x** | Core PHP framework |
| **PHP 8.4** | Language (Enums, Attributes, Property Hooks) |
| **MySQL** | Production-grade relational database |
| **Google Gemini 2.5 Flash** | AI listing generation |
| **Laravel Fortify** | Authentication & account security |
| **Eloquent ORM** | Fluent database interaction |
| **Laravel Policies** | Fine-grained authorization per model |
| **Laravel Notifications** | Multi-channel notification dispatch |
| **Queue (Database Driver)** | Non-blocking background processing |
| **Gmail SMTP** | Transactional email delivery |

</td>
<td valign="top" width="50%">

### 🎨 Frontend & Tooling
| Technology | Role |
|-----------|------|
| **Blade Templates** | Laravel's powerful templating engine |
| **Tailwind CSS 4.0** | Utility-first styling framework |
| **Alpine.js 3.x** | Lightweight JavaScript reactivity |
| **Vite 8.x** | Lightning-fast asset bundling + HMR |
| **Pest PHP** | Modern elegant testing framework |
| **Laravel Pint** | Automatic PSR-12 code formatter |
| **Laravel Pail** | Real-time terminal log monitoring |
| **Faker** | Realistic fake data for testing |

</td>
</tr>
</table>

---

## 🏗️ Project Architecture

```
Car-Showooroom/
│
├── 📁 app/
│   ├── 📁 Actions/                        # Isolated business logic (Single Responsibility)
│   │
│   ├── 📁 Enums/
│   │   └── DealerRequestStatus.php         # Pending | Approved | Rejected
│   │
│   ├── 📁 Http/
│   │   ├── 📁 Controllers/
│   │   │   │
│   │   │   ├── 📁 Admin/
│   │   │   │   └── AdminController.php        # Full admin panel — users, dealers, cars, showrooms
│   │   │   │
│   │   │   ├── 📁 Customer/
│   │   │   │   ├── FavoriteController.php      # Add / remove favorites
│   │   │   │   └── TestDriveController.php     # Book a test drive + trigger notification
│   │   │   │
│   │   │   ├── 📁 Dealer/
│   │   │   │   ├── 📁 AI/
│   │   │   │   │   └── CarAssistantController.php   # Gemini AI: generate & improve listings
│   │   │   │   ├── CarController.php                # Full CRUD + image management + soft deletes
│   │   │   │   ├── DashboardController.php           # Dealer stats & overview
│   │   │   │   ├── InquiryController.php             # Threaded messaging with customers
│   │   │   │   ├── ShowroomController.php            # Showroom profile management
│   │   │   │   └── TestDriveController.php           # Accept / decline test drive requests
│   │   │   │
│   │   │   ├── 📁 Api/
│   │   │   │   └── CarApiController.php    # Public JSON API for car listing
│   │   │   │
│   │   │   ├── HomeController.php          # Homepage, hero section, browsing
│   │   │   ├── ProfileController.php       # User profile updates
│   │   │   ├── NotificationController.php  # Read, mark, mark-all notifications
│   │   │   └── DealerRequestController.php # Submit dealer application
│   │   │
│   │   ├── 📁 Middleware/                  # Auth guards, role checks, access layers
│   │   └── 📁 Requests/                   # Form Requests for input validation
│   │
│   ├── 📁 Models/
│   │   ├── User.php             # Customer / Dealer / Admin (role field)
│   │   ├── Car.php              # Listing with SoftDeletes, status (draft/published/sold)
│   │   ├── CarImage.php         # Multiple images, is_main flag
│   │   ├── Showroom.php         # Dealer's showroom profile
│   │   ├── Inquiry.php          # Conversation thread per car
│   │   ├── InquiryMessage.php   # Individual messages within a thread
│   │   ├── TestDrive.php        # Booking with status tracking
│   │   └── DealerRequest.php    # Application with enum-driven status
│   │
│   ├── 📁 Notifications/               # 7 notification types — all queued, dual-channel
│   │   ├── NewInquiry.php                    → DB + Email to dealer
│   │   ├── NewInquiryReply.php               → DB + Email to recipient
│   │   ├── NewTestDriveRequest.php           → DB + Email to dealer
│   │   ├── TestDriveStatusUpdated.php        → DB + Email to customer
│   │   ├── NewDealerRequestNotification.php  → DB + Email to admin
│   │   ├── DealerRequestApprovedNotification.php  → DB + Email to applicant
│   │   └── DealerRequestRejectedNotification.php  → DB + Email to applicant
│   │
│   ├── 📁 Policies/                    # Laravel Authorization — model-level access control
│   │   ├── CarPolicy.php           # Only the car's owner dealer can manage it
│   │   ├── InquiryPolicy.php       # Only buyer + dealer of that inquiry can read/reply
│   │   ├── ShowroomPolicy.php      # Dealer can only edit their own showroom
│   │   └── TestDrivePolicy.php     # Dealer controls their own test drives
│   │
│   └── 📁 Services/
│       ├── CarService.php                    # Search, filter, query logic
│       └── 📁 AI/
│           └── CarListingAssistantService.php # Gemini API integration + mock fallback
│
├── 📁 database/
│   ├── 📁 migrations/              # 16 migration files — complete, ordered schema
│   ├── 📁 factories/               # Model factories for fake test data
│   └── 📁 seeders/                 # Database seeders
│
├── 📁 resources/
│   ├── 📁 views/
│   │   ├── welcome.blade.php          # Homepage: hero, search, featured cars
│   │   ├── resultscars.blade.php      # Search results with filter sidebar
│   │   ├── 📁 admin/                 # Admin panel views
│   │   ├── 📁 dashboarddealer/       # Dealer dashboard (cars, inquiries, AI)
│   │   ├── 📁 dashboardcustomer/     # Customer dashboard (favorites, bookings)
│   │   ├── 📁 auth/                  # Login, register, password reset
│   │   ├── 📁 inquiries/             # Threaded messaging UI
│   │   ├── 📁 notifications/         # Notification center
│   │   └── 📁 components/            # Reusable Blade components
│   ├── 📁 css/                       # Custom Tailwind styles
│   └── 📁 js/                        # JavaScript entry points
│
├── 📁 routes/
│   └── web.php                        # All routes — clean, grouped by role
│
├── 📁 tests/                          # Pest PHP test suite
├── 📄 composer.json                   # PHP dependencies + dev scripts
├── 📄 package.json                    # Node.js dependencies
├── 📄 vite.config.js                  # Vite bundler config
└── 📄 .env.example                    # Environment variables template
```

---

## 🗄️ Database Schema

**16 tables** built through timestamped, ordered migrations on **MySQL**:

| Table | Description |
|-------|-------------|
| `users` | All users — role field determines Customer / Dealer / Admin |
| `showrooms` | Dealer showroom profiles (one per dealer) |
| `cars` | Listings with `status` (draft/published/sold) + SoftDeletes |
| `car_images` | Multiple images per car + `is_main` boolean flag |
| `favorites` | Customer ↔ Car many-to-many pivot |
| `inquiries` | Threaded conversation per car with `status` + `last_message_at` |
| `inquiry_messages` | Messages with `sender_id` inside each inquiry |
| `test_drives` | Booking requests with accept/decline status |
| `dealer_requests` | Role-upgrade applications with enum status |
| `notifications` | Laravel database notifications (polymorphic, queued) |
| `jobs` | Laravel queue jobs table |
| `cache` | Application cache backend |
| `sessions` | Database-backed user sessions |

### Entity Relationships

```
User ──────────────< Showroom                 (one-to-one: dealer has one showroom)
Showroom ───────────< Car                     (one-to-many: showroom lists many cars)
Car ──────────────── < CarImage               (one-to-many: car has many images, one is_main)
User >──────────────< Car    (favorites)      (many-to-many via favorites pivot table)
Car ────────────────< Inquiry                 (one-to-many: car has many inquiry threads)
Inquiry ────────────< InquiryMessage          (one-to-many: thread has many messages)
User ────────────────< TestDrive >── Car      (bookings linked to user + car)
User ────────────────< DealerRequest          (one application at a time per user)
User ────────────────< Notification           (polymorphic, queued notifications)
```

---

## 🔐 Roles & Permissions

Three-tier role system enforced through **Laravel Policies** and route middleware:

```
┌──────────────┬─────────────────────────────────────────────────────────────┐
│  Role        │  Capabilities                                               │
├──────────────┼─────────────────────────────────────────────────────────────┤
│ 👤 Customer  │  Browse cars, search & filter, view car details,            │
│              │  save favorites, book test drives, open inquiries,           │
│              │  receive notifications via DB + Email                        │
├──────────────┼─────────────────────────────────────────────────────────────┤
│ 🏪 Dealer    │  Everything a customer can + manage their showroom,          │
│              │  CRUD their car listings, use Gemini AI assistant,           │
│              │  reply to inquiries, accept/decline test drives,             │
│              │  receive notifications for all dealer events                 │
├──────────────┼─────────────────────────────────────────────────────────────┤
│ 🛡️  Admin   │  Full platform oversight: manage all users, approve/reject   │
│              │  dealer applications, remove any car or showroom,            │
│              │  monitor all inquiries platform-wide                        │
└──────────────┴─────────────────────────────────────────────────────────────┘
```

**Dealer Upgrade Flow:**
```
User submits POST /become-dealer
        ↓
Admin receives DB + Email notification
        ↓
Admin reviews in /admin/dealers/requests
        ↓
   Approve → User role upgraded to dealer + Email sent
   Reject  → Application closed + Email sent explaining outcome
```

---

## 🌐 Routes Reference

### 🌍 Public Routes

```http
GET  /                           # Homepage — hero, search, featured cars
GET  /cars/search                # Search results with advanced filters
GET  /cars/{car}                 # Full car detail page with images
GET  /api/vehicle-list           # Public JSON API for car listings
```

### 👤 Customer Routes *(Auth Required)*

```http
# ── Inquiries (Threaded Messaging) ────────────────────────────────────
POST   /cars/{car}/inquiry              # Open a new inquiry on a car
GET    /inquiries                       # My inquiry threads list
GET    /inquiries/{id}                  # View thread with all messages
POST   /inquiries/{id}/message          # Send a new message in thread
POST   /inquiries/{id}/close            # Close the thread

# ── Favorites ─────────────────────────────────────────────────────────
POST   /cars/{car}/favorite             # Save car to favorites
DELETE /cars/{car}/favorite             # Remove from favorites

# ── Test Drives ───────────────────────────────────────────────────────
POST   /cars/{car}/test-drive           # Book a test drive

# ── Account & Notifications ───────────────────────────────────────────
PATCH  /profile                         # Update my profile
POST   /become-dealer                   # Apply to become a dealer
GET    /notifications                   # My notification center
PATCH  /notifications/{id}/read        # Mark one as read
POST   /notifications/read-all          # Mark all as read
```

### 🏪 Dealer Dashboard `[/dashboarddealer]`

```http
# ── Overview ──────────────────────────────────────────────────────────
GET    /dashboarddealer/                    # Stats, recent inquiries, bookings
GET    /dashboarddealer/profile             # My dealer profile

# ── Showroom ──────────────────────────────────────────────────────────
GET    /dashboarddealer/showroom            # View / edit showroom settings
POST   /dashboarddealer/showroom            # Create showroom
PATCH  /dashboarddealer/showroom            # Update showroom

# ── Car Listings ──────────────────────────────────────────────────────
GET    /dashboarddealer/cars                         # My car listings
POST   /dashboarddealer/cars                         # Add a new car
GET    /dashboarddealer/cars/{car}                   # View a car
PUT    /dashboarddealer/cars/{car}                   # Edit car details
DELETE /dashboarddealer/cars/{car}                   # Soft delete (recoverable)
PATCH  /dashboarddealer/cars/{car}/restore           # Restore soft-deleted car
DELETE /dashboarddealer/cars/{car}/force-delete      # Permanently delete
DELETE /dashboarddealer/cars/{car}/images/{image}    # Remove one image
POST   /dashboarddealer/cars/{car}/images/{image}/main  # Set as cover image

# ── Test Drive Management ─────────────────────────────────────────────
GET    /dashboarddealer/test-drives             # Incoming test drive requests
PATCH  /dashboarddealer/test-drives/{testDrive} # Accept or decline

# ── Gemini AI ─────────────────────────────────────────────────────────
POST   /dashboarddealer/ai/generate             # Generate new listing with AI
POST   /dashboarddealer/ai/improve/{car}        # Improve existing listing with AI
```

### 🛡️ Admin Panel `[/admin]`

```http
GET    /admin/dashboard                     # Platform stats overview
GET    /admin/users                         # All registered users
PATCH  /admin/users/{user}/status           # Activate or deactivate a user
DELETE /admin/users/{user}                  # Permanently delete a user
GET    /admin/dealers                       # All verified dealers
GET    /admin/dealers/requests              # Pending dealer applications
PATCH  /admin/dealers/{req}/approve         # Approve dealer application
PATCH  /admin/dealers/{req}/reject          # Reject dealer application
PATCH  /admin/dealers/{user}/status         # Enable or disable a dealer
GET    /admin/cars                          # All platform car listings
DELETE /admin/cars/{car}                    # Remove a car
GET    /admin/showrooms                     # All registered showrooms
PATCH  /admin/showrooms/{showroom}/status   # Toggle showroom status
GET    /admin/inquiries                     # Monitor all inquiry threads
```

---

## 🚀 Getting Started

### Prerequisites

| Tool | Version | Notes |
|------|---------|-------|
| **PHP** | 8.4 | Extensions: pdo_mysql, mbstring, openssl, fileinfo |
| **Composer** | ≥ 2.x | PHP dependency manager |
| **MySQL** | ≥ 8.0 | Production database |
| **Node.js** | ≥ 18.x | JavaScript runtime |
| **npm** | ≥ 9.x | Bundled with Node.js |

---

### ⚡ Quick Setup

```bash
# 1. Clone the repository
git clone https://github.com/AlaaEid-1/alaacarexplore.git
cd alaacarexplore

# 2. Run the all-in-one setup script
composer run setup
```

> **`composer run setup` does the following in order:**
> - Installs all PHP dependencies
> - Copies `.env.example` → `.env`
> - Generates the application encryption key
> - Runs all 16 database migrations
> - Installs Node.js dependencies
> - Builds frontend assets

---

### 🔧 Manual Setup (Step by Step)

```bash
# 1. Install PHP dependencies
composer install

# 2. Create environment file
cp .env.example .env

# 3. Generate app encryption key
php artisan key:generate

# 4. Configure your MySQL database in .env, then run migrations
php artisan migrate

# 5. (Optional) Seed sample data
php artisan db:seed

# 6. Install frontend dependencies
npm install

# 7. Start the Vite dev server
npm run dev
```

---

### 🎛️ Running the Full Dev Environment

```bash
composer run dev
```

Launches **three parallel processes** with color-coded output:

```
🔵  php artisan serve          →  PHP server at http://localhost:8000
🟣  php artisan queue:listen   →  Background queue (sends notifications)
🟠  npm run dev                →  Vite HMR for instant frontend reloading
```

> The queue worker is **required** for notifications to be dispatched — always run `composer run dev` instead of `php artisan serve` alone.

---

## ⚙️ Environment Configuration

```bash
cp .env.example .env
```

Then configure these key sections:

```env
# ─────────────────────────────────────────
#  Application
# ─────────────────────────────────────────
APP_NAME="Alaa Car Explore"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# ─────────────────────────────────────────
#  Database — MySQL
# ─────────────────────────────────────────
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Alaacarexplore
DB_USERNAME=root
DB_PASSWORD=your_password

# ─────────────────────────────────────────
#  Queue, Cache & Sessions
# ─────────────────────────────────────────
SESSION_DRIVER=database
QUEUE_CONNECTION=database     # Required for queued notifications
CACHE_STORE=database

# ─────────────────────────────────────────
#  Mail — Gmail SMTP
# ─────────────────────────────────────────
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_gmail@gmail.com
MAIL_PASSWORD=your_app_password     # Use a Gmail App Password, not your real password
MAIL_FROM_ADDRESS="your_gmail@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"

# ─────────────────────────────────────────
#  Gemini AI — Google Generative Language
# ─────────────────────────────────────────
GEMINI_API_KEY=your_gemini_api_key
GEMINI_MODEL=gemini-2.5-flash
```

> **💡 Gmail App Password:** Go to [myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords), generate a 16-character app password, and paste it as `MAIL_PASSWORD`. Never use your actual Gmail password.

> **💡 Gemini API Key:** Get yours free at [aistudio.google.com](https://aistudio.google.com). If the key is missing, the AI assistant automatically falls back to a smart mock generator — no crashes.

---

## 🧪 Running Tests

This project uses **Pest PHP** — a modern testing framework built on PHPUnit.

```bash
# Run the full test suite
composer run test

# Run via Artisan
php artisan test

# Filter to a specific test
php artisan test --filter=CarTest

# Show code coverage
php artisan test --coverage
```

### Example Tests (Pest syntax)

```php
it('allows a dealer to generate an AI car listing', function () {
    $dealer = User::factory()->dealer()->create();

    actingAs($dealer)
        ->post(route('dashboarddealer.ai.generate'), [
            'brand' => 'Toyota',
            'model' => 'Camry',
            'year'  => 2024,
            'price' => 35000,
        ])
        ->assertOk()
        ->assertJsonStructure(['title', 'description', 'highlights']);
});

it('notifies the dealer when a customer sends an inquiry', function () {
    $customer = User::factory()->create();
    $dealer   = User::factory()->dealer()->create();
    $car      = Car::factory()->for($dealer)->create();

    actingAs($customer)
        ->post(route('inquiries.store', $car), ['message' => 'Is this still available?'])
        ->assertRedirect();

    expect($dealer->notifications)->toHaveCount(1);
    expect($dealer->notifications->first()->type)->toContain('NewInquiry');
});

it('prevents a dealer from inquiring about their own car', function () {
    $dealer = User::factory()->dealer()->create();
    $car    = Car::factory()->for($dealer)->create();

    actingAs($dealer)
        ->post(route('inquiries.store', $car), ['message' => 'Hello?'])
        ->assertSessionHasErrors();
});
```

---

## 🤝 Contributing

```bash
# 1. Fork and clone the repository
git clone https://github.com/your-username/alaacarexplore.git

# 2. Create a feature branch
git checkout -b feature/your-feature-name

# 3. Make changes, then auto-format
composer exec pint

# 4. Run tests
php artisan test

# 5. Commit (Conventional Commits format)
git commit -m "feat: add AI-powered price suggestion"

# 6. Push and open a Pull Request
git push origin feature/your-feature-name
```

### Code Standards
- ✅ **PSR-12** coding standard — enforced by Laravel Pint
- ✅ Business logic in **Services** or **Actions**, not controllers
- ✅ All authorization through **Laravel Policies**
- ✅ Write **Pest tests** for every new feature
- ✅ Use **Conventional Commits**: `feat:`, `fix:`, `docs:`, `refactor:`

---

## 📄 License

This project is licensed under the **MIT License** — see the [LICENSE](LICENSE) file for details.

---

<div align="center">

<br/>

Built with ❤️ by **Alaa Eid**

[![GitHub](https://img.shields.io/badge/GitHub-AlaaEid--1-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/AlaaEid-1)

<br/>

*If this project helped you, consider giving it a ⭐ on GitHub!*

</div>
