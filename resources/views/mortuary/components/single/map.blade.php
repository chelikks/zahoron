
<div class="block_map_organization_single">
    <div class="block_border_gray_map_organization_single">
        <div class="flex_single_organization flex_single_organization_map">
            <div class="title_rewies">{{$mortuary->adres}}</div>
            <div class="block_btn_single_organization">
                <a href='https://yandex.ru/maps/?rtext=~{{$mortuary->width}},{{$mortuary->longitude}}' target="_target" class="icon_btn_single_organization">
                    <img class='blue_icon'src="{{asset('storage/uploads/marshryt.svg')}}" alt="">
                    <img class='white_icon'src="{{asset('storage/uploads/Vector (2).svg')}}" alt="">
                </a>
                <div class="text_black">Маршрут</div>
            </div>
        </div>
        <div class="flex_single_organization">
            <div class="text_black text_flex"><img src="{{asset('storage/uploads/mdi_clock-outline.svg')}}" alt=""> до {{$mortuary->time_end_work}}</div>
            <img src="{{asset('storage/uploads/svg.svg')}}" alt="">
        </div>

    </div>
    <div id="map_organization_single" style="width: 100%; height: 400px"></div>
</div>

<script>
    ymaps.ready(init);

function init() {
    var myMap = new ymaps.Map("map_organization_single", {
            center: [{{  $mortuary->width}}, {{$mortuary-> longitude}}],
            zoom: 10
        }, {
            searchControlProvider: 'yandex#search'
        });

@if(count($mortuary_all)>0)
    @foreach($mortuary_all as $mortuary_one)
      myMap.geoObjects
        .add(new ymaps.Placemark(['{{$mortuary_one->width}}', '{{$mortuary_one->longitude}}'], {
            balloonContent: '{!!$mortuary_one->title!!}',
            iconCaption: '{!!$mortuary_one->title!!}'
        },));
    @endforeach
@endif
}


</script>