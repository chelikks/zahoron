<div class="sidebar_account">

    <div class="item_page_sidebar">
        <a href='{{route('home')}}'class="title_page_sidebar"><img class='icon_page'src="{{asset('storage/uploads/Icon_sidebar_2.svg')}}" alt=""> Главная </a>
    </div>

    @foreach(adminPages() as $admin_children_pages)
        <div class="item_page_sidebar">
            <div class="title_page_sidebar"><img class='icon_page'src="{{asset($admin_children_pages[1])}}" alt=""> {{$admin_children_pages[0]}} <img class='open_children_pages_sidebar'src="{{asset('storage/uploads/Arrow_sidebar.svg')}}" alt=""></div>
            <div class="pages_children_sidebar">
                @foreach($admin_children_pages[2] as $admin_children_page)
                    <a  href="{{route($admin_children_page[1])}}" class="li_children_page_sidebar {{activateLink($admin_children_page[1], "li_children_page_sidebar_active")}}">{{$admin_children_page[0]}}</a>
                @endforeach
            </div>
        </div>
    @endforeach

</div>