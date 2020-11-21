<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;


class BeAShopRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shop_name' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'shop_image' => 'required',
            'business_id' => 'required',
            'tax_num' => 'required',
            'website' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'is_shop' => 'required',
        ];
    }

    function messages()
    {
        return [
            'shop_name.required' => trans('validation.shop_name'),
            'lat.required' => trans('validation.lat'),
            'lng.required' => trans('validation.lng'),
            'shop_image.required' => trans('validation.shop_image'),
            'business_id.required' => trans('validation.business_id'),
            'tax_num.required' => trans('validation.tax_num'),
            'website.required' => trans('validation.website'),
            'description.required' => trans('validation.description'),
            'category_id.required' => trans('validation.category_id'),
            'is_shop.required' => trans('validation.is_shop'),
        ];
    }
}
