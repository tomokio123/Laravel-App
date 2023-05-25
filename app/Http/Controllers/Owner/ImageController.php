<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//[php artisan make:controller Owner/ImageController --resource ]で自動生成
//Routeも設定しろ
class ImageController extends Controller
{
    
    public function __construct()
    {
        $this->middleware("auth:owners");//オーナーとしてログインしているときに限る

        $this->middleware(function ($request, $next) {
            //
            $id = $request->route()->parameter("image");
            if(!is_null($id)){//null判定
                $imagesOwnerId = Image::findOrFail($id)->owner->id;
                $ownerId = (int)$imagesOwnerId; //キャストして文字列を数字にした。
                if($ownerId !== Auth::id()){//同じでなかったら
                    abort(404); //404画面表示
                }
            }

            return $next($request);
        });
    }

    public function index()
    {
        //DBの外部キーである「owner_id」と現在ログインしているオーナーのAuthに紐づくIDが一致している画像たちを引っ張り出す
        $images = Image::where('owner_id', Auth::id())//画像たちはたくさんあるのでorderByで順番をソートする
        ->orderBy('updated_at', "desc")
        ->paginate(20);

        return view("owner.images.index", compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
