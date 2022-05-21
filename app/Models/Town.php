<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    use HasFactory;


    ## Relations
    public function nationality(){
        return $this->belongsTo(Nationality::class,'nationality_id');
    }
}
