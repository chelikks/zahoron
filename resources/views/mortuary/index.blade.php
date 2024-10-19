@include('header.header')

<section class="order_page bac_gray">
    <div class="container">
        <div class="content_order_page">
            <div class="index_title">Морги в г. {{$city->title}}</div>    
        </div>
        <img class='rose_order_page'src="{{asset('storage/uploads/rose-with-stem 1 (1).svg')}}" alt="">
        
    </div>
</section>


<div id="map" style="width: 100%; height: 600px"></div>

<section class="cemetery">
    <div class="container">
        <div class="block_places">
            <div class="ul_places">
                @if (isset($mortuaries) && $mortuaries->count()>0)
                    @foreach ($mortuaries as $mortuary)
                        <div  class="li_place">
                            <a  href="{{ $mortuary->route() }}"  class="img_place"> <img src="{{asset('storage/uploads_mortuary/'.$mortuary->img)}}" alt=""> </a>
                            <div class="content_place_mini">
                                <a href="{{ route('mortuary.single',$mortuary->id) }}" class="title_blue">{{$mortuary->title}}</a>
                                <div class="text_black">г.{{$city->title}}</div>
                            </div>
                            <div class="btn_border_gray">Открыто</div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="sidebar_place"></div>
        </div>


        <div class="block_info_place">
            <div class="title_middle">Информация о моргах в г. {{$city->title}}</div>
            <div class="text_black">
                {!!str_replace('city',$city->title,$city->content_mortuary)!!}
            </div>
        </div>
    </div>
</section>



@include('components.monuments-grave')

@include('components.rating-funeral-agencies-prices')

@include('components.rating-uneral-bureaus-raves-prices')

@include('components.rating-uneral-bureaus-raves-prices')

@include('mortuary.components.cities-places') 

<script>
    ymaps.ready(init);

function init() {
    var myMap = new ymaps.Map("map", {
            center: ['{{$city->width}}', '{{$city->longitude}}'],
            zoom: 12
        }, {
            searchControlProvider: 'yandex#search'
        });
@if (isset($mortuaries) && $mortuaries->count()>0)
    @foreach($mortuaries as $mortuary)
      myMap.geoObjects
        .add(new ymaps.Placemark(['{{$mortuary->width}}', '{{$mortuary->longitude}}'], {
            balloonContent: '{{$mortuary->title}}',
            iconCaption: '{{$mortuary->title}}'
        },));
    @endforeach
@endif
}
</script>
@include('footer.footer') 