<?php

namespace App\Http\Controllers\Eloquent\User;

use App\Http\Controllers\Interfaces\User\OrdersRepositoryInterface;
use App\Models\ActiveRequest;
use App\Models\Category;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Notify;
use App\Models\Order;
use App\Models\OrderImage;
use App\Models\OrderStatus;
use App\Models\ThirdCatOrder;
use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerThirdCat;
use DB;

class OrdersRepository implements OrdersRepositoryInterface
{
    public $model;
    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function create($input)
    {
        $array = array(
            'type' => $input->type,
            'cat_id' => $input->cat_id,
            'user_id' => $input->user_id,
            'address' => $input->address,
            'lat' => $input->lat,
            'lng' => $input->lng,
            'description' => $input->description,
            'date' => $input->date,
            'time' => $input->time,
        );

        $order = Order::create($array);

        if($order)
        {
            if($input->image)
            {
                foreach ($input->image as $image)
                {
                    OrderImage::create([
                        'order_id' => $order->id,
                        'type' => 'image',
                        'media' => $image
                    ]);
                }
            }
            if($input->video)
            {
                OrderImage::create([
                    'order_id' => $order->id,
                    'type' => 'video',
                    'media' => $input->video
                ]);
            }

            ActiveRequest::create([
                'order_id' => $order->id
            ]);
        }
        return $order;
    }

    public function third_cat_order($order_id, $cat_id, $hours)
    {
        ThirdCatOrder::create
        ([
            'order_id' => $order_id,
            'cat_id' => $cat_id,
            'hours' => $hours
        ]);

        $order = Order::whereId($order_id)->select('id','user_id','cat_id')->first();
        //
        $cats = Category::where("id",$order->cat_id)->first();
        //
        $filter_order = Order::filterbylatlng($order->user->lat,$order->user->lng,50,'workers',$order->cat_id);
        if(count($filter_order) > 0)
        {
            $ar_message = 'لديك طلب خدمة جديد,الرجاء الإستجابة';
            $en_message = 'You have a new order request,please respond';

            foreach ($filter_order as $filter)
            {
                Notification::create([
                    'user_id' => $order->user_id,
                    'worker_id' => $filter->id,
                    'order_id' => $order->id,
                    'ar_message' => $ar_message,
                    'en_message' => $en_message,
                    'send_to' => 'worker'
                ]);

                $active_request = ActiveRequest::where('order_id',$order->id)->first();

                if($active_request->sent_worker_id == null)
                {
                    $active_request->update([
                        'sent_worker_id' => $filter->id
                    ]);
                }else
                {
                    ActiveRequest::create([
                        'sent_worker_id' => $filter->id,
                        'order_id' => $order->id
                    ]);
                }

                $worker = Worker::whereId($filter->id)->pluck('token');
                Notify::send($worker,$ar_message,$en_message,'third_cat,'."$cats->en_name");
            }
            return true;
        }else{
            return false;
        }
    }

    public function activeRequests($user_id)
    {
        $orders = Order::whereUserId($user_id)->where('order_action',0)->where('worker_id',null)
            ->select('id','created_at')->latest()->get();
        $active_orders = [];
        foreach ($orders as $order)
        {
            $active_requests = ActiveRequest::orderBy("order_id","desc")
            ->where('order_id',$order->id)
                ->where('user_status',"!=",2)
                ->pluck('order_id');
            if(in_array($order->id,$active_requests->toArray()))
            {
                array_push($active_orders,$order);
            }
        }

        foreach ($active_orders as $active_order)
        {
            $active_req = ActiveRequest::where('order_id',$active_order->id)->where('worker_id','!=',null)
                ->where('user_status',"!=",2)
                ->get();

            $active_order['applied_worker'] = count($active_req);
        }
        return $active_orders;
    }

    public function companiesActiveRequests($order_id)
    {
        $active_request = ActiveRequest::whereOrderId($order_id)->where('worker_id','!=',null)
            ->select('order_id','worker_id')->with('worker')->get();
        if($active_request->count() > 0)
            return $active_request;
        else
            return "";
    }

    public function companyDetails($worker_id,$order_id,$lang)
    {
        $worker = Worker::whereId($worker_id)->select('name','image','rate','description')->first();

        $order = Order::whereId($order_id)->first();
        $cat = Category::where('id',$order->cat_id)->select('parent_id')->first();

        $order_status = OrderStatus::where('order_id',$order_id)->where('user_status',0)->first();
        if($cat->parent_id == 3)
        {
            $worker_third_cat = WorkerThirdCat::whereId($order_status->worker_third_cat_id)
                ->select('id','worker_id',$lang.'_name as name','price','description','image')->first();
            $worker['cat_id'] = 3;
            $worker['worker_third_cat'] = $worker_third_cat;
        }

        if($order_status)
        {
            $worker['is_waiting'] = 0;
            $worker['salary'] = $order_status->salary;
        }
        else
            $worker['is_waiting'] = 1;

        return $worker;
    }

    public function message($input)
    {
        $message = Message::create
        ([
            'user_id' => $input->user_id,
            'worker_id' => $input->worker_id,
            'order_id' => $input->order_id,
            'body' => $input->body,
            'send' => $input->send
        ]);

        $ar_message = 'رسالة جديدة.';
        $en_message = 'New Message.';

        if($input->send == 'user')
        {
            $image = User::whereId($input->user_id)->select('image')->first();
            $this->notification($input->user_id,$input->worker_id,$ar_message,$en_message,'worker');
            $token = Worker::whereId($input->worker_id)->pluck('token');
            Notify::send($token,null,$ar_message,$en_message,'message',$input->order_id,$input->worker_id,'',$image->image,$input->user_id,$input->body);
        }
        if($input->send == 'worker'){
            $image = Worker::whereId($input->worker_id)->select('image')->first();
            $this->notification($input->user_id,$input->worker_id,$ar_message,$en_message,'user');
            $token = User::whereId($input->user_id)->pluck('token');
            Notify::send($token,null,$ar_message,$en_message,'message',$input->order_id,$input->worker_id,'',$image->image,$input->user_id,$input->body);
        }


        return $message;
    }

    public function getMessages($user_id, $worker_id,$order_id)
    {
        return Message::whereUserId($user_id)->whereWorkerId($worker_id)->where('order_id',$order_id)->select('user_id','worker_id','body','send')->with(['user','worker'])->get();
    }

    public function updateOrderStatus($input)
    {
        $order_status = OrderStatus::whereOrderId($input->order_id)->whereWorkerId($input->worker_id)->first();
        $order_status->user_status = $input->user_status;
        $order_status->save();

        $token = Worker::whereId($input->worker_id)->pluck('token');

        if($input->user_status == 1)
        {
            $ar_message = 'وافق المستخدم على عرضك.';
            $en_message = 'User accept your offer.';

            $this->notification(null,$input->worker_id,$ar_message,$en_message,'worker');
            Order::whereId($input->order_id)->update(['order_total'=>$order_status->salary,'order_status'=>'accept_order']);
            Worker::whereId($input->worker_id)->update(['busy' => 1]);
            Notify::send($token,null,$ar_message,$en_message,'order',$input->order_id);

            $notifyOrderStatuses = OrderStatus::whereOrderId($input->order_id)->where('user_status',0)->get();
            foreach ($notifyOrderStatuses as $notifyOrderStatus)
            {
                $ar_text = 'قام المستخدم بقول عرض عامل آخر.';
                $en_text = 'User accept another worker offer.';

                $notifyOrderStatus->user_status = 2;
                $notifyOrderStatus->save();

                $this->notification(null,$notifyOrderStatus->worker_id,$ar_text,$en_text,'worker');
                $worker_token = Worker::whereId($notifyOrderStatus->worker_id)->pluck('token');
                Notify::send($worker_token,null,$ar_text,$en_text,'order',$input->order_id);
            }
        }else
        {
            $ar_message = 'رفض المستخدم عرضك.';
            $en_message = 'User decline your offer.';
            $this->notification(null,$input->worker_id,$ar_message,$en_message,'worker');
            Notify::send($token,null,$ar_message,$en_message,'order',$input->order_id);
        }
    }

    public function updateOrderPaymentStatus($input)
    {
        $payment_status = OrderStatus::whereOrderId($input->order_id)->whereWorkerId($input->worker_id)
            ->first();
        $payment_status->payment = $input->payment;
        $payment_status->save();
    }

    public function showWorkerThirdCat($worker_id,$lang)
    {
        return WorkerThirdCat::where('worker_id',$worker_id)
            ->select('id',$lang.'_name as name','price','description','image','worker_id')
            ->with('worker')->get();
    }

    public function acceptThirdCat($input)
    {
        $order_status = OrderStatus::whereOrderId($input->order_id)->whereWorkerId($input->worker_id)->first();
        $order_status->user_status = $input->user_status;
        $order_status->save();

        $token = Worker::whereId($input->worker_id)->pluck('token');

        if($input->user_status == 1)
        {
            $ar_message = 'وافق المستخدم على عرضك.';
            $en_message = 'User accept your offer.';

            if($input->worker_third_cat_id)
            {
                $order_status->worker_third_cat_id = $input->worker_third_cat_id;
                $order_status->save();
            }

            $this->notification(null,$input->worker_id,$ar_message,$en_message,'worker');
            Order::whereId($input->order_id)->update(['order_total'=>$order_status->salary,'order_status'=>'accept_order','worker_id'=>$input->worker_id]);
            Worker::whereId($input->worker_id)->update(['busy' => 1]);
            Notify::send($token,null,$ar_message,$en_message,'order');

            $notifyOrderStatuses = ActiveRequest::whereOrderId($input->order_id)->where('user_status',0)->
                where('worker_id','!=',null)->get();
            foreach ($notifyOrderStatuses as $notifyOrderStatus)
            {
                $ar_text = 'قام المستخدم بقول عرض عامل آخر.';
                $en_text = 'User accept another worker offer.';

                $notifyOrderStatus->user_status = 2;
                $notifyOrderStatus->save();

                $this->notification(null,$notifyOrderStatus->worker_id,$ar_text,$en_text,'worker');

                $worker_token = Worker::whereId($notifyOrderStatus->worker_id)->pluck('token');
                Notify::send($worker_token,null,$ar_text,$en_text,'order',$input->order_id);
            }
        }else
        {
            $ar_message = 'رفض المستخدم عرضك.';
            $en_message = 'User decline your offer.';
            $this->notification(null,$input->worker_id,$ar_message,$en_message,'worker');
            Notify::send($token,null,$ar_message,$en_message,'order',$input->order_id);
        }
    }

    public function allOrders()
    {
        return new Order();
    }

    public function orderById($id)
    {
        return Order::whereId($id)->first();
    }

    public function orderStatus()
    {
        return new OrderStatus();
    }

    public function orderHistory($input)
    {
        $orders = $this->allOrders()
        ->whereUserId($input->user_id)
        ->where('order_action','!=',2)
        ->where('order_action','!=',3)
        ->where('order_status','!=',"")
            ->select('id','worker_id','created_at','order_status as status')->with('worker')->latest()->get();
        return $orders;
    }

    public function orderDetail($input)
    {
        $order = $this->allOrders()->whereId($input->order_id)->whereUserId($input->user_id)
            ->select('id','worker_id', 'type', 'description', 'cat_id','address','order_status','date','time')->with('worker')->first();

        $order['salary'] = isset($this->orderStatus()->whereOrderId($order->id)->select('salary')->first()->salary)?
            $this->orderStatus()->whereOrderId($order->id)->select('salary')->first()->salary: 0;

        $order['salary']= isset(ThirdCatOrder::where("order_id",$order->id)->select('hours')->first()->hours)?
            (ThirdCatOrder::where("order_id",$order->id)->select('hours')->first()->hours) * $order['salary']: $order['salary'];

        $cats=Category::where("id",$order->cat_id)->first();

        if($cats->parent_id == 3 && ($cats->type == 3 || $cats->type == 2 ) ){

            $workerThirdCat=WorkerThirdCat::where("cat_id",$order->cat_id)
                ->select("image as media")
                ->get();

            if(sizeof($workerThirdCat)>0){

                $order['order_image'] = $workerThirdCat;


            }
            $order['order_image'] = [];
            //bedrab 3nd 3a6ef
            //$order['order_image'] = [$cats->image];
            return $order;
        }
        $order['order_image'] = $order->orderImage;

        return $order;
    }

    public function notification($user_id, $worker_id, $ar_message, $en_message,$send_to)
    {
        Notification::create
        ([
            'user_id' => $user_id,
            'worker_id' => $worker_id,
            'ar_message' => $ar_message,
            'en_message' => $en_message,
            'send_to' => $send_to,
        ]);
    }

    public function cancelOrder($input)
    {
        $order = Order::whereId($input->order_id)
            ->whereUserId($input->user_id)
            ->select('id','user_id','worker_id','order_action','order_status','order_total')->first();

        if($order->order_status == 'on_way' || $order->order_status == 'finish_order') {
            return false;
        }else{
            $order2 = Order::whereId($input->order_id)
                ->whereUserId($input->user_id)
                ->select('id','user_id','worker_id','order_action','order_status','order_total')
                ->get();
            foreach ($order2 as $o){
                $active_request = ActiveRequest::where('order_id',$input->order_id)
                    ->where('worker_id',$o->worker_id)->first();
                $active_request->update([
                    'user_status' => 2
                ]);
            }
            $order->order_action = 2;
            $order->order_status = 'user_cancelling';
            $order->cancel_reason = $input->cancel_reason;
            $order->order_total = 0;
            $order->save();


            Worker::where("id",$order->worker_id)->update(["busy" => 0]);

            if($order->worker_id != NULL){
                $ar_message = 'قام المستخدم بالغاء الطلب.';
                $en_message = 'User cancel the order';

                $token = Worker::whereId($order->worker_id)->pluck('token');
                Notification::create
                ([
                    'user_id' => $order->user_id,
                    'worker_id' => $order->worker_id,
                    'order_id' => $order->id,
                    'ar_message' => $ar_message,
                    'en_message' => $en_message,
                    'send_to' => 'worker',
                ]);
                Notify::send($token,'',$ar_message,$en_message,'order',$order->id);
            }


            return true;
        }
    }

    public function allCompanies()
    {
        return Worker::select('id','name','image','description','rate')
            ->where('role','company')
            ->where('active',1)
            ->where('accept',1)
            ->get();
    }
}
