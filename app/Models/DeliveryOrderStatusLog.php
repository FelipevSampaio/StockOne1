<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrderStatusLog extends Model
{
    protected $fillable = [
        'delivery_order_id',
        'old_status',
        'new_status',
        'changed_at',
    ];

    public $timestamps = false;

    public function deliveryOrder()
    {
        return $this->belongsTo(DeliveryOrder::class);
    }
}
