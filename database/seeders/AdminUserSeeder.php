<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'author']);
        Role::create(['name' => 'user']);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@devhub.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('admin');
    }
}
