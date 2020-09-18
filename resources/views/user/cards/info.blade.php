@php
    use App\Helpers\TextHelper;
    use App\Models\User\User;
    use Illuminate\Support\Facades\URL;
    /** @var User $user */
    /** @var int $books_rated */
    /** @var int $films_rated */
    /** @var int $games_rated */
    /** @var int $albums_rated */
    /** @var int $books_wanted */
    /** @var int $films_wanted */
    /** @var int $games_wanted */
    /** @var int $albums_wanted */
    /** @var int $books_unwanted */
    /** @var int $films_unwanted */
    /** @var int $games_unwanted */
    /** @var int $albums_unwanted */
    $controllers = array(
        'rates' => 'User\UserController@rates',
        'wanted' => 'User\UserController@wanted',
        'unwanted' => 'User\UserController@unwanted',
    );
    $url = array(
        'rates' => array(
            'books' => URL::action($controllers['rates'], array($user->id, 'books')),
            'films' => URL::action($controllers['rates'], array($user->id, 'films')),
            'games' => URL::action($controllers['rates'], array($user->id, 'games')),
            'albums' => URL::action($controllers['rates'], array($user->id, 'albums')),
        ),
        'wanted' => array(
            'books' => URL::action($controllers['wanted'], array($user->id, 'books')),
            'films' => URL::action($controllers['wanted'], array($user->id, 'films')),
            'games' => URL::action($controllers['wanted'], array($user->id, 'games')),
            'albums' => URL::action($controllers['wanted'], array($user->id, 'albums')),
        ),
        'unwanted' => array(
            'books' => URL::action($controllers['unwanted'], array($user->id, 'books')),
            'films' => URL::action($controllers['unwanted'], array($user->id, 'films')),
            'games' => URL::action($controllers['unwanted'], array($user->id, 'games')),
            'albums' => URL::action($controllers['unwanted'], array($user->id, 'albums')),
        ),
    );
    $numbers = array(
        'rates' => array(
            'books' => TextHelper::number($books_rated, array('книгу', 'книги', 'книг')),
            'films' => TextHelper::number($films_rated, array('фильм', 'фильма', 'фильмов')),
            'games' => TextHelper::number($games_rated, array('игру', 'игры', 'игр')),
            'albums' => TextHelper::number($albums_rated, array('альбом', 'альбома', 'альбомов')),
        ),
        'wanted' => array(
            'books' => TextHelper::number($books_wanted, array('книгу', 'книги', 'книг')),
            'films' => TextHelper::number($films_wanted, array('фильм', 'фильма', 'фильмов')),
            'games' => TextHelper::number($games_wanted, array('игру', 'игры', 'игр')),
            'albums' => TextHelper::number($albums_wanted, array('альбом', 'альбома', 'альбомов')),
        ),
        'unwanted' => array(
            'books' => TextHelper::number($books_unwanted, array('книгу', 'книги', 'книг')),
            'films' => TextHelper::number($films_unwanted, array('фильм', 'фильма', 'фильмов')),
            'games' => TextHelper::number($games_unwanted, array('игру', 'игры', 'игр')),
            'albums' => TextHelper::number($albums_unwanted, array('альбом', 'альбома', 'альбомов')),
        ),
    );
@endphp
<div class="card @include('card.class')">
    <div class="card-body">
        <p class="card-text">Зарегистрирован {!! LocalizedCarbon::instance($user->created_at)->diffForHumans() !!}</p>
        @if(!empty($city) && RolesHelper::isAdmin($request))<p>Предполагаемый город: {!! $city->name !!}</p>@endif
        @if(0 != $books_rated || 0 != $films_rated || 0 != $games_rated)
            <p class="card-text">
                Оценил
                @if(0 != $books_rated)
                    <a href="{!! $url['rates']['books'] !!}">{!! $numbers['rates']['books'] !!}</a>,
                @endif
                @if(0 != $films_rated)
                    <a href="{!! $url['rates']['films'] !!}">{!! $numbers['rates']['films'] !!}</a>,
                @endif
                @if(0 != $games_rated)
                    <a href="{!! $url['rates']['games'] !!}">{!! $numbers['rates']['games'] !!}</a>,
                @endif
                @if(0 != $albums_rated)
                    <a href="{!! $url['rates']['albums'] !!}">{!! $numbers['rates']['albums'] !!}</a>
                @endif
            </p>
        @endif
        @if(0 != $books_wanted || 0 != $films_wanted || 0 != $games_wanted || 0 != $albums_wanted)
            <p class="card-text">
                Хочет
                @if(0 != $books_wanted)
                    прочесть <a href="{!! $url['wanted']['books'] !!}">{!! $numbers['wanted']['books'] !!}</a>,
                @endif
                @if(0 != $films_wanted)
                    посмотреть <a href="{!! $url['wanted']['films'] !!}">{!! $numbers['wanted']['films'] !!}</a>,
                @endif
                @if(0 != $games_wanted)
                    сыграть в <a href="{!! $url['wanted']['games'] !!}">{!! $numbers['wanted']['games'] !!}</a>,
                @endif
                @if(0 != $albums_wanted)
                    слушать <a href="{!! $url['wanted']['albums'] !!}">{!! $numbers['wanted']['albums'] !!}</a>
                @endif
            </p>
        @endif
        @if(0 != $books_unwanted || 0 != $films_unwanted || 0 != $games_unwanted || 0 != $albums_unwanted)
            <p class="card-text">
                Хочет
                @if(0 != $books_unwanted)
                    прочесть <a href="{!! $url['unwanted']['books'] !!}">{!! $numbers['unwanted']['books'] !!}</a>,
                @endif
                @if(0 != $films_unwanted)
                    посмотреть <a href="{!! $url['unwanted']['films'] !!}">{!! $numbers['unwanted']['films'] !!}</a>,
                @endif
                @if(0 != $games_unwanted)
                    сыграть в <a href="{!! $url['unwanted']['games'] !!}">{!! $numbers['unwanted']['games'] !!}</a>,
                @endif
                @if(0 != $albums_unwanted)
                    слушать <a href="{!! $url['unwanted']['albums'] !!}">{!! $numbers['unwanted']['albums'] !!}</a>
                @endif
            </p>
        @endif
    </div>
</div>
