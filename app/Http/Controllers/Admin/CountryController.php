<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gov;
use Illuminate\Http\Request;
use App\Models\Country;
use DB;
use Route;
use Session;


class CountryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $countries = Country::orderBy('id','desc')
            ->get();
        return view('cp.countries.index',[
            'countries'=>$countries,
        ]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name_ar' => 'required|unique:countries',
            'name_en' => 'required|unique:countries',
        ]);
        $add            = new Country();
        $add->name_ar   = $request->name_ar;
        $add->name_en   = $request->name_en;
        $add->image     = $request->image;

        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Country added successfully');
    }



    public function edit_country(Request $request){
        $this->validate($request,[
            'name_ar' => 'required|',
            'name_en' => 'required|',
        ]);
        /*Country::where('id', $request->country_id)
            ->update([
                'name_ar'      => $request->name_ar,
                'name_en'      => $request->name_en,
                'image'      => $request->image
            ]);*/
        $c=Country::where('id', $request->country_id)->first();
        $c->update($request->all());

        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Country updated successfully');
    }

    public function editCountryStatus(Request $request,$id)
    {
        $cat=Country::where("id",$id)->first();
        if($cat->active == 1){
            Country::where("id",$id)
                ->update(["active" => 0 ]);
        }else{
            Country::where("id",$id)
                ->update(["active" => 1 ]);
        }
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }

}
