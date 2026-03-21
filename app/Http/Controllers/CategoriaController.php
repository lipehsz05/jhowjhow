<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Formulário para cadastrar uma nova categoria.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Persiste a nova categoria.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:categorias,nome',
            'descricao' => 'nullable|string|max:5000',
            'ativa' => 'sometimes|boolean',
        ], [
            'nome.required' => 'Informe o nome da categoria.',
            'nome.unique' => 'Já existe uma categoria com este nome.',
        ]);

        $validated['ativa'] = $request->boolean('ativa');

        Categoria::create($validated);

        return redirect()
            ->route('categories.create')
            ->with('success', 'Categoria cadastrada com sucesso.');
    }
}
