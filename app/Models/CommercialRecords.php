<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommercialRecords extends Model
{
    use HasFactory;
    protected $guarded = [];

    ##  Mutators and Accessors
    public function getImageAttribute()
    {
        return get_file($this->attributes['image']);
    }

    ##  Relation
    public function provider()
    {
        return $this->belongsTo(Provider::class,'provider_id');
    }
}
