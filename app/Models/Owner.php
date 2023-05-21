<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
//SofrDeleteを扱う記述を以下で行うためにこれが必須
use App\Models\Shop;

class Owner extends Authenticatable
{
    use HasFactory, SoftDeletes;
    //この記述をすることで、Ownerモデル内のdeleteを扱うときは「ソフトデリート」として扱える

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    
    //リレーション設定。これをしないとviews/admin/owners/edit.phpの{{ $owner->shop->name }}のように
    //「外部キーのプロパティ」に自由にアクセスすることができなくなる。今回の例では
    //「オーナー画面から紐づくshop_id(外部キー)のname(店舗名)」にアクセスできなくなる。
    //その関係を記述するためにOwner.php(Model)にshop()メソッドを登録し、以下のようにhasOne(一対一関係を明記)しておくと
    //views/admin/owners/edit.phpの{{ $owner->shop->name }}のようにアクセスが可能になる。
    //詳しくはreadoble->Eloquent:リレーション->「一対一」
    public function shop()
    {
        return $this->hasOne(Shop::class);
    }
}
