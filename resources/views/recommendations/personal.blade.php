@extends('layouts.default')

@section('title')Рекомендации@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center">
		<h1 class="pt-5">@yield('title')</h1>
		<h2 class="pb-3">@yield('subtitle')</h2>
	</section>

	<script>

        $(function() {

            var today = new Date();
            var year = today.getFullYear();
            var years_interval = $('#years_interval');
            years_interval.ionRangeSlider({
                min: 1890,
                max: year,
				step: 10,
				type: 'double',
                prettify_enabled: false,
                grid: true,
                from: 2000,
				to: year
            });

            var rates_interval = $('#rates_interval');
            rates_interval.ionRangeSlider({
                min: 1,
                max: 10,
				step: 1,
                type: 'double',
                prettify_enabled: false,
                from: 7,
				to: 10
            });

            var rec_num_interval = $('#rec_num_interval');
            rec_num_interval.ionRangeSlider({
                min: 3,
                max: 30,
                step: 3,
                prettify_enabled: false,
                from: 15
            });

        } );

	</script>

	<form method="post" action="<?=$_SERVER['REQUEST_URI']?>" class="form_tabs">

	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#filter-1" role="tab">Общие настройки</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#filter-2" role="tab">Тип рекомендаций</a>
		</li>
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
				<div class="btn-group btn-group-toggle" data-toggle="buttons">
					<label class="btn btn-outline-success">
						<input type="radio" name="element_type" id="books" autocomplete="off"> Книги
					</label>
					<label class="btn btn-outline-success active">
						<input type="radio" name="element_type" id="films" autocomplete="off" checked> Фильмы
					</label>
					<label class="btn btn-outline-success">
						<input type="radio" name="element_type" id="games" autocomplete="off"> Игры
					</label>
				</div>

				<legend class="col-form-legend mt-4">Рейтинги</legend>
				<div class="btn-group btn-group-toggle" data-toggle="buttons">
					<label class="btn btn-outline-success">
						<input type="radio" name="ratings" id="high" autocomplete="off"> Высокие
					</label>
					<label class="btn btn-outline-success active">
						<input type="radio" name="ratings" id="any" autocomplete="off" checked> Любые
					</label>
					<label class="btn btn-outline-success">
						<input type="radio" name="ratings" id="low" autocomplete="off"> Низкие
					</label>
				</div>

				<legend class="col-form-legend mt-4">Количество оценок</legend>
				<div class="btn-group btn-group-toggle" data-toggle="buttons">
					<label class="btn btn-outline-success">
						<input type="radio" name="rates_count" id="high" autocomplete="off"> Много
					</label>
					<label class="btn btn-outline-success active">
						<input type="radio" name="rates_count" id="high" autocomplete="off" checked> Все равно
					</label>
					<label class="btn btn-outline-success">
						<input type="radio" name="rates_count" id="high" autocomplete="off"> Мало
					</label>
				</div>

				<legend class="col-form-legend mt-4">Годы выпуска</legend>

				<div>
					<input name="years" id="years_interval">
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

			</div>

		</div>

		<div class="tab-pane" id="filter-2" role="tabpanel">

			<fieldset>

				<!--legend>Тип рекомендаций</legend-->

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

		</div>

		<div class="tab-pane" id="filter-3" role="tabpanel">

			<fieldset>

				<!--legend>Точные настройки</legend-->

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

		</div>

	</div>

		<div><input type="submit" class="btn btn-primary btn-block" value="Искать"></div>

	</form>

@stop