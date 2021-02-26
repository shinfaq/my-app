<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class shift extends Model
{
    use HasFactory,SoftDeletes;
    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
        'deleted_at' => 'date:Y-m-d H:i:s',
    ];
    protected $fillable = [
        'name', 'start', 'end', 'start_money', 'end_money', 'user_id'
    ];
    protected $hidden = [
        'created_at',
        'deleted_at',
    ];
    public function list_order()
    {
        return $this->hasMany(order::class, 'shifts_id', 'id');
    }
}