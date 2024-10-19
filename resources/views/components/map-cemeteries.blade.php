<?php 
$city=selectCity();
$cemeteries=allCemetery();
?>

<section class="karta_all">
    <div class="container">
        <div class="title">Морги г. {{$city->title}} на карте</div>
        <div id="map_mortuary" style="width: 100%; height: 600px"></div>
    </div>
</section>


<script>
    ymaps.ready(init);

function init() {
    var myMap = new ymaps.Map("map", {
            center: ['{{$city->width}}','{{$city->longitude}}'],
            zoom: 10
        }, {
            searchControlProvider: 'yandex#search'
        });
@if(count($cemeteries)>0)
    @foreach($cemeteries as $cemetery)
      myMap.geoObjects
        .add(new ymaps.Placemark(['{{$cemetery->width}}', '{{$cemetery->longitude}}'], {
            balloonContent: '{{$cemetery->title}}',
            iconCaption: '{{$cemetery->title}}'
        },));
    @endforeach
@endif
}
</script>