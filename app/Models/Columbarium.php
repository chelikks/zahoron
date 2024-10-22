<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Columbarium extends Model
{
    use HasFactory;

    public function district(){
        $district=District::find($this->district_id);
        return $district;
    }

    public function route(){
        return route('columbarium.single',$this->id);
    }

    public function openOrNot(){
        //$day=strtotime(getTimeByCoordinates($this->width,$this->longitude)['dayOfTheWeek']);
        // $time=strtotime(getTimeByCoordinates($this->width,$this->longitude)['current_time']);
        $time=strtotime('23:00');
        $day='Saturday';
        $get_hours=WorkingHoursColumbarium::where('columbarium_id',$this->id)->where('day',$day)->first();
        if($get_hours!=null){
           if($get_hours->holiday!=1 && $time<strtotime($get_hours->time_end_work) && $time>strtotime($get_hours->time_start_work)){
                return 'Открыто';
           }
        }
        return 'Закрыто';
    }

    public function countReviews(){
        return ReviewColumbarium::where('columbarium_id',$this->id)->where('status',1)->count();
    }

    public function urlImg(){
        if($this->href_img==0){
            return asset('storage/uploads_columbarium/'.$this->img);
        }
        return $this->img;
    }
}
