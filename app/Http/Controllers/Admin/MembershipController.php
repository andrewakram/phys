<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gov;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Models\City;
use DB;
use Route;
use Session;


class MembershipController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cats = Membership::orderBy('id', 'desc')
            ->get();
        return view('cp.memberships.index', [
            'cats' => $cats,
        ]);

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name_ar' => 'required',
            'name_en' => 'required',
            'price' => 'required',

        ]);
        $add                    = new Membership();
        $add->name_ar           = $request->name_ar;
        $add->name_en           = $request->name_en;
        $add->description_ar    = $request->description_ar;
        $add->description_en    = $request->description_en;
        $add->period            = $request->period;
        $add->price             = $request->price;
        $add->no_of_images      = $request->no_of_images;
        $add->no_of_videos      = $request->no_of_videos;
        $add->image             = $request->image;
        $add->save();
        session()->flash('insert_message', 'تمت العملية بنجاح');
        return back()->with('success', 'City added successfully');
    }


    public function edit_membership(Request $request)
    {
        $this->validate($request, [
            'name_ar' => 'required',
            'name_en' => 'required',
            'price' => 'required',

        ]);
        $c = Membership::where('id', $request->membership_id)->first();
        $c->update($request->all());
        session()->flash('insert_message', 'تمت العملية بنجاح');
        return back()->with('success', 'City updated successfully');
    }

//    public function editCatStatus(Request $request,$id)
//    {
//        $cat=Category::where("id",$id)->first();
//        if($cat->active == 1){
//            Category::where("id",$id)
//                ->update(["active" => 0 ]);
//        }else{
//            Category::where("id",$id)
//                ->update(["active" => 1 ]);
//        }
//        session()->flash('insert_message','تمت العملية بنجاح');
//        return back();
//    }

}
