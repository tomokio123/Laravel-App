<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owner;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        "filename",
    ];

    //リレーション。「Image」は「オーナー一人」に紐づく。(ownerはたくさんのImagesを持つ.hasMany)
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
