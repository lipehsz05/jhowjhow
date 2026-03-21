<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'clientes';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cpf_cnpj',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'data_cadastro',
        'data_ultima_compra',
        'valor_ultima_compra',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'data_cadastro' => 'datetime',
        'data_ultima_compra' => 'datetime',
        'valor_ultima_compra' => 'float',
    ];

    /**
     * Relacionamento com as vendas do cliente
     */
    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }

    /**
     * URL do WhatsApp (wa.me) ou null se não houver telefone válido.
     */
    public function whatsappUrl(): ?string
    {
        $d = preg_replace('/\D/', '', (string) $this->telefone);
        if ($d === '') {
            return null;
        }
        if (str_starts_with($d, '0')) {
            $d = substr($d, 1);
        }
        if (strlen($d) >= 10 && strlen($d) <= 11 && ! str_starts_with($d, '55')) {
            $d = '55'.$d;
        }

        return 'https://wa.me/'.$d;
    }
}
