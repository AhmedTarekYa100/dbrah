<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded=[];

    protected $appends=['is_list'];

    ##  Mutators and Accessors
    public function getImagesAttribute()
    {
        return get_file($this->attributes['images']);
    }
    public function getMainImageAttribute()
    {
        return get_file($this->attributes['main_image']);
    }

    public function getIsListAttribute()
    {

        if(request()->has('user_id'))
        {
            $count = MyList::where('user_id',request()->user_id)->where('product_id',$this->id)->count();
            if ($count > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        return false;
    }


    ## Relations
    public function mainCategory(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function subCategory(){
        return $this->belongsTo(Category::class,'sub_category_id');
    }

    public function images(){
        return $this->hasMany(ProductImages::class,'product_id');
    }


    ///// mohamed gamal
    public function list()
    {
        return $this->hasMany(MyList::class,'product_id');
    }//end fun
}//end class
