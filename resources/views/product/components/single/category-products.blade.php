
<?php 
    use App\Models\Organization;
    use App\Models\ImageProduct;
?>
@if(count($category_products))
    <div class="ul_products">
        @foreach($category_products as $category_product)
            <div class="li_product_market">
                <?php $images=ImageProduct::where('product_id',$category_product->id)->get();?>
                    @if (isset($images))
                        @if (count($images)>0)
                            <img class='img_market_product' src="{{ asset('storage/uploads_product/'.$images[0]->title) }}" alt="">
                        @endif
                    @endif
                    <a href='{{ route('product.single',$category_product->id) }}'class="title_product_market">{{ $category_product->title }}</a>
                    <?php $organization_product=Organization::find($category_product->organization_id);?>
                    <div class="flex_raiting">
                        <div class="text_gray_mini">{{$organization_product->title}}</div>
                        <div class="flex_stars">
                            <img src="{{asset('storage/uploads/Frame 334.svg')}}" alt="">
                            <div class="text_black_mini">{{raitingOrganization($organization_product)}}</div>
                        </div>
                    </div>
                    <div class="flex_btn_li_product_market">
                        <div class="price_product_market">{{ priceProduct($category_product) }} руб.</div>
                        {!!addToCartProduct($category_product->id)!!}
                    </div>
                </div>
        @endforeach
    </div>
@endif