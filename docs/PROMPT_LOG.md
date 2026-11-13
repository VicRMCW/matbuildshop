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
