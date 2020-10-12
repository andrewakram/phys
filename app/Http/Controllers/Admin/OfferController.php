<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $offers = Offer::join("shop_details","shop_details.shop_id","offers.shop_id")
            ->orderBy('id','desc')
            ->select("offers.id","name_en","name_ar","old_price","new_price","new_price",
                "active","offers.description_en","offers.description_ar","offers.image",
                "shop_details.name","offers.created_at")
            ->get();
        return view('cp.offers.index',[
            'offers'=>$offers,
        ]);

    }

    public function editOfferStatus(Request $request,$id)
    {
        $off=Offer::where("id",$id)->first();
        if($off->active == 1){
            Offer::where("id",$id)
                ->update(["active" => 0 ]);
        }else{
            Offer::where("id",$id)
                ->update(["active" => 1 ]);
        }
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }


}
