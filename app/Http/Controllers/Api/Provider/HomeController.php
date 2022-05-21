<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductsDontHaveProvider;
use App\Models\ProductSuggestion;
use App\Models\Provider;
use App\Models\ProviderCategories;
use App\Models\Reviews;
use App\Traits\GeneralTrait;
use App\Traits\PhotoTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    use GeneralTrait,PhotoTrait;

    public function statistics(request $request){
        $validator = Validator::make($request->all(), [
            'provider_id'  => 'required|exists:providers,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $data['client_month'] = Order::where('provider_id',$request->provider_id)
            ->whereMonth('created_at', date('m'))->count();

        $data['orders'] = Order::whereMonth('created_at', date('m'))->count();

        $data['client_year'] = Order::where('provider_id',$request->provider_id)
            ->whereYear('created_at', date('Y'))->count();

        $data['miss_orders'] = Order::whereMonth('created_at', date('m'))->doesntHave('orderOffers')->count();

        return $this->returnData('data',$data);
    }

    public function reviews(request $request){
        $validator = Validator::make($request->all(), [
            'provider_id'  => 'required|exists:providers,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $data = Reviews::where('provider_id',$request->provider_id)->with('user')->get();
        return $this->returnData('data',$data,'get data successfully');
    }


    public function suggest_product(request $request){
        $validator = Validator::make($request->all(),[
            'provider_id'  => 'required|exists:providers,id',
            'image'  => 'required|image|mimes:jpeg,jpg,png,gif:',
            'title' => 'required',
            'main_category_id' => ["required",Rule::exists('categories','id')
                ->where('level',1)],
            'specifications' => 'nullable'
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $data = $request->all();
        $file_name = $this->saveImage($request->image,'assets/uploads/suggests');
        $data['image'] = 'assets/uploads/suggests/'.$file_name;
        ProductSuggestion::create($data);
        return $this->returnSuccessMessage('تم تقديم الاقتراح بنجاح');
    }

    public function control_products(request $request){
        $validator = Validator::make($request->all(), [
            'provider_id'     => 'required|exists:providers,id',
            'category_id'     => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:categories,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $providerCategoriesIds = ProviderCategories::where('provider_id',$request->provider_id)->pluck('category_id')->toArray();
        $dontHaveIds = ProductsDontHaveProvider::where('provider_id',$request->provider_id)->pluck('product_id')->toArray();
        $data = Product::whereIn('category_id',$providerCategoriesIds)->get();
        if($request->has('category_id')){
            $data = $data->where('category_id',$request->category_id)->values();
        }
        if($request->has('sub_category_id')){
            $data = $data->where('sub_category_id',$request->sub_category_id)->values();
        }
        foreach ($data as $product){
            if(in_array($product->id,$dontHaveIds)){
                $product->have_or_not = 'not';
            }
            else{
                $product->have_or_not = 'have';
            }
        }
        return $this->returnData('data',$data,'get data successfully');
    }

    public function edit_my_products(request $request){
        $validator = Validator::make($request->all(), [
            'provider_id'     => 'required|exists:providers,id',
            'products_id'   => 'required|array',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $provider = Provider::find($request->provider_id);
//        $provider->hiddenProducts()->delete();
        foreach ($request->products_id as $key => $value){
            $data['provider_id'] = $request->provider_id;
            $data['product_id']  = $value;
            $new = ProductsDontHaveProvider::where([['product_id',$value],['provider_id',$request->provider_id]])->first();
            if($new){
                $new->delete();
            }
            else
                ProductsDontHaveProvider::updateOrCreate($data);
        }
        return $this->returnSuccessMessage('تم التحديث بنجاح');
    }
}
