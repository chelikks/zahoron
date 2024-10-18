<?php
use App\Models\Product;
use App\Models\Organization;
use App\Models\ImageProduct;
 $city=selectCity();
 $products_memorial_hall=Product::where('city_id', $city->id)->where('category_id',47)->get();
?>

@if(count($products_memorial_hall)>0)

<section class="memorial_dinners">
    <div class="container">
        <div class="title">Арендовать поминальный зал в г. {{$city->title}} на маркет плейсе</div>
            <div class="swiper memorial_hall_swiper">
                <div class="swiper-wrapper">
                @foreach($products_memorial_hall as $product_memorial_hall)
                    <div class="swiper-slide">
                        <div class="li_memorial_hall">
                            <?php $images=ImageProduct::where('product_id',$product_memorial_hall->id)->get();?>
                            @if (isset($images))
                                @if (count($images)>0)
                                    <img class='img_memorial_hall' src="{{ asset('storage/uploads_product/'.$images[0]->title) }}" alt="">
                                @endif
                            @endif
                            <div class="grid_two">
                                <div class="flex_info_hall">
                                    <?php $organization=Organization::find($product_memorial_hall->organization_id);?>
                                    <img src="{{asset('storage/uploads_organization/'.$organization->logo)}}" alt="">
                                    <div class="flex_hall">
                                        <a href='{{route('product.single',$product_memorial_hall->id)}}' class="title_memorial_hall">{{$product_memorial_hall->title}}</a>
                                        <div class="flex_monuments_grave">
                                            <div class="raiting_memorial_dinner">
                                                <img src="{{asset('storage/uploads/Star 1 copy.svg')}}" alt="">5
                                            </div>
                                            <div class="text_black">{{$product_memorial_hall->сapacity}} человек</div> 
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_center">
                                    {!!addToCartProduct($product_memorial_hall->id)!!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>

            <div class="swiper-button-next swiper_button_next_memorial_hall"><img src='{{asset('storage/uploads/Переключатель.svg')}}'></div>
            <div class="swiper-button-prev swiper_button_prev_memorial_hall"><img src='{{asset('storage/uploads/Переключатель (1).svg')}}'></div>
    </div>
</section>
@endif

<script>
    $( ".add_to_cart_product" ).on( "click", function() {
    let this_btn=$(this)
    let id_product= $(this).attr('id_product');
    $.ajax({
        type: 'GET',
        url: '{{ route("product.add.cart") }}',
        data: {
            "_token": "{{ csrf_token() }}",
            'id_product': id_product,
        }, success: function (result) {
            
            if(result['error']){
                alert(result['error'])
            }else{
                this_btn.html('Оформить <img src="{{asset("storage/uploads/done-v-svgrepo-com.svg")}}">')
                let price= Number($('.blue_block_all_price span').html())+Number(result['price'])
                $('.blue_block_all_price span').html(price)
                
            }
        },
        error: function () {
            alert('Ошибка');
        }
    });


    

});
</script>