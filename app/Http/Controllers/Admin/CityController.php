<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gov;
use Illuminate\Http\Request;
use App\Models\City;
use DB;
use Route;
use Session;


class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $cities = City::orderBy('id','desc')
            ->join("govs","govs.id","cities.gov_id")
            ->select("cities.id","cities.name_ar","cities.name_en","govs.id as gov_id",
                "govs.name_ar as gov_name_ar","govs.name_en as gov_name_en")
            ->get();
        $govs = Gov::orderBy('id','desc')->get();
        return view('cp.cities.index',[
            'cities'=>$cities,
            'govs'=>$govs,
        ]);

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name_ar' => 'required|unique:cities',
            'name_en' => 'required|unique:cities',
            'gov_id' => 'required',
        ]);
        $add            = new City();
        $add->name_ar   = $request->name_ar;
        $add->name_en   = $request->name_en;
        $add->gov_id   = $request->gov_id;
        $add->save();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','City added successfully');
    }



    public function edit_city(Request $request){
        $this->validate($request,[
            'name_ar' => 'required|',
            'name_en' => 'required|',
            'gov_id' => 'required',

        ]);
        City::where('id', $request->city_id)
            ->update([
                'name_ar'      => $request->name_ar,
                'name_en'      => $request->name_en,
                'gov_id'      => $request->gov_id,
            ]);
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','City updated successfully');
    }

    public function editCityStatus(Request $request,$id)
    {
        $cat=City::where("id",$id)->first();
        if($cat->active == 1){
            City::where("id",$id)
                ->update(["active" => 0 ]);
        }else{
            City::where("id",$id)
                ->update(["active" => 1 ]);
        }
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }

}
