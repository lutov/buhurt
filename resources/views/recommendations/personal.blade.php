@extends('layouts.default')

@section('title')Рекомендации@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center">
		<h1 class="pt-5">@yield('title')</h1>
		<h2 class="pb-3">@yield('subtitle')</h2>
	</section>

	<script>

        function toggle_section(section, status) {

            if(true === status) {

                if('books' === section) {

                    $('#books_options input').removeAttr('disabled');
                    $('#books_options').show();

                    $('#films_options input').attr('disabled', 'disabled');
                    $('#films_options').hide();

                    $('#games_options input').attr('disabled', 'disabled');
                    $('#games_options').hide();

                }

                if('films' === section) {

                    $('#films_options input').removeAttr('disabled');
                    $('#films_options').show();

                    $('#books_options input').attr('disabled', 'disabled');
                    $('#books_options').hide();

                    $('#games_options input').attr('disabled', 'disabled');
                    $('#games_options').hide();

                }

                if('games' === section) {

                    $('#games_options input').removeAttr('disabled');
                    $('#games_options').show();

                    $('#films_options input').attr('disabled', 'disabled');
                    $('#films_options').hide();

                    $('#books_options input').attr('disabled', 'disabled');
                    $('#books_options').hide();

                }

            }

        }

        $(document).ready(function() {

            var years_interval = $('#years_interval');
            years_interval.ionRangeSlider({
                min: 1890,
                max: {!! $options['years']['max'] !!},
				step: 10,
				type: 'double',
                prettify_enabled: false,
                grid: true,
                from: {!! $options['years']['from'] !!},
				to: {!! $options['years']['to'] !!}
            });

            var rates_interval = $('#rates_interval');
            rates_interval.ionRangeSlider({
                min: 1,
                max: 10,
				step: 1,
                type: 'double',
                prettify_enabled: false,
                grid: true,
                grid_snap: true,
                from: {!! $options['rates']['from'] !!},
				to: {!! $options['rates']['to'] !!}
            });

            var rec_num_interval = $('#rec_num_interval');
            rec_num_interval.ionRangeSlider({
                min: 3,
                max: 30,
                step: 3,
                prettify_enabled: false,
                grid: true,
                grid_snap: true,
                from: {!! $options['limit'] !!}
            });

            toggle_section('{!! $section !!}', true);

        } );

	</script>

	<form method="post" action="<?=$_SERVER['REQUEST_URI']?>" class="form_tabs">

	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#filter-1" role="tab">Общие настройки</a>
		</li>
		<!--li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#filter-2" role="tab">Тип рекомендаций</a>
		</li-->
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#filter-3" role="tab">Точные настройки</a>
		</li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content p-4 border border-top-0">

		<div class="tab-pane active" id="filter-1" role="tabpanel">

			<div class="form-row">

			<fieldset class="col-md-12">

				<legend class="col-form-legend">Раздел</legend>
				<div class="custom-control custom-radio custom-control-inline" onclick="toggle_section('books', $('#element_type_books').prop('checked'));">
					<input type="radio" id="element_type_books" name="element_type" value="books" class="custom-control-input" autocomplete="off" @if('books' == $section) checked @endif>
					<label class="custom-control-label" for="element_type_books">Книги</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline" onclick="toggle_section('films', $('#element_type_films').prop('checked'));">
					<input type="radio" id="element_type_films" name="element_type" value="films" class="custom-control-input" autocomplete="off" @if('films' == $section) checked @endif>
					<label class="custom-control-label" for="element_type_films">Фильмы</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline" onclick="toggle_section('games', $('#element_type_games').prop('checked'));">
					<input type="radio" id="element_type_games" name="element_type" value="games" class="custom-control-input" autocomplete="off" @if('games' == $section) checked @endif>
					<label class="custom-control-label" for="element_type_games">Игры</label>
				</div>

				<div class="mt-4">
					<button class="btn btn-outline-secondary" type="button" data-toggle="collapse" data-target="#collapseDetails" aria-expanded="false" aria-controls="collapseDetails">
						Подробнее
					</button>
				</div>

				<div class="collapse" id="collapseDetails">

					<!--legend class="col-form-legend mt-4">Рейтинги</legend>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="ratings_high" name="ratings" value="high" class="custom-control-input" autocomplete="off">
						<label class="custom-control-label" for="ratings_high">Высокие</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="ratings_any" name="ratings" value="any" class="custom-control-input" autocomplete="off" checked>
						<label class="custom-control-label" for="ratings_any">Любые</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="ratings_low" name="ratings" value="low" class="custom-control-input" autocomplete="off">
						<label class="custom-control-label" for="ratings_low">Низкие</label>
					</div>

					<legend class="col-form-legend mt-4">Количество оценок</legend>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="rates_count_high" name="rates_count" value="high" class="custom-control-input" autocomplete="off">
						<label class="custom-control-label" for="rates_count_high">Много</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="rates_count_any" name="rates_count" value="any" class="custom-control-input" autocomplete="off" checked>
						<label class="custom-control-label" for="rates_count_any">Все равно</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="rates_count_low" name="rates_count" value="low" class="custom-control-input" autocomplete="off">
						<label class="custom-control-label" for="rates_count_low">Мало</label>
					</div-->

					<legend class="col-form-legend mt-4">Принцип рекомендации</legend>
					<div class="custom-control custom-radio">
						<input type="radio" id="liked_genres" name="recommendation_principle" value="liked_genres" class="custom-control-input" autocomplete="off" @if('liked_genres' == $principle) checked @endif>
						<label class="custom-control-label" for="liked_genres">В жанрах, которые я хорошо оцениваю</label>
					</div>
					<div class="custom-control custom-radio">
						<input type="radio" id="faved_genres" name="recommendation_principle" value="faved_genres" class="custom-control-input" autocomplete="off" @if('faved_genres' == $principle) checked @endif>
						<label class="custom-control-label" for="faved_genres">В жанрах, которые я часто оцениваю</label>
					</div>
					<!--div class="custom-control custom-radio">
						<input type="radio" id="more_of_the_same" name="recommendation_principle" value="more_of_the_same" class="custom-control-input" autocomplete="off">
						<label class="custom-control-label" for="more_of_the_same">Еще не оцененные произведения высоко оцененных авторов</label>
					</div>
					<div class="custom-control custom-radio">
						<input type="radio" id="similar_users" name="recommendation_principle" value="similar_users" class="custom-control-input" autocomplete="off">
						<label class="custom-control-label" for="similar_users">Понравившееся пользователям с похожими оценками</label>
					</div-->

					<legend class="col-form-legend mt-4">Релевантные оценки</legend>
					<div><input name="rates" id="rates_interval"></div>

					<legend class="col-form-legend mt-4">Годы выпуска</legend>
					<div><input name="years" id="years_interval"></div>

					<legend class="col-form-legend mt-4">Количество рекомендаций</legend>
					<div><input name="recommendations" id="rec_num_interval"></div>

					<legend class="col-form-legend mt-4">Исключения</legend>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" name="include_wanted" value="1" id="include_wanted" @if($options['include_wanted']) checked @endif>
						<label class="custom-control-label" for="include_wanted">Включать произведения из списка желаемого</label>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" name="include_not_wanted" value="1" id="include_not_wanted" @if($options['include_not_wanted']) checked @endif>
						<label class="custom-control-label" for="include_not_wanted">Включать произведения из списка нежелаемого</label>
					</div>

				</div>

			</fieldset>

			</div>

		</div>

		<!--div class="tab-pane" id="filter-2" role="tabpanel">

			<div class="form-row">

				<fieldset class="col-md-12">

				</fieldset>

			</div>

		</div-->

		<div class="tab-pane" id="filter-3" role="tabpanel">

			<div class="form-row">

				<fieldset class="col-md-12" id="books_options">

					<legend class="col-form-legend">Издательства</legend>
					<?php
					$largest_publishers = '';
					foreach($forms['largest_publishers'] as $key => $publisher) {

						$checkbox_id = 'publisher'.$publisher->company_id;

						$largest_publishers .= '<div class="custom-control custom-checkbox">';
						$largest_publishers .= '<input type="checkbox" name="publisher[]" id="'.$checkbox_id.'" value="'.$publisher->company_id.'" class="custom-control-input" autocomplete="off" checked>';
						$largest_publishers .= '<label for="'.$checkbox_id.'" class="custom-control-label">'.$publisher->company_name.'</label>';
						$largest_publishers .= '</div>';

					}
					echo $largest_publishers;
					?>

				</fieldset>

				<fieldset class="col-md-12" id="films_options">

					<legend class="col-form-legend">Страны</legend>
					<?php
					$cinema_countries = '';
					foreach($forms['cinema_countries'] as $key => $country) {

						$checkbox_id = 'country'.$country->country_id;

						$cinema_countries .= '<div class="custom-control custom-checkbox">';
						$cinema_countries .= '<input type="checkbox" name="country[]" id="'.$checkbox_id.'" value="'.$country->country_id.'" class="custom-control-input" autocomplete="off" checked>';
						$cinema_countries .= '<label for="'.$checkbox_id.'" class="custom-control-label">'.$country->country_name.'</label>';
						$cinema_countries .= '</div>';

					}
					echo $cinema_countries;
					?>

				</fieldset>

				<fieldset class="col-md-12" id="games_options">

					<legend class="col-form-legend">Платформы</legend>
					<?php
					$top_platforms = '';
					foreach($forms['top_platforms'] as $key => $platform) {

						$checkbox_id = 'platform'.$platform->platform_id;

						$top_platforms .= '<div class="custom-control custom-checkbox">';
						$top_platforms .= '<input type="checkbox" name="platform[]" id="'.$checkbox_id.'" value="'.$platform->platform_id.'" class="custom-control-input" autocomplete="off" checked>';
						$top_platforms .= '<label for="'.$checkbox_id.'" class="custom-control-label">'.$platform->platform_name.'</label>';
						$top_platforms .= '</div>';

					}
					echo $top_platforms;
					?>

				</fieldset>

			</div>

		</div>

	</div>

	<div><input type="submit" class="btn btn-primary mt-4" value="Искать"></div>

	</form>

	@if(count($elements))
	<div class="row mt-5">

		<div class="col-md-12">
			<?php $options = array(
				'header' => true,
				'paginate' => false,
				'footer' => true,
			); ?>
			{!! ElementsHelper::getElements($request, $elements, $section, $options) !!}

		</div>

	</div>
	@endif

@stop