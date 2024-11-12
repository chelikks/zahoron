<?php

namespace App\Services\FuneralService;



use App\Models\City;
use App\Models\Mortuary;
use App\Models\Cemetery;
use App\Models\FuneralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class FuneralServiceService {


    public static function addFuneralService($data){
        if(Auth::check()){
            $user=Auth::user();
        }
        if($data['funeral_service']==1){
            $funeral_service=FuneralService::create([
                'service'=>$data['funeral_service'],
                'city_id'=>$data['city_funeral_service'],
                'city_id_to'=>$data['city_funeral_service_to'],
                'service'=>$data['funeral_service'],
                'status_death'=>$data['status_death_people_funeral_service'],
                'civilian_status_death'=>$data['civilian_status_people_funeral_service'],
                'user_id'=>$user->id,
            ]); 
            
        }
        if($data['funeral_service']==2){
            $funeral_service=FuneralService::create([
                'service'=>$data['funeral_service'],
                'city_id'=>$data['city_funeral_service'],
                'service'=>$data['funeral_service'],
                'status_death'=>$data['status_death_people_funeral_service'],
                'civilian_status_death'=>$data['civilian_status_people_funeral_service'],
                'user_id'=>$user->id,
            ]); 
            
        }
        if($data['funeral_service']==3){
            $funeral_service=FuneralService::create([
                'service'=>$data['funeral_service'],
                'city_id'=>$data['city_funeral_service'],
                'cemetery_id'=>$data['cemetery_funeral_service'],
                'service'=>$data['funeral_service'],
                'status_death'=>$data['status_death_people_funeral_service'],
                'civilian_status_death'=>$data['civilian_status_people_funeral_service'],
                'user_id'=>$user->id,
            ]);
        }
        if(!isset($data['none_mortuary'])){
            $funeral_service->update([
                'mortuary_id'=>$data['mortuary_funeral_service'],
            ]);
        }
        if(isset($data['funeral_service_church'])){
            $funeral_service->update([
                'funeral_service_church'=>1,
            ]);
        }
        if(isset($data['farewell_hall'])){
            $funeral_service->update([
                'farewell_hall'=>1,
            ]);
        }
        if(isset($data['call_time'])){
            if($data['call_time']!=null){
                $funeral_service->update(['call_time'=>$data['call_time']]);
            }
        }
        if(isset($data['call_tomorrow'])){
            $d = strtotime("+1 day");
            $funeral_service->update(['call_time'=>date("d.m.Y", $d)]);
        }
        return redirect()->back()->with("message_words_memory", 'Заявка отправлена');
           
    }

    public static function ajaxMortuary($city){
        $mortuaries=Mortuary::orderBy('title','asc')->where('city_id',$city)->get();
        return view('components.components_form.mortuaries',compact('mortuaries'));
    }

    public static function ajaxCemetery($city){
        $cemeteries_beatification=Cemetery::orderBy('title','asc')->where('city_id',$city)->get();
        return view('components.components_form.cemetery',compact('cemeteries_beatification'));
    }
}