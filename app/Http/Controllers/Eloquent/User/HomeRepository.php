<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 22/05/2019
 * Time: 01:53 م
 */

namespace App\Http\Controllers\Eloquent\User;


use App\Http\Controllers\Interfaces\User\HomeRepositoryInterface;
use App\Models\Block;
use App\Models\Category;
use App\Models\Country;
use App\Models\Follower;
use App\Models\Gov;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\OfferImage;
use App\Models\Review;
use App\Models\Aboutus;
use App\Models\Term;
use App\Models\ComplainSuggests;
use App\Models\Phone;
use App\Models\Shop_detail;
use App\Models\User;
use App\Models\Membership;
use DB;
use Carbon\Carbon;

class HomeRepository implements HomeRepositoryInterface
{
    public $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function memberships($lang)
    {
        return Membership::orderBy("id", "asc")
            ->select('id','name_'.$lang.' as name','description_'.$lang.' as description',
                'image','price','period','no_of_images','no_of_videos')
            ->get();
    }

    public function subscribeMembership($user_id, $input, $lang)
    {
        $user = Shop_detail::where('shop_id', $user_id)->first();
        $user->membership_id = $input->membership_id;
        $user->save();
        return true;
    }

    public function countries($lang)
    {
        return Country::orderBy("id", "asc")->select('id', 'name_' . $lang . ' as name', 'image')->get();
    }

    public function govsAndCities($input, $lang)
    {
        return Gov::orderBy("id", "asc")->where("country_id", $input->country_id)
            ->select('id', 'name_' . $lang . ' as name')->with(["cities" => function ($query) use ($lang) {
                $query->select('id', 'gov_id', 'name_' . $lang . ' as name');
            }])->get();

    }

    public function category($lang)
    {
        return Category::orderBy("id", "asc")
            ->where('active', 1)
            ->select('id', 'name_' . $lang . ' as name', 'image')->get();
    }

    public function shopsOfCategory($input, $lang)
    {
        /*$fillable = [
       'name', 'description_en','description_ar','lat','lng','website',
       'tax_num','open_hours','open_from','open_to','category_id','shop_id','bisiness_id','image'
        ];*/
        $cat_ids = explode(",", $input->cat_ids);
        $arr = [];
        foreach ($cat_ids as $id) {
            $shops = Shop_detail::join("users", "users.id", "shop_details.shop_id")
                ->orderBy("id", "asc")
                ->where("users.city_id", $input->city_id)
                ->where("shop_details.category_id", $id)
                ->where("accept", 1)
                ->where("suspend", 0)
                ->select('shop_details.shop_id as id', 'shop_details.name', 'shop_details.image',
                    'shop_details.description_' . $lang . ' as description')
                ->get();
            foreach ($shops as $shop) {
                array_push($arr, $shop);
            }

        }
        return $arr;
    }

    public function shopDetails($input, $user, $lang)
    {
        $shop = Shop_detail::where("shop_id", $input->shop_id)
            ->with(["phones" => function ($query) use ($lang) {
                $query->select('id', 'phone', 'shop_id');
            }])->with(["offers" => function ($query) use ($lang) {
                $query->where('active', 1)
                    ->select('id', 'name_' . $lang . " as name", "image", "old_price", "new_price",
                        "description_" . $lang . " as description", "shop_id");
            }])->select("shop_id as id", "name", "email", "image", "business_id", "tax_num", "lat", "lng",
                "description_" . $lang . " as description", "website", "open_hours", "open_from",
                "open_to", "shop_id", "category_id")
            ->first();
        $shop_adress = User::join("cities", "cities.id", "users.city_id")
            ->join("govs", "govs.id", "cities.gov_id")
            ->join("countries", "countries.id", "govs.country_id")
            ->where("users.id", $shop->id)
            ->select("cities.name_" . $lang . " as city_name", "govs.name_" . $lang . " as gov_name",
                "countries.name_" . $lang . " as country_name")
            ->first();
        $is_following = Follower::where("shop_id", $input->shop_id)
            ->where("follower_id", $user)->first();
        /*$shop = Shop_detail::where("shop_id",$input->shop_id)
        ->with('phones')->with('offers')->first();*/
        $rate = Review::where("shop_id", $input->shop_id)->pluck('rate');
        $average = collect($rate)->avg();
        $shop->rate = isset($average) ? $average : 0;
        $shop->counts = Review::where("shop_id", $input->shop_id)->count();
        $shop->address = "$shop_adress->city_name" . " , " . "$shop_adress->gov_name" . " , " . "$shop_adress->country_name";
        $shop->is_followed = !empty($is_following) ? true : false;
        return $shop;
    }

    public function offerDetails($input, $lang)
    {
        $offer = Offer::where("id", $input->offer_id)
            ->select("id", "name_" . $lang . " as name", "image", "old_price",
                "new_price", "description_" . $lang . " as description")
            ->first();
        //dd(pathinfo($offer->image, PATHINFO_EXTENSION));
// .WEBM
// .MPG, .MP2, .MPEG, .MPE, .MPV
// .OGG
// .MP4, .M4P, .M4V
// .AVI, .WMV
// .MOV, .QT
// .FLV, .SWF
// AVCHD

//dd(pathinfo($offer->image,PATHINFO_EXTENSION));
        $vid_extns = array('webm', 'mpg', 'mp2', 'mpeg', 'mpe', 'mpv', 'ogg', 'mp4', 'm4p', 'm4v', 'avi', 'wmv', 'mov', 'qt', 'flv', 'swf', 'avchd');
        if ($offer) {
            $img = pathinfo($offer->image, PATHINFO_EXTENSION);
            if (in_array($img, $vid_extns))
                $offer->is_image = false;
            else
                $offer->is_image = true;
            return $offer;
        }
        return [];

    }

    public function followAndUnfollow($id, $input, $lang)
    {
        if ($id != $input->shop_id) {
            $blockCheck = Block::where("shop_id", $input->shop_id)
                ->where("user_id", $id)->first();
            if (!($blockCheck)) {
                $followCheck = Follower::where("shop_id", $input->shop_id)
                    ->where("follower_id", $id)->first();
                if ($followCheck) {
                    Follower::where("shop_id", $input->shop_id)
                        ->where("follower_id", $id)->forcedelete();
                    return false;
                } else {
                    $add = new Follower();
                    $add->shop_id = $input->shop_id;
                    $add->follower_id = $id;
                    $add->save();
                    return true;

                    $follower = User::where("id", $id)->first()->name;
                    if ($lang == "en") {
                        $title = "E3LN";
                        $message = $follower . " has followed you";
                    } else {
                        $title = "اعلن";
                        $message = $follower . "قام بمتابعتك";
                    }
                    $user = User::where("id", $input->shop_id)->first();
                    Notification::send("$user->token", $title, $message, 0);
                }
            }
            return "blocked";
        }
    }

    public function blockAndUnblock($id, $input, $lang)
    {
        if ($id != $input->user_id) {
            $blockCheck = Block::where("shop_id", $id)
                ->where("user_id", $input->user_id)->first();
            if ($blockCheck) {
                Block::where("shop_id", $id)
                    ->where("user_id", $input->user_id)->forcedelete();
                return false;
            } else {
                $add = new Block();
                $add->shop_id = $id;
                $add->user_id = $input->user_id;
                $add->save();
                return true;
            }
        }

    }


    public function getReviews($input, $lang)
    {
        $reviews = [];
        /*$getReviews = User::join("reviews","reviews.user_id","users.id")
            ->where("shop_id",$input->shop_id)
            ->select("reviews.id","rate","comment","user_id","shop_id","reviews.created_at","users.name","users.image")
            ->get();*/
        $getReviews = Review::join("users", "users.id", "reviews.user_id")
            ->where("shop_id", $input->shop_id)
            ->select("reviews.id", "rate", "comment", "user_id", "shop_id", "reviews.created_at", "users.name", "users.image")
            ->get();
        foreach ($getReviews as $g) {
            $g->image = asset("users/" . $g->image);
        }

        $reviews['reviews'] = $getReviews;
        $reviews['average'] = Review::where("shop_id", $input->shop_id)->avg('rate');
        $reviews['counts'] = Review::where("shop_id", $input->shop_id)->count();
        return $reviews;
    }

    public function addReview($user_id, $input, $lang)
    {
        $blockCheck = Block::where("shop_id", $input->shop_id)
            ->where("user_id", $user_id)->first();
        if (!($blockCheck)) {
            $review = Review::where("user_id", $user_id)
                ->where("shop_id", $input->shop_id)
                ->first();
            if ($review) {
                Review::where("user_id", $user_id)
                    ->where("shop_id", $input->shop_id)
                    ->update([
                        'rate' => $input->rate,
                        'comment' => $input->comment,
                        'user_id' => $user_id,
                        'shop_id' => $input->shop_id,
                    ]);
            } else {
                if ($user_id != $input->shop_id) {
                    $add = new Review;
                    $add->rate = $input->rate;
                    $add->comment = $input->comment;
                    $add->user_id = $user_id;
                    $add->shop_id = $input->shop_id;
                    $add->save();
                } else return false;
            }

            $reviewer = User::where("id", $user_id)->first()->name;
            if ($lang == "en") {
                $title = "E3LN";
                $message = $reviewer . " " . " has reviewed you";
            } else {
                $title = "اعلن";
                $message = $reviewer . " " . "قام بتقييمك";
            }
            $user = User::where("id", $input->shop_id)->first();
            Notification::send("$user->token", $title, $message, 1, $input->shop_id);

            return true;
        }
        return false;

    }

    public function deleteReview($input, $lang)
    {
        Review::where("id", $input->id)->delete();
    }

    public function followingList($user_id, $is_shop, $lang)
    {
        if ($is_shop == 1) {
            $follow_list = Follower::where("shop_id", $user_id)->get();
            $arr = [];
            foreach ($follow_list as $f) {
                $user = User::where("id", $f->follower_id)
                    ->select("id", "name", "image")->first();
                $blockCheck = Block::where("user_id", $f->follower_id)
                    ->where("shop_id", $user_id)
                    ->first();

                if ($blockCheck) {
                    $user->isblocked = 1;
                } else {
                    $user->isblocked = 0;
                }
                $user->rate = 0;
                array_push($arr, $user);
            }
        }
        if ($is_shop == 0) {
            $follow_list = Follower::where("follower_id", $user_id)->get();
            $arr = [];
            foreach ($follow_list as $f) {
                $shop = Shop_detail::where("shop_id", $f->shop_id)
                    ->select("shop_id as id", "name", "image")->first();
                $averageRate = Review::where("shop_id", $f->shop_id)->avg('rate');
                $shop->rate = $averageRate == null ? 0 : $averageRate;

                $blockCheck = Block::where("user_id", $user_id)
                    ->where("shop_id", $f->shop_id)
                    ->first();

                if ($blockCheck) {
                    $shop->isfollowed = 0;
                } else {
                    $shop->isfollowed = 1;
                }

                $shop->isblocked = "";
                array_push($arr, $shop);
            }
        }
        return $arr;
    }

    public function blockingList($shop_id, $lang)
    {
        $block_list = Block::where("shop_id", $shop_id)->get();
        $arr = [];
        foreach ($block_list as $b) {
            $user = User::where("id", $b->user_id)
                ->select("id", "name", "image")->first();
            array_push($arr, $user);
        }
        return $arr;
    }

    public function addOffer($input, $shop_id, $lang)
    {
        $offer = Offer::create([
            'name_en' => $input->name,
            'name_ar' => $input->name,
            'old_price' => $input->old_price,
            'new_price' => $input->new_price,
            'description_en' => $input->description,
            'description_ar' => $input->description,
            'shop_id' => $shop_id,
            'image' => $input->image,
            'expire_at' => Carbon::now()->addDays(2),
        ]);

//        if(sizeof($input->image) > 0){
//            foreach ($input->image as $image){
//                OfferImage::create([
//                    'image' => $image,
//                    'offer_id' => $offer->id
//                ]);
//            }
//        }

        $offer = Offer::where("shop_id", "$shop_id")
            ->with(["images" => function ($query) {
                $query->select('id', 'image', 'created_at', 'offer_id');
            }])->orderBy('id', 'desc')
            ->select('id', 'name_' . $lang . ' as name', 'image', 'old_price', 'new_price', 'description_' . $lang . ' as description')
            ->first();

        //dd(pathinfo($offer->image, PATHINFO_EXTENSION));
// .WEBM
// .MPG, .MP2, .MPEG, .MPE, .MPV
// .OGG
// .MP4, .M4P, .M4V
// .AVI, .WMV
// .MOV, .QT
// .FLV, .SWF
// AVCHD

//dd(pathinfo($offer->image,PATHINFO_EXTENSION));
        $vid_extns = array('webm', 'mpg', 'mp2', 'mpeg', 'mpe', 'mpv', 'ogg', 'mp4', 'm4p', 'm4v', 'avi', 'wmv', 'mov', 'qt', 'flv', 'swf', 'avchd');

        if ($offer->image) {
            $img = pathinfo($offer->image, PATHINFO_EXTENSION);
            if (in_array($img, $vid_extns))
                $offer->is_image = false;
            else
                $offer->is_image = true;
        }

        if ($offer->images) {
            foreach ($offer->images as $image) {
                $img = pathinfo($image, PATHINFO_EXTENSION);
                if (in_array($img, $vid_extns))
                    $image->is_image = false;
                else
                    $image->is_image = true;
            }
        }
        return $offer;
    }

    public function editOffer($input, $lang)
    {
        $offer = Offer::where('id', $input->id)->first();
        $offer->update([
            "name_ar" => $input->name,
            "name_en" => $input->name,
            "old_price" => $input->old_price,
            "new_price" => $input->new_price,
            "description_en" => $input->description,
            "description_ar" => $input->description,
        ]);
        if ($input->hasFile('image')) {
            $offer->update(["image" => $input->image]);
            $offer->save();
        }
        return Offer::where('id', $input->id)->select('id', 'name_' . $lang . ' as name', 'image', 'old_price', 'new_price', 'description_' . $lang . ' as description')->first();
    }

    public function deleteOffer($input)
    {
        Offer::where("id", $input->id)->delete();
    }

    public function deletePhone($input)
    {
        Phone::where('id', $input->id)->delete();
    }

    public function phoneList($shop_id)
    {
        return Phone::orderBy("id", "desc")
            ->where("shop_id", $shop_id)
            ->get();
    }

    public function addPhone($input, $shop_id)
    {
        $add = new Phone();
        $add->phone = $input->phone;
        $add->shop_id = $shop_id;
        $add->save();

        return Phone::orderBy("id", "desc")
            ->where("shop_id", $shop_id)
            ->where("phone", $input->phone)
            ->first();
    }

    public function aboutUs($lang)
    {
        /*$aboutus = Aboutus::select("phone","email","website","facebook",
            "twitter","instagram","linkedin","body_".$lang." as body")
            ->first();*/
        $aboutus = Aboutus::select("body_" . $lang . " as body")
            ->first();
        return $aboutus;
    }

    public function terms($lang)
    {
        $terms = Term::select("term_" . $lang . " as terms")
            ->first();
        return $terms;
    }

    public function complains_suggestions($input, $lang)
    {
        $add = new ComplainSuggests();
        $add->name = $input->name;
        $add->email = $input->email;
        $add->message = $input->message;
        $add->save();

        return true;
    }

    public function notifications($user_id, $lang)
    {
        $terms = Notification::select("id", "title", "body", "created_at")
            ->where("user_id", $user_id)
            ->get();
        return $terms;
    }

    public function filterMinMaxPrices($lang)
    {
        $data = DB::select("select MIN(new_price) AS max_price, MAX(new_price) AS min_price
        from offers");

        return $data[0];
    }

    public function search($input, $lang)
    {
        global $shops;
        global $offers;
        $offers = [];
        if ($input->sortby == 1) { //search by nearest shops
            if (isset($input->lat) && isset($input->lng)) {
                $shops = Shop_detail::selectRaw("( FLOOR(6371000 * ACOS( COS( RADIANS( $input->lat) ) * COS( RADIANS( lat ) ) * COS( RADIANS( lng ) - RADIANS($input->lng) ) + SIN( RADIANS($input->lat) ) * SIN( RADIANS( lat ) ) )) ) distance,id,lat,lng,shop_id ")
                    ->orderBy("distance", "asc")->get();
            } elseif (isset($input->city_id)) {
                $shops = Shop_detail::join("users", "users.id", "shop_details.shop_id")
                    ->where("users.city_id", $input->city_id)
                    ->get();
            } else {
                $shops = Shop_detail::join("users", "users.id", "shop_details.shop_id")->get();
            }


        }//end search by nearest shops

        //======================
        if ($input->sortby == 2) { //search by rate of shops
            $shops2 = Shop_detail::get();
            foreach ($shops2 as $shop) {
                $shop->rate = Review::where("shop_id", $shop->shop_id)->avg('rate') == null ? 0 : Review::where("shop_id", $shop->shop_id)->avg('rate');
            }
            $shops = $shops2->sortByDesc('rate')->values();
        }//end search by name[a-z] of shops

        //======================
        if ($input->sortby == 3) { //search by name[a-z] of offers
            $shops = Shop_detail::get();
            foreach ($shops as $shop) {
                $offer = Offer::where("shop_id", $shop->shop_id)
                    ->where("new_price", ">=", $input->priceFrom)
                    ->where("new_price", "<=", $input->priceTo)
                    ->orderBy("name_" . $lang, "asc")
                    ->select("id", "name_" . $lang . " as name", "image", "old_price", "new_price",
                        "description_" . $lang . " as description", "description_" . $lang . " as description",'expire_at')
                    ->get();
                if (sizeof($offer) > 0) {
                    foreach ($offer as $o) {
                        if($o->expire_at > Carbon::now())
                            array_push($offers, $o);
                    }
                }
                $shop->offers = $offer;
            }
            return collect($offers)->sortBy('new_price')->values();
        }//end search by name[a-z] of offers

        if ($shops) {
            foreach ($shops as $shop) {
                $offer = Offer::where("shop_id", $shop->shop_id)
                    ->where("new_price", ">=", $input->priceFrom)
                    ->where("new_price", "<=", $input->priceTo)
                    ->orderBy("new_price", "asc")
                    ->select("id", "name_" . $lang . " as name", "image", "old_price", "new_price",
                        "description_" . $lang . " as description", "description_" . $lang . " as description",'expire_at')
                    ->get();
                if (sizeof($offer) > 0) {
                    foreach ($offer as $o) {
                        if($o->expire_at > Carbon::now())
                            array_push($offers, $o);
                    }
                }
                $shop->offers = $offer;
            }
            return collect($offers)->sortBy('new_price')->values();
        }
        return $offers;

    }

    public function searchByName($input, $lang)
    {
        $shops = Shop_detail::join("users", "users.id", "shop_details.shop_id")
            ->orderBy("id", "asc")
            ->where('shop_details.name', 'like', '%' . $input->name . '%')
            ->select('shop_details.shop_id as id', 'shop_details.name', 'shop_details.image',
                'shop_details.description_' . $lang . ' as description')
            ->get();
        return $shops;
    }


}
