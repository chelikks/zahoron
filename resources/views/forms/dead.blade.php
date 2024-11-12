
<?php 

use Illuminate\Support\Facades\Auth;
use App\Models\City;
use App\Models\Mortuary;

$cities_dead=City::orderBy('title','asc')->get();
$mortuaries=Mortuary::orderBy('title','asc')->where('city_id',selectCity()->id)->get();
$user=null;
if(Auth::check()){
    $user=Auth::user();
}

?>

<div class="modal fade" id="dead_form"  tabindex="-1" aria-labelledby="dead_form" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body message">
                <div class="flex_title_message">
                    <div class="text_center">
                        <div class="title_middle">Узнать информацию по умерешему</div>
                    </div>
                    <div data-bs-dismiss="modal" class="close_message">
                        <img src="{{ asset('storage/uploads/close (2).svg') }}" alt="">
                    </div>
                </div>
                <form action="{{ route('dead.send') }}" method="get" class='form_popup'>
                    @csrf

                    <div class="flex_input_form_contacts flex_beautification_form">
                        <div class="block_input" >
                            <label for="">Выберите город</label>
                            <div class="select"><select name="city_dead" >
                                @if(count($cities_dead)>0)
                                    @foreach ($cities_dead as $city_dead)
                                        <option <?php if(selectCity()->id==$city_dead->id){echo 'selected';}?> value="{{$city_dead->id}}">{{$city_dead->title}}</option>
                                    @endforeach
                                @endif
                            </select></div>
                            @error('city_dead')
                                <div class='error-text'>{{ $message }}</div>
                            @enderror
                        </div>  
                        <div class="block_input" >
                            <div class="flex_input"><label for="">Выберите морг</label> <label class='flex_input_checkbox checkbox'><input type="checkbox" name='none_mortuary'>Неизвестно</label></div>
                            <div class="select"><select name="mortuary_dead" >
                                {{ view('components.components_form.mortuaries',compact('mortuaries')) }}
                            </select></div>
                            @error('mortuary_dead')
                                <div class='error-text'>{{ $message }}</div>
                            @enderror
                        </div>  
                    </div>
                   
                    <div class="block_input" >
                        <label for="">Ф.И.О. Умершего</label>
                        <input  type="text" name="fio_dead" placeholder="Иванов Иван Иванович">
                        @error('fio_dead')
                            <div class='error-text'>{{ $message }}</div>
                        @enderror
                    </div>  
                    <div class="block_info_user_form">
                        <div class="flex_input_form_contacts flex_beautification_form">
                            <div class="block_input">
                                <label for="">Имя</label>
                                <input type="text" name='name_dead' placeholder="Имя" <?php if($user!=null){echo 'value='.$user->name;}?>>
                                @error('name_dead')
                                    <div class='error-text'>{{ $message }}</div>
                                @enderror
                            </div> 
                            <div class="block_input">
                                <label for="">Номер телефона</label>
                                <input type="text" name="phone_dead" id="" placeholder="Номер телефона" <?php if(isset($user)){if($user!=null){echo 'value="'.$user->phone.'"';}}?> >
                                @error('phone_dead')
                                    <div class='error-text'>{{ $message }}</div>
                                @enderror
                            </div> 
                        </div>
                    </div>
                    <label class="aplication checkbox active_checkbox">
                        <input required type="checkbox" name="aplication"  checked >
                        <p>Я согласен на обработку персональных данных в соответствии с Политикой конфиденциальности</p>
                    </label>
                    <div class="flex_btn block_call_time">
                        <div class="btn_bac_gray open_call_time">
                            Позвонить по времени<img src='{{asset('storage/uploads/Vector 9 (1).svg')}}'>
                        </div>
                        <div class="call_time">
                            <input class="btn_bac_gray" type="time" name="call_time" id="">
                            <label class="aplication checkbox">
                                <input  type="checkbox" name="call_tomorrow" >
                                <p>Позвонить завтра</p>
                            </label>
                        </div>
                            
                        <button type='submit'class="blue_btn">Запросить информацию</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $( "#dead_form select[name='city_dead']" ).on( "change", function() {
        let data  = {
            'city_id':$(this).children('option:checked').val(),
        };
        $.ajax({
            type: 'GET',
            url: '{{route('dead.ajax.mortuary')}}',
            data:  data,
            success: function (result) {
                $( "#dead_form select[name='mortuary_dead']" ).html(result)
            },
            error: function () {
                alert('Ошибка');
            }
        });
    });
</script>