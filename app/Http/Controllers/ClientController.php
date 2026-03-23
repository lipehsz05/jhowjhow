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

        // Quem mais compra (valor total em vendas concluídas) no topo; empate por nome
        $clientes = $query
            ->withCount('vendas')
            ->withSum([
                'vendas as total_gasto_concluidas' => fn ($q) => $q->where('status', 'concluida'),
            ], 'valor_total')
            ->orderByDesc(
                Venda::query()
                    ->selectRaw('COALESCE(SUM(valor_total), 0)')
                    ->whereColumn('cliente_id', 'clientes.id')
                    ->where('status', 'concluida')
            )
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
     * Próxima venda do cliente em relação a vendas concluídas (para mensagens ao vendedor na tela de nova venda).
     */
    public function milestonePreview(Cliente $cliente)
    {
        $vendasConcluidas = Venda::query()
            ->where('cliente_id', $cliente->id)
            ->where('status', 'concluida')
            ->count();

        $proximaNumero = $vendasConcluidas + 1;

        $marcos = [1, 5, 10, 20, 30, 40, 50, 100, 200, 300, 400, 500, 1000];
        $ehMarco = in_array($proximaNumero, $marcos, true);

        $nome = (string) $cliente->nome;

        $resumo = $ehMarco
            ? null
            : ($vendasConcluidas === 0
                ? 'Este cliente ainda não possui vendas concluídas. Ao finalizar esta venda como concluída, será a 1ª.'
                : "Este cliente já possui {$vendasConcluidas} venda(s) concluída(s). Ao finalizar esta venda como concluída, será a {$proximaNumero}ª.");

        $payload = [
            'cliente_id' => $cliente->id,
            'nome' => $nome,
            'vendas_concluidas' => $vendasConcluidas,
            'proxima_venda_numero' => $proximaNumero,
            'eh_marco' => $ehMarco,
            'titulo' => null,
            'mensagem' => null,
            'resumo' => $resumo,
        ];

        if ($ehMarco) {
            $msg = $this->mensagemVendaMarcoParaVendedor($proximaNumero, $nome);
            $payload['titulo'] = $msg['titulo'];
            $payload['mensagem'] = $msg['mensagem'];
        }

        return response()->json($payload);
    }

    /**
     * @return array{titulo: string, mensagem: string}
     */
    private function mensagemVendaMarcoParaVendedor(int $numero, string $nomeCliente): array
    {
        return match ($numero) {
            1 => [
                'titulo' => "Boas-vindas, {$nomeCliente}!",
                'mensagem' => 'Esta será a primeira venda concluída deste cliente. Momento ideal para um atendimento caloroso, confirmar dados de contato e já deixar um bom registro no sistema.',
            ],
            5 => [
                'titulo' => '5ª venda concluída no horizonte!',
                'mensagem' => "Com {$nomeCliente}, a relação já está ganhando ritmo. Vale reforçar preferências, agradecer a recorrência e sugerir um complemento que faça sentido.",
            ],
            10 => [
                'titulo' => 'Essa é a 10ª venda deste cliente!',
                'mensagem' => "{$nomeCliente} já é cliente recorrente. Confira o histórico de compras antes de fechar e aproveite para oferecer novidades alinhadas ao perfil.",
            ],
            20 => [
                'titulo' => 'Marco: 20 vendas concluídas!',
                'mensagem' => "Parabéns pela fidelização com {$nomeCliente}. Cliente de longa data — personalize o atendimento e use observações anteriores para agilizar.",
            ],
            30 => [
                'titulo' => '30ª venda — cliente de peso!',
                'mensagem' => "{$nomeCliente} já acumula bastante histórico com a loja. Se algo mudou (tamanho, gosto, restrição), atualize no cadastro para o próximo vendedor.",
            ],
            40 => [
                'titulo' => '40 vendas concluídas!',
                'mensagem' => "Relacionamento consolidado com {$nomeCliente}. Ótimo momento para cruzar dados de ticket médio e pensar em ofertas sob medida.",
            ],
            50 => [
                'titulo' => '50ª venda — número redondo!',
                'mensagem' => "{$nomeCliente} chegou a um marco simbólico. Se a loja tiver política de reconhecimento, este é um bom candidato — e sempre cabe um agradecimento sincero.",
            ],
            100 => [
                'titulo' => '100 vendas! Marco histórico.',
                'mensagem' => "{$nomeCliente} é um case de fidelização. Confira se há observações importantes no cadastro e mantenha o padrão de excelência no atendimento.",
            ],
            200 => [
                'titulo' => '200ª venda concluída!',
                'mensagem' => "Cliente de altíssima recorrência. Com {$nomeCliente}, priorize clareza em troca/devolução e prazos — relacionamento longo merece zero surpresas desagradáveis.",
            ],
            300 => [
                'titulo' => '300 vendas — consistência total.',
                'mensagem' => "{$nomeCliente} está no radar há muito tempo. Revise rapidamente últimas compras para evitar repetir o que não funcionou e reforçar o que funcionou.",
            ],
            400 => [
                'titulo' => '400ª venda!',
                'mensagem' => "Marco de volume elevado com {$nomeCliente}. Use o histórico para agilizar: tamanhos, cores e preferências já devem estar claros.",
            ],
            500 => [
                'titulo' => '500 vendas — patamar especial.',
                'mensagem' => "{$nomeCliente} é praticamente um cliente âncora da base. Internamente, vale destacar esse perfil para campanhas e estoque.",
            ],
            1000 => [
                'titulo' => '1000 vendas! Marco excepcional.',
                'mensagem' => "{$nomeCliente} é um nível raro de fidelização — quase embaixador da loja. Trate com prioridade, documente bem o atendimento e comemore a conquista com a equipe.",
            ],
            default => [
                'titulo' => 'Venda marcante',
                'mensagem' => "Próxima venda concluída será um marco para {$nomeCliente}.",
            ],
        };
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
