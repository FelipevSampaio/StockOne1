<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurante;
use App\Models\Insumo;
use App\Models\Estoque;
use App\Models\CardapioItem;
use App\Models\Receita;
use Carbon\Carbon;

class BomSaborPastelFrangoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Cria insumos para fazer pastel de frango e estoque fictício
     * para o restaurante Bom Sabor
     */
    public function run(): void
    {
        // Buscar ou criar o restaurante "Bom Sabor"
        $restaurante = Restaurante::firstOrCreate(
            ['nome' => 'Bom Sabor'],
            [
                'cnpj' => '11.222.333/0001-44',
                'endereco' => 'Rua das Flores, 123 - Centro',
                'telefone' => '(11) 98765-4321',
                'email' => 'contato@bomsabor.com.br',
                'status' => 'ativo',
            ]
        );

        $this->command->info("Restaurante '{$restaurante->nome}' encontrado/criado (ID: {$restaurante->id})");

        // Definir os insumos necessários para fazer pastel de frango
        $insumos = [
            [
                'nome' => 'Frango Desfiado',
                'descricao' => 'Frango cozido e desfiado para recheio de pastel',
                'categoria' => 'Proteína',
                'unidade_medida' => 'kg',
                'ponto_reposicao_minimo' => 5.0,
                'custo_unitario' => 18.50,
                'data_validade_minima' => Carbon::now()->addDays(3),
                'estoque' => [
                    'quantidade_atual' => 12.5,
                    'localizacao' => 'Freezer A1',
                ],
            ],
            [
                'nome' => 'Massa de Pastel',
                'descricao' => 'Massa pronta para pastel',
                'categoria' => 'Massa',
                'unidade_medida' => 'kg',
                'ponto_reposicao_minimo' => 10.0,
                'custo_unitario' => 8.90,
                'data_validade_minima' => Carbon::now()->addDays(7),
                'estoque' => [
                    'quantidade_atual' => 25.0,
                    'localizacao' => 'Geladeira B2',
                ],
            ],
            [
                'nome' => 'Cebola',
                'descricao' => 'Cebola branca para tempero',
                'categoria' => 'Tempero',
                'unidade_medida' => 'kg',
                'ponto_reposicao_minimo' => 3.0,
                'custo_unitario' => 4.50,
                'data_validade_minima' => Carbon::now()->addDays(10),
                'estoque' => [
                    'quantidade_atual' => 8.0,
                    'localizacao' => 'Hortifruti C1',
                ],
            ],
            [
                'nome' => 'Alho',
                'descricao' => 'Alho para tempero',
                'categoria' => 'Tempero',
                'unidade_medida' => 'kg',
                'ponto_reposicao_minimo' => 1.0,
                'custo_unitario' => 15.00,
                'data_validade_minima' => Carbon::now()->addDays(15),
                'estoque' => [
                    'quantidade_atual' => 2.5,
                    'localizacao' => 'Hortifruti C1',
                ],
            ],
            [
                'nome' => 'Azeite de Oliva',
                'descricao' => 'Azeite extra virgem para refogar',
                'categoria' => 'Óleo',
                'unidade_medida' => 'L',
                'ponto_reposicao_minimo' => 2.0,
                'custo_unitario' => 28.00,
                'data_validade_minima' => Carbon::now()->addMonths(6),
                'estoque' => [
                    'quantidade_atual' => 5.0,
                    'localizacao' => 'Despensa D1',
                ],
            ],
            [
                'nome' => 'Sal',
                'descricao' => 'Sal refinado',
                'categoria' => 'Tempero',
                'unidade_medida' => 'kg',
                'ponto_reposicao_minimo' => 2.0,
                'custo_unitario' => 2.50,
                'data_validade_minima' => null, // Sal não vence
                'estoque' => [
                    'quantidade_atual' => 10.0,
                    'localizacao' => 'Despensa D2',
                ],
            ],
            [
                'nome' => 'Pimenta do Reino',
                'descricao' => 'Pimenta do reino moída',
                'categoria' => 'Tempero',
                'unidade_medida' => 'kg',
                'ponto_reposicao_minimo' => 0.5,
                'custo_unitario' => 35.00,
                'data_validade_minima' => Carbon::now()->addMonths(12),
                'estoque' => [
                    'quantidade_atual' => 1.2,
                    'localizacao' => 'Despensa D2',
                ],
            ],
            [
                'nome' => 'Óleo de Soja',
                'descricao' => 'Óleo de soja para fritura',
                'categoria' => 'Óleo',
                'unidade_medida' => 'L',
                'ponto_reposicao_minimo' => 10.0,
                'custo_unitario' => 6.50,
                'data_validade_minima' => Carbon::now()->addMonths(8),
                'estoque' => [
                    'quantidade_atual' => 30.0,
                    'localizacao' => 'Despensa D3',
                ],
            ],
            [
                'nome' => 'Tomate',
                'descricao' => 'Tomate para molho e tempero',
                'categoria' => 'Hortifruti',
                'unidade_medida' => 'kg',
                'ponto_reposicao_minimo' => 5.0,
                'custo_unitario' => 5.80,
                'data_validade_minima' => Carbon::now()->addDays(5),
                'estoque' => [
                    'quantidade_atual' => 12.0,
                    'localizacao' => 'Hortifruti C2',
                ],
            ],
            [
                'nome' => 'Salsinha',
                'descricao' => 'Salsinha fresca para finalização',
                'categoria' => 'Hortifruti',
                'unidade_medida' => 'kg',
                'ponto_reposicao_minimo' => 0.5,
                'custo_unitario' => 12.00,
                'data_validade_minima' => Carbon::now()->addDays(3),
                'estoque' => [
                    'quantidade_atual' => 1.5,
                    'localizacao' => 'Hortifruti C2',
                ],
            ],
        ];

        $this->command->info("Criando insumos e estoque para pastel de frango...");

        foreach ($insumos as $dadosInsumo) {
            // Extrair dados do estoque
            $dadosEstoque = $dadosInsumo['estoque'];
            unset($dadosInsumo['estoque']);

            // Criar ou atualizar o insumo
            $insumo = Insumo::updateOrCreate(
                [
                    'restaurante_id' => $restaurante->id,
                    'nome' => $dadosInsumo['nome'],
                ],
                array_merge($dadosInsumo, [
                    'restaurante_id' => $restaurante->id,
                ])
            );

            // Criar ou atualizar o estoque
            Estoque::updateOrCreate(
                ['insumo_id' => $insumo->id],
                $dadosEstoque
            );

            $this->command->info("  ✓ {$insumo->nome} - Estoque: {$dadosEstoque['quantidade_atual']} {$insumo->unidade_medida}");
        }

        $this->command->info("\n✅ Insumos e estoque criados com sucesso para o restaurante '{$restaurante->nome}'!");
        $this->command->info("Total de insumos: " . count($insumos));

        // Criar item do cardápio "Pastel de Frango"
        $this->command->info("\nCriando item do cardápio 'Pastel de Frango'...");
        
        $cardapioItem = CardapioItem::updateOrCreate(
            [
                'restaurante_id' => $restaurante->id,
                'nome' => 'Pastel de Frango',
            ],
            [
                'restaurante_id' => $restaurante->id,
                'nome' => 'Pastel de Frango',
                'descricao' => 'Pastel frito com recheio de frango desfiado temperado com cebola, alho e ervas. Crocante por fora e suculento por dentro.',
                'preco_venda' => 8.50,
                'tempo_preparo_minutos' => 15,
                'complexidade_preparo' => 2,
                'categoria' => 'Salgados',
                'ativo_online' => true,
                'disponibilidade' => true,
            ]
        );

        $this->command->info("  ✓ Item do cardápio criado: {$cardapioItem->nome} - R$ " . number_format($cardapioItem->preco_venda, 2, ',', '.'));

        // Criar receita com quantidades necessárias por unidade de pastel
        $this->command->info("\nCriando receita de Pastel de Frango...");
        
        // Definir receita: quantidade necessária de cada insumo para fazer 1 pastel
        $receita = [
            [
                'nome_insumo' => 'Frango Desfiado',
                'quantidade_necessaria' => 0.08, // 80g por pastel
                'essencial' => true,
            ],
            [
                'nome_insumo' => 'Massa de Pastel',
                'quantidade_necessaria' => 0.05, // 50g por pastel
                'essencial' => true,
            ],
            [
                'nome_insumo' => 'Cebola',
                'quantidade_necessaria' => 0.02, // 20g por pastel
                'essencial' => true,
            ],
            [
                'nome_insumo' => 'Alho',
                'quantidade_necessaria' => 0.001, // 1g por pastel
                'essencial' => true,
            ],
            [
                'nome_insumo' => 'Azeite de Oliva',
                'quantidade_necessaria' => 0.005, // 5ml por pastel
                'essencial' => false, // Pode usar óleo comum
            ],
            [
                'nome_insumo' => 'Sal',
                'quantidade_necessaria' => 0.001, // 1g por pastel
                'essencial' => true,
            ],
            [
                'nome_insumo' => 'Pimenta do Reino',
                'quantidade_necessaria' => 0.0001, // 0.1g por pastel
                'essencial' => false, // Opcional
            ],
            [
                'nome_insumo' => 'Óleo de Soja',
                'quantidade_necessaria' => 0.1, // 100ml por pastel (para fritura, mas reutilizado)
                'essencial' => true, // Necessário para fritar
            ],
            [
                'nome_insumo' => 'Tomate',
                'quantidade_necessaria' => 0.01, // 10g por pastel
                'essencial' => false, // Opcional
            ],
            [
                'nome_insumo' => 'Salsinha',
                'quantidade_necessaria' => 0.001, // 1g por pastel
                'essencial' => false, // Opcional
            ],
        ];

        foreach ($receita as $itemReceita) {
            $insumo = Insumo::where('restaurante_id', $restaurante->id)
                ->where('nome', $itemReceita['nome_insumo'])
                ->first();

            if ($insumo) {
                Receita::updateOrCreate(
                    [
                        'cardapio_item_id' => $cardapioItem->id,
                        'insumo_id' => $insumo->id,
                    ],
                    [
                        'cardapio_item_id' => $cardapioItem->id,
                        'insumo_id' => $insumo->id,
                        'quantidade_necessaria' => $itemReceita['quantidade_necessaria'],
                        'essencial' => $itemReceita['essencial'],
                    ]
                );

                $tipo = $itemReceita['essencial'] ? 'ESSENCIAL' : 'opcional';
                $this->command->info("  ✓ {$insumo->nome}: {$itemReceita['quantidade_necessaria']} {$insumo->unidade_medida} ({$tipo})");
            } else {
                $this->command->warn("  ⚠ Insumo '{$itemReceita['nome_insumo']}' não encontrado!");
            }
        }

        // Calcular custo total da receita
        $custoTotal = 0;
        $receitas = Receita::where('cardapio_item_id', $cardapioItem->id)
            ->with('insumo')
            ->get();
        
        foreach ($receitas as $rec) {
            if ($rec->insumo && $rec->insumo->custo_unitario) {
                $custoTotal += $rec->quantidade_necessaria * $rec->insumo->custo_unitario;
            }
        }

        $margemLucro = $cardapioItem->preco_venda > 0 
            ? (($cardapioItem->preco_venda - $custoTotal) / $cardapioItem->preco_venda) * 100 
            : 0;

        $this->command->info("\n📊 Análise de Custo:");
        $this->command->info("  Custo por unidade: R$ " . number_format($custoTotal, 2, ',', '.'));
        $this->command->info("  Preço de venda: R$ " . number_format($cardapioItem->preco_venda, 2, ',', '.'));
        $this->command->info("  Margem de lucro: " . number_format($margemLucro, 2, ',', '.') . "%");
        $this->command->info("  Lucro por unidade: R$ " . number_format($cardapioItem->preco_venda - $custoTotal, 2, ',', '.'));

        $this->command->info("\n✅ Receita de Pastel de Frango criada com sucesso!");
    }
}

