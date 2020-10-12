<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Shop_detail;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Database\Schema\Blueprint;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return vow_id
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function reports(){

        return view('cp.reports.reportIndex');
    }

    public function makeReport(Request $request){
        Session::put("dateFrom",$request->dateFrom);
        Session::put("dateTo",$request->dateTo);
        Session::put("type",$request->type);
        $newDateFrom = date("Y-m-d", strtotime(Session::get("dateFrom")));
        $newDateTo = date("Y-m-d", strtotime(Session::get("dateTo")));

        if(Session::get("type") == "usersReport"){
            $usercount = User::join("cities","cities.id","users.city_id")
                ->where("is_shop",0)
                ->where("users.created_at",">=",$newDateFrom)
                ->where("users.created_at","<=",$newDateTo)
                ->count();
            $users=User::join("cities","cities.id","users.city_id")
                ->orderBy('id','desc')
                ->where("is_shop",0)
                ->where("users.created_at",">=",$newDateFrom)
                ->where("users.created_at","<=",$newDateTo)
                ->select("users.id","users.name","users.phone","users.email","users.active",
                    "users.suspend","cities.name_ar","cities.name_en","users.image")
                ->get();
            return view('cp.reports.usersReport',[
                'usercount'         =>$usercount,
                'users'             =>$users,
            ]);
        }

        if(Session::get("type") == "shopsReport"){
            $usercount=User::join("shop_details","shop_details.shop_id","users.id")
                ->join("categories","categories.id","shop_details.category_id")
                ->join("cities","cities.id","users.city_id")
                ->orderBy('id','desc')
                ->where("shop_details.created_at",">=",$newDateFrom)
                ->where("shop_details.created_at","<=",$newDateTo)
                ->where("is_shop",1)
                ->count();
            $users=User::join("shop_details","shop_details.shop_id","users.id")
                ->join("categories","categories.id","shop_details.category_id")
                ->join("cities","cities.id","users.city_id")
                ->orderBy('id','desc')
                ->where("shop_details.created_at",">=",$newDateFrom)
                ->where("shop_details.created_at","<=",$newDateTo)
                ->where("is_shop",1)
                ->select("users.id","shop_details.name","users.phone","users.email","users.active",
                    "users.suspend","cities.name_ar","cities.name_en","users.is_shop","shop_details.image",
                    "business_id","tax_num","website","open_hours","open_from","open_to",
                    "categories.name_en as cat_name_en","categories.name_ar as cat_name_ar")
                ->get();
            return view('cp.reports.shopsReport',[
                'usercount'         =>$usercount,
                'users'             =>$users,
            ]);
        }

        if(Session::get("type") == "offersReport"){
            $usercount=Offer::join("shop_details","shop_details.shop_id","offers.shop_id")
                ->orderBy('id','desc')
                ->where("offers.created_at",">=",$newDateFrom)
                ->where("offers.created_at","<=",$newDateTo)
                ->count();
            $users=Offer::join("shop_details","shop_details.shop_id","offers.shop_id")
                ->orderBy('id','desc')
                ->select("offers.id","name_en","name_ar","old_price","new_price","new_price",
                    "active","offers.description_en","offers.description_ar","offers.image",
                    "shop_details.name","offers.created_at")
                ->where("offers.created_at",">=",$newDateFrom)
                ->where("offers.created_at","<=",$newDateTo)
                ->get();
            return view('cp.reports.offersReport',[
                'usercount'         =>$usercount,
                'users'             =>$users,
            ]);
        }

    }

    public function usersInvoice(){
        $newDateFrom = date("Y-m-d", strtotime(Session::get("dateFrom")));
        $newDateTo = date("Y-m-d", strtotime(Session::get("dateTo")));
        $usercount = User::join("cities","cities.id","users.city_id")
            ->where("is_shop",0)
            ->where("users.created_at",">=",$newDateFrom)
            ->where("users.created_at","<=",$newDateTo)
            ->count();
        $users=User::join("cities","cities.id","users.city_id")
            ->orderBy('id','desc')
            ->where("is_shop",0)
            ->where("users.created_at",">=",$newDateFrom)
            ->where("users.created_at","<=",$newDateTo)
            ->select("users.id","users.name","users.phone","users.email","users.active",
                "users.suspend","cities.name_ar","cities.name_en","users.image")
            ->get();
        return view('cp.reports.usersInvoice',[
            'usercount'         =>$usercount,
            'users'             =>$users,
        ]);
    }

    public function shopsInvoice(){
        $newDateFrom = date("Y-m-d", strtotime(Session::get("dateFrom")));
        $newDateTo = date("Y-m-d", strtotime(Session::get("dateTo")));
        $usercount=User::join("shop_details","shop_details.shop_id","users.id")
            ->join("categories","categories.id","shop_details.category_id")
            ->join("cities","cities.id","users.city_id")
            ->orderBy('id','desc')
            ->where("shop_details.created_at",">=",$newDateFrom)
            ->where("shop_details.created_at","<=",$newDateTo)
            ->where("is_shop",1)
            ->count();
        $users=User::join("shop_details","shop_details.shop_id","users.id")
            ->join("categories","categories.id","shop_details.category_id")
            ->join("cities","cities.id","users.city_id")
            ->orderBy('id','desc')
            ->where("shop_details.created_at",">=",$newDateFrom)
            ->where("shop_details.created_at","<=",$newDateTo)
            ->where("is_shop",1)
            ->select("users.id","shop_details.name","users.phone","users.email","users.active",
                "users.suspend","cities.name_ar","cities.name_en","users.is_shop","shop_details.image",
                "business_id","tax_num","website","open_hours","open_from","open_to",
                "categories.name_en as cat_name_en","categories.name_ar as cat_name_ar")
            ->get();
        return view('cp.reports.shopsInvoice',[
            'usercount'         =>$usercount,
            'users'             =>$users,
        ]);
    }

    public function offersInvoice(){
        $newDateFrom = date("Y-m-d", strtotime(Session::get("dateFrom")));
        $newDateTo = date("Y-m-d", strtotime(Session::get("dateTo")));
        $usercount=Offer::join("shop_details","shop_details.shop_id","offers.shop_id")
            ->orderBy('id','desc')
            ->where("offers.created_at",">=",$newDateFrom)
            ->where("offers.created_at","<=",$newDateTo)
            ->count();
        $users=Offer::join("shop_details","shop_details.shop_id","offers.shop_id")
            ->orderBy('id','desc')
            ->select("offers.id","name_en","name_ar","old_price","new_price","new_price",
                "active","offers.description_en","offers.description_ar","offers.image",
                "shop_details.name","offers.created_at")
            ->where("offers.created_at",">=",$newDateFrom)
            ->where("offers.created_at","<=",$newDateTo)
            ->get();
        return view('cp.reports.offersInvoice',[
            'usercount'         =>$usercount,
            'users'             =>$users,
        ]);
    }


}
