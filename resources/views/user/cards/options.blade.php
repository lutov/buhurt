<div class="card @include('card.class')">
    <div class="card-body">
        <div class="card-title">Безопасность</div>
        <p class="card-text">
            <a href="{!! URL::action('User\UserController@change_password') !!}" class="">Сменить пароль</a>
        </p>
    </div>
    <div class="card-body">
        <div class="card-title">Комментарии</div>
        {!! Form::open(array('url' => 'user/'.$user->id.'/options', 'class' => 'options', 'method' => 'POST')) !!}
        @foreach($options as $option)
            <p class="card-text m-0">
                {!! Form::hidden($option->name, $value = '0', array('autocomplete' => 'off')) !!}
                {!! Form::checkbox($option->name, 1, in_array($option->id, $user_options), array('id' => $option->name, 'autocomplete' => 'off')) !!}
                <label for="{!! $option->name !!}">{!! $option->description !!}</label>
            </p>
        @endforeach
        <div class="mt-2">
            {!! Form::submit('Сохранить', $attributes = array('id' => 'set_options', 'type' => 'button', 'class' => 'btn btn-sm btn-secondary')) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>
