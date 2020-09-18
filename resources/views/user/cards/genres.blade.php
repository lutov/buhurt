<div class="card @include('card.class')">
    <div class="card-body">
        @if(count($fav_gens_books))
            <p class="card-text">
                Книги: {!! DatatypeHelper::arrayToString($fav_gens_books, ', ', '/genres/'); !!}
            </p>
        @endif
        @if(count($fav_gens_films))
            <p class="card-text">
                Фильмы: {!! DatatypeHelper::arrayToString($fav_gens_films, ', ', '/genres/'); !!}
            </p>
        @endif
        @if(count($fav_gens_games))
            <p class="card-text">
                Игры: {!! DatatypeHelper::arrayToString($fav_gens_games, ', ', '/genres/'); !!}
            </p>
        @endif
        @if(count($fav_gens_albums))
            <p class="card-text">
                Альбомы: {!! DatatypeHelper::arrayToString($fav_gens_albums, ', ', '/genres/'); !!}
            </p>
        @endif
    </div>
</div>
