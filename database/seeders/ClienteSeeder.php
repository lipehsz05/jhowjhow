<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use Carbon\Carbon;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clientes para testes iniciais
        Cliente::create([
            'nome' => 'João Silva',
            'email' => 'joao.silva@email.com',
            'telefone' => '(11) 98765-4321',
            'cpf_cnpj' => '123.456.789-00',
            'endereco' => 'Rua das Flores, 123',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'cep' => '01234-567',
            'data_cadastro' => Carbon::now()->subDays(30),
        ]);

        Cliente::create([
            'nome' => 'Maria Oliveira',
            'email' => 'maria.oliveira@email.com',
            'telefone' => '(11) 91234-5678',
            'cpf_cnpj' => '987.654.321-00',
            'endereco' => 'Avenida Central, 456',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'cep' => '04567-890',
            'data_cadastro' => Carbon::now()->subDays(25),
            'data_ultima_compra' => Carbon::now()->subDays(10),
            'valor_ultima_compra' => 156.90,
        ]);

        Cliente::create([
            'nome' => 'Pedro Santos',
            'email' => 'pedro.santos@email.com',
            'telefone' => '(21) 99876-5432',
            'cpf_cnpj' => '456.789.123-00',
            'endereco' => 'Rua do Comércio, 789',
            'cidade' => 'Rio de Janeiro',
            'estado' => 'RJ',
            'cep' => '20000-001',
            'data_cadastro' => Carbon::now()->subDays(20),
            'data_ultima_compra' => Carbon::now()->subDays(5),
            'valor_ultima_compra' => 89.70,
        ]);

        Cliente::create([
            'nome' => 'Ana Souza',
            'email' => 'ana.souza@email.com',
            'telefone' => '(31) 98765-4321',
            'cpf_cnpj' => '789.123.456-00',
            'endereco' => 'Avenida Principal, 1010',
            'cidade' => 'Belo Horizonte',
            'estado' => 'MG',
            'cep' => '30000-000',
            'data_cadastro' => Carbon::now()->subDays(15),
        ]);

        Cliente::create([
            'nome' => 'Carlos Ferreira',
            'email' => 'carlos.ferreira@email.com',
            'telefone' => '(41) 91234-5678',
            'cpf_cnpj' => '321.654.987-00',
            'endereco' => 'Rua das Araucárias, 222',
            'cidade' => 'Curitiba',
            'estado' => 'PR',
            'cep' => '80000-000',
            'data_cadastro' => Carbon::now()->subDays(10),
            'data_ultima_compra' => Carbon::now()->subDays(2),
            'valor_ultima_compra' => 210.50,
        ]);
        
        // Cliente empresarial
        Cliente::create([
            'nome' => 'Mercado Bom Preço LTDA',
            'email' => 'contato@bompreco.com',
            'telefone' => '(11) 3456-7890',
            'cpf_cnpj' => '12.345.678/0001-90',
            'endereco' => 'Avenida Comercial, 1500',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'cep' => '04000-000',
            'data_cadastro' => Carbon::now()->subDays(45),
            'data_ultima_compra' => Carbon::now()->subDays(1),
            'valor_ultima_compra' => 1450.75,
        ]);
    }
}
