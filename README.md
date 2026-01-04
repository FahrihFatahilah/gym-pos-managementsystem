# Sistem Manajemen Membership Gym & POS Minuman

Aplikasi web untuk mengelola membership gym dan sistem Point of Sale (POS) untuk penjualan minuman, dibangun dengan Laravel dan MySQL.

## 🚀 Fitur Utama

### 👥 Manajemen Member
- CRUD data member (nama, nomor HP, email, alamat)
- Status member (aktif/expired)
- History pembayaran membership
- Pencarian dan filter data member

### 🏋️ Manajemen Membership
- Jenis membership (bulanan/tahunan/custom)
- Perpanjangan membership otomatis
- Tracking tanggal mulai dan berakhir
- Auto update status expired

### 🛒 Point of Sale (POS)
- Interface kasir yang user-friendly
- Tambah produk ke keranjang
- Hitung total otomatis
- Multiple metode pembayaran (Cash/QRIS/Transfer)
- Cetak struk PDF
- History transaksi

### 📦 Manajemen Stok
- CRUD produk minuman
- Tracking stok masuk/keluar
- History perubahan stok
- Alert stok minimum
- Update stok otomatis dari transaksi

### 📊 Dashboard & Laporan
- Dashboard dengan statistik real-time
- Total member aktif
- Penjualan harian
- Produk stok minimum
- Chart penjualan 7 hari terakhir
- Laporan penjualan dan membership

## 🛠️ Teknologi

- **Backend**: Laravel 11
- **Database**: MySQL
- **Frontend**: Blade Template, Bootstrap 5
- **Icons**: Font Awesome 6
- **PDF**: DomPDF
- **Charts**: Chart.js
- **Notifications**: SweetAlert2

## 👤 Role & Permission

### Admin
- Full akses ke semua fitur
- Manajemen user dan sistem

### Staff/Kasir
- Akses POS dan input data
- Manajemen member dan produk
- Tidak bisa akses laporan lengkap

### Owner
- View dashboard dan laporan
- Monitoring bisnis
- Tidak bisa input data operasional

## 📋 Instalasi

### Persyaratan Sistem
- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js & NPM (untuk asset compilation)

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd gym-pos-system
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gym_pos_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Database Migration & Seeding**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Compile Assets**
   ```bash
   npm run dev
   # atau untuk production
   npm run build
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

   Aplikasi akan berjalan di `http://localhost:8000`

## 🔐 Default Login

Setelah seeding, gunakan akun berikut untuk login:

### Admin
- Email: `admin@gym.com`
- Password: `password`

### Staff/Kasir
- Email: `staff@gym.com`
- Password: `password`

### Owner
- Email: `owner@gym.com`
- Password: `password`

## 📁 Struktur Database

### Tabel Utama
- `users` - Data pengguna sistem
- `members` - Data member gym
- `memberships` - Data membership member
- `products` - Data produk minuman
- `transactions` - Data transaksi penjualan
- `transaction_details` - Detail item transaksi
- `stock_histories` - History perubahan stok
- `payments` - Data pembayaran membership

## 🎨 UI/UX Features

- **Responsive Design** - Mobile, tablet, dan desktop friendly
- **Modern Interface** - Bootstrap 5 dengan custom styling
- **Interactive Elements** - SweetAlert untuk konfirmasi
- **Real-time Updates** - AJAX untuk operasi cepat
- **Print Ready** - Struk PDF yang siap cetak
- **Search & Filter** - Pencarian dan filter data yang mudah

## 🔧 Konfigurasi Tambahan

### PDF Configuration
Untuk konfigurasi PDF, edit file `config/dompdf.php` jika diperlukan.

### Permission Configuration
Sistem menggunakan Spatie Laravel Permission. Konfigurasi ada di `config/permission.php`.

## 📝 Penggunaan

### 1. Dashboard
- Lihat statistik bisnis real-time
- Monitor member aktif dan penjualan
- Cek produk dengan stok minimum

### 2. Manajemen Member
- Tambah member baru dari menu Member
- Edit data member yang sudah ada
- Lihat history membership dan pembayaran

### 3. POS System
- Akses menu POS untuk mulai transaksi
- Klik produk untuk menambah ke keranjang
- Pilih metode pembayaran dan proses transaksi
- Cetak struk untuk customer

### 4. Manajemen Stok
- Update stok produk dari menu Stok
- Lihat history perubahan stok
- Monitor produk dengan stok rendah

## 🚨 Troubleshooting

### Error Permission
```bash
chmod -R 775 storage bootstrap/cache
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Database Issues
```bash
php artisan migrate:fresh --seed
```

## 🤝 Kontribusi

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 Lisensi

Aplikasi ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail.

## 📞 Support

Untuk pertanyaan atau dukungan, silakan hubungi:
- Email: support@gym-pos.com
- GitHub Issues: [Create Issue](https://github.com/your-repo/issues)

---

**Dibuat dengan ❤️ menggunakan Laravel**