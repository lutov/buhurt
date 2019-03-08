<!DOCTYPE html>
<html lang="ru">
    <head>	
        <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
        
        <title>@yield('title')</title>
        
		<meta name="keywords" content="@yield('keywords')" />
		<meta name="description" content="@yield('description')" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
        
		<link rel="shortcut icon" href="https://buhurt.ru/favicon.ico" type="image/x-icon">

		<link href="/data/bootstrap-4.0.0-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="/data/bootstrap-star-rating/css/star-rating.min.css" rel="stylesheet" type="text/css" />
		<link href="/data/js/ion.rangeSlider-master/css/ion.rangeSlider.css" rel="stylesheet" type="text/css" />
		<link href="/data/js/ion.rangeSlider-master/css/ion.rangeSlider.skinFlat.css" rel="stylesheet" type="text/css" />
		<link href="/data/css/main.css" rel="stylesheet" type="text/css" />

		<script type="text/javascript" src="/data/js/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="/data/js/jquery-ui-1.12.1.custom/jquery-ui.min.js" defer></script>
		<script type="text/javascript" src="/data/bootstrap-4.0.0-dist/js/bootstrap.min.js" defer></script>
		<script type="text/javascript" src="/data/bootstrap-star-rating/js/star-rating.min.js" defer></script>
		<script type="text/javascript" src="/data/js/ion.rangeSlider-master/js/ion.rangeSlider.min.js" defer></script>
		<script type="text/javascript" src="/data/js/main.js" defer></script>

		<link rel="manifest" href="/manifest.json">

	</head>
	
    <body>

    	<header>

			<nav class="navbar navbar-expand-md navbar-light bg-light border-bottom shadow fixed-top">

				<a class="navbar-brand" href="/">
					<!--img src="/data/img/design/logo.svg" alt="Бугурт" title="«Бугурт» — свободная система оценок" width="30" height="30" /-->
					Бугурт
				</a>

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarsExampleDefault">

					<ul class="navbar-nav mr-auto">

						<li class="nav-item">
							<a class="nav-link" href="/books/">📚 Книги</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/films/">&#127909; Фильмы</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/games/">&#127918; Игры</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/albums/">&#127911; Альбомы</a>
						</li>

						@if (Auth::check())

							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									👤 {!! Auth::user()->username !!}
								</a>
								<div class="dropdown-menu" aria-labelledby="dropdown01">

									<a class="dropdown-item" href="{!! URL::action('UserController@view', array(Auth::user()->id)) !!}">Профиль</a>
									<a class="dropdown-item" href="/events">Лента</a>
									<a class="dropdown-item" href="/recommendations/">Случайный список</a>
									<a class="dropdown-item" href="/user/{!! Auth::user()->id !!}/recommendations">Рекомендации</a>

									<a class="dropdown-item" href="/user/logout/">Выйти</a>

								</div>
							</li>

						@else

							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									👥 Авторизация
								</a>
								<div class="dropdown-menu" aria-labelledby="dropdown01">

									<a class="dropdown-item" href="/recommendations/">Рекомендации</a>
									<a class="dropdown-item" href="/user/login/">Войти</a>
									<a class="dropdown-item" href="/user/register/">Зарегистрироваться</a>

								</div>
							</li>

						@endif

					</ul>

					{!! Form::open(array('action' => 'SearchController@everything', 'class' => 'form-inline my-2 my-lg-0', 'id' => 'search_form', 'method' => 'GET')) !!}
					{!! Form::text(
                        'query',
                        $value = Input::get('query', ''),
                        $attributes = array(
                            'placeholder' => 'Поиск',
                            'class' => 'form-control mr-sm-2',
                            'id' => 'search'
                        )
                    ) !!}
					<button class="btn btn-outline-primary my-2 my-sm-0" type="submit">🔎</button>
					{!! Form::close() !!}

				</div>

			</nav>

			<script>
				$(document).ready(function() {

                    $('#search').autocomplete({
                        source: "{!! URL::action('SearchController@everythingJson') !!}", // url-адрес
                        minLength: 3, // минимальное количество для совершения запроса
                        delay: 500,
                        select: function (event, ui) {
                            $('#search').val(ui.item.value);
                            $('#search_form').submit();
                        }
                    });

					@if(Auth::check())

                    $('.fast_rating').rating({

                        //fx: 'full',
                        //url: '/rates/rate',

                        language: 'ru',
                        theme: 'krajee-uni',
                        size: 'xs',
                        emptyStar: '&#9734;',
                        filledStar: '&#9733;',
                        clearButton: '&#10006;',
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

                    });

					@endif

					<?php

					$message = Session::get('message');

					$output_message = '';
					if (isset($message) && !empty($message)) {

						$output_message .= 'var popup_message = {type:"message", title: "Сообщение", message:"'.$message.'", images:[]};';
						//$output_message .= 'console.log(popup_message);';
						$output_message .= 'show_popup(popup_message);';

					} else {
						//console.log('No message');
					}
					echo $output_message;

					?>
					
				});

            </script>

    	</header>

		<main role="main" class="container pt-5 pb-5">
