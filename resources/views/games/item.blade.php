@extends('layouts.default')

@section('title'){!! $game->name !!}@stop

@section('subtitle'){!! $game->alt_name !!}@stop

@section('keywords')игра, {!! $game->name !!}, {!! $game->alt_name !!}, {!! $game->year !!}@stop
@section('description'){!! Helpers::words_limit($game->description, 15) !!}@stop

@section('content')

	<div itemscope itemtype="http://schema.org/Game">

		<h2 itemprop="alternativeHeadline">@yield('subtitle')</h2>
		<h1 itemprop="name">
			@yield('title')
            @if(Helpers::is_admin())
                <p id="element_edit_button">
                    <a href="/admin/delete/games/{!! $game->id !!}" onclick="return window.confirm('Удалить игру?');">
                        <img src="/data/img/design/delete2.svg" alt="Удалить" />
                    </a>
                </p>
				<p id="element_edit_button"><a href="/admin/edit/games/{!! $game->id !!}"><img src="/data/img/design/edit.svg" alt="Редактировать" /></a></p>
			@endif
		</h1>

        @if(!empty($rating))
            <p itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="text_rating">
                Средняя оценка: <b itemprop="ratingValue">{!! $rating['average'] !!}</b>
                ({!! Helpers::number($rating['count'], array('голос', 'голоса', 'голосов')) !!})
            </p>
        @endif

		@if(Auth::check())
			<div class="rating">
				<input name="val" value="{!! $rate !!}" type="hidden">
				<input type="hidden" name="vote_id" value="{!! $section !!}/{!! $game->id !!}"/>
			</div>

			@if(0 != $rate) <a href="/rates/unrate/{!! $section !!}/{!! $game->id !!}" class="unrate" id="unrate_{!! $section !!}_{!! $game->id !!}" title="Удалить оценку"></a> @endif

			<span id="like" title="Хочу"
			@if(0 == $wanted) class="like" onclick="like('{!! $section !!}', '{!! $game->id !!}')" @else class="liked" onclick="unlike('{!! $section !!}', '{!! $game->id !!}')" @endif
					></span>
			<span id="dislike" title="Не хочу"
			@if(0 == $not_wanted) class="dislike"  onclick="dislike('{!! $section !!}', '{!! $game->id !!}')" @else class="disliked" onclick="undislike('{!! $section !!}', '{!! $game->id !!}')" @endif
					></span>
		@else
			{!! Helpers::reg2rate() !!}
		@endif

		<div class="element_additional_info">

			<p>
				@if(count($developers)){!! Helpers::array2string($developers, ', ', '/companies/', false, 'creator') !!}@endif
				@if(count($publishers)) | {!! Helpers::array2string($publishers, ', ', '/companies/', false, 'publisher') !!}@endif
			</p>

			<p>
				@if(!empty($game->year)) <a itemprop="datePublished" href="/years/{!! $section !!}/{!! $game->year !!}">{!! $game->year !!}</a> @endif
				@if(count($genres)) | {!! Helpers::collection2string($genres, 'genre', ', ', '/genres/games/', false, 'genre') !!} @endif
			</p>

			@if(count($platforms))
			<p>
				Платформы: {!! Helpers::array2string($platforms, ', ', '/platforms/games/') !!}
			</p>
			@endif

		</div>
		
		<div class="element_card">
			<div class="element_img">
				<img itemprop="image" src="/data/img/covers/games/{!! $cover !!}.jpg" alt="{!! $game->name !!}" />
			</div><!--
			--><div itemprop="description" class="element_description">

				{!! nl2br($game->description) !!}

			</div>
		</div>

		@if(count($collections)) <p>Коллекции: {!! Helpers::collection2string($collections, 'collection', ', ', '/collections/', false, "isPartOf") !!}</p> @endif
		
		@if(0 < $relations)
			<p><a href="{{$_SERVER['REQUEST_URI']}}/relations/">Связи ({!! $relations !!})</a></p>
		@else
			@if(Helpers::is_admin())
				<p><a href="{{$_SERVER['REQUEST_URI']}}/relations/">Установить связи</a></p>
			@endif
		@endif

        @if(count($similar))

            <h3>Похожие</h3>

            {!! Helpers::get_elements($similar, 'games', [], false) !!}

        @endif

	</div>

			<h3>Комментарии</h3>

            {!! Helpers::show_comment_form() !!}

			<div itemscope itemtype="http://schema.org/UserComments" class="comments">

				{!! Helpers::show_comments($comments) !!}
			
			</div>
			
	<script>
		$('#comment_save').click(function(){
			comment_add('{!! $section !!}', '{!! $game->id !!}');
		});
	</script>

@stop