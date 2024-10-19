<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organization;

class Cemetery extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function district(){
        $district=District::find($this->district_id);
        return $district;
    }

    public function cemeteryOrganiaztions(){
        $organiazations=Organization::where(function($item) {
            $item->orWhere('cemetery_ids',"LIKE", "%,".$this->id.",%")->orWhere('cemetery_ids',"LIKE", $this->id.",%")->orWhere('cemetery_ids',"LIKE", "%,".$this->id);
        })->get();
        return $organiazations;
    }

    public function route(){
        return route('cemeteries.single',$this->id);
    }

}
