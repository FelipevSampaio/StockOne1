<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    const STATUS_PENDENTE = 'pendente';
    const STATUS_PREPARO = 'em_preparo';
    const STATUS_DESPACHADO = 'despachado';
    const STATUS_ENTREGUE = 'entregue';
    const STATUS_CANCELADO = 'cancelado';

    public static function statusList()
    {
        return [
            self::STATUS_PENDENTE => 'Pendente',
            self::STATUS_PREPARO => 'Em preparo',
            self::STATUS_DESPACHADO => 'Despachado',
            self::STATUS_ENTREGUE => 'Entregue',
            self::STATUS_CANCELADO => 'Cancelado',
        ];
    }

    protected $fillable = [
        'pedido_id',
        'rota',
        'horario_entrega',
        'status',
        'tempo_preparo',
        'tempo_despacho',
    ];

    // Relacionamento com Pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    /**
     * Calcula o tempo ideal de preparo e despacho baseado na rota e horário de entrega.
     */
    public function calcularTempoIdeal()
    {
        // Exemplo simples: tempo de preparo depende da distância (simulada) e horário
        $distancia = $this->obterDistanciaRota();
        $tempo_preparo = max(20, $distancia * 2); // mínimo 20 min, 2 min por km
        $tempo_despacho = max(10, $distancia);    // mínimo 10 min, 1 min por km
        $this->tempo_preparo = $tempo_preparo;
        $this->tempo_despacho = $tempo_despacho;
        $this->save();
    }

    /**
     * Simula obtenção da distância da rota (em km).
     */
    public function obterDistanciaRota()
    {
        // Exemplo: extrai número da rota ou retorna valor fixo
        if (preg_match('/(\d+)/', $this->rota, $matches)) {
            return (int)$matches[1];
        }
        return 5; // valor padrão
    }
}
