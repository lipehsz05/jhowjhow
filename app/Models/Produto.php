<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;
    
    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'produtos';
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'descricao',
        'codigo',
        'categoria_id',
        'tamanho',
        'preco_compra',
        'preco_venda',
        'quantidade_estoque',
        'estoque_minimo',
        'unidade',
        'ativo',
        'fornecedor',
        'data_cadastro',
        'imagem',
    ];
    
    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'preco_compra' => 'float',
        'preco_venda' => 'float',
        'quantidade_estoque' => 'integer',
        'estoque_minimo' => 'integer',
        'ativo' => 'boolean',
        'data_cadastro' => 'datetime',
    ];
    
    /**
     * Relacionamento com a categoria do produto
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    
    /**
     * Relacionamento com itens de venda
     */
    public function itensVenda()
    {
        return $this->hasMany(ItemVenda::class);
    }
    
    /**
     * Relacionamento com movimentações de estoque
     */
    public function movimentacoes()
    {
        return $this->hasMany(MovimentacaoEstoque::class);
    }
}
