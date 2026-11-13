<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // forceFill dipakai karena role dikecualikan dari $fillable untuk mencegah mass assignment.
        $admin = new User();
        $admin->forceFill([
            'name'  => 'Administrator',
            'email' => 'admin@matbuildshop.test',
            'role'  => User::ROLE_ADMIN,
        ]);
        $admin->password = Hash::make('password123');
        $admin->save();

        $customers = [
            ['name' => 'Budi Santoso',   'email' => 'budi@example.test'],
            ['name' => 'Siti Rahayu',    'email' => 'siti@example.test'],
        ];

        foreach ($customers as $data) {
            $user = new User();
            $user->forceFill([
                'name'  => $data['name'],
                'email' => $data['email'],
                'role'  => User::ROLE_CUSTOMER,
            ]);
            $user->password = Hash::make('password123');
            $user->save();
        }
    }
}
