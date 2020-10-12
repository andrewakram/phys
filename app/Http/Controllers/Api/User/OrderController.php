<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Interfaces\User\AuthRepositoryInterface;
use App\Http\Controllers\Interfaces\User\OrdersRepositoryInterface;
use App\Models\Notify;
use App\Models\Order;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $orderRepository;
    protected $userAuthRepository;
    protected $workerAuthRepository;

    public function __construct(OrdersRepositoryInterface $ordersRepository, AuthRepositoryInterface $authRepository,\App\Http\Controllers\Interfaces\Shop\AuthRepositoryInterface $workerAuthRepository)
    {
        $this->orderRepository = $ordersRepository;
        $this->userAuthRepository = $authRepository;
        $this->workerAuthRepository = $workerAuthRepository;
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'type' => 'required|in:urgent,scheduled',
            'cat_id' => 'required|exists:categories,id,type,2',
            'third_cat_id' => 'sometimes|exists:categories,id,type,3',
            'user_id' => 'required|exists:users,id',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'description' => 'required|max:191',
            'date' => 'sometimes',
            'time' => 'sometimes',
            'hours' => 'sometimes|numeric',
            'image' => 'sometimes',
            'video' => 'sometimes',
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()]);
        }

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $order = $this->orderRepository->create($request);
            if($request->third_cat_id)
            {
                $order_third_cat = $this->orderRepository->third_cat_order($order->id,$request->third_cat_id,$request->hours);
                if($order_third_cat == false)
                    return response()->json(msg($request, failed(), 'no_available_worker'));
            }

            return response()->json(msg($request, success(),'success'));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function activeRequests(Request $request)
    {
       $validator = Validator::make($request->all(),[
           'user_id' => 'required|exists:users,id',
       ]);

        if($validator->fails())
        {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()]);
        }

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $orders = $this->orderRepository->activeRequests($request->user_id);

            return response()->json(msgdata($request, success(),'success',$orders));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));

    }

    public function companiesActiveRequests(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'order_id' => 'required|exists:orders,id',
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()]);
        }

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $orders = $this->orderRepository->companiesActiveRequests($request->order_id);

            return response()->json(msgdata($request, success(),'success',$orders));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));

    }

    public function companyDetails(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'worker_id' => 'required|exists:workers,id',
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => 'error', 'msg' => $validator->messages()]);
        }

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $worker = $this->orderRepository->companyDetails($request->worker_id,$request->order_id,$request->header('lang'));

            return response()->json(msgdata($request, success(),'success',$worker));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));

    }

    public function message(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required|exists:users,id',
            'worker_id' => 'required|exists:workers,id',
            'order_id' => 'required|exists:orders,id',
            'body' => 'required',
            'send' => 'required|in:user,worker'
        ]);

        if($validator->fails()) return response()->json(['status' => 'error', 'msg' => $validator->messages()]);

        if($this->userAuthRepository->checkJWT($request->header('jwt')) || $this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $getMessage = $this->orderRepository->message($request);

            return response()->json(msgdata($request,success(),'send',$getMessage));
        }else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function getMessage(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required|exists:users,id',
            'worker_id' => 'required|exists:workers,id',
            'order_id' => 'required|exists:orders,id'
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed', 'msg' => $validator->messages()]);

        if($this->userAuthRepository->checkJWT($request->header('jwt')) || $this->workerAuthRepository->checkJWT($request->header('jwt')))
        {
            $data = $this->orderRepository->getMessages($request->user_id, $request->worker_id,$request->order_id);
            return response()->json(msgdata($request,success(),'success',$data));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function orderStatus(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'order_id' => 'required|exists:orders,id',
            'worker_id' => 'required|exists:workers,id',
            'user_status' => 'required|in:1,2',
        ]);

        if($validator->fails()) return response()->json(['status' => 'error', 'msg' => $validator->messages()]);

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $this->orderRepository->updateOrderStatus($request);

            return response()->json(msg($request, success(), 'success'));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function paymentStatus(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'order_id' => 'required|exists:orders,id',
            'worker_id' => 'required|exists:workers,id',
            'payment' => 'required|in:cash,online',
        ]);

        if($validator->fails()) return response()->json(['status' => 'error', 'msg' => $validator->messages()]);

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $this->orderRepository->updateOrderPaymentStatus($request);
            return response()->json(msg($request, success(), 'success'));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function acceptThirdCat(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'order_id' => 'required|exists:orders,id',
            'worker_id' => 'required|exists:workers,id',
            'user_status' => 'required|in:1,2',
            'worker_third_cat_id' => 'sometimes|exists:worker_third_cats,id'
        ]);

        if($validator->fails()) return response()->json(['status' => 'error', 'msg' => $validator->messages()]);

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $worker = Worker::where("id",$request->worker_id)
                ->where("token","!=","")
                ->first();
            if($worker){
                $this->orderRepository->acceptThirdCat($request);

                return response()->json(msg($request, success(), 'success'));
            }else{
                return response()->json(msg($request, failed(), 'worker_not_available'));
            }
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function orderHistory(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required|exists:users,id',
        ]);

        if($validator->fails()) return response()->json(['status' => 'error', 'msg' => $validator->messages()]);

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $order_history = $this->orderRepository->orderHistory($request);
            return response()->json(msgdata($request, success(), 'success',$order_history));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function orderDetail(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'required|exists:users,id'
        ]);

        if($validator->fails()) return response()->json(['status' => 'error', 'msg' => $validator->messages()]);

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $order_detail = $this->orderRepository->orderDetail($request);
            return response()->json(msgdata($request, success(), 'success',$order_detail));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function rate(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'order_id' => 'required|exists:orders,id',
            'rate' => 'required|numeric'
        ]);

        if($validator->fails()) return response()->json(['status' => 'error', 'msg' => $validator->messages()]);

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $order_rate = $this->orderRepository->orderById($request->order_id);
            if($order_rate->rate == 0)
            {
                $order_rate->rate = $request->rate;
                $order_rate->save();

                $worker_orders = Order::whereWorkerId($order_rate->worker_id)->pluck('rate')->toArray();
                $rate = array_sum($worker_orders) / count($worker_orders);
                Worker::where('id',$order_rate->worker_id)->update([
                    'rate' => $rate
                ]);

                return response()->json(msg($request, success(), 'rate'));
            }
            else return response()->json(msg($request, failed(), 'already_rate'));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function cancelOrder(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'order_id' => 'required|exists:orders,id,user_id,'.$request->user_id,
            'user_id' => 'required|exists:users,id',
            'cancel_reason' => 'required'
        ]);

        if($validator->fails()) return response()->json(['status' => 'error', 'msg' => $validator->messages()]);

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $cancel_order = $this->orderRepository->cancelOrder($request);
            if($cancel_order == false)
                return response()->json(msg($request,failed(),'canot_cancel'));
            else
                return response()->json(msg($request,success(),'success'));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function showWorkerThirdCat(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'worker_id' => 'required|exists:workers,id'
        ]);

        if($validator->fails()) return response()->json(['status'=>'failed','msg'=>$validator->messages()]);

        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $workers = $this->orderRepository->showWorkerThirdCat($request->worker_id,$request->header('lang'));
            return response()->json(msgdata($request,success(),'success',$workers));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }

    public function allCompanies(Request $request)
    {
        if($this->userAuthRepository->checkJWT($request->header('jwt')))
        {
            $workers = $this->orderRepository->allCompanies();
            return response()->json(msgdata($request,success(),'success',$workers));
        }
        else return response()->json(msg($request, failed(), 'invalid_data'));
    }
}
