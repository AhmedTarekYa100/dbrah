<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProduct;
use App\Models\Category;
use App\Models\CategorySubCategories;
use App\Models\Product;
use App\Models\ProductImages;
use App\Traits\WebpTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Yoeunes\Toastr\Toastr;

class ProductController extends Controller
{
    use WebpTrait;
    public function index(request $request)
    {
        if($request->ajax()) {
            $products = Product::latest()->get();
            return Datatables::of($products)
                ->addColumn('action', function ($products) {
                    return '
                            <button type="button" data-id="' . $products->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $products->id . '" data-title="' . $products->title_ar . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->editColumn('main_image', function ($products) {
                    return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar-md rounded-circle" src="'.$products->main_image.'">
                    ';
                })
                ->editColumn('details_at', function ($products) {
                    return Str::limit($products->details_at,50);
                })
                ->editColumn('category_id', function ($products) {
                    return ($products->mainCategory->title_ar) ?? '';
                })
                ->editColumn('sub_category_id', function ($products) {
                    return ($products->SubCategory->title_ar) ?? '';
                })
                ->addColumn('images', function ($products) {
                    $url = route('showProductImages',$products->id);
                    return "<a class='btn btn-primary' href = '".$url."'>الصور <i class='fa fa-images'></i> </a>";
                })
                ->escapeColumns([])
                ->make(true);
        }else{
            return view('Admin/product/index');
        }
    }

    public function showProductImages($product_id){
        $images = ProductImages::where('product_id',$product_id)->latest()->get();
        $product= Product::findOrFail($product_id);
        return view('Admin/product/images',compact('images','product'));
    }

    public function deleteProductImage(request $request){
        $image = ProductImages::find($request->id);
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


    public function addProductPhoto(request $request){
        $validator = Validator::make($request->all(),[
           'image' => 'required'
        ]);
        foreach ($request->image as $img){
            ProductImages::create([
               'product_id' => $request->product_id,
                'image'     => $this->saveImage($img,'assets/uploads/products')
            ]);
        }
        toastSuccess('تم الاضافة بنجاح');
        return back();
    }



    public function create()
    {
        $mainCategories = Category::mainCategory()->whereHas('subCategory')->with('subCategory.subCategory')->latest()->get();
        return view('Admin/product/parts/create',compact('mainCategories'));
    }


    public function store(StoreProduct $request)
    {
        $data = $request->all();
        $data['main_image'] = $this->saveImage($data['main_image'],'assets/uploads/products',null,100);
        if(Product::create($data))
            return response()->json(['status'=>200]);
        else
            return response()->json(['status'=>405]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }



    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $mainCategories = Category::mainCategory()->whereHas('subCategory')->with('subCategory.subCategory')->latest()->get();
        $subcategories  = CategorySubCategories::where('category_id',$product->category_id)->pluck('sub_category_id');
        $categories     = Category::whereIn('id',$subcategories)->get();
        return view('Admin/product/parts/edit',compact('product','mainCategories','subcategories','categories'));
    }



    public function update(StoreProduct $request, $id)
    {
        $data    = $request->except('_token','id');
        $product = Product::find($id);

        if($request->has('main_image')){
            // delete old image
            if (file_exists($product->getAttributes()['main_image']))
                unlink($product->getAttributes()['main_image']);

            // save the new image
            $data['main_image'] = $this->saveImage($request->main_image,'assets/uploads/products',null,100);
        }
        if($product->update($data))
            return response()->json(['status'=>200]);
        else
            return response()->json(['status'=>405]);
    }



    public function destroy($id)
    {
        //
    }

    public function delete(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $images  = ProductImages::where('product_id',$request->id)->get();

        if (file_exists($product->getAttributes()['main_image']))
            unlink($product->getAttributes()['main_image']);

        foreach ($images as $image){
            if (file_exists($image->getAttributes()['image'])) {
                unlink($image->getAttributes()['image']);
            }
        }
        $product->delete();
        return response(['message'=>'تم الحذف بنجاح','status'=>200],200);
    }
}
