# MIT Kiosk - Staff Mobile App 📱

The official mobile application for MIT Kiosk staff (Faculty, Guidance, Principals).

## 🚀 Connectivity

The app is pre-configured to talk to the **Render Production Server**:
`https://laravel-web-app.onrender.com` (or your configured Render custom domain)

## 🛠️ Developer Setup

### NDK Fix (Critical)
This project uses a dependency override to fix crashes in environments with missing/broken Android NDKs:
- `path_provider_android: 2.2.12`

### Run Locally
```bash
flutter pub get
flutter run
```

## 📦 Building Standalone App

To create an APK that works on any network (Data/Wifi) without being connected to a computer:

1. **Build Release APK**:
   ```bash
   flutter build apk --release
   ```
2. **Transfer to Phone**:
   Find the file at `build/app/outputs/flutter-apk/app-release.apk` and send it to your device.

---

## 🎨 Features
- **Real-time Notifications**: Receive appointment requests instantly.
- **Availability Toggle**: Set your status to Available, Away, or Busy.
- **Consultation Tracking**: Start and finish consultations directly from the app.
- **Secure Authentication**: OTP-based login verified via Resend API.
