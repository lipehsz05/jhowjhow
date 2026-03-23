<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Categoria;
use App\Support\TamanhosBrasil;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    
    /**
     * Exibe a lista de produtos no estoque
     */
    public function index(Request $request)
    {
        $produtos = $this->produtosIndexQuery($request)->paginate(15)->withQueryString();
        $categorias = Categoria::where('ativa', true)->orderBy('nome')->get();

        return view('inventory.index', compact('produtos', 'categorias'));
    }

    /**
     * Dados da tabela de estoque para atualização via AJAX (pesquisa e filtro em tempo real).
     */
    public function tableData(Request $request)
    {
        $produtos = $this->produtosIndexQuery($request)
            ->paginate(15)
            ->appends($request->only(['search', 'categoria', 'estoque_baixo']));

        return response()->json([
            'html' => view('inventory.partials.table-rows', compact('produtos'))->render(),
            'pagination' => view('inventory.partials.pagination-inventory', compact('produtos'))->render(),
            'total' => $produtos->total(),
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder<Produto>
     */
    private function produtosIndexQuery(Request $request)
    {
        $query = Produto::query()->with('categoria');

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                    ->orWhere('codigo', 'like', "%{$search}%")
                    ->orWhere('tamanho', 'like', "%{$search}%");
            });
        }

        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->input('categoria'));
        }

        if ($request->has('estoque_baixo') && $request->boolean('estoque_baixo')) {
            $query->whereRaw('quantidade_estoque <= estoque_minimo');
        }

        return $query->orderBy('nome');
    }
    
    /**
     * Mostra o formulário para criar um novo produto
     */
    public function create()
    {
        $categorias = Categoria::where('ativa', true)->orderBy('nome')->get();
        return view('inventory.create', compact('categorias'));
    }
    
    /**
     * Armazena um novo produto
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->produtoValidationRules($request, null));

        $categoria = Categoria::findOrFail($validated['categoria_id']);
        $tipo = $categoria->tipo_tamanho ?? TamanhosBrasil::TIPO_UNICO;

        $data = [
            'nome' => $validated['nome'],
            'codigo' => $validated['codigo'],
            'descricao' => $request->input('descricao'),
            'categoria_id' => $validated['categoria_id'],
            'tamanho' => in_array($tipo, [TamanhosBrasil::TIPO_ROUPA, TamanhosBrasil::TIPO_CALCADO], true)
                ? $validated['tamanho']
                : null,
            'preco_compra' => $validated['preco_compra'],
            'preco_venda' => $validated['preco_venda'],
            'quantidade_estoque' => $validated['quantidade_estoque'],
            'estoque_minimo' => $validated['estoque_minimo'],
            'fornecedor' => $request->input('fornecedor'),
            'ativo' => $request->boolean('ativo'),
            'data_cadastro' => now(),
        ];

        if ($request->hasFile('imagem')) {
            $data['imagem'] = $request->file('imagem')->store('produtos', 'public');
        }

        Produto::create($data);
        
        return redirect()->route('inventory.index')
            ->with('success', 'Produto cadastrado com sucesso!');
    }
    
    /**
     * Mostra o formulário para editar um produto existente
     */
    public function edit(Produto $produto)
    {
        $categorias = Categoria::where('ativa', true)->orderBy('nome')->get();
        return view('inventory.edit', compact('produto', 'categorias'));
    }
    
    /**
     * Atualiza um produto existente
     */
    public function update(Request $request, Produto $produto)
    {
        $validated = $request->validate($this->produtoValidationRules($request, $produto->id));

        $categoria = Categoria::findOrFail($validated['categoria_id']);
        $tipo = $categoria->tipo_tamanho ?? TamanhosBrasil::TIPO_UNICO;

        $data = [
            'nome' => $validated['nome'],
            'codigo' => $validated['codigo'],
            'descricao' => $request->input('descricao'),
            'categoria_id' => $validated['categoria_id'],
            'tamanho' => in_array($tipo, [TamanhosBrasil::TIPO_ROUPA, TamanhosBrasil::TIPO_CALCADO], true)
                ? $validated['tamanho']
                : null,
            'preco_compra' => $validated['preco_compra'],
            'preco_venda' => $validated['preco_venda'],
            'quantidade_estoque' => $validated['quantidade_estoque'],
            'estoque_minimo' => $validated['estoque_minimo'],
            'fornecedor' => $request->input('fornecedor'),
            'ativo' => $request->boolean('ativo'),
        ];

        if ($request->hasFile('imagem')) {
            if ($produto->imagem) {
                Storage::disk('public')->delete($produto->imagem);
            }
            $data['imagem'] = $request->file('imagem')->store('produtos', 'public');
        }

        $produto->update($data);
        
        return redirect()->route('inventory.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }
    
    /**
     * Remove um produto
     */
    public function destroy(Produto $produto)
    {
        try {
            // Iniciar uma transação para garantir a integridade dos dados
            DB::beginTransaction();
            
            // Remover todos os itens de venda relacionados ao produto
            // Esta é uma solução mais direta que não preserva o histórico completo,
            // mas permite a exclusão do produto sem violar a integridade do banco
            DB::table('itens_venda')->where('produto_id', $produto->id)->delete();
            
            // Remover todas as movimentações de estoque relacionadas ao produto
            DB::table('movimentacoes_estoque')->where('produto_id', $produto->id)->delete();
            
            // Excluir imagem
            if ($produto->imagem) {
                Storage::disk('public')->delete($produto->imagem);
            }
            
            // Excluir o produto
            $produto->delete();
            
            // Confirmar a transação
            DB::commit();
            
            return redirect()->route('inventory.index')
                ->with('success', 'Produto excluído com sucesso!');
                
        } catch (\Exception $e) {
            // Reverter a transação em caso de erro
            DB::rollBack();
            
            return redirect()->route('inventory.index')
                ->with('error', 'Erro ao excluir produto: ' . $e->getMessage());
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function produtoValidationRules(Request $request, ?int $produtoId): array
    {
        $codigoRule = $produtoId !== null
            ? 'required|max:50|unique:produtos,codigo,'.$produtoId
            : 'required|unique:produtos,codigo|max:50';

        $rules = [
            'nome' => 'required|max:255',
            'codigo' => $codigoRule,
            'preco_compra' => 'required|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'quantidade_estoque' => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagem' => 'nullable|image|max:2048',
        ];

        $categoria = Categoria::query()->find($request->input('categoria_id'));
        $tipo = $categoria ? ($categoria->tipo_tamanho ?? TamanhosBrasil::TIPO_UNICO) : TamanhosBrasil::TIPO_UNICO;

        if ($tipo === TamanhosBrasil::TIPO_ROUPA) {
            $rules['tamanho'] = ['required', 'string', Rule::in(TamanhosBrasil::opcoesRoupa())];
        } elseif ($tipo === TamanhosBrasil::TIPO_CALCADO) {
            $rules['tamanho'] = ['required', 'string', Rule::in(TamanhosBrasil::opcoesCalcado())];
        } else {
            $rules['tamanho'] = 'nullable|string|max:20';
        }

        return $rules;
    }
}
