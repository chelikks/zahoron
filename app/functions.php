<?php

use App\Models\Acf;
use App\Models\ActivityCategoryOrganization;
use App\Models\City;
use App\Models\Edge;
use App\Models\Page;
use App\Models\Product;
use App\Models\Cemetery;
use App\Models\ImageService;
use App\Models\OrderProduct;
use App\Models\StageService;
use App\Models\AdditionProduct;
use App\Models\Burial;
use App\Models\CategoryProduct;
use App\Models\CategoryProductProvider;
use App\Models\CommentProduct;
use App\Models\FaqCategoryProduct;
use App\Models\FaqService;
use App\Models\Organization;
use App\Models\ReviewsOrganization;
use App\Models\Service;
use App\Models\ServiceReviews;
use App\Models\User;
use Ausi\SlugGenerator\SlugGenerator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;





function mainCities(){
    $cities=City::orderBy('title','asc')->take(8)->get();
    return $cities;
}

function categoryProductChoose(){
    return CategoryProduct::where('choose_admin',1)->first();
}

function categoryProductProviderChoose(){
    return CategoryProductProvider::where('choose_admin',1)->first();
}

function childrenCategoryProducts($cat){
    return CategoryProduct::orderBy('id','desc')->where('parent_id',$cat->id)->get();
}

function childrenCategoryProductsProvider($cat){
    return CategoryProductProvider::orderBy('id','desc')->where('parent_id',$cat->id)->get();
}

function childrenCategoryOrganization($organization,$category_organization){
    $categories_children=CategoryProduct::whereIn('id',ActivityCategoryOrganization::where('organization_id',$organization->id)->where('category_main_id',$category_organization->id)->pluck('category_children_id'))->get();
    return $categories_children;
}

function childrenCategoryOrganizationProvider($organization,$category_organization){
    $categories_children=CategoryProductProvider::whereIn('id',ActivityCategoryOrganization::where('organization_id',$organization->id)->where('category_main_id',$category_organization->id)->pluck('category_children_id'))->get();
    return $categories_children;
}

function sizesProducts(){
    $sizes=Product::where('size','!=',null)->pluck('size')->unique();
    $sizes_all=[];
    foreach($sizes as $size){
        $size=explode('|',$size);
        foreach($size as $size_one){
            $sizes_all[]=$size_one;
        }
    }
    return $sizes_all;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

function totalOrderService($services){
    $sum=0;
    foreach($services as $service){
        $sum+=$service->price;
    }
    return $sum;

}

function statusOrder($status){
   if($status==0){
        return '<div class="text_li">Не оплачен</div>';
   }
    else{
        return '<div class="text_li color_black">Оплачен</div>';
    }
}


function get_acf($id_page,$name_acf){
    $page=Page::findOrFail($id_page);
    $acf=Acf::where('name',$name_acf)->where('page_id',$page->id)->get();
    return $acf[0]->content;
}

function city_by_slug($slug){
    return City::where("slug", $slug)->first();
}
function first_city_slug(){
    return City::first()->slug;
}
function first_city_id(){
    return City::first()->id;
}
function insert_city_into_url($url, $kzn){

    // Parse the URL into its components
    $url_parts = parse_url($url);

    // Extract the path component
    $path = $url_parts['path'];
    // Use a regular expression to replace the first path segment
    $path = preg_replace('/^\/[^\/]+/', '/' . $kzn, $path, 1);

    // Reconstruct the URL with the modified path
    return $url_parts['scheme'] . '://' . $url_parts['host'] . ':'.$url_parts['port']  . $path;
}

function filterProducts($data){
    $products=Product::orderBy('id','desc');
    if(isset($data['sort'])){
        if($data['sort']!='Сортировка' && $data['sort']!='undefined'){
            if($data['sort']=='price_down'){
                $products=Product::orderBy('total_price','desc');
            }
            if($data['sort']=='price_up'){
                $products=Product::orderBy('total_price','asc');
            }
            if($data['sort']=='date'){
                $products=Product::orderBy('id','desc');
            }
            if($data['sort']=='sale'){
                $products=Product::where('status','sale');
            }
        }
    }
    
    $category=categoryProductChoose();
    if(isset($data['category'])){
        if($data['category']!='undefined'){
            $category=CategoryProduct::findOrFail($data['category']);
        }
    }
    $products=$products->where('category_id',$category->id);
    if($category->parent_id==36){
        if(isset($data['cemetery_id'])  && $data['cemetery_id']!='undefined'){
            $products=$products->where('cemetery_id',$data['cemetery_id']);
         }

         if(isset($data['size'])){
             if($data['size']!='Размер'  && $data['size']!='undefined'){
                 $products=$products->where('size','like','%'.$data['size'].'%');
             }
             
         }

         if(isset($data['material'])){
             if($data['material']!='Материал' && $data['material']!='undefined'){
                 $products=$products->where('material',$data['material']);
             }
         }

         if(isset($data['layering'])){
            if($data['layering']!=null && $data['layering']!='undefined'){
                $products=$products->where('layering',$data['layering']);
            }
        }
    }
    if($category->parent_id==45){
        if(isset($data['district_id'])  && $data['district_id']!='undefined'){
            $products=$products->where('district_id',$data['district_id']);
         }
    }
    return $products->paginate(12);

}

    function faqCatsProduct($data){
        if(isset($data['category'])){
            return FaqCategoryProduct::where('category_id',$data['category'])->get();
        }return [];
        
    }
    function cemeteryProduct($data){
        $city=selectCity();
        if(isset($data['cemetery_id'])){
            return  Cemetery::findOrFail($data['cemetery_id']);
        }
        return null;

        
    }
    function ajaxCatContent($data){
        if(isset($data['category'])){
            return  CategoryProduct::findOrFail($data['category']);
        }
        return categoryProductChoose();
        
        
    }
    function ajaxCatManual($data){
        if(isset($data['category'])){
            return  CategoryProduct::findOrFail($data['category']);
        }return null;
        
    }
    


function priceAdditionals($ids){
    $additionals=AdditionProduct::whereIn('id',$ids)->pluck('price');
    $sum=0;
    foreach($additionals as $additional){
        $sum+=(int)$additional;
    }
    return $sum;

}


function selectCity(){
    if(isset($_COOKIE['city'])){
        return $city=City::findOrFail($_COOKIE['city']);
    }
    $city=City::where('selected_admin',1)->first();
    setcookie("city", $city->id, time()+20*24*60*60,'/');
    return $city;
}

function priceProductOrder($cart_item){
    $product=Product::findOrFail($cart_item[0]);
    $price=$product->price;
    if($cart_item[1]!=[]){
        foreach($cart_item[1] as $additional){
            $price+=AdditionProduct::findOrFail($additional)->price;
        }
    }return $price*$cart_item[2];
    
}

function ulCemeteries($user_id){
    $ids_cemteries=OrderProduct::where('user_id',$user_id)->pluck('cemetery_id')->unique();
    $cemteries=Cemetery::whereIn('id',$ids_cemteries)->get();
    return $cemteries;
}

function serviceOneTimeCleaning($service){
    $cemetery=Cemetery::findOrFail($service->cemetery_id);
    $city=City::findOrFail($cemetery->city_id);
    $edge=Edge::findOrFail($city->edge_id);
    $imgs_service=ImageService::where('service_id',$service->id)->get();
    $stages_service=StageService::orderBy('id','asc')->where('service_id',$service->id)->get();
    return view('service.single.single-one-time-cleaning',compact('imgs_service','stages_service','service','cemetery','edge','city'));
}

function servicePaintingFence($service){
    $cemetery=Cemetery::findOrFail($service->cemetery_id);
    $city=City::findOrFail($cemetery->city_id);
    $edge=Edge::findOrFail($city->edge_id);
    $reviews=ServiceReviews::orderBy('id','asc')->where('service_id',$service->id)->get();
    $imgs_service=ImageService::where('service_id',$service->id)->get();
    $stages_service=StageService::orderBy('id','asc')->where('service_id',$service->id)->get();
    $faqs=FaqService::orderBy('id','desc')->where('service_id',$service->id)->get();
    return view('service.single.single-painting-fence',compact('imgs_service','reviews','stages_service','service','faqs','cemetery','edge','city'));
}

function serviceDepartureBrigadeCalculation($service){
    
    $cemetery=Cemetery::findOrFail($service->cemetery_id);
    $city=City::findOrFail($cemetery->city_id);
    $edge=Edge::findOrFail($city->edge_id);
    $reviews=ServiceReviews::orderBy('id','asc')->where('service_id',$service->id)->get();
    $imgs_service=ImageService::where('service_id',$service->id)->get();
    $stages_service=StageService::orderBy('id','asc')->where('service_id',$service->id)->get();
    $faqs=FaqService::orderBy('id','desc')->where('service_id',$service->id)->get();
    return view('service.single.single-departure-brigade-calculation',compact('imgs_service','reviews','stages_service','service','faqs','cemetery','edge','city'));
}

function priceProduct($product){
    if($product->price_sale!=null){
        return $product->price_sale;
    }
    return $product->price;
}


function procentPriceProduct($product){
    if($product->price_sale!=null){
        $procent=100-intdiv($product->price_sale*100,$product->price);
        return '<div class="procent_sale_product">'.$procent.'%</div>';
    }
    return ;
}


function procentSaleProduct($product){
    if($product->price_sale!=null){
        $procent=100-intdiv($product->price_sale*100,$product->price);
        return $procent;
    }
    return ;
}


function registration($number,$name){
    $user_phone=User::where('phone',$number)->get();
    if(!isset($user_phone[0])){
        $password=generateRandomString(8);
        $user=User::create([
        'name'=>$name,
        'phone'=>$number,
        'password'=>Hash::make($password),
        ]);
    }
    return $user;
}

function ddata(){
    $token = "4e4378db0c787716d3f05adaccb75002bb1ce6b6";
        $dadata = new \Dadata\DadataClient($token, null);
        $result = $dadata->findById("party", "7707083893", 1);
}


function sendSms($phone,$message){
    $body = file_get_contents("https://sms.ru/sms/send?api_id=ABEA29AD-63BB-1657-6B5D-8F7501A7825C&to=".$phone."&msg=".urlencode($message)."&json=1"); 
    return $json = json_decode($body);
}


function organizationRatingFuneralAgenciesPrices($city){
    $sorted_organizations_ids=ActivityCategoryOrganization::whereIn('category_children_id',[32,33,34,35])->where('price','!=',null)->where('city_id',$city)->pluck('organization_id');
    $orgainizations=Organization::whereIn('id',$sorted_organizations_ids)->get()->map(function ($organization) {
        $price_1=ActivityCategoryOrganization::where('category_children_id',32)->where('organization_id',$organization->id)->get();
        $price_2=ActivityCategoryOrganization::where('category_children_id',33)->where('organization_id',$organization->id)->get();
        $price_3=ActivityCategoryOrganization::where('category_children_id',34)->where('organization_id',$organization->id)->get();
        $price_4=ActivityCategoryOrganization::where('category_children_id',35)->where('organization_id',$organization->id)->get();
        if(count($price_1)>0 && count($price_2)>0 && count($price_3)>0 && count($price_4)>0 ){
            $organization->all_price=$price_1->first()->price+$price_2->first()->price+$price_3->first()->price+$price_4->first()->price;
            return $organization;
        }


    });
    
    
    // Сортируем продукты по минимальной цене
    $sortedProducts = $orgainizations->sortBy('all_price');
    // Возвращаем 10 самых выгодных продуктов
    return $sortedProducts->take(10);

    return null;
}
   


function organizationRatingUneralBureausRavesPrices($city){

    $sorted_organizations_ids=ActivityCategoryOrganization::whereIn('category_children_id',[29,30,39])->where('price','!=',null)->where('city_id',$city)->pluck('organization_id');
    $orgainizations=Organization::whereIn('id',$sorted_organizations_ids)->get()->map(function ($organization) {
        $price_1=ActivityCategoryOrganization::where('category_children_id',29)->where('organization_id',$organization->id)->get();
        $price_2=ActivityCategoryOrganization::where('category_children_id',30)->where('organization_id',$organization->id)->get();
        $price_3=ActivityCategoryOrganization::where('category_children_id',39)->where('organization_id',$organization->id)->get();
        if(count($price_1)>0 && count($price_2)>0 && count($price_3)>0 ){
            $organization->all_price=$price_1->first()->price+$price_2->first()->price+$price_3->first()->price;
            return $organization;
        }

    });
    // Сортируем продукты по минимальной цене
    $sortedProducts = $orgainizations->sortBy('all_price');

    // Возвращаем 10 самых выгодных продуктов
    return $sortedProducts->take(10);
}



function organizationratingEstablishmentsProvidingHallsHoldingCommemorations($city){
    $sorted_organizations=ActivityCategoryOrganization::where('category_children_id',46)->where('price','!=',null)->orderBy('price','asc')->where('city_id',$city)->get();
    return $sorted_organizations->take(10);
}


function savingsPrice($id){
    $city=selectCity();
    $orgainizations=ActivityCategoryOrganization::where('category_children_id',$id)->where('price','!=',null)->orderBy('price','asc')->where('city_id',$city->id)->get();

    if(count($orgainizations)>0){
        $price=$orgainizations->last()->price-$orgainizations->first()->price;
        return $price;
    }
    return $price=null;

    
}

function reviewsOrganization($city){
    $reviews_organization=ReviewsOrganization::orderBy('id','desc')->where('status',1)->where('city_id',$city)->get()->take(8);
    return $reviews_organization;
}

function priceAdditional($price){
    if($price==0 || $price==null){
        return null;
    }
    return $price.' ₽';
}

function addToCartProduct($id){
    $product=Product::find($id);
    if($product->category_id==46 || $product->category_id==47 || $product->category_id==32 || $product->category_id==33 || $product->category_id==34 || $product->category_id==35){
        return '<a href="'.route('product.single',$id).'" class="blue_btn">'.'Оформить</a>';
    }
    return '<div id_product="'. $product->id .'" class="blue_btn add_to_cart_product">Купить</div>';

}

function get_ip()
{
	$value = '';
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$value = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$value = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif (!empty($_SERVER['REMOTE_ADDR'])) {
		$value = $_SERVER['REMOTE_ADDR'];
	}
  
	return $value;
}


function custom_echo($x, $length)
{
  if(strlen($x)<=$length)
  {
    echo $x;
  }
  else
  {
    $y=substr($x,0,$length) . '... <br> <div class="open_all_content_block">Читать</div>';
    echo $y;
  }
}


function raitingOrganization($organization){
    $reviews=ReviewsOrganization::orderBy('id','desc')->where('organization_id',$organization->id)->where('status',1)->get();
    $rating_reviews=null;
    if($reviews!=null && $reviews->count()>1){
        $rating_reviews=explode('.',strval($reviews->pluck('rating')->sum()/$reviews->count()));
        if(count($rating_reviews)>1){
            $rating_reviews=$rating_reviews[0].".".substr($rating_reviews[1],0,1);
        }else{
            $rating_reviews=$reviews->pluck('rating')->sum()/$reviews->count();
        }
    }
    return $rating_reviews;
}

function countReviewsOrganization($organization){
    $reviews=ReviewsOrganization::orderBy('id','desc')->where('organization_id',$organization->id)->where('status',1)->get();
    if($reviews!=null && count($reviews)>0){
        return count($reviews);
    }
    return null;
}

function orgniaztionsFilters($data){
    $city=selectCity();
    if(isset($data['category_id'])){
        $organizations_category=ActivityCategoryOrganization::where('category_children_id',$data['category_id'])->where('city_id',$city->id)->where('role','organization');
    }else{
        $organizations_category=ActivityCategoryOrganization::where('category_children_id',categoryProductChoose()->id)->where('city_id',$city->id)->where('role','organization');
    }
    if(isset($data['cemetery_id']) && $data['cemetery_id']!=null && $data['cemetery_id']!='null'){
        $cemetery_id=$data['cemetery_id'];
        $organizations_category=$organizations_category->where(function($item) use ($cemetery_id){
            return $item->orWhere('cemetery_ids',"LIKE", "%,".$cemetery_id.",%")->orWhere('cemetery_ids',"LIKE", $cemetery_id.",%")->orWhere('cemetery_ids',"LIKE", "%,".$cemetery_id);
        });
    }  
    if(isset($data['district_id']) && $data['district_id']!=null && $data['district_id']!='null'){
        $district_id=$data['district_id'];
        $organizations_category=$organizations_category->where(function($item) use ($district_id){
            return $item->orWhere('district_ids',"LIKE", "%,".$district_id.",%")->orWhere('district_ids',"LIKE", $district_id.",%")->orWhere('district_ids',"LIKE", "%,".$district_id);
        });
    }        
    if(isset($data['sort']) && $data['sort']!=null && $data['sort']!=''){
        if($data['sort']!='Сортировка'){
            if($data['sort']=='price_down'){
                $organizations_category=$organizations_category->orderBy('price','desc');
            }
            if($data['sort']=='price_up'){
                $organizations_category=$organizations_category->orderBy('price','asc');
            }
            if($data['sort']=='date'){
                $organizations_category=$organizations_category->orderBy('id','desc');
            }
            if($data['sort']=='popular'){
                $organizations_category=$organizations_category->orderBy('rating','desc');
            }
        }
    }
    if(isset($data['filter_work']) && $data['filter_work']!=null){
        if($data['filter_work']=='on'){
            $organizations_category_ids=$organizations_category->get()->map(function ($organization) {
                $time=strtotime('10:00');
                $organization_choose=$organization->organization();
                // $time=strtotime(getTimeByCoordinates($organization_choose->width,$organization_choose->longitude)['current_time']);
                if( strtotime($organization_choose->time_start_work)<$time &&  strtotime($organization_choose->time_end_work)>$time){
                    $organization->open=1;
                    return $organization;
                }
            });
            $organizations_category=$organizations_category->whereIn('id',$organizations_category_ids->where('open',1)->pluck('id'));
        }  
    }
    return $organizations_category->paginate(1);
    
}

function organizationsPrices($data){
    $city=selectCity();
    $category_id=categoryProductChoose()->id;
    if(isset($data['category_id'])){
        $category_id=$data['category_id'];
    }
    $organizations_prices=ActivityCategoryOrganization::where('category_children_id',$category_id)->where('city_id',$city->id)->where('role','organization');
    if (isset($data['cemetery_id']) && $data['cemetery_id']!=null && $data['cemetery_id']!='null' ){
        $cemetery_id=$data['cemetery_id'];
        $organizations_prices=$organizations_prices->where(function($item) use ($cemetery_id){
            return $item->orWhere('cemetery_ids',"LIKE", "%,".$cemetery_id.",%")->orWhere('cemetery_ids',"LIKE", $cemetery_id.",%")->orWhere('cemetery_ids',"LIKE", "%,".$cemetery_id);
        });
    }
    if(isset($data['district_id']) && $data['district_id']!=null && $data['district_id']!='null'){
        $district_id=$data['district_id'];
        $organizations_prices=$organizations_prices->where(function($item) use ($district_id){
            return $item->orWhere('district_ids',"LIKE", "%,".$district_id.",%")->orWhere('district_ids',"LIKE", $district_id.",%")->orWhere('district_ids',"LIKE", "%,".$district_id);
        });
    }   
    if(isset($data['filter_work']) && $data['filter_work']!=null){
        if($data['filter_work']=='on'){
            $organizations_prices_ids=$organizations_prices->get()->map(function ($organization) {
                $time=strtotime('10:00');
                $organization_choose=$organization->organization();
                // $time=strtotime(getTimeByCoordinates($organization_choose->width,$organization_choose->longitude)['current_time']);
                if( strtotime($organization_choose->time_start_work)<$time &&  strtotime($organization_choose->time_end_work)>$time){
                    $organization->open=1;
                    return $organization;
                }
            });
            $organizations_prices=$organizations_prices->whereIn('id',$organizations_prices_ids->where('open',1)->pluck('id'));
        }  
    }
    $organizations_prices=$organizations_prices->get();
    if($organizations_prices!=null && $organizations_prices->count()>0){
        $price_min=$organizations_prices->min('price');
        $price_middle=round($organizations_prices->avg('price'));
        $price_max=$organizations_prices->max('price');
        return  [$price_min,$price_middle,$price_max];
    }
    return null;
    
}


function timeDifference($time1,$time2){

    if($time1!=null && $time2!=null){
        $startTime = new DateTime($time1);
        $endTime = new DateTime($time2);
        return $interval = $startTime->diff($endTime);
    }

    return null;
}


function getTimeByCoordinates($latitude, $longitude)
{
    $apiKey='f85b1a2e01a144d496d767cb921c8b60';
    $client = new Client();
    $response = $client->get("https://api.opencagedata.com/geocode/v1/json?q={$latitude}+{$longitude}&key={$apiKey}");
    $data = json_decode($response->getBody(), true);
    
    if (isset($data['results'][0]['annotations']['timezone'])) {
        $timezone = $data['results'][0]['annotations']['timezone']['name'];
        $currentTime = new \DateTime("now", new \DateTimeZone($timezone));

        return [
            'timezone' => $timezone,
            'current_time' => $currentTime->format('H:i'),
        ];
    }

    return null; // Обработка случая, когда данные не найдены
}



function orgniaztionsProviderFilters($data){

    $city=selectCity();
    if(isset($data['city_id']) && $data['city_id']!=null){
        $city=City::find($data['city_id']);
    }

    if(isset($data['category_id']) && $data['category_id']!=null){
        $organizations_category=ActivityCategoryOrganization::where('category_children_id',$data['category_id'])->where('city_id',$city->id)->where('role','organization-provider');
    }else{
        $organizations_category=ActivityCategoryOrganization::where('category_children_id',categoryProductProviderChoose()->id)->where('city_id',$city->id)->where('role','organization-provider');
    }
     
    
    if(isset($data['sort']) && $data['sort']!=null && $data['sort']!=''){
        if($data['sort']!='Сортировка'){
            if($data['sort']=='price_down'){
                $organizations_category=$organizations_category->orderBy('price','desc');
            }
            if($data['sort']=='price_up'){
                $organizations_category=$organizations_category->orderBy('price','asc');
            }
            if($data['sort']=='date'){
                $organizations_category=$organizations_category->orderBy('id','desc');
            }
            if($data['sort']=='popular'){
                $organizations_category=$organizations_category->orderBy('rating','desc');
            }
        }
    }

    if(isset($data['filter_work']) && $data['filter_work']!=null){
        if($data['filter_work']=='on'){
            $organizations_category_ids=$organizations_category->get()->map(function ($organization) {
                $time=strtotime('10:00');
                $organization_choose=$organization->organization();
                // $time=strtotime(getTimeByCoordinates($organization_choose->width,$organization_choose->longitude)['current_time']);
                if( strtotime($organization_choose->time_start_work)<$time &&  strtotime($organization_choose->time_end_work)>$time){
                    $organization->open=1;
                    return $organization;
                }
            });
            $organizations_category=$organizations_category->whereIn('id',$organizations_category_ids->where('open',1)->pluck('id'));
        }  
    }

    return $organizations_category->paginate(1);
}


function organizationsProviderPrices($data){

    $city=selectCity();
    if(isset($data['city_id']) && $data['city_id']!=null){
        $city=City::find($data['city_id']);
    }

    $category_id=categoryProductProviderChoose()->id;
    if(isset($data['category_id']) && $data['category_id']!=null){
        $category_id=$data['category_id'];
    }
    
    $organizations_prices=ActivityCategoryOrganization::where('category_children_id',$category_id)->where('city_id',$city->id)->where('role','organization-provider');
   
    if(isset($data['filter_work']) && $data['filter_work']!=null){
        if($data['filter_work']=='on'){
            $organizations_prices_ids=$organizations_prices->get()->map(function ($organization) {
                $organization_choose=$organization->organization();
                // $time=strtotime(getTimeByCoordinates($organization_choose->width,$organization_choose->longitude)['current_time']);
                $time=strtotime('10:00');

                if( strtotime($organization_choose->time_start_work)<$time &&  strtotime($organization_choose->time_end_work)>$time){
                    $organization->open=1;
                    return $organization;
                }
            });
            $organizations_prices=$organizations_prices->whereIn('id',$organizations_prices_ids->where('open',1)->pluck('id'));
        }  
    }

    $organizations_prices=$organizations_prices->get();
    if($organizations_prices!=null && $organizations_prices->count()>0){
        $price_min=$organizations_prices->min('price');
        $price_middle=round($organizations_prices->avg('price'));
        $price_max=$organizations_prices->max('price');
        return  [$price_min,$price_middle,$price_max];
    }
    return null;
    
}


function searchOrganization($name){
    if($name!=null){
        $organizations=Organization::where('title','like',$name.'%')->where('role','organization-provider')->where('status',1)->get()->take(15);
        return $organizations;
    }
    return null;
}


function cityWithOrganizationProvider(){
    $ids_city=ActivityCategoryOrganization::where('role','organization-provider')->pluck('city_id');
    $cities=City::whereIn('id',$ids_city)->get();
    return $cities;
}


function nameSort($name){
    if( $name!=null && $name!=''){
        if($name!='Сортировка'){
            if($name=='price_down'){
                return 'По убыванию цены';
            }
            if($name=='price_up'){
                return 'По возрастанию цены';
            }
            if($name=='date'){
                return 'По новизне';
            }
            if($name=='popular'){
                return 'По попуялрности';
            }
        }return 'Сортировка';
    }return 'Сортировка';
}


function reviewProducts($data){
    if(isset($data['category']) && $data['category']!=null && $data['category']!=''){
        $reviews=CommentProduct::where('category_id',$data['category'])->orderBy('id','desc')->get(); 
    }
    else{
        $reviews=CommentProduct::where('category_id',categoryProductChoose()->id)->orderBy('id','desc')->get(); 
    }
    return $reviews;
}

function cartPrice(){
    $price_all=0;
    if(isset($_COOKIE['add_to_cart_product'])){
        foreach(json_decode($_COOKIE['add_to_cart_product']) as $product){
            $price_product=Product::findOrFail($product[0])->price*$product[2];
            if(count($product[1])>0){
                foreach($product[1] as $additional){
                    $price_product=$price_product+AdditionProduct::findOrFail($additional)->price;
                }
            }
            $price_all=$price_all+$price_product;
        }
    }
    return $price_all;
}


function user(){

    if(Auth::check()){
        return Auth::user();
    }
    return null;
}


function slug($item){
    
    $generator = new SlugGenerator(); 

    return $generator->generate($item); 

}

function getBurial($id){
    return $product=Burial::findOrFail($id);
}

function servicesBurial($ids){
    return Service::whereIn('id',$ids)->get();
}

function routeMarketplace($category){
    return redirect()->route('marketplace.category',$category->slug);
}

function activateLink($name, $active_class){
    if(request()->route()->getName() == $name){
        echo $active_class;
    }
    return "";
}

function dateBurial($date){
    $date=explode('.',$date);
    $new_date="{$date[2]}-{$date[1]}-{$date[0]}";
    return $new_date;
}

function dateBurialInBase($date){
    $date=explode('-',$date);
    $new_date="{$date[2]}.{$date[1]}.{$date[0]}";
    return $new_date;
}