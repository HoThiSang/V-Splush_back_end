<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class OrderItem extends Model
{
    use HasFactory;
    protected $table = 'order_items';

    protected $fillable = [
        'user_id',
        'product_id',
        'unit_price',
    ];

    public function order(){
        return $this->belongsTo('App\Models\Order', 'order_id','id');
    }

    public function creatNewOrderItem($data)
    {
        return DB::table($this->table)->insert($data);
    }
}
