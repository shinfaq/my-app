<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class order_detail extends Model
{
    use HasFactory,SoftDeletes;
    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
        'deleted_at' => 'date:Y-m-d H:i:s',
    ];
    protected $fillable = [
        'amount',
        'price',
        'product_id',
        'order_id'
    ];
    protected $hidden = [
        'created_at',
        'deleted_at',
    ];
    public function product()
    {
        return $this->belongsTo(product::class, 'product_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo(order::class, 'order_id', 'id');
    }
}
