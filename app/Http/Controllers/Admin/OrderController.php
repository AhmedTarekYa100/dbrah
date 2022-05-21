<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    public function newOrders(request $request){
        if($request->ajax()) {
            $orders = Order::where('status','new')->latest()->get();
            return Datatables::of($orders)
                ->addColumn('action', function ($orders) {
                    return '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $orders->id . '" data-title="' . $orders->user->name . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->addColumn('details', function ($orders) {
                    $url = "#";
                    return "<a class='btn btn-default' href = '".$url."'>تفاصيل </a>";
                })
                ->editColumn('user_id', function ($orders) {
                    $url  = route('clientProfile',$orders->user->id);
                    $name = $orders->user->name;
                    return "<a class='text-dark fw-bold'  href = '".$url."'>$name</a>";
                })
                ->addColumn('phone', function ($orders) {
                    $phone = $orders->user->phone_code.$orders->user->phone;
                    return '<a href = "tel:'.$phone.'"> '.$phone.'</a>';
                })
                ->editColumn('created_at', function ($orders) {
                    return $orders->created_at->diffForHumans();
                })
                ->escapeColumns([])
                ->make(true);
        }else{
            return view('Admin/orders/new-orders');
        }
    }


    public function currentOrders(request $request){
        if($request->ajax()) {
            $orders = Order::whereIn('status',['accepted','offered','preparing','on_way'])->latest()->get();
            return Datatables::of($orders)
                ->addColumn('details', function ($orders) {
                    $url = "#";
                    return "<a class='btn btn-default' href = '".$url."'>تفاصيل </a>";
                })
                ->editColumn('user_id', function ($orders) {
                    $url  = route('clientProfile',$orders->user->id);
                    $name = $orders->user->name;
                    return "<a class='text-dark fw-bold'  href = '".$url."'>$name</a>";
                })
                ->addColumn('phone', function ($orders) {
                    $phone = $orders->user->phone_code.$orders->user->phone;
                    return '<a href = "tel:'.$phone.'"> '.$phone.'</a>';
                })
                ->editColumn('created_at', function ($orders) {
                    return $orders->created_at->diffForHumans();
                })
                ->editColumn('status', function ($orders) {
                    if($orders->status == 'accepted')
                        return '<span class="badge badge-default">انتظار تقديم العروض</span>';
                    elseif ($orders->status == 'offered')
                        return '<span class="badge badge-warning">انتظار قبول العروض</span>';
                    elseif ($orders->status == 'preparing')
                        return '<span class="badge badge-success">يتم التحضير</span>';
                else
                    return '<span class="badge badge-primary">يتم توصيله</span>';
                })
                ->escapeColumns([])
                ->make(true);
        }else{
            return view('Admin/orders/current-orders');
        }
    }

    public function endedOrders(request $request){
        if($request->ajax()) {
            $orders = Order::whereIn('status',['rejected','delivered'])->latest()->get();
            return Datatables::of($orders)
                ->addColumn('details', function ($orders) {
                    $url = "#";
                    return "<a class='btn btn-default' href = '".$url."'>تفاصيل </a>";
                })
                ->editColumn('user_id', function ($orders) {
                    $url  = route('clientProfile',$orders->user->id);
                    $name = $orders->user->name;
                    return "<a class='text-dark fw-bold'  href = '".$url."'>$name</a>";
                })
                ->addColumn('phone', function ($orders) {
                    $phone = $orders->user->phone_code.$orders->user->phone;
                    return '<a href = "tel:'.$phone.'"> '.$phone.'</a>';
                })
                ->editColumn('created_at', function ($orders) {
                    return $orders->created_at->diffForHumans();
                })
                ->editColumn('status', function ($orders) {
                if ($orders->status == 'rejected')
                        return '<span class="badge badge-danger">تم الالغاء</span>';
                else
                    return '<span class="badge badge-primary">تم التوصيل</span>';
                })
                ->escapeColumns([])
                ->make(true);
        }else{
            return view('Admin/orders/ended-orders');
        }
    }
}
