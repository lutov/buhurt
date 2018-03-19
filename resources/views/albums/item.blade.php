@extends('layouts.default')

@section('title'){!! $album->name !!}@stop

@section('subtitle'){!! $album->alt_name !!}@stop

@section('keywords')альбом, {!! $album->name !!}, {!! $album->year !!}@stop
@section('description'){!! Helpers::words_limit($album->description, 15) !!}@stop

@section('content')

	<div itemscope itemtype="http://schema.org/MusicAlbum">

		<h2 itemprop="alternativeHeadline">@yield('subtitle')</h2>
		<h1 itemprop="name">
			@yield('title')
            @if(Helpers::is_admin())
                <p id="element_edit_button">
                    <a href="/admin/delete/albums/{!! $album->id !!}" onclick="return window.confirm('Удалить альбом?');">
                        <img src="/data/img/design/delete2.svg" alt="Удалить" />
                    </a>
                </p>
				<p id="element_edit_button"><a href="/admin/edit/albums/{!! $album->id !!}"><img src="/data/img/design/edit.svg" alt="Редактировать" /></a></p>
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
				<input type="hidden" name="vote_id" value="{!! $section !!}/{!! $album->id !!}"/>
			</div>

			@if(0 != $rate) <a href="/rates/unrate/{!! $section !!}/{!! $album->id !!}" class="unrate" id="unrate_{!! $section !!}_{!! $album->id !!}" title="Удалить оценку"></a> @endif

			<span id="like" title="Хочу"
			@if(0 == $wanted) class="like" onclick="like('{!! $section !!}', '{!! $album->id !!}')" @else class="liked" onclick="unlike('{!! $section !!}', '{!! $album->id !!}')" @endif
					></span>
			<span id="dislike" title="Не хочу"
			@if(0 == $not_wanted) class="dislike"  onclick="dislike('{!! $section !!}', '{!! $album->id !!}')" @else class="disliked" onclick="undislike('{!! $section !!}', '{!! $album->id !!}')" @endif
					></span>
		@else
			{!! Helpers::reg2rate() !!}
		@endif

		<div class="element_additional_info">

			<p>
				@if(!empty($album->year)) <a itemprop="datePublished" href="/years/{!! $section !!}/{!! $album->year !!}">{!! $album->year !!}</a> @endif
				@if(count($genres)) | {!! Helpers::collection2string($genres, 'genre', ', ', '/genres/albums/', false, 'genre') !!} @endif
			</p>

			@if(count($bands))
			<p>
				Исполнители: {!! Helpers::array2string($bands, ', ', '/bands/') !!}
			</p>
			@endif

		</div>
		
		<div class="element_card">
			<div class="element_img">
				<img itemprop="image" src="/data/img/covers/albums/{!! $cover !!}.jpg" alt="{!! $album->name !!}" />
			</div><!--
			--><div itemprop="description" class="element_description">

				<?php /*nl2br($album->description)*/ ?>

                @if(count($tracks))
                <ol>
                    <li>{!! Helpers::object2js_array($tracks, '</li><li>', true) !!}</li>
                </ol>
                @endif

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

            {!! Helpers::get_elements($similar, 'albums', [], false) !!}

        @endif

	</div>

			<h3>Комментарии</h3>

            {!! Helpers::show_comment_form() !!}

			<div itemscope itemtype="http://schema.org/UserComments" class="comments">

				{!! Helpers::show_comments($comments) !!}
			
			</div>
			
	<script>
		$('#comment_save').click(function(){
			comment_add('{!! $section !!}', '{!! $album->id !!}');
		});
	</script>

@stop