<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mortuary extends Model
{
    use HasFactory;
    
    protected $guarded =[];

    public function district(){
        $district=District::find($this->district_id);
        return $district;
    }

    public function route(){
        return route('mortuary.single',$this->id);
    }

}
