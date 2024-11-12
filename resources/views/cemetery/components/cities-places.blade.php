@php 

$cities=selectCity()->edgeCities();

@endphp
<section class="block_cities_places">
    <div class="container">
        <div class="title_middle">Города нашего края и их кладбища</div>
        @if(isset($cities) && $cities!=null && $cities->count()>0)
            <div class="ul_cities_places">
                @foreach($cities as $city_place)
                    <a href='{{$city_place->route()}}' class="li_city_place">
                        <img class='white_icon_city_place' src="{{asset('storage/uploads/fluent_city-16-regular.svg')}}" alt=""> <img class='blue_icon_city_place' src="{{asset('storage/uploads/fluent_city-16-regular (2).svg')}}" alt=""> Кладбища г. {{$city_place->title}}
                        <div class="line_blue_place"></div>
                    </a>
                @endforeach
            </div>

        @endif
    </div>
</section>