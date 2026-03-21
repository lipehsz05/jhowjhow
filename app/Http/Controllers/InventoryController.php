<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    
    /**
     * Exibe a lista de produtos no estoque
     */
    public function index(Request $request)
    {
        $query = Produto::query();
        
        // Filtros
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }
        
        if ($request->has('estoque_baixo') && $request->estoque_baixo) {
            $query->whereRaw('quantidade_estoque <= estoque_minimo');
        }
        
        $produtos = $query->orderBy('nome')->paginate(15);
        $categorias = Categoria::where('ativa', true)->orderBy('nome')->get();
        
        return view('inventory.index', compact('produtos', 'categorias'));
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
        $request->validate([
            'nome' => 'required|max:255',
            'codigo' => 'required|unique:produtos,codigo|max:50',
            'preco_compra' => 'required|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'quantidade_estoque' => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagem' => 'nullable|image|max:2048',
        ]);
        
        $data = $request->all();
        $data['data_cadastro'] = now();
        
        // Upload da imagem
        if ($request->hasFile('imagem')) {
            $path = $request->file('imagem')->store('produtos', 'public');
            $data['imagem'] = $path;
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
        $request->validate([
            'nome' => 'required|max:255',
            'codigo' => 'required|max:50|unique:produtos,codigo,'.$produto->id,
            'preco_compra' => 'required|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'quantidade_estoque' => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagem' => 'nullable|image|max:2048',
        ]);
        
        $data = $request->all();
        
        // Upload da nova imagem
        if ($request->hasFile('imagem')) {
            // Excluir imagem anterior
            if ($produto->imagem) {
                Storage::disk('public')->delete($produto->imagem);
            }
            
            $path = $request->file('imagem')->store('produtos', 'public');
            $data['imagem'] = $path;
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
}
