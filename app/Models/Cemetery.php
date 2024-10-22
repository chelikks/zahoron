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
        $organiazations=Organization::where('role','organization')->where(function($item) {
            $item->orWhere('cemetery_ids',"LIKE", "%,".$this->id.",%")->orWhere('cemetery_ids',"LIKE", $this->id.",%")->orWhere('cemetery_ids',"LIKE", "%,".$this->id);
        })->get();
        return $organiazations;
    }

    public function route(){
        return route('cemeteries.single',$this->id);
    }

    public function openOrNot(){
        //$day=strtotime(getTimeByCoordinates($this->width,$this->longitude)['dayOfTheWeek']);
        // $time=strtotime(getTimeByCoordinates($this->width,$this->longitude)['current_time']);
        $time=strtotime('23:00');
        $day='Saturday';
        $get_hours=WorkingHoursCemetery::where('cemetery_id',$this->id)->where('day',$day)->first();
        if($get_hours!=null){
           if($get_hours->holiday!=1 && $time<strtotime($get_hours->time_end_work) && $time>strtotime($get_hours->time_start_work)){
                return 'Открыто';
           }
        }
        return 'Закрыто';
    }

    public function countReviews(){
        return ReviewCemetery::where('cemetery_id',$this->id)->where('status',1)->count();
    }

    public function urlImg(){
        if($this->href_img==0){
            return asset('storage/uploads_cemeteries/'.$this->img);
        }
        return $this->img;
    }
    

}
