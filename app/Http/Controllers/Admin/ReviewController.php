<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $reviews = Review::join("users","users.id","reviews.user_id")
            ->join("shop_details","shop_details.shop_id","reviews.shop_id")
            ->select("reviews.id","rate","comment","users.name as user_name","shop_details.name as shop_name")
            ->orderBy('id','desc')
            ->get();
        return view('cp.reviews.index',[
            'reviews'=>$reviews,
        ]);

    }

    public function delete_review(Request $request,$id){
        Review::where("id",$id)->forcedelete();
        session()->flash('insert_message','تمت العملية بنجاح');
        return back()->with('success','Review deleted successfully');
    }


}
