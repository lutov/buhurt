@extends('layouts.default')

@section('title')
	{!! $element->name !!}
@stop

@section('subtitle')
	{!! $element->alt_name !!}
@stop

@section('keywords')фильм, {!! $element->name !!}, {!! $element->alt_name !!}, {!! $element->year !!}@stop
@section('description'){!! TextHelper::wordsLimit($element->description, 15) !!}@stop

@section('content')

	<div itemscope itemtype="http://schema.org/Movie">

		<h2 itemprop="alternativeHeadline">@yield('subtitle')</h2>
		<h1 itemprop="name">
			@yield('title')
            @if(RolesHelper::isAdmin($request))
                <p id="element_edit_button">
                    <a href="/admin/delete/films/{!! $element->id !!}" onclick="return window.confirm('Удалить фильм?');">
                        <img src="/data/img/design/delete2.svg" alt="Удалить" />
                    </a>
                </p>
				<p id="element_edit_button"><a href="/admin/edit/films/{!! $element->id !!}"><img src="/data/img/design/edit.svg" alt="Редактировать" /></a></p>
			@endif
		</h1>
		
        @if(!empty($rating))
            <p itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="text_rating">
                Средняя оценка: <b itemprop="ratingValue">{!! $rating['average'] !!}</b>
                ({!! TextHelper::number($rating['count'], array('голос', 'голоса', 'голосов')) !!})
            </p>
        @endif

		@if(Auth::check())
			<div class="rating">
				<input name="val" value="{!! $rate !!}" type="hidden">
				<input type="hidden" name="vote_id" value="{!! $section !!}/{!! $element->id !!}"/>
			</div>

			@if(0 != $rate) <a href="/rates/unrate/{!! $section !!}/{!! $element->id !!}" class="unrate" id="unrate_{!! $section !!}_{!! $element->id !!}" title="Удалить оценку"></a> @endif

			<span id="like" title="Хочу"
			@if(0 == $wanted) class="like" onclick="like('{!! $section !!}', '{!! $element->id !!}')" @else class="liked" onclick="unlike('{!! $section !!}', '{!! $element->id !!}')" @endif
					></span>
			<span id="dislike" title="Не хочу"
			@if(0 == $not_wanted) class="dislike"  onclick="dislike('{!! $section !!}', '{!! $element->id !!}')" @else class="disliked" onclick="undislike('{!! $section !!}', '{!! $element->id !!}')" @endif
					></span>
		@else
			{!! DummyHelper::regToRate() !!}
		@endif

		<div class="element_additional_info">

			<p>
				@if(!empty($element->year)) <a itemprop="datePublished" href="/years/{!! $section !!}/{!! $element->year !!}">{!! $element->year !!}</a> @endif
				@if(count($countries)) | {!! DatatypeHelper::arrayToString($countries, ', ', '/countries/films/') !!} @endif
				@if(!empty($element->length)) | <meta itemprop="duration" content="T{!! $element->length !!}M" />{!! $element->length !!} мин. @endif
				@if(count($genres)) | {!! DatatypeHelper::collectionToString($genres, 'genre', ', ', '/genres/films/', false, 'genre') !!} @endif
			</p>


			@if(count($directors))
			<p>
				Режиссер: {!! DatatypeHelper::arrayToString($directors, ', ', '/persons/', false, 'director') !!}
			</p>
			@endif

			@if(count($screenwriters))
			<p>
				Сценарий: {!! DatatypeHelper::arrayToString($screenwriters, ', ', '/persons/', false, 'creator') !!}
			</p>
			@endif

			@if(count($producers))
			<p>
				Продюсер: {!! DatatypeHelper::arrayToString($producers, ', ', '/persons/', false, 'producer') !!}
			</p>
			@endif

		</div>
		
		<div class="element_card">
			<div class="element_img">
				<img src="/data/img/covers/films/{!! $cover !!}.jpg" alt="{!! $element->name !!}" itemprop="image" />
			</div><!--
			--><div itemprop="description" class="element_description">

				{!! nl2br($element->description) !!}

				@if(count($actors))
					<p>
						В ролях: {!! DatatypeHelper::arrayToString($actors, ', ', '/persons/', false, 'actor') !!}
					</p>
				@endif
				
				@if(RolesHelper::isAdmin($request))
					{!! DummyHelper::getExtLink('rutracker', $element->name); !!}
				@endif

			</div>
		</div>

		@if(count($collections)) <p>Коллекции: {!! DatatypeHelper::collectionToString($collections, 'collection', ', ', '/collections/', false, "isPartOf") !!}</p> @endif
		
		@if(0 < $relations)
			<p><a href="{{$_SERVER['REQUEST_URI']}}/relations/">Связи ({!! $relations !!})</a></p>
		@else
			@if(RolesHelper::isAdmin($request))
				<p><a href="{{$_SERVER['REQUEST_URI']}}/relations/">Установить связи</a></p>
			@endif
		@endif

        @if(count($similar))

            <h3>Похожие</h3>
			<?php
			$options = array(
				'header' => true,
				'paginate' => false,
				'footer' => true,
			);
			?>
            {!! ElementsHelper::getElements($request, $similar, 'films', $options) !!}

        @endif
		
	</div>

	<div class="row mt-3">

		<div class="col-md-12">

			<h3>Комментарии</h3>

			{!! CommentsHelper::showCommentForm() !!}

			<div itemscope itemtype="http://schema.org/UserComments" class="comments">

				{!! CommentsHelper::showComments($comments) !!}

			</div>

		</div>

	</div>

	<script>

        $(document).ready(function() {

			@if(Auth::check())

            $('.main_rating').rating({

                //fx: 'full',
                //url: '/rates/rate',

                language: 'ru',
                theme: 'krajee-uni',
                //size: 'xs',
                emptyStar: '&#9734;',
                filledStar: '&#9733;',
                clearButton: '&#10008;',
                min: 0,
                max: 10,
                step: 1.0,
                stars: '10',
                animate: false,
                showCaption: false,
                showClear: false,
                //defaultCaption: 'Нет оценки',
                clearCaption: 'Нет оценки',
                starCaptions: {
                    1: 'Очень плохо',
                    2: 'Плохо',
                    3: 'Посредственно',
                    4: 'Ниже среднего',
                    5: 'Средне',
                    6: 'Выше среднего',
                    7: 'Неплохо',
                    8: 'Хорошо',
                    9: 'Отлично',
                    10: 'Великолепно'
                },
                starCaptionClasses: function (val) {
                    //console.log(val);
                    if (val === null) {
                        return 'badge badge-default';
                    } else if (val <= 3) {
                        return 'badge badge-danger';
                    } else if (val <= 5) {
                        return 'badge badge-warning';
                    } else if (val <= 7) {
                        return 'badge badge-primary';
                    } else {
                        return 'badge badge-success';
                    }
                }

                /*
                callback: function(responce){
                    //this.vote_success.fadeOut(2000);

                    $.post('/achievements', {}, function(data) {
                        //console.log(data);

                        show_popup(data);

                    }, 'json');
                }
                */

            });

            $('#comment_save').click(function(){
                comment_add('{!! $section !!}', '{!! $element->id !!}');
            });

			@endif

        });

	</script>
			
@stop