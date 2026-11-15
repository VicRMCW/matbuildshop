# MatbuildShop

Website e-commerce toko bangunan (semen, besi, cat, pipa, alat tangan, alat listrik) berbasis Laravel. Dibuat untuk Tugas Besar Praktikum Pemrograman E-Commerce, Program Studi Sistem Informasi. Pembayaran memakai Midtrans Sandbox.

- Pengembang: Vicky Andrean, G1F024021
- Mata kuliah: Praktikum Pemrograman E-Commerce

## Fitur

### Pelanggan
- Registrasi, login, logout, dan pengelolaan profil (nama, telepon, alamat, ganti kata sandi).
- Beranda dengan kategori dan produk terbaru.
- Katalog produk di `/katalog` dengan pencarian, filter kategori, pagination, dan halaman detail.
- Keranjang belanja: tambah, ubah jumlah, hapus, subtotal dan total, serta pencegahan jumlah melebihi stok.
- "Beli sekarang": membeli satu produk langsung dari halaman detail tanpa melalui keranjang. Isi keranjang tidak terpengaruh.
- Checkout dengan validasi data penerima. Total dihitung ulang oleh server.
- Pembayaran lewat Midtrans Sandbox (Snap) dengan status berhasil, tertunda, kedaluwarsa, dan gagal.
- Riwayat pesanan, detail pesanan, dan invoice yang bisa dicetak.

### Admin
- Dashboard: total produk, kategori, pelanggan, pesanan, pesanan menunggu pembayaran, dan pesanan yang sudah dibayar.
- Manajemen kategori (penghapusan memperhitungkan relasi produk).
- Manajemen produk: tambah, ubah, hapus (soft delete), unggah gambar, harga, dan stok.
- Manajemen pesanan: daftar dengan filter dan pencarian, detail pesanan, informasi pelanggan, total, status pembayaran, dan pembaruan status pemrosesan (diproses, dikirim, selesai).
- Status pembayaran tidak dapat diubah admin. Hanya hasil verifikasi dari Midtrans yang mengubahnya.

## Teknologi

- Laravel 13 dan PHP 8.5 (versi tepat di `composer.json` dan `composer.lock`; syarat minimal PHP tertulis di `composer.json`)
- MySQL dan Eloquent ORM
- Blade dan Tailwind CSS lewat Vite
- Midtrans PHP SDK (`midtrans/midtrans-php`)

## Instalasi

Kebutuhan: PHP sesuai syarat di `composer.json`, Composer, Node.js, dan MySQL (Laragon di Windows sudah mencakup semuanya).

```bash
git clone https://github.com/[USERNAME]/matbuildshop.git
cd matbuildshop
composer install
npm install
cp .env.example .env        # Windows: copy .env.example .env
php artisan key:generate
```

Buat database kosong bernama `matbuildshop`, lalu sesuaikan bagian database di `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=matbuildshop
DB_USERNAME=root
DB_PASSWORD=
```

Lanjutkan:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`.

Gambar produk tidak disertakan di repository. Unggah lewat panel admin (Produk, lalu Ubah).

## Akun demo

Akun dibuat oleh seeder dan hanya untuk demo.

| Peran | Email | Kata sandi |
|---|---|---|
| Admin | `admin@matbuildshop.test` | `password123` |
| Pelanggan | buat sendiri

## Konfigurasi Midtrans Sandbox

1. Masuk ke dashboard Midtrans Sandbox, buka Settings, lalu Access Keys.
2. Buat Server Key. Izin yang dipakai sistem ini hanya **Snap Checkout: Write** dan **Core API: Read**. Fitur dan kemampuan lain dibiarkan **None**.
3. Salin Server Key dan Client Key ke `.env`:

```
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_IS_PRODUCTION=false
```

4. Jalankan `php artisan config:clear`, lalu jalankan ulang `php artisan serve`.
5. (Opsional, untuk menerima webhook di komputer lokal) Buka tunnel ke `http://localhost:8000` memakai Cloudflare Tunnel atau ngrok, lalu isi Payment Notification URL di Settings, Configuration, dengan `https://[alamat-tunnel]/midtrans/notification`.

Kartu uji (kartu sukses dari dokumentasi Midtrans): `4811 1111 1111 1114`, CVV `123`, OTP `112`. Untuk Virtual Account dan metode lain, gunakan simulator pembayaran Midtrans Sandbox.

Tanpa tunnel, status pembayaran tetap diperbarui: saat halaman pesanan dibuka, server menanyakan status langsung ke Midtrans dengan Server Key.

### Pemetaan status

| Status Midtrans | Status pembayaran | Efek |
|---|---|---|
| `settlement`, atau `capture` dengan `fraud_status=accept` | Dibayar | `paid_at` terisi |
| `pending`, atau `capture` dengan `fraud_status=challenge` | Menunggu pembayaran | |
| `deny`, `cancel` | Gagal | Pesanan dibatalkan, stok dikembalikan |
| `expire` | Kedaluwarsa | Pesanan dibatalkan, stok dikembalikan |

## Keamanan

- Pembatasan akses lewat middleware `role` (admin dan customer), middleware `auth`, dan `guest`.
- Kata sandi disimpan sebagai hash. Login dibatasi 5 percobaan per menit. Session diregenerasi setelah login.
- Seluruh form memakai token CSRF. Endpoint webhook dikecualikan karena diamankan oleh signature.
- Kolom `role` dan `payment_status` tidak ada di `$fillable`, sehingga tidak bisa diisi lewat form.
- Total pesanan dihitung ulang di server dari harga database. Form checkout tidak membawa harga atau total.
- Pesanan dibuat dalam satu transaksi database dengan penguncian baris produk (`lockForUpdate`) untuk mencegah stok terjual ganda. Jalur keranjang dan jalur "Beli sekarang" memakai logika yang sama, dan data pembelian langsung di session diperiksa ulang di server.
- Harga dan nama produk disalin ke `order_items` saat checkout, sehingga pesanan lama tidak berubah saat harga berubah.
- Pelanggan hanya bisa membuka pesanan dan invoice miliknya sendiri.
- Webhook Midtrans memverifikasi `signature_key` (SHA512 dari `order_id`, `status_code`, `gross_amount`, dan Server Key), mencocokkan nominal dengan database, dan bersifat idempotent. Status akhir (dibayar, gagal, kedaluwarsa) tidak bisa tertimpa notifikasi susulan.
- Server Key hanya ada di `.env`, yang tidak ikut ke repository.

## Struktur database

| Tabel | Isi |
|---|---|
| `users` | Akun dengan kolom `role` (`admin` atau `customer`), telepon, alamat |
| `categories` | Kategori produk |
| `products` | Produk, harga, stok, gambar, soft delete |
| `cart_items` | Keranjang per pengguna (unik per pengguna dan produk) |
| `orders` | Pesanan, `payment_status`, `order_status`, `snap_token`, `paid_at` |
| `order_items` | Barang dalam pesanan beserta salinan nama dan harga |
| `payments` | Catatan setiap notifikasi atau status dari Midtrans |

## Struktur folder penting

```
app/Http/Controllers/        Controller pelanggan (Admin/ untuk admin, Auth/ untuk autentikasi)
app/Http/Middleware/         EnsureUserHasRole
app/Http/Requests/           Form Request (validasi checkout, kategori, produk)
app/Models/                  Model Eloquent dan relasinya
app/Services/                MidtransService (komunikasi Midtrans), PaymentService (penerapan status)
database/migrations/         Struktur tabel
database/seeders/            Data awal (akun, kategori, produk)
resources/views/             Tampilan Blade (admin/, auth/, cart/, checkout/, orders/, shop/)
routes/web.php               Seluruh route
docs/                        Log prompt dan tabel pengujian
```


## Batasan yang diketahui

- Pesanan yang tidak pernah dibayar dan tidak pernah memilih metode pembayaran tetap berstatus menunggu, dan stoknya tertahan. Midtrans hanya mengirim notifikasi kedaluwarsa untuk transaksi yang sudah dibuat. Pembatalan terjadwal belum dibuat.
- Ongkos kirim tidak dihitung. Total pesanan sama dengan jumlah subtotal barang.
- Refund dan pembatalan pesanan oleh admin tidak termasuk dalam ruang lingkup tugas.
- Pesanan yang gagal atau kedaluwarsa tidak bisa dibayar ulang. Pelanggan membuat pesanan baru.

## Sumber gambar

- Foto produk: -belum
- Ilustrasi banner beranda (`public/images/hero-bangunan.png`): dibuat oleh gemini

## Penggunaan bantuan AI

pengembangan dibantu asisten AI (Claude dan Gemini lewat Antigravity). Riwayat prompt dicatat di [docs/PROMPT_LOG.md](docs/PROMPT_LOG.md).

## Referensi

Repo Kinetic laptop asisten praktikum dan juga shopee dan tokopedia
