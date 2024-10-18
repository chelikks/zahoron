<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cemetery;
use App\Models\Mortuary;

class City extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function cityOrganizations(){
        return $oganizations=Organization::where('city_id',$this->id)->get();
    }

    public function cemeteries(){
        return Cemetery::where('city_id',$this->id)->get();
    }

    public function mortuaries(){
        return Mortuary::where('city_id',$this->id)->get();
    }

    public function districts(){
        return District::where('city_id',$this->id)->get();
    }

  
}
