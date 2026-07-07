# Sistem Manajemen Inventaris — Laravel

Aplikasi web manajemen inventaris kantor, dibuat untuk **Challenge Seleksi Magang Sistem Informasi**
(studi kasus PT Telkomsel). Dibangun dengan Laravel 11, PostgreSQL, dan Bootstrap 5.

## Fitur

- **Landing page publik** — statistik inventaris & daftar barang yang bisa dipinjam, sebelum login.
- **Autentikasi**: Login, Register, Logout, Forgot/Reset Password (lihat catatan alur di bawah).
- **Role Management** (3 role): Admin (full access), Staff (kelola inventaris), Manager (lihat laporan & approve peminjaman).
- **Master Data Barang**: tambah/edit/hapus/detail, pencarian, filter kategori, pagination, upload gambar.
- **Peminjaman Barang**: pengajuan multi-item, approval (admin/manager), pengembalian, riwayat & status.
- **Dashboard**: total barang, barang dipinjam, barang tersedia, grafik peminjaman per bulan, notifikasi stok menipis.
- **Laporan**: Export PDF & Excel untuk **Data Barang** maupun **Riwayat Peminjaman** (menu "Laporan").
- **REST API** (`/api/products`) untuk daftar & detail barang.
- **Dark Mode** menyeluruh (termasuk fix untuk kartu Bootstrap, menggunakan `data-bs-theme` bawaan Bootstrap 5.3).
- **Automated tests** (PHPUnit) untuk autentikasi, CRUD barang & hak akses role, serta alur peminjaman.

## Tech Stack

- PHP 8.2+ / Laravel 11
- PostgreSQL (dikelola lewat pgAdmin)
- Bootstrap 5 + Bootstrap Icons
- Chart.js (grafik dashboard)
- barryvdh/laravel-dompdf, maatwebsite/excel

---

## 1. Persiapan

Pastikan sudah terpasang:

- PHP >= 8.2 beserta ekstensi: `pdo_pgsql`, `mbstring`, `openssl`, `fileinfo`, `gd`
- Composer
- Node.js 18+ & NPM
- PostgreSQL + **pgAdmin**

## 2. Buat Database di pgAdmin

1. Buka **pgAdmin**, klik kanan `Databases` → `Create` → `Database...`
2. Beri nama database: `inventaris_db`
3. Klik **Save**.

Ada dua cara untuk mengisi skema tabelnya, pilih salah satu:

**Cara A — lewat migration Laravel (disarankan):** lanjut ke langkah 5 di bawah (`php artisan migrate --seed`), tidak perlu melakukan apa pun di sini.

**Cara B — import file `.sql` langsung:** buka database `inventaris_db` di pgAdmin → klik kanan → **Query Tool** → buka file `database/inventaris_db.sql` dari project ini → jalankan (Execute/F5). File ini berisi seluruh `CREATE TABLE` + data contoh (role, user, kategori, barang, peminjaman). **Jika memakai cara ini, lewati langkah migrate & seed di bawah.**

## 3. Install Dependency

```bash
cd inventaris-app
composer install
npm install
```

## 4. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Buka file `.env`, sesuaikan kredensial PostgreSQL dengan pgAdmin Anda:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=inventaris_db
DB_USERNAME=postgres
DB_PASSWORD=isi_password_postgres_anda
```

## 5. Migrasi & Seeder Database

```bash
php artisan migrate --seed
```

Perintah ini akan membuat seluruh tabel (`users`, `roles`, `categories`, `products`, `borrowings`,
`borrowing_details`, dll) sekaligus mengisi data contoh (kategori, barang, dan akun login testing).

## 6. Buat Symlink Storage (untuk upload gambar barang)

```bash
php artisan storage:link
```

## 7. Build Asset Frontend

```bash
npm run build
```

Untuk mode pengembangan (auto-reload), jalankan di terminal terpisah:

```bash
npm run dev
```

## 8. Jalankan Aplikasi

```bash
php artisan serve
```

Buka browser ke **http://127.0.0.1:8000**

---

## Akun Login Testing

| Role    | Email                     | Password   |
|---------|----------------------------|------------|
| Admin   | admin@inventaris.test       | password   |
| Staff   | staff@inventaris.test       | password   |
| Manager | manager@inventaris.test     | password   |

## Hak Akses Role

| Fitur                          | Admin | Staff | Manager |
|---------------------------------|:-----:|:-----:|:-------:|
| Kelola Master Barang (CRUD)     |  ✅   |  ✅   |   👁️ (lihat saja) |
| Ajukan Peminjaman               |  ✅   |  ✅   |   ✅    |
| Approve/Tolak Peminjaman        |  ✅   |  ❌   |   ✅    |
| Lihat Dashboard & Laporan       |  ✅   |  ✅   |   ✅    |
| Export PDF/Excel                |  ✅   |  ✅   |   ✅    |

## Struktur Tabel Database

- `users`, `roles` — akun & hak akses
- `categories` — kategori barang
- `products` — master data barang (kode, nama, stok, lokasi, kondisi, gambar)
- `borrowings` — data pengajuan peminjaman (peminjam, tanggal, status)
- `borrowing_details` — detail barang per transaksi peminjaman (mendukung multi-item)

## REST API

| Method | Endpoint              | Keterangan                     |
|--------|------------------------|---------------------------------|
| GET    | `/api/products`        | Daftar barang (paginate, `?q=`) |
| GET    | `/api/products/{id}`   | Detail satu barang              |

Contoh:
```bash
curl http://127.0.0.1:8000/api/products
curl http://127.0.0.1:8000/api/products/1
```

## Export Laporan

Menu **Laporan** di sidebar berisi 2 jenis laporan, masing-masing bisa diunduh sebagai PDF atau Excel:

- **Laporan Data Barang** — `/reports/products/pdf` & `/reports/products/excel`
- **Laporan Peminjaman** — `/reports/borrowings/pdf` & `/reports/borrowings/excel`

(seluruh route laporan memerlukan login)

## Alur Forgot / Reset Password

Untuk menghindari ketergantungan pada server email asli (SMTP Gmail dkk) yang tidak selalu tersedia
saat demo/penilaian, alur "Lupa Password" dibuat seperti ini:

1. User membuka **Lupa Password** dan memasukkan **email**.
2. Jika email terdaftar, sistem membuat token reset resmi (lewat Password Broker bawaan Laravel,
   token yang sama seperti yang biasanya dikirim lewat email) dan **langsung mengarahkan** user
   ke halaman **Reset Password** dengan token tersebut — tanpa perlu buka inbox email.
3. Jika email tidak terdaftar, muncul pesan error.

Ini bukan sekadar "reset tanpa verifikasi" — token tetap dibuat & divalidasi lewat mekanisme resmi
Laravel (`password_reset_tokens` table), hanya saja proses pengirimannya lewat redirect langsung,
bukan lewat email. Ini pilihan yang wajar untuk aplikasi internal/demo seperti submission challenge ini.

**Jika suatu saat ingin pakai email asli** (misal untuk deployment produksi), ganti `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email_anda@gmail.com
MAIL_PASSWORD=app_password_gmail_anda
MAIL_ENCRYPTION=tls
```
lalu ubah method `store()` di `app/Http/Controllers/Auth/PasswordResetLinkController.php` untuk memanggil
`Password::sendResetLink()` (versi standar Laravel) alih-alih redirect langsung.

## Menjalankan Test (PHPUnit)

Project ini sudah dilengkapi automated test (memakai SQLite in-memory, tidak menyentuh database PostgreSQL Anda):

```bash
php artisan test
```

atau

```bash
composer test
```

Test yang tersedia mencakup:
- **Landing & akses halaman**: landing page, halaman login/register bisa diakses, guest diarahkan ke login saat akses dashboard.
- **Autentikasi**: login sukses/gagal, register otomatis dapat role staff, logout, alur forgot password (redirect ke reset form untuk email terdaftar, error untuk email tidak terdaftar).
- **Master Barang & Role**: admin & staff bisa CRUD barang, manager **tidak bisa** tambah barang (403) tapi tetap bisa melihat daftar, pencarian barang berfungsi.
- **Alur Peminjaman**: staff mengajukan peminjaman (stok belum berkurang), admin approve (stok berkurang), staff tidak bisa approve (403), pengembalian barang (stok bertambah lagi), admin bisa menolak pengajuan.

## Catatan Deployment

- Untuk deploy ke VPS/hosting, jalankan `composer install --no-dev --optimize-autoloader`,
  set `APP_ENV=production` & `APP_DEBUG=false`, lalu `php artisan config:cache route:cache view:cache`.
- Untuk email reset password sungguhan, ganti `MAIL_MAILER` di `.env` (default: `log`, email tersimpan di `storage/logs/laravel.log`).

## Struktur Direktori Penting

```
app/Http/Controllers/       Controller (Auth, Product, Category, Borrowing, Report, Api)
app/Models/                 Eloquent Models
app/Exports/                Export Excel (ProductsExport, BorrowingsExport)
database/migrations/        Skema seluruh tabel
database/seeders/           Data awal (role, user, kategori, barang, peminjaman contoh)
database/inventaris_db.sql  Alternatif skema + seed dalam bentuk SQL murni (untuk import manual di pgAdmin)
database/factories/         Factory untuk kebutuhan testing
resources/views/            Blade templates (layout, auth, dashboard, products, categories, borrowings, reports)
routes/web.php               Route halaman web
routes/api.php               Route REST API
tests/Feature/               Automated test (auth, produk, peminjaman)
```
