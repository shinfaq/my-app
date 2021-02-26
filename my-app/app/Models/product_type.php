<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class product_type extends Model
{
    use HasFactory,SoftDeletes;
    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
        'deleted_at' => 'date:Y-m-d H:i:s',
    ];
    protected $fillable = [
        'name'
    ];
    protected $hidden = [
        'created_at',
        'deleted_at',
    ];
    public function list_product()
    {
        return $this->hasMany(product::class, 'type', 'id');
    }
    public function order_detail()
    {
        return $this->hasManyThrough(order_detail::class,product::class, 'type', 'product_id','id');
    }
}