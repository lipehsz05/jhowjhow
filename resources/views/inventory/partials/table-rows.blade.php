@forelse($produtos ?? [] as $produto)
    <tr>
        <td>{{ $produto->codigo }}</td>
        <td>{{ $produto->nome }}</td>
        <td>{{ $produto->tamanho ?? '—' }}</td>
        <td>{{ $produto->categoria->nome }}</td>
        <td>R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}</td>
        <td>{{ $produto->quantidade_estoque }}</td>
        <td>
            @if($produto->quantidade_estoque > 10)
                <span class="badge" style="background: var(--primary); color: white;">Em estoque</span>
            @elseif($produto->quantidade_estoque > 0)
                <span class="badge" style="background: var(--warning); color: white;">Estoque baixo</span>
            @else
                <span class="badge" style="background: var(--danger); color: white;">Sem estoque</span>
            @endif
        </td>
        <td>
            <div class="d-flex align-items-center">
                @if (!auth()->user()->isVendedor())
                    <a href="{{ route('inventory.edit', $produto->id) }}" class="btn btn-primary btn-sm me-3">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <form action="{{ route('inventory.destroy', $produto->id) }}" method="POST" id="form-delete-{{ $produto->id }}" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $produto->id }}" data-name="{{ $produto->nome }}">
                        <i class="fas fa-trash-alt"></i> Excluir
                    </button>
                @else
                    <span class="text-muted">Apenas visualização</span>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center">Nenhum produto encontrado</td>
    </tr>
@endforelse
