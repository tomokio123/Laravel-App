<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() //認証されているユーザーが使えるかどうか→基本的にはTrueにする
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //Imageのバリデーションを書くi 
            'image'=>'image|mimes:jpg,jpeg,png|max:2048|'
        ];
    }

    public function messages()
    {
        return [
            //Imageのバリデーションを書くi 
            'image'=>'指定されたファイルが画像ではありません',
            'mines'=>'指定された拡張子(jpg/jpeg/png)ではありません',
            'max'=>'ファイルサイズは2MB以内にしてください',

        ];
    }
}
