@include('header.header')

<section class="order_page bac_gray">
    <div class="container">
        <div class="content_order_page">
            <div class="index_title">Кладбища</div>    
        </div>
        <img class='rose_order_page'src="{{asset('storage/uploads/rose-with-stem 1 (1).svg')}}" alt="">
        
    </div>
</section>

<section class="cemetery">
    <div class="container">
        <div class="text_block_mini">
           {!!get_acf(2,'content_1')!!}
        </div>
        <div class="block_cemetery">
            <div class="title_our_works">Список кладбищ</div>

            <div class="ul_cemeteries">
                @if (isset($cemeteries) && $cemeteries->count()>0)
                <?php $k=1;?>
                    @foreach ($cemeteries as $cemetery)
                        <a href="{{ route('cemeteries.single',$cemetery->id) }}" class="li_cemetery">
                            <div class="number_cemetery">{{ $k++ }}</div>
                            {{ $cemetery->title }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
        
        <div class="block_cemetery">
            <div class="title_our_works">Закрытые кладбища</div>
            <div class="text_block_mini">
                {!!get_acf(2,'content_2')!!}
            </div>
        </div>
    </div>
</section>

@include('forms.search-form') 

<section class="map_cemeteries">
    <div class="container">
        <div class="title_our_works">Карта кладбищ</div>
        <div id="map" style="width: 100%; height: 600px"></div>
</section>
<script>
    ymaps.ready(init);

function init() {
    var myMap = new ymaps.Map("map", {
        center: ['{{$city->width}}', '{{$city->longitude}}'],
        zoom: 10
        }, {
            searchControlProvider: 'yandex#search'
        });
@if (isset($cemeteries) && $cemeteries->count()>0)
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
@include('footer.footer') 