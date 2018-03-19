@extends('layouts.default')

@section('title')
	{!! $book->name !!}
@stop

@section('subtitle')
	{!! Helpers::array2string($writers, ', ', '/persons/', false, 'author') !!}
@stop

@section('keywords')книга, {!! $book->name !!}, {!! $book->alt_name !!}, {!! $book->year !!}@stop
@section('description'){!! Helpers::words_limit($book->description, 15) !!}@stop

@section('content')

	<div itemscope itemtype="http://schema.org/Book">

		<h2>@yield('subtitle')</h2>
		@if (!empty($book->alt_name)) <h2 itemprop="alternativeHeadline">{!! $book->alt_name !!}</h2> @endif
		<h1 itemprop="name">
			@yield('title')
			@if(Helpers::is_admin())
                <p id="element_edit_button">
                    <a href="/admin/delete/books/{!! $book->id !!}" onclick="return window.confirm('Удалить книгу?');">
                        <img src="/data/img/design/delete2.svg" alt="Удалить" />
                    </a>
                </p>
				<p id="element_edit_button"><a href="/admin/edit/books/{!! $book->id !!}"><img src="/data/img/design/edit.svg" alt="Редактировать" /></a></p>
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
				<input type="hidden" name="vote_id" value="{!! $section !!}/{!! $book->id !!}"/>
			</div>

			@if(0 != $rate) <a href="/rates/unrate/{!! $section !!}/{!! $book->id !!}" class="unrate" id="unrate_{!! $section !!}_{!! $book->id !!}" title="Удалить оценку"></a> @endif

			<span id="like" title="Хочу"
			@if(0 == $wanted) class="like" onclick="like('{!! $section !!}', '{!! $book->id !!}')" @else class="liked" onclick="unlike('{!! $section !!}', '{!! $book->id !!}')" @endif
					></span>
			<span id="dislike" title="Не хочу"
			@if(0 == $not_wanted) class="dislike"  onclick="dislike('{!! $section !!}', '{!! $book->id !!}')" @else class="disliked" onclick="undislike('{!! $section !!}', '{!! $book->id !!}')" @endif
					></span>
		@else
			{!! Helpers::reg2rate() !!}
		@endif

		<div class="element_additional_info">

			<p>
				@if(count($publishers)){!! Helpers::array2string($publishers, ', ', '/companies/', false, 'publisher') !!} | @endif
				@if(!empty($book->year)) <a itemprop="datePublished" href="/years/{!! $section !!}/{!! $book->year !!}">{!! $book->year !!}</a> @endif
				@if(count($genres)) | {!! Helpers::collection2string($genres, 'genre', ', ', '/genres/books/', false, 'genre') !!} @endif
			</p>

		</div>

		<div class="element_card">
			<div class="element_img">
				<img itemprop="image" src="/data/img/covers/books/{!! $cover !!}.jpg" alt="{!! $book->name !!}" />
			</div><!--
			--><div itemprop="description" class="element_description">

				{!! nl2br($book->description) !!}

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

            {!! Helpers::get_elements($similar, 'books', [], false) !!}

        @endif
	
	</div>

			<h3>Комментарии</h3>
			
            {!! Helpers::show_comment_form() !!}

			<div itemscope itemtype="http://schema.org/UserComments" class="comments">

				{!! Helpers::show_comments($comments) !!}
			
			</div>
			
	<script>
		$('#comment_save').click(function(){
			comment_add('{!! $section !!}', '{!! $book->id !!}');
		});
	</script>

@stop