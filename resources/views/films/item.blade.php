@extends('layouts.default')

@section('title')
	{!! $film->name !!}
@stop

@section('subtitle')
	{!! $film->alt_name !!}
@stop

@section('keywords')фильм, {!! $film->name !!}, {!! $film->alt_name !!}, {!! $film->year !!}@stop
@section('description'){!! Helpers::words_limit($film->description, 15) !!}@stop

@section('content')

	<div itemscope itemtype="http://schema.org/Movie">

		<h2 itemprop="alternativeHeadline">@yield('subtitle')</h2>
		<h1 itemprop="name">
			@yield('title')
            @if(Helpers::is_admin())
                <p id="element_edit_button">
                    <a href="/admin/delete/films/{!! $film->id !!}" onclick="return window.confirm('Удалить фильм?');">
                        <img src="/data/img/design/delete2.svg" alt="Удалить" />
                    </a>
                </p>
				<p id="element_edit_button"><a href="/admin/edit/films/{!! $film->id !!}"><img src="/data/img/design/edit.svg" alt="Редактировать" /></a></p>
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
				<input type="hidden" name="vote_id" value="{!! $section !!}/{!! $film->id !!}"/>
			</div>

			@if(0 != $rate) <a href="/rates/unrate/{!! $section !!}/{!! $film->id !!}" class="unrate" id="unrate_{!! $section !!}_{!! $film->id !!}" title="Удалить оценку"></a> @endif

			<span id="like" title="Хочу"
			@if(0 == $wanted) class="like" onclick="like('{!! $section !!}', '{!! $film->id !!}')" @else class="liked" onclick="unlike('{!! $section !!}', '{!! $film->id !!}')" @endif
					></span>
			<span id="dislike" title="Не хочу"
			@if(0 == $not_wanted) class="dislike"  onclick="dislike('{!! $section !!}', '{!! $film->id !!}')" @else class="disliked" onclick="undislike('{!! $section !!}', '{!! $film->id !!}')" @endif
					></span>
		@else
			{!! Helpers::reg2rate() !!}
		@endif

		<div class="element_additional_info">

			<p>
				@if(!empty($film->year)) <a itemprop="datePublished" href="/years/{!! $section !!}/{!! $film->year !!}">{!! $film->year !!}</a> @endif
				@if(count($countries)) | {!! Helpers::array2string($countries, ', ', '/countries/films/') !!} @endif
				@if(!empty($film->length)) | <meta itemprop="duration" content="T{!! $film->length !!}M" />{!! $film->length !!} мин. @endif
				@if(count($genres)) | {!! Helpers::collection2string($genres, 'genre', ', ', '/genres/films/', false, 'genre') !!} @endif
			</p>


			@if(count($directors))
			<p>
				Режиссер: {!! Helpers::array2string($directors, ', ', '/persons/', false, 'director') !!}
			</p>
			@endif

			@if(count($screenwriters))
			<p>
				Сценарий: {!! Helpers::array2string($screenwriters, ', ', '/persons/', false, 'creator') !!}
			</p>
			@endif

			@if(count($producers))
			<p>
				Продюсер: {!! Helpers::array2string($producers, ', ', '/persons/', false, 'producer') !!}
			</p>
			@endif

		</div>
		
		<div class="element_card">
			<div class="element_img">
				<img src="/data/img/covers/films/{!! $cover !!}.jpg" alt="{!! $film->name !!}" itemprop="image" />
			</div><!--
			--><div itemprop="description" class="element_description">

				{!! nl2br($film->description) !!}

				@if(count($actors))
					<p>
						В ролях: {!! Helpers::array2string($actors, ', ', '/persons/', false, 'actor') !!}
					</p>
				@endif
				
				@if(Helpers::is_admin())
					{!! Helpers::get_ext_link('rutracker', $film->name); !!}
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

            <?php
                // die(print_r($similar, true));
                /*
                foreach($similar as $key => $value) {

                    echo Helpers::get_element((object) $value, 'films');

                }
                */
            ?>

            {!! Helpers::get_elements($similar, 'films', [], false) !!}

        @endif
		
	</div>

			<h3>Комментарии</h3>

            {!! Helpers::show_comment_form() !!}
			
			<div itemscope itemtype="http://schema.org/UserComments" class="comments">

				{!! Helpers::show_comments($comments) !!}
			
			</div>

	<script>
		$('#comment_save').click(function(){
			comment_add('{!! $section !!}', '{!! $film->id !!}');
		});
	</script>
			
@stop