# PROMPT_LOG

Riwayat prompt dan keputusan teknis per sesi.

---

## 2026-11-13

### chore: initial Laravel project
- Git init branch `main`, remote origin `https://github.com/VicRMCW/matbuildshop.git`.
- Push `main` dan `develop`.

### DESIGN.md
- Dibuat di root project: palet, tipografi, komponen, ketentuan kerja MatbuildShop.

### feat: add database migrations
Tabel yang dibuat:

| Migration | Tabel | Catatan |
|---|---|---|
| `0001_01_01_000000` | `users` | Tambah `role`, `phone`, `address` ke migration bawaan Laravel |
| `2025_01_01_000010` | `categories` | Induk produk |
| `2025_01_01_000020` | `products` | FK ke categories (RESTRICT), softDeletes, index `is_active` |
| `2025_01_01_000030` | `cart_items` | FK ke users+products (CASCADE), unique (user_id, product_id) |
| `2025_01_01_000040` | `orders` | FK ke users (RESTRICT), index `payment_status` dan `order_status` |
| `2025_01_01_000050` | `order_items` | FK ke orders (CASCADE), products (RESTRICT); snapshot nama+harga |
| `2025_01_01_000060` | `payments` | FK ke orders (CASCADE), index `transaction_status`, JSON notifikasi |

Keputusan: status pakai `string` bukan `enum` agar nilai bisa ditambah tanpa `ALTER TABLE`.
Hasil: `migrate:fresh` sukses, semua 9 migration status `Ran`.

### feat: add Eloquent models and relationships

| Model | Catatan |
|---|---|
| `User` | Tambah `phone`, `address` ke fillable. `role` dikecualikan (mass-assignment guard). Konstanta `ROLE_ADMIN/CUSTOMER`, method `isAdmin()`, `isCustomer()`. Relasi `orders`, `cartItems`. |
| `Category` | fillable name/slug/description. Relasi `products`. |
| `Product` | SoftDeletes. Accessor `formatted_price` (format Rupiah). Scope `active()`. Relasi `category`, `cartItems`, `orderItems`. |
| `CartItem` | Accessor `subtotal` dihitung on-the-fly dari `quantity * product.price`. |
| `Order` | Konstanta payment dan order status (string huruf kecil). `payment_status` dan `paid_at` dikecualikan fillable, hanya bisa diubah lewat `markPaymentStatus()` dan `markAsPaid()`. |
| `OrderItem` | Relasi `product` pakai `withTrashed()` agar tidak putus saat produk di-soft delete. |
| `Payment` | Cast `raw_notification` ke array, `transaction_time` ke datetime. |

Hasil tinker: `App\Models\Category::count()` = 0 (belum ada seeder).

### feat: add database seeders

| Seeder | Baris |
|---|---|
| `UserSeeder` | 3 (1 admin, 2 customer) |
| `CategorySeeder` | 6 kategori |
| `ProductSeeder` | 18 produk (3 per kategori) |

Catatan: `role` diisi lewat `forceFill()` karena dikecualikan dari `$fillable`.
Urutan `DatabaseSeeder`: users > categories > products (sesuai ketergantungan FK).
Hasil `migrate:fresh --seed`: semua seeder `DONE`, tinker konfirmasi 3/6/18 baris.

### feat: add authentication
- Controller auth manual tanpa starter kit: `RegisterController` (role customer diset eksplisit, password hashed, autologin) dan `LoginController` (`Auth::attempt`, session regenerate, pesan error netral, role-based redirect, POST logout).
- View Blade: `layouts/app.blade.php`, `auth/login.blade.php`, `auth/register.blade.php`, dan `shop/home.blade.php`.
- Throttle `throttle:5,1` diterapkan pada POST `/login`.

### feat: add role middleware
- Middleware `EnsureUserHasRole` dengan proteksi redirect ke login bila belum auth, dan `abort(403)` jika role tidak sesuai.
- Registrasi alias `'role'` di `bootstrap/app.php` via `$middleware->alias(...)`.
- Area admin dilindungi `['auth', 'role:admin']`, layout `layouts/admin.blade.php`, dan placeholder view `admin.dashboard`.

### feat: add customer profile
- `ProfileController` untuk `GET /profile` dan `PUT /profile`, hanya mengubah instance `Auth::user()`.
- Validasi nama required, phone/address nullable, dan ganti password opsional dengan `current_password`.
- View `profile/edit.blade.php` sesuai aturan visual `DESIGN.md`.

### feat: add admin dashboard
- Buat `Admin\DashboardController` menggantikan closure `admin.dashboard`.
- Query 6 statistik real dari database: total produk (tanpa soft-deleted), total kategori, total pelanggan (`role = 'customer'`), total pesanan, pesanan pending (`Order::PAYMENT_PENDING`), pesanan paid (`Order::PAYMENT_PAID`).
- Perbarui `resources/views/layouts/admin.blade.php` dengan navigasi sidebar: Dashboard, Kategori, Produk, Pesanan (placeholder) dan penanda menu aktif via `request()->routeIs(...)`.
- Buat partial flash message di `resources/views/partials/flash.blade.php` (menangani `status`, `success`, `error`, `$errors->any()`) dan pasang di layout app maupun admin.
- View `resources/views/admin/dashboard.blade.php` menampilkan 6 kartu statistik rapi sesuai pedoman `DESIGN.md`.

### feat: add category management
- Resource controller `Admin\CategoryController` (except `show`) dengan route `admin.categories.*`.
- Form Requests: `StoreCategoryRequest` dan `UpdateCategoryRequest` (validasi name required, max 100, unique ignore self; description max 500).
- Penanganan slug otomatis via `Str::slug` yang menjamin keunikan dengan penambahan suffix angka berurutan jika ada tabrakan. Slug hanya diperbarui saat nama berubah.
- Listing index dengan `withCount('products')` untuk eliminasi query N+1, pagination 10 item.
- Proteksi `destroy()`: cek `products()->withTrashed()->exists()`; tolak penghapusan bila ada produk (termasuk yang di-soft-delete) dengan pesan error spesifik.
- Blade views: `index.blade.php`, `create.blade.php`, `edit.blade.php`, dan partial form `_form.blade.php`.
