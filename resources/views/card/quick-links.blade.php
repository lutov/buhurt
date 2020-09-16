<div class="row quick-links pb-4">
    <div class="col-4 col-md-4">
        <div class="card @include('card.class') small">
            <div class="card-header"><a href="/q_add/books/?new_name={!! urlencode($query) !!}">Добавить книгу</a></div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <a href="/q_add/books/?new_name={!! urlencode($query) !!}&template=fiction_book">
                        Фантастика и фэнтези
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/books/?new_name={!! urlencode($query) !!}&template=action_book">
                        Детективы и боевики
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/books/?new_name={!! urlencode($query) !!}&template=adventure_book">
                        Приключения и исторический роман
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/books/?new_name={!! urlencode($query) !!}&template=lovestory_book">
                        Любовный роман
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/books/?new_name={!! urlencode($query) !!}&template=novel_book">
                        Современная проза
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/books/?new_name={!! urlencode($query) !!}&template=nonfiction_book">
                        Публицистика и нон-фикшен
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/books/?new_name={!! urlencode($query) !!}&template=poetry_book">
                        Поэзия
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/books/?new_name={!! urlencode($query) !!}&template=comic_book">
                        Комиксы и манга
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/books/?new_name={!! urlencode($query) !!}&template=marvel_book">
                        Marvel Comics
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/books/?new_name={!! urlencode($query) !!}&template=dc_book">
                        DC Comics
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/books/?new_name={!! urlencode($query) !!}&template=image_book">
                        Image Comics
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/books/?new_name={!! urlencode($query) !!}&template=valiant_book">
                        Valiant Comics
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-4 col-md-4">
        <div class="card @include('card.class') small">
            <div class="card-header"><a href="/q_add/films/?new_name={!! urlencode($query) !!}">Добавить фильм</a></div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <a href="/q_add/films/?new_name={!! urlencode($query) !!}&template=fiction_film">
                        Фантастика
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/films/?new_name={!! urlencode($query) !!}&template=marvel_film">
                        Marvel Comics
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/films/?new_name={!! urlencode($query) !!}&template=dc_film">
                        DC Comics
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/films/?new_name={!! urlencode($query) !!}&template=fantasy_film">
                        Фэнтези
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/films/?new_name={!! urlencode($query) !!}&template=drama_film">
                        Драмы
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/films/?new_name={!! urlencode($query) !!}&template=melodrama_film">
                        Мелодрамы
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/films/?new_name={!! urlencode($query) !!}&template=comedy_film">
                        Комедии
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/films/?new_name={!! urlencode($query) !!}&template=family_film">
                        Семейные
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/films/?new_name={!! urlencode($query) !!}&template=adventure_film">
                        Приключения
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/films/?new_name={!! urlencode($query) !!}&template=detective_film">
                        Детективы
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/films/?new_name={!! urlencode($query) !!}&template=action_film">
                        Экшены
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/films/?new_name={!! urlencode($query) !!}&template=thriller_film">
                        Триллеры
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/films/?new_name={!! urlencode($query) !!}&template=horror_film">
                        Ужасы
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/films/?new_name={!! urlencode($query) !!}&template=series_film">
                        Сериалы
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/films/?new_name={!! urlencode($query) !!}&template=animated_film">
                        Мультфильмы
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/films/?new_name={!! urlencode($query) !!}&template=anime">
                        Аниме
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-4 col-md-4">
        <div class="card @include('card.class') small">
            <div class="card-header"><a href="/q_add/games/?new_name={!! urlencode($query) !!}">Добавить игру</a></div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <a href="/q_add/games/?new_name={!! urlencode($query) !!}&template=action_game">
                        Экшен
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/games/?new_name={!! urlencode($query) !!}&template=roleplay_game">
                        Ролевые игры
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/games/?new_name={!! urlencode($query) !!}&template=strategy_game">
                        Стратегии
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/games/?new_name={!! urlencode($query) !!}&template=quest_game">
                        Приключения
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/games/?new_name={!! urlencode($query) !!}&template=arcade_game">
                        Аркады
                    </a>
                </li>
            </ul>
        </div>
        <div class="card @include('card.class') small mt-4">
            <div class="card-header"><a href="/q_add/albums/?new_name={!! urlencode($query) !!}">Добавить альбом</a></div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <a href="/q_add/albums/?new_name={!! urlencode($query) !!}&template=rock_album">
                        Рок
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/albums/?new_name={!! urlencode($query) !!}&template=rap_album">
                        Рэп
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/albums/?new_name={!! urlencode($query) !!}&template=pop_album">
                        Поп-музыка
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/q_add/albums/?new_name={!! urlencode($query) !!}&template=electronic_album">
                        Электроника
                    </a>
                </li>
            </ul>
        </div>
        @if($isAdmin)
            <div class="card @include('card.class') mt-4">
                <div class="card-header"><a href="/q_add/memes/?new_name={!! urlencode($query) !!}">Добавить мем</a></div>
            </div>
        @endif
    </div>
</div>
