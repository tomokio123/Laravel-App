<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
                //''などの「キー」はview側から入ってくるname属性
                'name' => ['required', 'string', 'max:255'],
                'information' => ['required', 'string', 'max:1000'],
                'price' => ['required', 'integer'],
                'sort_order' => ["nullable", 'integer'],
                'quantity' => ['required', 'integer', "between:0,99"],
                //exits:shop_idが存在しているかどうかの確認。=>[exists:shops,id]//shopsと書いている場所にはtable名を書いている
                'shop_id' => ['required', 'exists:shops,id'],
                'category' => ['required', 'exists:secondary_categories,id'],
                'image1' => ['nullable', 'exists:images,id'],
                'image2' => ['nullable', 'exists:images,id'],
                'image3' => ['nullable', 'exists:images,id'],
                'image4' => ['nullable', 'exists:images,id'],
                'is_selling' => ['required', 'boolean'],
        ];
    }
}
