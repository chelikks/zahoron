
<?php 

use Illuminate\Support\Facades\Auth;
use App\Models\City;
use App\Models\District;

$cities_memorial=City::orderBy('title','asc')->get();
$districts=District::orderBy('title','asc')->where('city_id',selectCity()->id)->get();
$user=null;
if(Auth::check()){
    $user=Auth::user();
}

?>

<div class="modal fade" id="memorial_form"  tabindex="-1" aria-labelledby="memorial_form" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body message">
                <div class="flex_title_message">
                    <div class="text_center">
                        <div class="title_middle">Быстрый запрос стоимости</div>
                        <div class="text_block">от 10 поминальных залов</div>
                    </div>
                    <div data-bs-dismiss="modal" class="close_message">
                        <img src="{{ asset('storage/uploads/close (2).svg') }}" alt="">
                    </div>
                </div>
                <form action="{{ route('memorial.send') }}" method="get" class='form_popup'>
                    @csrf

                    <div class="flex_input_form_contacts flex_beautification_form">
                        <div class="block_input" >
                            <label for="">Выберите город</label>
                            <div class="select"><select name="city_memorial" >
                                @if(count($cities_memorial)>0)
                                    @foreach ($cities_memorial as $city_memorial)
                                    <option <?php if(selectCity()->id==$city_memorial->id){echo 'selected';}?> value="{{$city_memorial->id}}">{{$city_memorial->title}}</option>
                                    @endforeach
                                @endif
                            </select></div>
                            @error('city_memorial')
                                <div class='error-text'>{{ $message }}</div>
                            @enderror
                        </div>  
                        <div class="block_input" >
                            <label for="">Выберите район</label>
                            <div class="select"><select name="district_memorial" >
                                {{view('components.components_form.district',compact('districts'))}}
                            </select></div>
                            @error('district_memorial')
                                <div class='error-text'>{{ $message }}</div>
                            @enderror
                        </div>  
                    </div>
                    <div class="flex_input_form_contacts flex_beautification_form">
                        <div class="block_input" >
                            <label for="">Дата брони</label>
                            <input type="date" name="date_memorial" id="">
                            @error('date_memorial')
                                <div class='error-text'>{{ $message }}</div>
                            @enderror
                        </div>  
                        <div class="block_input" >
                            <label for="">Время брони</label>
                           <input type="time" name="time_memorial" id="">
                            @error('time_memorial')
                                <div class='error-text'>{{ $message }}</div>
                            @enderror
                        </div>  
                    </div>
                    <div class="flex_input_form_contacts flex_beautification_form">
                        <div class="block_input" >
                            <label for="">Количество персон</label>
                            <input min=1 type="number" name="count_people" value='1'>
                            @error('count_people')
                                <div class='error-text'>{{ $message }}</div>
                            @enderror
                        </div>  
                        <div class="block_input" >
                            <label for="">Количество часов</label>
                            <input min=1 type="number" name="count_time" value='1'>
                            @error('count_time')
                                <div class='error-text'>{{ $message }}</div>
                            @enderror
                        </div>  
                    </div>
                    <div class="block_info_user_form">
                        <div class="flex_input_form_contacts flex_beautification_form">
                            <div class="block_input">
                                <label for="">Имя</label>
                                <input type="text" name='name_memorial' placeholder="Имя" <?php if($user!=null){echo 'value='.$user->name;}?>>
                                @error('name_memorial')
                                    <div class='error-text'>{{ $message }}</div>
                                @enderror
                            </div> 
                            <div class="block_input">
                                <label for="">Номер телефона</label>
                                <input type="text" name="phone_memorial" id="" placeholder="Номер телефона" <?php if(isset($user)){if($user!=null){echo 'value="'.$user->phone.'"';}}?> >
                                @error('phone_memorial')
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
                            
                        <button type='submit'class="blue_btn">Получить ценовое предложение</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $( "#memorial_form select[name='city_memorial']" ).on( "change", function() {
        let data  = {
            'city_id':$(this).children('option:checked').val(),
        };

        $.ajax({
            type: 'GET',
            url: '{{route('memorial.ajax.district')}}',
            data:  data,
            success: function (result) {
                $( "#memorial_form select[name='district_memorial']" ).html(result)
            },
            error: function () {
                alert('Ошибка');
            }
        });
       
    });
</script>