<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//ストレージフォルダでimageのアップロードなどを扱いたいので以下を読み込む
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UploadImageRequest;
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
        return view("owner.images.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request)//UploadImageRequest型に制限する
    {
        $imageFiles = $request->file('files');
        if(!is_null($imageFiles)){
            foreach($imageFiles as $imageFile => $e){
                $fileNameToStore = Storage::putFile("public/products", $e['image']); //リサイズなしの場合
                Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore  
                ]);
            }
            //if(is_array($imageFiles)){
            //    foreach($imageFiles as $imageFile){
            //        $fileNameToStore = Storage::putFile("public/products", $imageFile); //リサイズなしの場合
            //    }
            //} else {
            //    $imageFile = $imageFiles;
            //    $fileNameToStore = Storage::putFile("public/products", $imageFile);
            //}
        }

        return redirect()
        ->route('owner.images.index')
        ->with(['message' => '画像情報を更新しました。',
                'status' => 'info' ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $image = Image::findOrFail($id); 

        return view("owner.images.edit", compact("image"));
        //compactで渡すときは変数から$を抜いたものに""をつける
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
        //まず3つにバリデーションかけて
        $request->validate([ 
            'title' => ['string', 'max:50'],
        ]);

        $image = Image::findOrFail($id);
        $image->title = $request->title; 
        $image->save();//保存

        return redirect()
        ->route("owner.images.index")
        ->with(['message' => '画像情報を更新しました。',
                'status' => 'info' ]);//views/owner/shops/index.bladeにフラッシュメッセージの表示を書く
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
