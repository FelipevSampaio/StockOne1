@php
    $receita = $receita ?? null;
@endphp

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
            Item do cardápio *
            <select name="cardapio_item_id" required class="mt-1 w-full rounded-xl border-gray-200 dark:border-gray-600 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">Selecione...</option>
                @foreach ($cardapioItens as $item)
                    <option value="{{ $item->id }}" @selected(old('cardapio_item_id', $receita->cardapio_item_id ?? '') == $item->id)>
                        {{ $item->nome }}
                        @if($item->preco_venda)
                            (R$ {{ number_format($item->preco_venda, 2, ',', '.') }})
                        @endif
                    </option>
                @endforeach
            </select>
        </label>
    </div>

    <div>
        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
            Insumo *
            <select name="insumo_id" required class="mt-1 w-full rounded-xl border-gray-200 dark:border-gray-600 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">Selecione...</option>
                @foreach ($insumos as $insumo)
                    <option value="{{ $insumo->id }}" @selected(old('insumo_id', $receita->insumo_id ?? '') == $insumo->id)>
                        {{ $insumo->nome }}
                        @if($insumo->custo_unitario)
                            (R$ {{ $insumo->custo_unitario < 0.01 ? number_format($insumo->custo_unitario, 6, ',', '.') : number_format($insumo->custo_unitario, 2, ',', '.') }}/{{ $insumo->unidade_medida }})
                        @endif
                    </option>
                @endforeach
            </select>
        </label>
    </div>

    <div>
        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
            Quantidade necessária *
            <input type="number" step="0.01" min="0.01" name="quantidade_necessaria" value="{{ old('quantidade_necessaria', $receita->quantidade_necessaria ?? '') }}" required class="mt-1 w-full rounded-xl border-gray-200 dark:border-gray-600 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
        </label>
    </div>

    <div class="flex items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 px-4 py-3">
        <input type="checkbox" name="essencial" value="1" @checked(old('essencial', $receita->essencial ?? true)) class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
        <span class="text-sm text-gray-700 dark:text-gray-300">Insumo essencial para produção</span>
    </div>
</div>

