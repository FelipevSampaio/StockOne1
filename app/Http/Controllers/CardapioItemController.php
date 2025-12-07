<?php

namespace App\Http\Controllers;

use App\Models\CardapioItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CardapioItemController extends Controller
{
    /**
     * Verifica se o item do cardápio está disponível (todos insumos essenciais em estoque)
     * e sugere substituições automáticas para itens em falta.
     */
    public function verificarDisponibilidadeEsubstituicoes(CardapioItem $item)
    {
        $disponivel = true;
        $substituicoes = [];
        foreach ($item->receitas()->where('essencial', true)->get() as $receita) {
            $insumo = $receita->insumo;
            $estoque = $insumo->estoque;
            if (!$estoque || $estoque->quantidade_atual < $receita->quantidade_necessaria) {
                $disponivel = false;
                // Buscar insumos da mesma categoria com estoque suficiente
                $alternativas = \App\Models\Insumo::where('categoria', $insumo->categoria)
                    ->where('id', '!=', $insumo->id)
                    ->whereHas('estoque', function($q) use ($receita) {
                        $q->where('quantidade_atual', '>=', $receita->quantidade_necessaria);
                    })
                    ->get();
                foreach ($alternativas as $alt) {
                    $substituicoes[] = [
                        'insumo_faltante' => $insumo->nome,
                        'substituto' => $alt->nome,
                        'categoria' => $alt->categoria,
                    ];
                }
            }
        }
        return [
            'disponivel' => $disponivel,
            'substituicoes' => $substituicoes,
        ];
    }
    public function index(Request $request)
    {
        $restauranteId = $this->restauranteId();

        $query = CardapioItem::with('restaurante')
            ->where('restaurante_id', $restauranteId);

        // Filtro de busca
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%")
                  ->orWhere('categoria', 'like', "%{$search}%");
            });
        }

        // Filtro por categoria
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->get('categoria'));
        }

        // Filtro por status
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'online') {
                $query->where('ativo_online', true);
            } elseif ($status === 'offline') {
                $query->where('ativo_online', false);
            }
        }

        // Ordenação
        $sortBy = $request->get('sort', 'nome');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $itens = $query->paginate($perPage)->withQueryString();

        // Estatísticas
        $stats = [
            'total' => CardapioItem::where('restaurante_id', $restauranteId)->count(),
            'online' => CardapioItem::where('restaurante_id', $restauranteId)
                ->where('ativo_online', true)->count(),
            'offline' => CardapioItem::where('restaurante_id', $restauranteId)
                ->where('ativo_online', false)->count(),
            'categorias' => CardapioItem::where('restaurante_id', $restauranteId)
                ->distinct()->count('categoria'),
        ];

        // Categorias disponíveis
        $categorias = CardapioItem::where('restaurante_id', $restauranteId)
            ->whereNotNull('categoria')
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria');

        return view('cardapio_itens.index', compact('itens', 'stats', 'categorias'));
    }

    public function create()
    {
        return view('cardapio_itens.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'preco_venda' => ['required', 'numeric'],
            'tempo_preparo_minutos' => ['nullable', 'integer', 'min:0'],
            'complexidade_preparo' => ['required', 'integer', 'min:1', 'max:10'],
            'categoria' => ['nullable', 'string', 'max:100'],
            'ativo_online' => ['nullable', 'boolean'],
            'imagem' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:8192'],
            'disponibilidade' => ['nullable', 'boolean'],
            'ingredientes' => ['nullable', 'string'],
            'promocao' => ['nullable', 'string'],
        ]);

        $data['restaurante_id'] = $this->restauranteId();
        $data['ativo_online'] = $request->boolean('ativo_online');
        $data['disponibilidade'] = $request->boolean('disponibilidade');

        // Ingredientes: transforma string em array
        if (!empty($data['ingredientes'])) {
            $data['ingredientes'] = array_map('trim', explode(',', $data['ingredientes']));
        } else {
            $data['ingredientes'] = [];
        }

        // Promoção: salva como array json
        if (!empty($data['promocao'])) {
            $data['promocao'] = ['descricao' => $data['promocao']];
        } else {
            $data['promocao'] = null;
        }

        if ($request->hasFile('imagem')) {
            $data['imagem'] = $request->file('imagem')->store('cardapio-itens', 'public');
        }

        CardapioItem::create($data);

        return redirect()->route('admin.cardapio.index')->with('success', 'Item de cardápio cadastrado com sucesso.');
    }

    public function edit(CardapioItem $cardapioItem)
    {
        $this->authorizeItem($cardapioItem);

        return view('cardapio_itens.edit', ['item' => $cardapioItem]);
    }

    public function update(Request $request, CardapioItem $cardapioItem)
    {
        $this->authorizeItem($cardapioItem);

        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'preco_venda' => ['required', 'numeric'],
            'tempo_preparo_minutos' => ['nullable', 'integer', 'min:0'],
            'complexidade_preparo' => ['required', 'integer', 'min:1', 'max:10'],
            'categoria' => ['nullable', 'string', 'max:100'],
            'ativo_online' => ['nullable', 'boolean'],
            'imagem' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:8192'],
            'disponibilidade' => ['nullable', 'boolean'],
            'ingredientes' => ['nullable', 'string'],
            'promocao' => ['nullable', 'string'],
        ]);

        $data['ativo_online'] = $request->boolean('ativo_online');
        $data['disponibilidade'] = $request->boolean('disponibilidade');

        // Ingredientes: transforma string em array
        if (!empty($data['ingredientes'])) {
            $data['ingredientes'] = array_map('trim', explode(',', $data['ingredientes']));
        } else {
            $data['ingredientes'] = [];
        }

        // Promoção: salva como array json
        if (!empty($data['promocao'])) {
            $data['promocao'] = ['descricao' => $data['promocao']];
        } else {
            $data['promocao'] = null;
        }

        if ($request->hasFile('imagem')) {
            // Remove a imagem antiga se existir
            if ($cardapioItem->imagem) {
                Storage::disk('public')->delete($cardapioItem->imagem);
            }
            $data['imagem'] = $request->file('imagem')->store('cardapio-itens', 'public');
        }

        $cardapioItem->update($data);

        return redirect()->route('admin.cardapio.index')->with('success', 'Item de cardápio atualizado com sucesso.');
    }

    public function destroy(CardapioItem $cardapioItem)
    {
        $this->authorizeItem($cardapioItem);

        // Remove a imagem se existir
        if ($cardapioItem->imagem) {
            Storage::disk('public')->delete($cardapioItem->imagem);
        }

        $cardapioItem->delete();

        return redirect()->route('cardapio-itens.index')->with('success', 'Item de cardápio removido com sucesso.');
    }

    protected function restauranteId(): int
    {
        return (int) session('restaurante_id');
    }

    protected function authorizeItem(CardapioItem $cardapioItem): void
    {
        abort_unless($cardapioItem->restaurante_id === $this->restauranteId(), 403);
    }
}

