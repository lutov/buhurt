<div class="card @include('card.class')">
    <div class="card-body">
        <div class="card-title">Безопасность</div>
        <p class="card-text">
            <a href="{!! URL::action('User\UserController@change_password') !!}" class="">Сменить пароль</a>
        </p>
    </div>
    <div class="card-body">
        <div class="card-title">Комментарии</div>
        <form action="/user/{{ $user->id }}/options" class="options" method="POST">
            @foreach($options as $option)
                <p class="card-text m-0">
                    <input type="hidden" name="{{ $option->name }}" autocomplete="off" />
                    <input type="checkbox" name="{{ $option->name }}" id="{{ $option->name }}" value="1" autocomplete="off" @if(in_array($option->id, $user_options)) checked="checked" @endif />
                    <label for="{!! $option->name !!}">{!! $option->description !!}</label>
                </p>
            @endforeach
            <div class="mt-2">
                <input type="submit" value="Сохранить" id="set_options" class="btn btn-sm btn-secondary" />
            </div>
        </form>
    </div>
</div>
