<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;
use Carbon\Carbon;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Produtos da categoria Alimentos
        Produto::create([
            'nome' => 'Arroz Tipo 1',
            'descricao' => 'Pacote de arroz tipo 1 de 5kg',
            'codigo' => 'ALI001',
            'categoria_id' => 1,
            'preco_compra' => 15.00,
            'preco_venda' => 22.90,
            'quantidade_estoque' => 50,
            'estoque_minimo' => 10,
            'unidade' => 'UN',
            'ativo' => true,
            'fornecedor' => 'Distribuidor Alimentos Brasil',
            'data_cadastro' => Carbon::now()
        ]);

        Produto::create([
            'nome' => 'Feijão Carioca',
            'descricao' => 'Pacote de feijão carioca de 1kg',
            'codigo' => 'ALI002',
            'categoria_id' => 1,
            'preco_compra' => 5.50,
            'preco_venda' => 8.90,
            'quantidade_estoque' => 30,
            'estoque_minimo' => 8,
            'unidade' => 'UN',
            'ativo' => true,
            'fornecedor' => 'Distribuidor Alimentos Brasil',
            'data_cadastro' => Carbon::now()
        ]);

        // Produtos da categoria Bebidas
        Produto::create([
            'nome' => 'Refrigerante Cola 2L',
            'descricao' => 'Refrigerante sabor cola garrafa 2 litros',
            'codigo' => 'BEB001',
            'categoria_id' => 2,
            'preco_compra' => 4.50,
            'preco_venda' => 8.50,
            'quantidade_estoque' => 48,
            'estoque_minimo' => 12,
            'unidade' => 'UN',
            'ativo' => true,
            'fornecedor' => 'Distribuidora de Bebidas Nacional',
            'data_cadastro' => Carbon::now()
        ]);

        Produto::create([
            'nome' => 'Água Mineral 500ml',
            'descricao' => 'Água mineral sem gás garrafa 500ml',
            'codigo' => 'BEB002',
            'categoria_id' => 2,
            'preco_compra' => 0.80,
            'preco_venda' => 2.00,
            'quantidade_estoque' => 60,
            'estoque_minimo' => 20,
            'unidade' => 'UN',
            'ativo' => true,
            'fornecedor' => 'Distribuidora Águas Claras',
            'data_cadastro' => Carbon::now()
        ]);
        
        // Produtos da categoria Limpeza
        Produto::create([
            'nome' => 'Detergente Líquido 500ml',
            'descricao' => 'Detergente líquido neutro para louças',
            'codigo' => 'LIM001',
            'categoria_id' => 3,
            'preco_compra' => 1.20,
            'preco_venda' => 2.50,
            'quantidade_estoque' => 45,
            'estoque_minimo' => 15,
            'unidade' => 'UN',
            'ativo' => true,
            'fornecedor' => 'Fornecedor Produtos de Limpeza',
            'data_cadastro' => Carbon::now()
        ]);
        
        // Produtos da categoria Eletrônicos
        Produto::create([
            'nome' => 'Carregador USB Tipo-C',
            'descricao' => 'Carregador rápido para smartphone USB tipo C',
            'codigo' => 'ELE001',
            'categoria_id' => 4,
            'preco_compra' => 12.00,
            'preco_venda' => 29.90,
            'quantidade_estoque' => 25,
            'estoque_minimo' => 5,
            'unidade' => 'UN',
            'ativo' => true,
            'fornecedor' => 'Eletrônicos Importados',
            'data_cadastro' => Carbon::now()
        ]);

        // Produtos da categoria Roupas
        Produto::create([
            'nome' => 'Camiseta Básica',
            'descricao' => 'Camiseta básica de algodão unissex',
            'codigo' => 'ROU001',
            'categoria_id' => 5,
            'preco_compra' => 15.00,
            'preco_venda' => 35.90,
            'quantidade_estoque' => 30,
            'estoque_minimo' => 10,
            'unidade' => 'UN',
            'ativo' => true,
            'fornecedor' => 'Confecções Têxtil',
            'data_cadastro' => Carbon::now()
        ]);

        // Produtos da categoria Material Escolar
        Produto::create([
            'nome' => 'Caderno Universitário 100 folhas',
            'descricao' => 'Caderno universitário espiral 100 folhas',
            'codigo' => 'ESC001',
            'categoria_id' => 10,
            'preco_compra' => 8.00,
            'preco_venda' => 15.90,
            'quantidade_estoque' => 40,
            'estoque_minimo' => 10,
            'unidade' => 'UN',
            'ativo' => true,
            'fornecedor' => 'Distribuidora Escolar',
            'data_cadastro' => Carbon::now()
        ]);
    }
}
