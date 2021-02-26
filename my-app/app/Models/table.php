<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class table extends Model
{
    use HasFactory,SoftDeletes;
    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
        'deleted_at' => 'date:Y-m-d H:i:s',
    ];
    protected $fillable = [
        'name',
        'status'
    ];
    protected $hidden = [
        'created_at',
        'deleted_at',
    ];
    public function list_order(){
        return $this->hasMany(order::class,'tables_id','id');
    }
}