<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//table名を[t_stocks]に変更する(m_:master用、t_:transaction用)
class Stock extends Model
{
    use HasFactory;
    protected $table = 't_stocks';

    protected $fillable = [
        'product_id',
        'quantity',
        'type'
    ];
}
