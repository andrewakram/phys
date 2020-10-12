<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Notification;
use App\Models\Review;
use App\Models\Shop_detail;
use App\Models\Offer;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
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
    public function index()
    {
        $cats=Category::count();
        $shops=Shop_detail::count();
        $users=User::where("is_shop",0)->count();
        $offers=Offer::count();
        $nots=Notification::count();
        $rates=Review::count();

        $users_charts = DB::SELECT("select id, count(*) as count,
            date(created_at) as date from users
            WHERE  `is_shop`=0 AND date(created_at) >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY date(created_at),id");

        $shops_charts = DB::SELECT("select id, count(*) as count,
            date(created_at) as date from users
            WHERE  `is_shop`=1 AND date(created_at) >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY date(created_at),id");

        $offers_charts = DB::SELECT("select id, count(*) as count,
            date(created_at) as date from offers
            WHERE  `active`=1 AND date(created_at) >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY date(created_at),id");

        $rates_charts = DB::SELECT("select id, count(*) as count,
            date(created_at) as date from reviews
            WHERE date(created_at) >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY date(created_at),id");



        return view('cp.home',
            compact('cats','shops','users','offers','nots','rates',
                'users_charts','shops_charts','offers_charts','rates_charts'));
    }
}
