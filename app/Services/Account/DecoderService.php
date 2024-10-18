<?php

namespace App\Services\Account;

use App\Models\Burial;
use App\Models\Task;
use App\Models\Training;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DecoderService {

    public static function index(){
        $user=user();
        return view('account.decoder.index',compact('user'));
    }

    public static function settings(){
        $user=user();
        return view('account.decoder.settings',compact('user'));
    }

    public static function settingsUpdate($data){
        $user=Auth::user();
        $user_email=User::where('email',$data['email'])->where('id','!=',$user->id)->get();
        $user_phone=User::where('phone',$data['phone'])->where('id','!=',$user->id)->get();
        if(count($user_email)<1 && count($user_phone)<1){
            User::find($user->id)->update([
                'name'=>$data['name'],
                'surname'=>$data['surname'],
                'patronymic'=>$data['patronymic'],
                'phone'=>$data['phone'],
                'city'=>$data['city'],
                'adres'=>$data['adres'],
                'email'=>$data['email'],
                'whatsapp'=>$data['whatsapp'],
                'telegram'=>$data['telegram'],
                'language'=>$data['language'],
                'theme'=>$data['theme'],
                'number_cart'=>$data['number_cart'],
                'bank'=>$data['bank'],
            ]);
            if($data['password']!=null){
                if(Hash::check($data['password'], $user->password)==true){
                    if($data['password_new']==$data['password_new_2'] && strlen($data['password_new'])>7){
                        User::find($user->id)->update([
                            'password'=>Hash::make($data['password_new'])
                        ]);
                        
                        return redirect()->back();

                    }
                    return redirect()->back()->with("error", 'Новые пароли не совпадают');
                }
                return redirect()->back()->with("error", 'Неверный пароль');
            }
            if(!isset($data['email_notifications'])){
                User::find($user->id)->update([
                    'email_notifications'=>0
                ]);
            }else{
                User::find($user->id)->update([
                    'email_notifications'=>1
                ]);
            }
            if(!isset($data['sms_notifications'])){
                User::find($user->id)->update([
                    'sms_notifications'=>0
                ]);
            }else{
                User::find($user->id)->update([
                    'sms_notifications'=>1
                ]);
            }
            return redirect()->route('account.decoder.settings');
        }
        return redirect()->back()->with("error", 'Такой телефон или email уже существует');
        
    }

    public static function trainingMaterialVideo(){
        $trainings=Training::orderBy('id','desc')->where('video','!=',null)->get();        
        return view('account.decoder.training-material.video',compact('trainings'));
    }

    public static function trainingMaterialFile(){
        $trainings=Training::orderBy('id','desc')->where('file','!=',null)->get();        
        return view('account.decoder.training-material.file',compact('trainings'));
    }


    public static function paymentsPaid(){
        $user=user();
        $payments=Task::orderBy('id','desc')->where('status',1)->where('user_id',$user->id)->get();        
        return view('account.decoder.payments.paid',compact('payments'));
    }

    public static function paymentsOnVerification(){
        $user=user();
        $payments=Task::orderBy('id','desc')->where('status',0)->where('user_id',$user->id)->get();        
        return view('account.decoder.payments.verification',compact('payments'));
    }

    public static function iconUpdateUser($file){
        $user=user();
        $filename=generateRandomString().".jpeg";
        $file->storeAs("uploads_decoder", $filename, "public");
        $user->update([
            'icon'=>$filename,
        ]);  
        return redirect()->route('account.decoder.settings')->with("message_cart", 'Фото обновлено');

    }


    public static function viewEditBurial(){
        $burial=Burial::orderBy('id','desc')->where('status',0)->first();
        return view('account.decoder.view-edit-burial',compact('burial'));
    }

    public static function addCommentBurial($data){
        $burial=Burial::find($data['burial_id'])->update([
            'comment'=>$data['comment'],
            'status'=>2,
            'decoder_id'=>user()->id,

        ]);
        return redirect()->back()->with("message_cart", 'Комментарий отправлен');
    }


    public static function updateBurial($data){
        $burial=Burial::find($data['burial_id']);
        $burial->update([
            'name'=>$data['name'],
            'surname'=>$data['surname'],
            'patronymic'=>$data['patronymic'],
            'date_death'=>dateBurialInBase($data['date_death']),
            'status'=>1,
            'decoder_id'=>user()->id,
            'date_birth'=>dateBurialInBase($data['date_birth']),
        ]);
        Task::create([
            'title'=>'Расшифровка',
            'burial_id'=>$burial->id,
            'user_id'=>user()->id,
            'price'=>$burial->cemetery()->price_decode,
        ]);
        return redirect()->back()->with("message_cart", 'Захоронение успешно обновлено');
    }

}