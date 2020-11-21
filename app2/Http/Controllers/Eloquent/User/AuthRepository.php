<?php

namespace App\Http\Controllers\Eloquent\User;

use App\Http\Controllers\Interfaces\User\AuthRepositoryInterface;
use App\Models\Membership;
use App\Models\Offer;
use App\Models\User;
use App\Models\Shop_detail;
use App\Models\Shop_categorie;
use App\Models\Verification;
use App\User as AppUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthRepository implements AuthRepositoryInterface {

   
 


    /*public function assign_shop_categories($shop_id,$category_ids){
        foreach ($category_ids as $category_id){
            $Shop_categorie                 = new Shop_categorie();
            $Shop_categorie->shop_id        = $shop_id;
            $Shop_categorie->category_id    = $category_id;
            $Shop_categorie->save();
        }
    }*/

    
 

    public function checkIfPhoneExist($phone)
    {
         $user = User::wherePhone($phone)->first();
         return $user;
    }

    

  

    /*public function checkJWT($jwt)
    {
        return User::whereJwt($jwt)->select('id','password')->first();
    }*/

    public function checkId($id)
    {
        return User::whereId($id)->first();
    }

    

    public function activeUser($phone)
    {
        $user = $this->checkIfPhoneExist($phone);
        $user->active = 1;
        $user->save();
        return $user;
    }

    public function userData($jwt)
    {
        return User::where('jwt',$jwt)->first();
    }

   
 

    // public function editeProfile($id,$input,$lang){
    //     if($input->is_shop == 1){ /*update shop additional data*/
    //         $startTime = Carbon::parse($input->open_from);
    //         $finishTime = Carbon::parse($input->open_to);

    //         $totalDuration = $finishTime->diffInSeconds($startTime);
    //         $x = (int)gmdate('H', $totalDuration);
    //         $x = $x."H" ;


    //         $user = Shop_detail::where('shop_id', $id)->first();

    //         if( $input->hasFile('image') ){
    //             $user->update([ "image"  => $input->image ]);
    //             $user->save();
    //         }
    //         $user->update($input->except(['email',
    //             'open_hour','open_from','open_to','image']));
    //         $user->update([
    //             'open_hours' => $input->open_hours == "" ? $x : $input->open_hours,
    //             'open_from' => $startTime,
    //             'open_to'   => $finishTime,
    //                 ]);

    //         $user->save();


    //         $user=Shop_detail::join("users","users.id","shop_details.shop_id")
    //             ->where("shop_id",$id)
    //             ->select("shop_id as id","users.jwt","shop_details.name", "users.email", "users.phone",
    //                 "business_id", "tax_num","shop_details.lat","shop_details.lng","users.city_id",
    //                 "description_".$lang." as description","users.active","users.is_shop",
    //                 "website","open_hours","open_from","open_to","users.image","users.token","shop_details.category_id")
    //             ->first();
    //     } /*End update shop additional data*/
    //     ////////
    //     ////////
    //     if($input->is_shop == 0){ /*update user data*/
    //         $user = User::where('id', $id)->first();
    //         $user->update($input->except(['email','phone']));
    //         $user->business_id = "";
    //         $user->tax_num = "";
    //         $user->description = "";
    //         $user->website = "";
    //         $user->open_hours = "";
    //         $user->open_from = "";
    //         $user->open_to = "";

    //     } /*End update user data*/
    //     //
    //     ////////
    //     ////////

    //     //return data
    //     return $user;
    // }
}
