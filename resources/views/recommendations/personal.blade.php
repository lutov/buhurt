@extends('layouts.default')

@section('title')Рекомендации@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center">
		<h1 class="pt-5">@yield('title')</h1>
		<h2 class="pb-3">@yield('subtitle')</h2>
	</section>

	<script>

        $( function() {

            /*
            $( ".radio input" ).checkboxradio({
                icon: false
            });

            $( ".checkbox input" ).checkboxradio({
                icon: false
            });
            */

            //$( "fieldset" ).controlgroup();

            $( ".form_tabs" ).tabs();

            var years_val = $('#years_val');
            var years_interval = $('#years_interval');
            years_interval.slider({
                range: true,
                min: 1960,
                max: 2018,
                values: [ 2000, 2018 ],
                slide: function( event, ui ) {
                    years_val.val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
                }
            });
            years_val.val( "" + years_interval.slider( "values", 0 ) + " - " + years_interval.slider( "values", 1 ) );

            var rates_val = $('#rates_val');
            var rates_interval = $('#rates_interval');
            rates_interval.slider({
                range: true,
                min: 1,
                max: 10,
                values: [ 7, 10 ],
                slide: function( event, ui ) {
                    rates_val.val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
                }
            });
            rates_val.val( "" + rates_interval.slider( "values", 0 ) + " - " + rates_interval.slider( "values", 1 ) );

            var rec_num_val = $('#rec_num_val');
            var rec_num_interval = $('#rec_num_interval');
            rec_num_interval.slider({
                value:15,
                min: 3,
                max: 30,
                step: 3,
                slide: function( event, ui ) {
                    rec_num_val.val( "" + ui.value );
                }
            });
            rec_num_val.val( "" + rec_num_interval.slider( "value" ) );

        } );

	</script>

	<form method="post" action="<?=$_SERVER['REQUEST_URI']?>" class="form_tabs">

		<ul>
			<li><a href="#filter-1">Первый фильтр</a></li>
			<li><a href="#filter-2">Второй фильтр</a></li>
			<li><a href="#filter-3">Третий фильтр</a></li>
		</ul>

		<fieldset id="filter-1">

			<legend>Общие настройки</legend>

			<div class="radio">

				<span>Раздел</span>
				<p>
					<input type="radio" name="element_type" value="books" id="element_type_books">
					<label for="element_type_books">Книги</label>

					<input type="radio" name="element_type" value="films" id="element_type_films" checked>
					<label for="element_type_films">Фильмы</label>

					<input type="radio" name="element_type" value="games" id="element_type_games">
					<label for="element_type_games">Игры</label>
				</p>

			</div>

			<div class="radio">

				<span>Рейтинги</span>
				<p>
					<input type="radio" name="ratings" value="high" id="ratings_high">
					<label for="ratings_high">Высокие</label>

					<input type="radio" name="ratings" value="any" id="ratings_any" checked>
					<label for="ratings_any">Любые</label>

					<input type="radio" name="ratings" value="low" id="ratings_low">
					<label for="ratings_low">Низкие</label>
				</p>

			</div>

			<div class="radio">

				<span>Количество оценок</span>
				<p>
					<input type="radio" name="rates_count" value="high" id="rates_count_high">
					<label for="rates_count_high">Много</label>

					<input type="radio" name="rates_count" value="any" id="rates_count_any" checked>
					<label for="rates_count_any">Все равно</label>

					<input type="radio" name="rates_count" value="low" id="rates_count_low">
					<label for="rates_count_low">Мало</label>
				</p>

			</div>

			<div>

				<p>
					<label for="years_val">Годы выпуска:</label>
					<input type="text" name="years" id="years_val" readonly style="border:0; margin: 0;">
				</p>

				<div id="years_interval"></div>

			</div>

			<div>

				<p>
					<input type="checkbox" name="include_wanted" value="1" id="include_wanted">
					<label for="include_wanted">Включать произведения из списка желаемого</label>
				</p>

			</div>

			<div>

				<p>
					<input type="checkbox" name="include_not_wanted" value="1" id="include_not_wanted">
					<label for="include_not_wanted">Включать произведения из списка нежелаемого</label>
				</p>

			</div>

		</fieldset>

		<fieldset id="filter-2">

			<legend>Тип рекомендаций</legend>

			<div class="radio">

				<span>Принцип рекомендации</span>
				<p>
					<input type="radio" name="recommendation_principle" value="liked_genres" id="liked_genres" checked>
					<label for="liked_genres">В жанрах, которые я хорошо оцениваю</label>
				</p>
				<p>
					<input type="radio" name="recommendation_principle" value="faved_genres" id="faved_genres">
					<label for="faved_genres">В жанрах, которые я часто оцениваю</label>
				</p>
				<p>
					<input type="radio" name="recommendation_principle" value="more_of_the_same" id="more_of_the_same">
					<label for="more_of_the_same">Еще не оцененные произведения высоко оцененных авторов</label>
				</p>
				<p>
					<input type="radio" name="recommendation_principle" value="similar_users" id="similar_users">
					<label for="similar_users">Понравившееся пользователям с похожими оценками</label>
				</p>

			</div>

			<div>

				<p>
					<label for="rates_val">Релевантные оценки:</label>
					<input type="text" name="rates" id="rates_val" readonly style="border:0; margin: 0;">
				</p>

				<div id="rates_interval"></div>

			</div>

		</fieldset>

		<fieldset id="filter-3">

			<legend>Точные настройки</legend>

			<div id="books_options">

				<div class="checkbox">

					<span>Издательства</span>

					<p>
						<?php

						//echo DebugHelper::dump($forms);

						$largest_publishers = '';

						foreach($forms['largest_publishers'] as $key => $publisher) {

							$checkbox_id = 'publisher'.$publisher->company_id;
							$largest_publishers .= '<input type="checkbox" name="publishers[]" id="'.$checkbox_id.'" checked>';
							$largest_publishers .= '&nbsp;';
							$largest_publishers .= '<label for="'.$checkbox_id.'">'.$publisher->company_name.'</label>';
							$largest_publishers .= '<br/>';

						}

						echo $largest_publishers;

						?>
					</p>

				</div>

			</div>

			<div id="films_options">

				<div class="checkbox">

					<span>Страны</span>
					<p>
						<?php

						$cinema_countries = '';

						foreach($forms['cinema_countries'] as $key => $country) {

							$checkbox_id = 'country'.$country->country_id;
							$cinema_countries .= '<input type="checkbox" name="countries[]" id="'.$checkbox_id.'" checked>';
							$cinema_countries .= '&nbsp;';
							$cinema_countries .= '<label for="'.$checkbox_id.'">'.$country->country_name.'</label>';
							$cinema_countries .= '<br/>';

						}

						echo $cinema_countries;

						?>
					</p>

				</div>

			</div>

			<div id="games_options">

				<div class="checkbox">

					<span>Платформы</span>
					<p>
						<?php

						$top_platforms = '';

						foreach($forms['top_platforms'] as $key => $platform) {

							$checkbox_id = 'platform'.$platform->platform_id;
							$top_platforms .= '<input type="checkbox" name="platforms[]" id="'.$checkbox_id.'" checked>';
							$top_platforms .= '&nbsp;';
							$top_platforms .= '<label for="'.$checkbox_id.'">'.$platform->platform_name.'</label>';
							$top_platforms .= '<br/>';

						}

						echo $top_platforms;

						?>
					</p>

				</div>

			</div>

			<div>

				<p>
					<label for="rec_num_val">Количество рекомендаций:</label>
					<input type="text" name="recommendations" id="rec_num_val" readonly style="border:0; margin: 0;">
				</p>

				<div id="rec_num_interval"></div>

			</div>

		</fieldset>

		<input type="submit" class="full" value="Искать">

	</form>

@stop