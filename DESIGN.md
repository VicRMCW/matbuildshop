# DESIGN.md — MatbuildShop

## Identitas Sistem

**Nama:** MatbuildShop  
**Jenis:** Toko bangunan online  
**Kategori produk:** semen, besi, cat, pipa, alat tangan, alat listrik

---

## Kesan Visual

Kokoh, jujur, mudah dipindai. Menarik tapi kalem — pengguna datang untuk mencari barang, bukan menikmati efek visual.

---

## Warna

| Peran | Nilai |
|---|---|
| Latar utama | Abu-abu beton (`#F2F1EF`) atau putih tulang hangat (`#FAF8F5`) |
| Teks utama | Charcoal gelap (`#1E1E1E` / `#2C2C2C`) |
| Aksen tunggal | Kuning keselamatan (`#F5A800`) **atau** oranye tua (`#C8601A`) — pilih satu, konsisten di seluruh proyek |
| Aksen dipakai untuk | Tombol aksi utama, label harga, badge stok penting |

> Tidak ada warna dekoratif tambahan di luar palet ini.

---

## Tipografi

- **Maks. 2 font** di seluruh proyek.
- Judul: sans-serif tegas, boleh condensed (contoh: *Barlow Condensed*, *Inter*, *DM Sans*).
- Isi: sans-serif yang mudah dibaca pada layar kecil (contoh: *Inter*, *Nunito Sans*).
- Hindari font dekoratif atau serif.

---

## Komponen

### Kartu Produk
- Bentuk persegi, `border-radius` kecil (4–8 px) — tidak terlalu bulat.
- Urutan elemen: gambar → nama produk → harga → status stok → tombol.
- Semua data harga dan stok **diambil dari database**, tidak ada angka hardcode di tampilan.

### Tabel & Daftar
- Rapi, baris bergantian warna tipis jika perlu, tanpa dekorasi berlebihan.

### Tombol
- Tombol aksi utama memakai warna aksen.
- Tombol sekunder: outline atau abu-abu netral.

---

## Interaksi

- Hover dan transisi tipis (`150–200 ms ease`), tidak ada animasi berat.
- Fokus keyboard jelas terlihat (aksesibilitas).

---

## Layout

- **Mobile-first**, responsif hingga desktop.
- Grid produk: 1 kolom (mobile) → 2–3 kolom (tablet) → 3–4 kolom (desktop).
- Prioritas: keterbacaan dan kecepatan scan, bukan estetika dekoratif.

---

## Ketentuan Kerja

- Implementasi **hanya fitur yang ada di ketentuan tugas** — tidak menambah fitur di luar scope.
- Semua data harga dan stok berasal dari database. **Tidak ada angka karangan di tampilan.**
- Setiap komponen baru harus sesuai palet, tipografi, dan radius yang sudah ditetapkan di dokumen ini.
