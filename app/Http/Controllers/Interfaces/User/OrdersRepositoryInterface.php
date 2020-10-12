<?php

namespace App\Http\Controllers\Interfaces\User;

interface OrdersRepositoryInterface{
    public function create($attributes);
    public function third_cat_order($order_id,$cat_id,$hours);
    public function activeRequests($user_id);
    public function companiesActiveRequests($order_id);
    public function companyDetails($worker_id,$order_id,$lang);
    public function message($attributes);
    public function getMessages($user_id, $worker_id,$order_id);
    public function notification($user_id,$worker_id,$ar_message,$en_message,$send_to);
    public function updateOrderStatus($attributes);
    public function updateOrderPaymentStatus($attributes);
    public function acceptThirdCat($attributes);
    public function allOrders();
    public function orderById($id);
    public function orderStatus();
    public function orderHistory($attributes);
    public function orderDetail($attributes);
    public function showWorkerThirdCat($worker_id,$lang);
    public function cancelOrder($attributes);
    public function allCompanies();
}
