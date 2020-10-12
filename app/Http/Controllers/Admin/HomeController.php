<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Eloquent\Admin\HomeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class HomeController extends Controller
{
    protected $homeRepository;
    public function __construct(HomeRepository $homeRepository)
    {
        $this->homeRepository = $homeRepository;
    }

    public function dashboard()
    {
        $dashboard = $this->homeRepository->dashboard();
        $users_charts = DB::SELECT("select id, count(*) as count,
            date(created_at) as date from users
        WHERE   date(created_at) >= DATE(NOW()) - INTERVAL 30 DAY GROUP BY date(created_at)");
        $workers_charts = DB::SELECT("select id, count(*) as count,
            date(created_at) as date from workers
        WHERE   date(created_at) >= DATE(NOW()) - INTERVAL 30 DAY GROUP BY date(created_at)");
        $orders_charts = DB::SELECT("select id, count(*) as count,
            date(created_at) as date from orders
        WHERE   date(created_at) >= DATE(NOW()) - INTERVAL 30 DAY GROUP BY date(created_at)");
        return view('admin.dashboard',compact(
            'dashboard',
            'users_charts',
            'workers_charts',
            'orders_charts'
        ));
    }

    public function settings($type)
    {
        if($type == 'about_us') $new_type = 'About Us';
        elseif($type == 'term_condition') $new_type = 'Terms And Condition';
        $setting = $this->homeRepository->settings($type);
        return view('admin.settings.index',compact('setting','new_type','type'));
    }

    public function editSettings($type)
    {
        if($type == 'about_us') $new_type = 'About Us';
        elseif($type == 'term_condition') $new_type = 'Terms And Condition';
        $setting = $this->homeRepository->settings($type);
        return view('admin.settings.single',compact('setting','new_type','type'));
    }

    public function updateSettings($type,Request $request)
    {
        $this->homeRepository->updateSettings($type,$request);
        return redirect('/admin/settings/'.$type)->with('success','Updated Successfully');
    }

    public function complainSuggest()
    {
        $complains = $this->homeRepository->complainSuggest();
        return view('admin.settings.complain',compact('complains'));
    }

    public function deleteComplainSuggest(Request $request)
    {
        $this->homeRepository->deleteComplainSuggest($request->complain_id);
        return back()->with('success','Deleted Successfully');
    }
}
