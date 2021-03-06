<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Eloquent\Admin\HomeRepository;
use App\Http\Controllers\Interfaces\IndexRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class HomeController extends Controller
{
    protected $indexRepository;

    public function __construct(IndexRepositoryInterface $indexRepository)
    {
        $this->indexRepository = $indexRepository;
    }

    public function dashboard()
    {
        $users= $this->indexRepository->index('User')->where('deleted',0)->count();
        $questions= $this->indexRepository->index('Question')->where('deleted',0)->count();
        $groups= $this->indexRepository->index('Group')->where('deleted',0)->count();
        $exams= $this->indexRepository->index('Exam')->where('deleted',0)->count();

        return view('index',compact('users','groups','exams','questions'));
//        $dashboard = $this->homeRepository->dashboard();
//        $users_charts = DB::SELECT("select id, count(*) as count,
//            date(created_at) as date from users
//        WHERE   date(created_at) >= DATE(NOW()) - INTERVAL 30 DAY GROUP BY date(created_at)");
//        $workers_charts = DB::SELECT("select id, count(*) as count,
//            date(created_at) as date from workers
//        WHERE   date(created_at) >= DATE(NOW()) - INTERVAL 30 DAY GROUP BY date(created_at)");
//        $orders_charts = DB::SELECT("select id, count(*) as count,
//            date(created_at) as date from orders
//        WHERE   date(created_at) >= DATE(NOW()) - INTERVAL 30 DAY GROUP BY date(created_at)");
//        return view('admin.dashboard',compact(
//            'dashboard',
//            'users_charts',
//            'workers_charts',
//            'orders_charts'
//        ));
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
