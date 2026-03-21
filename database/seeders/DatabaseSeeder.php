<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chamar os seeders na ordem correta para respeitar dependências
        $this->call([
            UserSeeder::class,       // Primeiro usuários
            CategoriaSeeder::class,  // Depois categorias
            ProdutoSeeder::class,    // Depois produtos (dependem de categorias)
            ClienteSeeder::class,    // Depois clientes
        ]);
    }
}
