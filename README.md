# MIT Kiosk - Capstone Project 🚀

A comprehensive Appointment and Consultation Management System designed for MIT, featuring a Laravel 11 backend and a Flutter-based mobile application for staff.

## 🛠️ System Architecture

- **Backend**: Laravel 11 (PHP 8.2+) hosted on **Render** (via Docker).
- **Database**: PostgreSQL (Render Managed Database).
- **Mobile App**: Flutter (Staff Portal) for Android.
- **Email Service**: Resend API for OTP and notifications.

## 🌐 Production Environment

- **Live URL**: `https://laravel-web-app.onrender.com` (or your configured Render custom domain)
- **API Base**: `https://laravel-web-app.onrender.com/api`

---

## ☁️ Render Deployment (Blueprint)

This project is configured to deploy directly to **Render** using the provided `render.yaml` Blueprint specification. This automatically provisions a PostgreSQL database and a containerized Laravel Web Service.

### Setup Instructions
1. **Prepare Environment Variables**:
   - Make sure you have your Resend API Key (`RESEND_API_KEY`) and other custom credentials ready.
2. **Deploy via Blueprint**:
   - Go to your Render Dashboard.
   - Click **New +** -> **Blueprint**.
   - Connect your GitHub repository containing this project.
   - Render will detect `render.yaml` and prompt you to create the resources:
     - `laravel-postgres-db`: A free-tier PostgreSQL database.
     - `laravel-web-app`: A containerized Nginx/PHP-FPM web service.
3. **Configure the Application Key**:
   - Once the services are created, generate a production key locally:
     ```bash
     php artisan key:generate --show
     ```
   - In the Render Dashboard, go to your **laravel-web-app** settings -> **Environment**.
   - Add a new environment variable `APP_KEY` and paste the generated key.
   - Save changes. Render will automatically redeploy the service with the new key.

### Database Migrations & Caching
The Docker configuration automatically runs deployment commands at startup using the `scripts/00-laravel-deploy.sh` script:
- Runs database migrations (`php artisan migrate --force`).
- Caches configurations, routes, and views for optimal performance.

---

## 📱 Mobile App (Flutter Staff App)

The mobile application is designed for faculty, principals, and guidance counselors to manage requests on the go.

### 🔧 Build Requirements & Workarounds
Due to specific environment constraints (missing NDK/Android Studio in some dev environments), the following workaround is implemented in `pubspec.yaml`:

- **NDK Crash Fix**: We use a `dependency_override` for `path_provider_android: 2.2.12`. This version avoids modern JNI calls that trigger crashes in broken NDK environments while remaining compatible with Flutter 3.x.
- **Rendering**: Impeller is disabled in `AndroidManifest.xml` for maximum compatibility with older Android devices.

### 🚀 How to Build
1. Navigate to the app directory:
   ```powershell
   cd flutter_staff_app
   ```
2. Build the standalone APK:
   ```powershell
   flutter build apk --release
   ```
3. Locate the binary:
   `build\app\outputs\flutter-apk\app-release.apk`

---

## 📡 Key API Endpoints (Mobile)

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/mobile/login` | POST | Authenticate staff and receive Bearer Token |
| `/api/mobile/me` | GET | Retrieve current user profile |
| `/api/mobile/appointments` | GET | List pending/accepted appointment requests |
| `/api/mobile/consultations` | GET | List active/scheduled consultations |
| `/api/mobile/profile/availability` | POST | Update staff availability (Available/Away/Busy) |

---

## 📝 Developer Notes

- **Environment Variables**: Managed via Render dashboard environment variables and local `.env` (excluded from Git).
- **Security**: Sanctum-based API tokens for mobile authentication.
- **OTP**: Delivered via Resend API to the registered staff email.

---
