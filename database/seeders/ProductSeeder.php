<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Setiap produk diikat ke kategori lewat slug agar urutan insert tidak menjadi asumsi.
        $products = [

            // Semen & Perekat
            [
                'category' => 'semen-perekat',
                'name'     => 'Semen Portland 50 kg (per sak)',
                'price'    => 68000,
                'stock'    => 80,
                'desc'     => 'Semen portland tipe I untuk konstruksi umum: pondasi, kolom, dan plat lantai. Kuat tekan mencapai 42 MPa pada umur 28 hari.',
            ],
            [
                'category' => 'semen-perekat',
                'name'     => 'Semen Instan Plester 40 kg (per sak)',
                'price'    => 85000,
                'stock'    => 60,
                'desc'     => 'Mortar siap pakai untuk plesteran dinding interior dan eksterior. Tebal aplikasi 10-15 mm per lapis, waktu setting sekitar 3 jam.',
            ],
            [
                'category' => 'semen-perekat',
                'name'     => 'Perekat Keramik Fleksibel 25 kg (per sak)',
                'price'    => 72000,
                'stock'    => 50,
                'desc'     => 'Perekat berbasis semen modifikasi polimer untuk pemasangan keramik dan granit ukuran hingga 60x60 cm. Cocok untuk lantai basah dan dinding luar.',
            ],

            // Besi & Baja
            [
                'category' => 'besi-baja',
                'name'     => 'Besi Beton Polos 10 mm (per batang)',
                'price'    => 95000,
                'stock'    => 100,
                'desc'     => 'Besi beton polos diameter 10 mm panjang 12 meter, baja tulangan BJTP 24. Digunakan untuk rangkaian kolom praktis dan balok anak.',
            ],
            [
                'category' => 'besi-baja',
                'name'     => 'Besi Beton Ulir 13 mm (per batang)',
                'price'    => 148000,
                'stock'    => 75,
                'desc'     => 'Besi beton ulir diameter 13 mm panjang 12 meter, baja tulangan BJTD 40. Daya lekat lebih tinggi dibanding polos, cocok untuk balok utama.',
            ],
            [
                'category' => 'besi-baja',
                'name'     => 'Baja Ringan C75 (per batang 6 m)',
                'price'    => 87000,
                'stock'    => 60,
                'desc'     => 'Profil baja ringan tipe C75 tebal 0,75 mm panjang 6 meter untuk rangka atap. Dilapisi galvanis untuk mencegah korosi di lingkungan lembap.',
            ],

            // Cat & Pelapis
            [
                'category' => 'cat-pelapis',
                'name'     => 'Cat Tembok Interior 5 kg (per kaleng)',
                'price'    => 115000,
                'stock'    => 40,
                'desc'     => 'Cat emulsi akrilik untuk tembok interior, daya sebar 8-10 m2 per kg dengan 2 lapis. Tahan noda dan mudah dibersihkan dengan kain lembap.',
            ],
            [
                'category' => 'cat-pelapis',
                'name'     => 'Cat Tembok Eksterior 5 kg (per kaleng)',
                'price'    => 148000,
                'stock'    => 35,
                'desc'     => 'Cat berbasis akrilik elastomerik untuk fasad eksterior, tahan cuaca dan sinar UV. Memiliki sifat elastis yang menutup retakan rambut hingga 0,3 mm.',
            ],
            [
                'category' => 'cat-pelapis',
                'name'     => 'Pelapis Waterproof Bitumen 4 kg (per kaleng)',
                'price'    => 195000,
                'stock'    => 25,
                'desc'     => 'Pelapis berbasis bitumen untuk atap dak beton dan pondasi bawah tanah. Fleksibel saat kering, tahan retak akibat pergerakan struktur ringan.',
            ],

            // Pipa & Sanitasi
            [
                'category' => 'pipa-sanitasi',
                'name'     => 'Pipa PVC AW 1/2 inch (per batang 4 m)',
                'price'    => 32000,
                'stock'    => 90,
                'desc'     => 'Pipa PVC tipe AW diameter 1/2 inci panjang 4 meter untuk instalasi air bersih bertekanan. Tekanan kerja maksimal 10 bar pada suhu 25 derajat Celsius.',
            ],
            [
                'category' => 'pipa-sanitasi',
                'name'     => 'Pipa PVC D 4 inch (per batang 4 m)',
                'price'    => 95000,
                'stock'    => 50,
                'desc'     => 'Pipa PVC tipe D diameter 4 inci untuk saluran buangan dan drainase. Dinding lebih tipis dari tipe AW, dirancang untuk sistem gravitasi tanpa tekanan.',
            ],
            [
                'category' => 'pipa-sanitasi',
                'name'     => 'Kloset Duduk Keramik Standar (per unit)',
                'price'    => 875000,
                'stock'    => 15,
                'desc'     => 'Kloset duduk keramik vitreous china kapasitas flush 6 liter. Dilengkapi tangki tanam, dudukan slow-close, dan sertifikasi SNI.',
            ],

            // Alat Tangan
            [
                'category' => 'alat-tangan',
                'name'     => 'Obeng Plus PH2 150 mm (per pcs)',
                'price'    => 18500,
                'stock'    => 85,
                'desc'     => 'Obeng kepala plus ukuran PH2, panjang gagang 150 mm. Mata terbuat dari baja CrV, gagang ergonomis anti-selip untuk momen putar maksimal.',
            ],
            [
                'category' => 'alat-tangan',
                'name'     => 'Palu Besi 500 g (per pcs)',
                'price'    => 42000,
                'stock'    => 60,
                'desc'     => 'Palu kepala besi tuang berat 500 gram dengan gagang kayu keras panjang 300 mm. Cocok untuk pekerjaan bekisting, pemasangan paku, dan pembongkaran ringan.',
            ],
            [
                'category' => 'alat-tangan',
                'name'     => 'Meteran Baja 5 m (per pcs)',
                'price'    => 35000,
                'stock'    => 70,
                'desc'     => 'Meteran pita baja lebar 19 mm panjang 5 meter dengan kunci otomatis. Skala milimeter di kedua sisi pita, casing ABS tahan benturan.',
            ],

            // Alat Listrik
            [
                'category' => 'alat-listrik',
                'name'     => 'Bor Listrik 10 mm 500 W (per unit)',
                'price'    => 285000,
                'stock'    => 20,
                'desc'     => 'Bor genggam daya 500 watt, chuck 10 mm, kecepatan tanpa beban 0-2800 rpm. Cocok untuk mengebor beton ringan, kayu, dan besi dengan mata bor yang sesuai.',
            ],
            [
                'category' => 'alat-listrik',
                'name'     => 'Gerinda Tangan 4 inch 650 W (per unit)',
                'price'    => 325000,
                'stock'    => 15,
                'desc'     => 'Gerinda sudut daya 650 watt untuk batu gerinda atau disc potong diameter 100 mm. Kecepatan tanpa beban 11000 rpm, pelindung percikan dapat diputar 180 derajat.',
            ],
            [
                'category' => 'alat-listrik',
                'name'     => 'Lampu Kerja LED 50 W (per unit)',
                'price'    => 158000,
                'stock'    => 30,
                'desc'     => 'Lampu sorot LED daya 50 watt dengan fluks cahaya 4500 lumen, suhu warna 6500K. Dilengkapi kabel 2 meter dan tripod lipat setinggi 1,5 meter.',
            ],
        ];

        foreach ($products as $data) {
            $category = Category::where('slug', $data['category'])->firstOrFail();

            Product::create([
                'category_id' => $category->id,
                'name'        => $data['name'],
                'slug'        => Str::slug($data['name']),
                'description' => $data['desc'],
                'price'       => $data['price'],
                'stock'       => $data['stock'],
                'image'       => null,
                'is_active'   => true,
            ]);
        }
    }
}
