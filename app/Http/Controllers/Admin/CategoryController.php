<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $cats = Category::orderBy('id','desc')
            ->get();
        return view('cp.categories.index',[
            'cats'=>$cats,
        ]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name_ar' => 'required',
            'name_en' => 'required',
            'image' => 'required',

        ]);
        $add            = new Category();
        $add->name_ar   = $request->name_ar;
        $add->name_en   = $request->name_en;
        $add->image     = $request->image;
        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','City added successfully');
    }



    public function edit_cat(Request $request){
        $this->validate($request,[
            'name_ar' => 'required',
            'name_en' => 'required',
            'image' => '',
        ]);
        $c=Category::where('id', $request->cat_id)->first();
        $c->update($request->all());
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','City updated successfully');
    }

    public function editCatStatus(Request $request,$id)
    {
        $cat=Category::where("id",$id)->first();
        if($cat->active == 1){
            Category::where("id",$id)
                ->update(["active" => 0 ]);
        }else{
            Category::where("id",$id)
                ->update(["active" => 1 ]);
        }
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }



}
