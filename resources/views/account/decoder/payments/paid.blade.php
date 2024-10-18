@include('header.header-decoder')


<div class="account_container">
    <div class="flex_account">
        @include('account.decoder.components.sidebar')
        <div class="container_content_account">
            <div class="container">
                <div class="title_middle">Оплачено</div>             
                <div class="ul_payments_decoder">
                    @if($payments!=null && $payments->count()>0)
                        @foreach($payments as $payment)
                            <div class="li_payment_decoder">
                                <div class="text_black_big">Оплата №{{$payment->id}} от {{$payment->created_at}} г. {{$payment->title}}</div>
                                <div class="text_black_big_bold">{{$payment->price}} руб.</div>
                            </div>
                        @endforeach
                    @endif  
                </div>       
            </div>
        </div>
    </div>
</div>

@include('footer.footer')