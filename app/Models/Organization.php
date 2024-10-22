<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;
    protected $guarded =[];

    
    public function city(){
        return City::find($this->city_id);
    }

    public function openOrNot(){
        //$day=strtotime(getTimeByCoordinates($this->width,$this->longitude)['dayOfTheWeek']);
        // $time=strtotime(getTimeByCoordinates($this->width,$this->longitude)['current_time']);
        $time=strtotime('12:00');
        $day='Sunday';
        $get_hours=WorkingHoursOrganization::where('organization_id',$this->id)->where('day',$day)->first();
        if($get_hours!=null){
           if($get_hours->holiday!=1 && $time<strtotime($get_hours->time_end_work) && $time>strtotime($get_hours->time_start_work)){
                return 'Открыто';
           }
        }
        return 'Закрыто';
    }

    public function countReviews(){
        return ReviewsOrganization::where('organization_id',$this->id)->where('status',1)->count();
    }

    public function route(){
        return route('organization.single',$this->slug);
    }

    public function urlImg(){
        if($this->href_img==0){
            return asset('storage/uploads_organization/'.$this->logo);
        }
        return $this->logo;
    }
}
