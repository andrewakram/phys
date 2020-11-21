<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/13/2019
 * Time: 8:07 PM
 */

namespace App\Http\Controllers\Eloquent\Admin;


use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function index()
    {
        return Category::where('type',1)->where('parent_id',null)->get();
    }

    public function getSubCat($id)
    {
        return Category::where('type',2)->where('parent_id',$id)->get();

    }

    public function getThirdCat($id)
    {
        return Category::where('type',3)->where('parent_id',$id)->get();

    }

    public function storeSub($input)
    {
        Category::create([
            'type' => '2',
            'parent_id' => $input->parent_id,
            'ar_name' => $input->ar_name,
            'en_name' => $input->en_name,
            'image' => $input->image
        ]);
    }

    public function storeThird($input)
    {
        Category::create([
            'type' => '3',
            'parent_id' => $input->parent_id,
            'price' => $input->price,
            'ar_name' => $input->ar_name,
            'en_name' => $input->en_name,
            'image' => $input->image
        ]);
    }

    public function editCat($input)
    {
        $category = Category::whereId($input->cat_id)->first();
        $category->ar_name = $input->ar_name;
        $category->en_name = $input->en_name;

        if($input->image)
            $category->image = $input->image;

        if($input->price)
            $category->price = $input->price;

        if($input->description)
            $category->description = $input->description;

        $category->save();

    }
}
