<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Admin
        User::updateOrCreate(
            [
                'email' => 'admin@bitesstore.com',
            ],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Usuario Cliente
        User::updateOrCreate(
            [
                'email' => 'cliente@bitesstore.com',
            ],
            [
                'name' => 'Cliente Usuario',
                'password' => Hash::make('cliente123'),
                'role' => 'client',
            ]
        );

        // Categorías
        Category::updateOrCreate(
            ['name' => 'Equipos']
        );

        Category::updateOrCreate(
            ['name' => 'Accesorios']
        );
    }
}