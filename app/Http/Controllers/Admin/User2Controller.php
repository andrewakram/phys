<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\Membership;
use App\Models\Shop_detail;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use DB;

class User2Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $users = User::join("cities","cities.id","users.city_id")
            ->orderBy('id','desc')
            ->where("is_shop",0)
            ->select("users.id","users.name","users.phone","users.email","users.active",
                "users.suspend","cities.name_ar","cities.name_en","users.image")
            ->get();
        return view('cp.users.index',['users'=>$users]);

    }

    public function index2(){
        $type='المتاجر قيد التنفيذ';
        $users = User::join("shop_details","shop_details.shop_id","users.id")
            ->join("categories","categories.id","shop_details.category_id")
            ->join("cities","cities.id","users.city_id")
            ->orderBy('id','desc')
            ->where("is_shop",1)
            ->where("accept",0)
            ->select("users.id","shop_details.name","users.phone","users.email","users.active",
                "users.suspend","cities.name_ar","cities.name_en","users.is_shop","shop_details.image",
                "business_id","tax_num","website","open_hours","open_from","open_to","accept",
                "categories.name_en as cat_name_en","categories.name_ar as cat_name_ar",
                "shop_details.bussiness_registration","shop_details.tax_card","shop_details.commercial_register",
                "membership_id"
            )
            ->get();
        foreach ($users as $user){
            $user->membership = Membership::whereId($user->membership_id)
                ->select('id','name_ar','name_en','description_ar','description_ar',
                    'image','price','period','no_of_images','no_of_videos')
                ->first();
        }
        return view('cp.shops.index',['users'=>$users,'type'=>$type]);
    }

    public function activeShops(){
        $type='المتاجر المفعلة';
        $users = User::join("shop_details","shop_details.shop_id","users.id")
            ->join("categories","categories.id","shop_details.category_id")
            ->join("cities","cities.id","users.city_id")
            ->orderBy('id','desc')
            ->where("is_shop",1)
            ->where("suspend",0)
            ->where("accept",1)
            ->select("users.id","shop_details.name","users.phone","users.email","users.active",
                "users.suspend","cities.name_ar","cities.name_en","users.is_shop","shop_details.image",
                "business_id","tax_num","website","open_hours","open_from","open_to","accept",
                "categories.name_en as cat_name_en","categories.name_ar as cat_name_ar",
                "shop_details.bussiness_registration","shop_details.tax_card","shop_details.commercial_register",
                "membership_id"
            )
            ->get();
        foreach ($users as $user){
            $user->membership = Membership::whereId($user->membership_id)
                ->select('id','name_ar','name_en','description_ar','description_ar',
                    'image','price','period','no_of_images','no_of_videos')
                ->first();
        }
        return view('cp.shops.index',['users'=>$users,'type'=>$type]);
    }

    public function inactiveShops(){
        $type='المتاجر الغير مفعلة';
        $users = User::join("shop_details","shop_details.shop_id","users.id")
            ->join("categories","categories.id","shop_details.category_id")
            ->join("cities","cities.id","users.city_id")
            ->orderBy('id','desc')
            ->where("is_shop",1)
            ->where("suspend",1)
            ->where("accept",1)
            ->select("users.id","shop_details.name","users.phone","users.email","users.active",
                "users.suspend","cities.name_ar","cities.name_en","users.is_shop","shop_details.image",
                "business_id","tax_num","website","open_hours","open_from","open_to","accept",
                "categories.name_en as cat_name_en","categories.name_ar as cat_name_ar",
                "shop_details.bussiness_registration","shop_details.tax_card","shop_details.commercial_register",
                "membership_id"
            )
            ->get();
        foreach ($users as $user){
            $user->membership = Membership::whereId($user->membership_id)
                ->select('id','name_ar','name_en','description_ar','description_ar',
                    'image','price','period','no_of_images','no_of_videos')
                ->first();
        }
        return view('cp.shops.index',['users'=>$users,'type'=>$type]);
    }


    public function indexAdmin(){
        $users = User::orderBy('id','desc')
            ->where("is_shop",3) //as admin
            ->select("users.id","users.name","users.email","users.suspend")
            ->get();
        $permissions = DB::table('permissions')->get();
        return view('cp.admins.index',['users'=>$users,'permissions'=>$permissions]);
    }

    public function createSubAdmin(Request $request){
        //dd($request->permissions);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jwt' => uniqid(),
            'phone' => uniqid(),
            'active' => 1,
            'token' => uniqid(),
            'city_id' => 1,
            'is_shop' => 3,
        ]);
        $user->givePermissionTo($request->permissions);
        return redirect('/home');
    }



    public function editClientStatus(Request $request,$id)
    {
        $cat=User::where("id",$id)->first();
        if($cat->suspend == 1){
            User::where("id",$id)
                ->update(["suspend" => 0 ]);
        }else{
            User::where("id",$id)
                ->update(["suspend" => 1 ]);
        }
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }

    public function acceptShop(Request $request,$id)
    {
        $user=User::where("id",$id)->first();
        $cat=Shop_detail::where("shop_id",$id)->first();
        if($cat->accept == 0){
            Shop_detail::where("shop_id",$id)
                ->update(["accept" => 1 ]);
        }
        global $title;
        global $message;
        if($request->header('lang') == 'en'){
            $title='Welcome to E3ln App';
            $message='Welcome to E3ln App & Admin accepted your joining request.';
        }else{
            $title='مرحبا بكم في تطبيق اعلن';
            $message='مرحبا بكم في تطبيق اعلن " مدير النظام فام بالموافقة علي طلبانضمامك.';
        }
        Notification::send("$user->token", $title , $message , 2 );
        session()->flash('insert_message','تمت العملية بنجاح');
        return back();
    }

}
