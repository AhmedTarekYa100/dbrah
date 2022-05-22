<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommercialRecords;
use App\Models\Provider;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProviderController extends Controller
{
    public function index(request $request)
    {
        if($request->ajax()) {
            $providers = Provider::latest()->get();
            return Datatables::of($providers)
                ->addColumn('action', function ($providers) {
//                    $url  = route('clientProfile',$clients->id);
                    return '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $providers->id . '" data-title="' . $providers->name . '"><i class="fas fa-trash"></i></button>
                       ';
                })

                ->addColumn('commercial_photos', function ($providers) {
                    $url = route('showCommercialImages',$providers->id);
                    return "<a class='btn btn-primary' href = '".$url."'>بيانات <i class='fa fa-images'></i> </a>";
                })


                ->addColumn('phone', function ($providers) {
                    $phone = $providers->phone_code.$providers->phone;
                    return '<a href = "tel:'.$phone.'"> '.$phone.'</a>';
                })

                ->editColumn('image', function ($representatives) {
                    return '
                    <img onclick="window.open(this.src)" src="'.$representatives->image.'" alt="profile-user" class="brround  avatar-sm w-32 ml-2"> '.$representatives->name
                        ;
                })

                ->editColumn('town_id', function ($representatives) {
                    return $representatives->town->title_ar.','.$representatives->nationality->title_ar;
                })

                ->escapeColumns([])
                ->make(true);
        }else{
            return view('Admin/provider/index');
        }
    }

    public function delete(request $request)
    {
        $provider = Provider::findOrFail($request->id);
        if (file_exists($provider->getAttributes()['image']))
            unlink($provider->getAttributes()['image']);

        // should be at migration !!
        $provider->offers()->delete();
        $provider->reviews()->delete();
        $provider->commercial_records()->delete();
        $provider->hiddenProducts()->delete();
        $provider->notifications()->delete();

        $provider->delete();
        return response(['message'=>'تمت عملية الحذف بنجاح','status'=>200],200);
    }

    public function showCommercialImages($id){
        $images = CommercialRecords::where('provider_id',$id)->latest()->get();
        $provider  = Provider::find($id);
        return view('Admin/provider/images',compact('images','provider'));
    }

    public function deleteCommercialImage(request $request){
        $image = CommercialRecords::find($request->id);
        if (file_exists($image->getAttributes()['image'])) {
            unlink($image->getAttributes()['image']);
        }
        $image->delete();
        return response([
            'id'      =>$request->id,
            'message' =>'تم الحذف بنجاح',
            'status'  =>200
        ],200);
    }
}
