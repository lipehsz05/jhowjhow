<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário administrador
        User::create([
            'name' => 'Administrador',
            'username' => 'admin',
            'email' => 'admin@sistema.com',
            'password' => Hash::make('admin123'),
            'nivel_acesso' => 'admin',
        ]);

        // Criar usuário vendedor
        User::create([
            'name' => 'Vendedor',
            'username' => 'vendedor',
            'email' => 'vendedor@sistema.com',
            'password' => Hash::make('vendedor123'),
            'nivel_acesso' => 'vendedor',
        ]);
    }
}
