<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categorias comuns para um sistema de estoque
        $categorias = [
            ['nome' => 'Alimentos', 'descricao' => 'Produtos alimentícios em geral'],
            ['nome' => 'Bebidas', 'descricao' => 'Bebidas alcoólicas e não alcoólicas'],
            ['nome' => 'Limpeza', 'descricao' => 'Produtos de limpeza doméstica e industrial'],
            ['nome' => 'Eletrônicos', 'descricao' => 'Aparelhos e acessórios eletrônicos'],
            ['nome' => 'Roupas', 'descricao' => 'Vestuário masculino e feminino'],
            ['nome' => 'Calçados', 'descricao' => 'Sapatos, tênis e sandálias'],
            ['nome' => 'Móveis', 'descricao' => 'Móveis para casa e escritório'],
            ['nome' => 'Utensílios', 'descricao' => 'Utensílios domésticos'],
            ['nome' => 'Ferramentas', 'descricao' => 'Ferramentas manuais e elétricas'],
            ['nome' => 'Material Escolar', 'descricao' => 'Produtos para estudantes']
        ];
        
        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
