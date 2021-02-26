<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class product extends Model
{
    use HasFactory,SoftDeletes;
    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
        'deleted_at' => 'date:Y-m-d H:i:s',
    ];
    protected $fillable = [
        'name',
        'type',
        'price',
        'image'
    ];
    protected $hidden = [
        'created_at',
        'deleted_at',
    ];
    public function product_type_name()
    {
        return $this->belongsTo(product_type::class, 'type', 'id')->select(array('id','name'));
    }
    public function list_order()
    {
        return $this->hasMany(order_detail::class, 'product_id', 'id');
    }

    public function product_type()
    {
        return $this->belongsTo(product_type::class, 'type', 'id');
    }
}