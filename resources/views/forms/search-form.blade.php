
<section class='form_search'>
    <div class="container bac_gray">
        <div class="index_title">Быстрый поиск по захоронениям</div>
        <img class='rose_form_search' src="{{asset('storage/uploads/rose-with-stem 1 (2).svg')}}" alt="">
        <img class='flower_form_search' src="{{asset('storage/uploads/lily-of-the-valley 1 (1).svg')}}" alt="">
        <form method='get' action="{{route('search.burial')}}" class="search">
            @csrf

            <div class="block_input">
                <input type="text" name='surname' placeholder='Фамилия'>
                @error('surname')
                <div class='error-text'>{{ $message }}</div>
                @enderror
            </div>
            
            <div class="block_input">
                <input type="text" name='name' placeholder='Имя'>
                @error('name')
                <div class='error-text'>{{ $message }}</div>
                @enderror
            </div>
            <div class="block_input">
                <input type="text" name='patronymic' placeholder='Отчество'>
                @error('patronymic')
                <div class='error-text'>{{ $message }}</div>
                @enderror
            </div>
            <div class="block_input">
                <input type="text" name='city' placeholder='Город'>
                @error('city')
                <div class='error-text'>{{ $message }}</div>
                @enderror
            </div>
            <div class="block_input"><button class="blue_btn" type='submit'>Найти</button></div>
        </form>
    </div>
</section>