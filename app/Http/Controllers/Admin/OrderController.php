<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\OrderRepositoryInterface;
use App\Http\Controllers\Interfaces\Admin\WorkerRepositoryInterface;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\OrdersExport;
use App\Exports\CostsExport;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class OrderController extends Controller
{
    protected $orderRepository;
    protected $workerRepository;
    public function __construct(OrderRepositoryInterface $orderRepository,WorkerRepositoryInterface $workerRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->workerRepository = $workerRepository;
    }

    protected function index()
    {
        $orders = $this->orderRepository->index();
        $cats = Category::where('type',1)->where('parent_id',Null)->get();
        return view('admin.orders.index',compact('orders','cats'));
    }

    protected function indexCost()
    {
        $orders = $this->orderRepository->indexCost();
        $cats = Category::where('type',1)
            ->where('parent_id',Null)->get();
        return view('admin.orders.indexCost',compact('orders','cats'));
    }

    public function view($id)
    {
        $order = $this->orderRepository->view($id);
        $workers = $this->workerRepository->getWorkerCompany($order->cat_id);
        return view('admin.orders.show',compact('order','workers'));
    }

    public function changePayStatus($id)
    {
        $order = Order::where("id",$id)->select("paid_status")->first();
        if($order->paid_status == 0){
            Order::where("id",$id)->update(["paid_status" => 1]);
        }elseif($order->paid_status == 1){
            Order::where("id",$id)->update(["paid_status" => 0]);
        }
        $workers = $this->workerRepository->getWorkerCompany($order->cat_id);
        return back();
    }

    public function acceptOrder(Request $request)
    {
        $this->validate($request,[
            'worker_id' => 'required|exists:workers,id'
        ]);
        $id = (int)$request->order_id;
        $this->orderRepository->acceptOrder($request);
        return redirect(route('orders_view',$id))->with('success','Order accept successfully');
    }

    public function rejectOrder(Request $request)
    {

        $id = (int)$request->order_id;
        $order = $this->orderRepository->rejectOrder($id);
        if($order == true)
            return redirect(route('orders_view',$id))->with('success','Order sent to another worker successfully');
        else
            return redirect(route('orders_view',$id))->with('error','No worker in same area with user or busy');
    }

    public function finishOrder(Request $request)
    {
        $id = (int)$request->order_id;
        $order = $this->orderRepository->finishOrder($id);
        if($order == true)
            return redirect(route('orders_view',$id))->with('success','Order has Finished');
        else
            return redirect(route('orders_view',$id))->with('error','Order can not finish' );
    }

    public function search(Request $request)
    {
        Session::put("from",$request->from);
        Session::put("to",$request->to);
        Session::put("main_cats",$request->main_cats);
        Session::put("sub_cats",$request->sub_cats);
        Session::put("service_type",$request->service_type);
        $orders = $this->orderRepository->search($request);
        $cats = Category::where('type',1)->where('parent_id',Null)->get();
        return view('admin.orders.index',compact('orders','cats'));
    }

    public function search2(Request $request)
    {
        Session::put("from",$request->from);
        Session::put("to",$request->to);
        $orders = $this->orderRepository->search2($request);
        return view('admin.orders.indexCost',compact('orders'));
    }



    public function orders_export(Request $request)
    {
        $search = Input::get('search');
        $arr_search = [
            'search' => $search,
            'select_from' => Session::get("from"),
            'select_to' => Session::get("to"),
            'select_main_cats' => Session::get("main_cats"),
            'select_sub_cats' => Session::get("sub_cats"),
            'select_service_type' => Session::get("service_type"),
        ];
        Session::put("arr_search",$arr_search);



        return Excel::download(new OrdersExport, 'jaz_orders_invoice.xlsx');
        $this->orderRepository->export();
    }

    public function costs_export(Request $request)
    {
        $search2 = Input::get('search2');
        $arr_search2 = [
            'search2' => $search2,
            'select_from' => Session::get("from"),
            'select_to' => Session::get("to"),
        ];
        Session::put("arr_search2",$arr_search2);



        return Excel::download(new CostsExport, 'jaz_costs_invoice.xlsx');

    }
}
