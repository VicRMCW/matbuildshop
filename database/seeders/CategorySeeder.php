<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Semen & Perekat',   'description' => 'Semen portland, semen instan, dan berbagai jenis perekat bangunan.'],
            ['name' => 'Besi & Baja',        'description' => 'Besi beton, baja ringan, dan profil baja untuk konstruksi.'],
            ['name' => 'Cat & Pelapis',      'description' => 'Cat tembok, cat besi, dan pelapis waterproof untuk interior maupun eksterior.'],
            ['name' => 'Pipa & Sanitasi',    'description' => 'Pipa air, pipa drainase, fitting, dan aksesori sanitasi.'],
            ['name' => 'Alat Tangan',        'description' => 'Palu, kunci, gergaji, obeng, dan perkakas tangan lainnya.'],
            ['name' => 'Alat Listrik',       'description' => 'Bor listrik, gerinda, dan perkakas bertenaga listrik untuk pekerjaan bangunan.'],
        ];

        foreach ($categories as $data) {
            Category::create([
                'name'        => $data['name'],
                'slug'        => Str::slug($data['name']),
                'description' => $data['description'],
            ]);
        }
    }
}
