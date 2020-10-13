<div class="card @include('card.class')">
    <form action="/admin/save/" class="add_{{ $section->alt_name }}" method="POST" enctype="multipart/form-data">
        <div class="card-header">
            <h1 class="card-title">@yield('title')</h1>
            <h2 class="card-subtitle mb-2 text-muted">@yield('subtitle')</h2>
            <input type="hidden" name="action" value="edit" />
            <input type="hidden" name="section" value="{{ $section->alt_name }}" />
            <input type="hidden" name="element_id" value="{{ $element->id }}" />
        </div>
        <div class="card-body">

            <p><input name="name" value="{{ $element->name }}" placeholder="Название" id="name" class="form-control w-100" /></p>

            @if($element->has_alt_name)
                <p><input name="alt_name" value="{{ implode('; ', $element->alt_name) }}" placeholder="Альтернативное или оригинальное название" id="alt_name" class="form-control w-100" /></p>
            @endif

            @if(method_exists($element, 'bands'))
                <p><input name="bands" value="{{ $bands }}" placeholder="Авторы и исполнители" class="form-control w-100" id="bands" /></p>
            @endif

            @if(method_exists($element, 'tracks'))
                <ol id="tracks">
                    @php
                        /** @var $element */
                        $track_list = $element->tracks()->orderBy('order')->get();
                    @endphp
                    @foreach($track_list as $key => $track)
                        <li><input type="text" class="form-control w-100 mb-3" name="tracks[]" placeholder="Трек" value="{!! $track->name !!}"/></li>
                    @endforeach
                </ol>
                <p><input type="button" class="btn btn-secondary" value="Добавить трек" onclick="add_track()"></p>
            @endif

            @if(method_exists($element, 'writers'))
                <p>
                    <input name="writers" value="{{ $writers }}" placeholder="Автор" id="writers" class="form-control w-100" />
                </p>
            @endif

            @if(method_exists($element, 'books_publishers'))
                <p>
                    <input name="books_publishers" value="{{ $books_publishers }}" placeholder="Издатель" id="books_publishers" class="form-control w-100" />
                </p>
            @endif

            @if(method_exists($element, 'directors'))
                <p>
                    <input name="directors" value="{{ $directors }}" placeholder="Режиссер" id="directors" class="form-control w-100" />
                </p>
            @endif

            @if(method_exists($element, 'screenwriters'))
                <p>
                    <input name="screenwriters" value="{{ $screenwriters }}" placeholder="Сценарист" id="screenwriters" class="form-control w-100" />
                </p>
            @endif

            @if(method_exists($element, 'producers'))
                <p>
                    <input name="producers" value="{{ $producers }}" placeholder="Продюсер" id="producers" class="form-control w-100" />
                </p>
            @endif

            @if(method_exists($element, 'developers'))
                <p>
                    <input name="developers" value="{{ $developers }}" placeholder="Разработчик" id="developers" class="form-control w-100" />
                </p>
            @endif

            @if(method_exists($element, 'games_publishers'))
                <p>
                    <input name="games_publishers" value="{{ $games_publishers }}" placeholder="Издатель" id="games_publishers" class="form-control w-100" />
                </p>
            @endif

            @if($element->has_description)
                <p><textarea name="description" placeholder="Описание" class="form-control w-100" id="description" style="height: 10rem;">{!! $element->description !!}</textarea></p>
            @endif

            @if(method_exists($element, 'genres'))
                <p><input name="genres" value="{{ $genres }}" placeholder="Жанр" class="form-control w-100" id="genres" /></p>
            @endif

            @if(method_exists($element, 'countries'))
                <p>
                    <input name="countries" value="{{ $countries }}" placeholder="Страна производства" id="countries" class="form-control w-100" />
                </p>
            @endif

            @if(method_exists($element, 'platforms'))
                <p>
                    <input name="platforms" value="{{ $platforms }}" placeholder="Платформа" id="platforms" class="form-control w-100" />
                </p>
            @endif

            @if($element->has_length)
                <p><input name="length" value="{{ $element->length }}" placeholder="Продолжительность" class="form-control w-25" /></p>
            @endif

            @if($element->has_year)
                <p><input name="year" value="{{ $element->year }}" placeholder="Год выпуска" class="form-control w-25" /></p>
            @endif

            @if(method_exists($element, 'actors'))
                <p>
                    <input name="actors" value="{{ $actors }}" placeholder="Актеры" id="actors" class="form-control w-100" />
                </p>
            @endif

            @if(method_exists($element, 'collections'))
                <p><input name="collections" value="{{ $collections }}" placeholder="Коллекции" class="form-control w-100" id="collections" /></p>
            @endif

            <b>Обложка</b> <input type="file" class="form-control-file" name="cover" id="cover">
        </div>
        <div class="card-footer">
            <input type="submit" value="Сохранить" id="save" class="btn btn-sm btn-secondary" />
        </div>
    </form>
</div>
