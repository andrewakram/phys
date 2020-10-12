<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $nots = DB::table('notifications')
            ->join("users","users.id","notifications.user_id")
            ->orderBy('id','desc')
            ->select("notifications.id","notifications.title","notifications.body",
                "users.name","notifications.created_at")
            ->get();
        return view('cp.notifications.index',[
            'nots'=>$nots,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|',
            'body' => 'required|',
            'send_to' => 'required',
        ]);
        if($request->send_to == "users"){
            $users = User::where("is_shop",0)->get();
        }
        if($request->send_to == "shops"){
            $users = User::where("is_shop",1)->get();
        }
        if($request->send_to == "all"){
            //is_shop 5 is admin
            $users = User::where("is_shop","!=",5)->get();
        }

        foreach ($users as $user){
            $add            = new Notification();
            $add->title     = $request->title;
            $add->body      = $request->body;
            $add->user_id   = $user->id;
            $add->save();
            
            Notification::send("$user->token", $request->title , $request->body , "" );
        }
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','City added successfully');
    }

    public function delete_not(Request $request,$id){
        Notification::where("id",$id)->forcedelete();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Notification deleted successfully');
    }


}
