<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owner;

class Shop extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'owner_id',
        'name',
        'information',
        "filename",
        "is_selling"
    ];

    //Shop(model)は一人のオーナーに紐づく。Owner(model)も一つの店舗に紐づく(Owner.phpにも以下後逆の「shop」メソッドを定義)。「一対一」関係。
    //owner->shopがhasOneメソッドなら、逆のshop->ownerは「belongsTo」メソッドを定義する
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
