<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            [
                'email' => 'admin@super.com',
            ],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('#Admin123'),
                'status' => 'active',
            ]
        );

        $user->syncRoles(['super_admin']);
    }
}
