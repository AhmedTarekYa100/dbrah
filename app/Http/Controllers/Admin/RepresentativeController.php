<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Representative;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RepresentativeController extends Controller
{
    public function index(request $request)
    {
        if($request->ajax()) {
            $representatives = Representative::latest()->get();
            return Datatables::of($representatives)
                ->addColumn('action', function ($representatives) {
//                    $url  = route('clientProfile',$clients->id);
                    return '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $representatives->id . '" data-title="' . $representatives->name . '"><i class="fas fa-trash"></i></button>
                       ';
                })

                ->addColumn('phone', function ($representatives) {
                    $phone = $representatives->phone_code.$representatives->phone;
                    return '<a href = "tel:'.$phone.'"> '.$phone.'</a>';
                })

                ->editColumn('image', function ($representatives) {
                    if($representatives->status == 1)
                        $status =  '<span class="dot-label bg-success mr-1"></span>';
                    else
                        $status =  '<span class="dot-label bg-danger mr-1"></span>';
                    return '
                    <img onclick="window.open(this.src)" src="'.$representatives->image.'" alt="profile-user" class="brround  avatar-sm w-32 ml-2"> '.$representatives->name.$status
                    ;
                })

                ->editColumn('town_id', function ($representatives) {
                    return $representatives->town->title_ar.','.$representatives->nationality->title_ar;
                })

                ->editColumn('provider_id', function ($representatives) {
                    return ($representatives->provider->name) ?? 'لم يحدد';
                })

                ->editColumn('delivery_range', function ($representatives) {
                    return $representatives->delivery_range.' كيلو متر ';
                })

                ->escapeColumns([])
                ->make(true);
        }else{
            return view('Admin/representative/index');
        }
    }

    public function delete(request $request)
    {
        $representative = Representative::findOrFail($request->id);
        if (file_exists($representative->getAttributes()['image']))
            unlink($representative->getAttributes()['image']);

        $representative->delete();
        return response(['message'=>'تمت عملية الحذف بنجاح','status'=>200],200);
    }
}
