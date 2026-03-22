<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Venda;
use App\Support\BrFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    /**
     * Exibe a lista de clientes
     */
    public function index(Request $request)
    {
        $query = Cliente::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telefone', 'like', "%{$search}%")
                    ->orWhere('cpf_cnpj', 'like', "%{$search}%");
            });
        }

        $clientes = $query
            ->withCount('vendas')
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString();

        return view('clients.index', compact('clientes'));
    }

    /**
     * Detalhes do cliente, histórico de compras e totais financeiros
     */
    public function show(Cliente $cliente)
    {
        $vendas = $cliente->vendas()
            ->with(['itens.produto', 'usuario'])
            ->orderByDesc('data')
            ->get();

        $vendasConcluidas = $vendas->where('status', 'concluida');

        $totalGasto = (float) $vendasConcluidas->sum('valor_total');

        $totalLucro = 0.0;
        $linhasVendas = [];

        foreach ($vendas as $venda) {
            $custoVenda = 0.0;
            foreach ($venda->itens as $item) {
                $precoCompra = $item->produto ? (float) $item->produto->preco_compra : 0.0;
                $custoVenda += $item->quantidade * $precoCompra;
            }
            $lucroVenda = (float) $venda->valor_total - $custoVenda;
            if ($venda->status === 'concluida') {
                $totalLucro += $lucroVenda;
            }
            $linhasVendas[] = [
                'venda' => $venda,
                'custo' => $custoVenda,
                'lucro' => $lucroVenda,
            ];
        }

        return view('clients.show', compact(
            'cliente',
            'linhasVendas',
            'totalGasto',
            'totalLucro',
            'vendas'
        ));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateClienteRequest($request);
        $validated['data_cadastro'] = now();
        $this->assertCpfCnpjUnique($validated['cpf_cnpj'], null);

        Cliente::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Cliente cadastrado com sucesso!');
    }

    public function edit(Cliente $cliente)
    {
        return view('clients.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validated = $this->validateClienteRequest($request);
        $this->assertCpfCnpjUnique($validated['cpf_cnpj'], $cliente);

        $cliente->update($validated);

        return redirect()->route('clients.show', $cliente)
            ->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Remove o cadastro do cliente. Vendas permanecem no sistema (cliente_id = null);
     * valores e histórico financeiro não são apagados.
     */
    public function destroy(Cliente $cliente)
    {
        DB::transaction(function () use ($cliente) {
            Venda::where('cliente_id', $cliente->id)->update(['cliente_id' => null]);
            $cliente->delete();
        });

        return redirect()->route('clients.index')
            ->with('success', 'Cliente removido. As vendas e valores financeiros foram mantidos no sistema.');
    }

    public function search(Request $request)
    {
        $term = (string) $request->input('q', '');
        $clientes = Cliente::query()
            ->when($term !== '', function ($q) use ($term) {
                $q->where(function ($q2) use ($term) {
                    $q2->where('nome', 'like', "%{$term}%")
                        ->orWhere('cpf_cnpj', 'like', "%{$term}%")
                        ->orWhere('telefone', 'like', "%{$term}%");
                });
            })
            ->orderBy('nome')
            ->limit(20)
            ->get(['id', 'nome', 'telefone', 'cpf_cnpj']);

        return response()->json($clientes);
    }

    /**
     * @return array{nome: string, email: string, telefone: ?string, cpf_cnpj: ?string, endereco: ?string, cidade: ?string, estado: ?string, cep: ?string}
     */
    private function validateClienteRequest(Request $request): array
    {
        $semEndereco = $request->boolean('sem_endereco');

        $cepRules = ['nullable', 'string', 'max:12'];
        if (! $semEndereco) {
            $cepRules[] = function (string $attribute, mixed $value, \Closure $fail): void {
                $d = BrFormat::onlyDigits((string) $value);
                if ($d === '') {
                    return;
                }
                if (strlen($d) !== 8) {
                    $fail('O CEP deve ter 8 dígitos.');
                }
            };
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'sem_endereco' => 'sometimes|boolean',
            'cpf_cnpj' => [
                'nullable',
                'string',
                'max:22',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $d = BrFormat::onlyDigits((string) $value);
                    if ($d === '') {
                        return;
                    }
                    if (strlen($d) !== 11 && strlen($d) !== 14) {
                        $fail('Informe um CPF com 11 dígitos ou um CNPJ com 14 dígitos.');
                    }
                },
            ],
            'endereco' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => $cepRules,
        ], [
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido (com @).',
        ]);

        if ($semEndereco) {
            return [
                'nome' => $request->input('nome'),
                'email' => $request->input('email'),
                'telefone' => BrFormat::normalizeTelefone($request->input('telefone')),
                'cpf_cnpj' => BrFormat::normalizeCpfCnpj($request->input('cpf_cnpj')),
                'endereco' => null,
                'cidade' => null,
                'estado' => null,
                'cep' => null,
            ];
        }

        $estado = $request->input('estado');
        if (is_string($estado) && $estado !== '') {
            $estado = strtoupper(substr($estado, 0, 2));
        } else {
            $estado = null;
        }

        return [
            'nome' => $request->input('nome'),
            'email' => $request->input('email'),
            'telefone' => BrFormat::normalizeTelefone($request->input('telefone')),
            'cpf_cnpj' => BrFormat::normalizeCpfCnpj($request->input('cpf_cnpj')),
            'endereco' => $request->input('endereco') ?: null,
            'cidade' => $request->input('cidade') ?: null,
            'estado' => $estado,
            'cep' => BrFormat::normalizeCep($request->input('cep')),
        ];
    }

    private function assertCpfCnpjUnique(?string $digits, ?Cliente $ignore): void
    {
        if ($digits === null || $digits === '') {
            return;
        }

        $conflict = Cliente::query()
            ->when($ignore, fn ($q) => $q->where('id', '!=', $ignore->id))
            ->whereNotNull('cpf_cnpj')
            ->get(['id', 'cpf_cnpj'])
            ->contains(fn ($c) => BrFormat::onlyDigits((string) $c->cpf_cnpj) === $digits);

        if ($conflict) {
            throw ValidationException::withMessages([
                'cpf_cnpj' => ['Este CPF/CNPJ já está cadastrado.'],
            ]);
        }
    }
}
