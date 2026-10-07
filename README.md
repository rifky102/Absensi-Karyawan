# Sistem Absensi Karyawan dengan Geofencing

Aplikasi web modern untuk manajemen absensi karyawan di Yayasan dengan fitur geofencing berbasis lokasi GPS.

## 🎯 Fitur Utama

### Untuk Administrator/Management
- **Dashboard Admin**: Statistik real-time jumlah karyawan, guru, dan absensi harian
- **Manajemen User**: Tambah, edit, hapus user dengan berbagai role
- **Setting Lokasi Geofencing**: 
  - Setup titik lokasi gedung unit (TK, SD, SMP)
  - Hingga 3 lokasi per unit
  - Interactive map picker dengan Leaflet
  - Radius geofencing 10 meter per lokasi
- **Laporan Absensi**: Filter dan lihat laporan lengkap dengan berbagai kriteria
- **Profil**: Edit data pribadi dan ubah password

### Untuk Karyawan/Guru
- **Dashboard Karyawan**: Status absensi hari ini dan statistik absensi
- **Absensi dengan Geofencing**:
  - Check-in dan check-out dengan verifikasi GPS
  - Hanya bisa absen dalam radius 10m dari lokasi unit
  - Interactive map menampilkan posisi real-time
  - Koordinat GPS tercatat untuk audit trail
- **Riwayat Absensi**: Lihat history lengkap dengan pagination
- **Profil**: Edit nama, email, nomor telepon, alamat, dan ubah password

## 📋 Role & Jabatan

- **Admin/Management**: Pengurus penuh sistem
- **Guru**: Staf pengajar (dapat absensi dengan geofencing)
- **OB**: Office Boy
- **Keamanan**: Tim keamanan
- **Staff IT**: Staf IT
- **TU**: Tata Usaha
- **Bidang Usaha**: Divisi bisnis

## 🏗️ Struktur Database

### Tabel Utama
- `users`: Akun pengguna dengan role dan unit
- `units`: Unit (TK, SD, SMP)
- `locations`: Titik lokasi geofencing per unit
- `attendances`: Record absensi harian dengan GPS coordinates
- `employee_profiles`: Data lengkap karyawan (NIK, alamat, TMT, etc)

## 🛠️ Tech Stack

- **Backend**: Laravel 13
- **Frontend**: Blade Templates + Tailwind CSS v4
- **Database**: SQLite (default, bisa diubah)
- **Maps**: Leaflet + OpenStreetMap
- **CSS Framework**: Tailwind CSS dengan custom components
- **Build Tool**: Vite

## 📦 Instalasi & Setup

### Prerequisites
- PHP 8.5+
- Composer
- Node.js & npm
- Modern browser dengan support GPS

### Step by Step

1. **Clone atau extract project**
   ```bash
   cd absensi-karyawan
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Frontend Dependencies**
   ```bash
   npm install
   ```

4. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Setup Database**
   ```bash
   php artisan migrate
   php artisan db:seed --class=UnitSeeder
   ```

6. **Build Frontend Assets**
   ```bash
   npm run build
   ```

   Untuk development dengan auto-reload:
   ```bash
   npm run dev
   ```

7. **Jalankan Development Server**
   ```bash
   php artisan serve
   ```

   Akses di: http://127.0.0.1:8000

## 🔐 Default Credentials

**Admin Account**
- Username: `admin`
- Email: `admin@yayasan.com`
- Password: `admin123456`

**Ubah password setelah login pertama kali!**

## 📱 Fitur Geofencing

### Cara Kerja
1. Sistem menggunakan GPS browser untuk mendapatkan koordinat real-time pengguna
2. Setiap lokasi unit memiliki titik pusat (latitude/longitude) dan radius 10 meter
3. Check-in/Check-out hanya bisa dilakukan jika pengguna berada dalam radius tersebut
4. Aplikasi menggunakan Haversine formula untuk menghitung jarak akurat

### Setup Lokasi
1. Login sebagai Admin
2. Ke menu "Setting Lokasi"
3. Klik "+ Tambah Lokasi"
4. Pilih Unit (TK/SD/SMP)
5. Beri nama lokasi (contoh: "Gedung TK")
6. Klik di map untuk pilih koordinat
7. Radius otomatis 10 meter (bisa diubah)
8. Simpan

### Menggunakan Absensi
1. Login sebagai Guru/Karyawan
2. Ke menu "Absensi"
3. Izinkan akses GPS browser
4. Pilih lokasi unit Anda
5. Jika dalam radius: tombol "Check-In" aktif
6. Klik untuk check-in
7. Saat pulang, check-out dengan cara yang sama

## 📊 Laporan

Admin dapat membuat laporan dengan filter:
- Rentang tanggal
- Nama karyawan
- Status absensi (Hadir/Terlambat/Absen/Pulang Cepat)

Laporan menampilkan:
- Tanggal, karyawan, lokasi
- Jam check-in/out
- Koordinat GPS (untuk audit)
- Status absensi

## 🎨 UI/UX Features

- **Responsive Design**: Mobile-first, cocok untuk semua ukuran layar
- **Modern Dashboard**: Card-based layout dengan statistik real-time
- **Interactive Maps**: Leaflet maps untuk selection lokasi dan absensi
- **Intuitive Forms**: Form yang user-friendly dengan validasi
- **Color Coded Status**: Visual status (Hadir=Hijau, Terlambat=Orange, Absen=Merah)
- **Smooth Animations**: Transitions dan hover effects yang smooth

## 📝 Data Karyawan

Admin dapat input data lengkap saat membuat user:
- **Nama & Username**
- **Email** (opsional)
- **Role & Unit**
- **NIK Pegawai**
- **Tempat & Tanggal Lahir**
- **Pendidikan** (SMA/Sarjana)
- **TMT** (Tanggal Mulai Tugas)
- **Alamat & Telepon**

Karyawan dapat edit data pribadi mereka:
- Nama
- Email
- Nomor Telepon
- Alamat
- Password

## 🔒 Keamanan

- **Password Hashing**: Menggunakan bcrypt
- **CSRF Protection**: Token CSRF pada semua form
- **Session Management**: Session berbasis database
- **Role-based Access**: Middleware admin untuk proteksi routes
- **GPS Verification**: Koordinat diverifikasi pada server

## 🚀 Deployment

### Production Build
```bash
npm run build
```

### Environment Production
1. Ubah `APP_ENV=production` di `.env`
2. Set `APP_DEBUG=false`
3. Generate `APP_KEY` yang aman
4. Setup database production
5. Run migrations
6. Configure web server (Apache/Nginx)

## 📝 Project Structure

```
absensi-karyawan/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── UserController.php
│   │   │   ├── LocationController.php
│   │   │   ├── AttendanceController.php
│   │   │   └── ProfileController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Unit.php
│       ├── Location.php
│       ├── Attendance.php
│       └── EmployeeProfile.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── UnitSeeder.php
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── dashboard/
│       │   ├── admin.blade.php
│       │   └── employee.blade.php
│       ├── attendance/
│       │   ├── index.blade.php
│       │   └── history.blade.php
│       ├── users/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       ├── locations/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       ├── profile/
│       │   └── edit.blade.php
│       ├── reports/
│       │   └── attendance.blade.php
│       └── layouts/
│           └── app.blade.php
├── routes/
│   └── web.php
└── public/
    └── build/
```

## 📞 Support & Troubleshooting

### Problem: GPS tidak terdeteksi
- Pastikan browser allow GPS permission
- Gunakan HTTPS untuk production
- Test di outdoor atau area dengan sinyal GPS kuat

### Problem: Map tidak muncul
- Cek koneksi internet (Leaflet butuh internet)
- Buka browser console untuk error message

### Problem: Assets error
- Jalankan `npm run build`
- Clear browser cache
- Restart server Laravel

---

**Sistem Absensi Karyawan v1.0** | Dibuat untuk Yayasan

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
