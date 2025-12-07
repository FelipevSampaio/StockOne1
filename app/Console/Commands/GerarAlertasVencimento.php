<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Insumo;

class GerarAlertasVencimento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alertas:vencimento {dias=7}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera alertas automáticos para insumos próximos do vencimento';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dias = (int) $this->argument('dias');
        Insumo::gerarAlertasVencimento($dias);
        $this->info("Alertas de vencimento gerados para insumos com validade em até {$dias} dias.");
    }
}
