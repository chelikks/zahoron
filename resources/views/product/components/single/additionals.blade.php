@if($additionals!=null && $additionals->count()>0)
<div class="cats_news product_sidebar_block">
    <div class="title_news">Дополнительно</div>
        @foreach ($additionals as $additional)
            <label class='input_additional checkbox'><input price='{{$additional->price}}' type="checkbox" name='additionals[]' value='{{ $additional->id }}'>{{ $additional->title }}<div class="title_news"><span>{{ priceAdditional($additional->price) }}</span> </div></label>
        @endforeach
</div>
@endif