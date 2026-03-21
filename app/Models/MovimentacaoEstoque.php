<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimentacaoEstoque extends Model
{
    use HasFactory;
    
    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'movimentacoes_estoque';
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'produto_id',
        'usuario_id',
        'tipo', // entrada ou saída
        'quantidade',
        'data',
        'motivo',
        'observacao',
        'documento_referencia', // nota fiscal, venda_id, etc
    ];
    
    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'quantidade' => 'integer',
        'data' => 'datetime',
    ];
    
    /**
     * Relacionamento com o produto.
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
    
    /**
     * Relacionamento com o usuário que registrou a movimentação.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
