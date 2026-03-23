<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Support\TamanhosBrasil;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::query()
            ->withCount('produtos')
            ->orderBy('nome')
            ->paginate(20);

        return view('categories.index', compact('categorias'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:categorias,nome',
            'descricao' => 'nullable|string|max:5000',
            'tipo_tamanho' => ['required', Rule::in(array_keys(TamanhosBrasil::labelsTipo()))],
            'ativa' => 'sometimes|boolean',
        ], [
            'nome.required' => 'Informe o nome da categoria.',
            'nome.unique' => 'Já existe uma categoria com este nome.',
        ]);

        $validated['ativa'] = $request->boolean('ativa');

        Categoria::create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoria cadastrada com sucesso.');
    }

    public function edit(Categoria $categoria)
    {
        return view('categories.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:categorias,nome,' . $categoria->id,
            'descricao' => 'nullable|string|max:5000',
            'tipo_tamanho' => ['required', Rule::in(array_keys(TamanhosBrasil::labelsTipo()))],
            'ativa' => 'sometimes|boolean',
        ], [
            'nome.required' => 'Informe o nome da categoria.',
            'nome.unique' => 'Já existe uma categoria com este nome.',
        ]);

        $validated['ativa'] = $request->boolean('ativa');

        $categoria->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->produtos()->exists()) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'Não é possível excluir: existem produtos vinculados a esta categoria.');
        }

        $categoria->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoria excluída com sucesso.');
    }
}
