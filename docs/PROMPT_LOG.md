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
