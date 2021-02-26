<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class order extends Model
{
    use HasFactory,SoftDeletes;
    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
        'deleted_at' => 'date:Y-m-d H:i:s',
    ];
    protected $fillable = [
        'time',
        'total_money',
        'shift_id',
        'table_id'
    ];
    protected $hidden = [
        'created_at',
        'deleted_at',
    ];
    public function list_order()
    {
        return $this->hasMany(order_detail::class, 'order_id', 'id');
    }


}