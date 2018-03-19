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

		<link rel="stylesheet" href="/data/bootstrap-4.0.0-dist/css/bootstrap.min.css">
		<!--link href="/data/css/normalize.min.css" rel="stylesheet" type="text/css" /-->
		<link href="/data/css/fonts.css" rel="stylesheet" type="text/css" />		
		<!--link href="/data/css/main.css" rel="stylesheet" type="text/css" /-->
		<link href="/data/js/jquery_rating/styles/jquery.rating.css" rel="stylesheet" type="text/css" />
		<!--link rel="stylesheet" href="/data/js/jquery-ui-1.12.1.custom/jquery-ui.min.css"-->

		<script type="text/javascript" src="/data/js/jquery-2.1.1.min.js"></script>		
		<script type="text/javascript" src="/data/js/jquery_rating/js/jquery.rating-2.0.min.mod.js"></script>
		<script src="/data/js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
		<script type="text/javascript" src="/data/js/jquery.lightbox_me.min.js"></script>		
		<script type="text/javascript" src="/data/js/main.min.js"></script>
		<script type="text/javascript" src="/data/bootstrap-4.0.0-dist/js/bootstrap.min.js"></script>

	</head>
	
    <body>

    	<header>

			<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">

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
							<a class="nav-link" href="/books/">Книги</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/films/">Фильмы</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/games/">Игры</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/albums/">Альбомы</a>
						</li>

						@if (Auth::check())

							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									{!! Auth::user()->username !!}
								</a>
								<div class="dropdown-menu" aria-labelledby="dropdown01">

									<a class="dropdown-item" href="{!! URL::action('UserController@view', array(Auth::user()->id)) !!}">Профиль</a>
									<a class="dropdown-item" href="/search/advanced">Расширенный поиск</a>
									@if (RolesHelper::is_admin())
									<a class="dropdown-item" href="/books/random">Случайная книга</a>
									@endif

									<a class="dropdown-item" href="/user/logout/">Выйти</a>

								</div>
							</li>

						@else

							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Авторизация
								</a>
								<div class="dropdown-menu" aria-labelledby="dropdown01">

									<a class="dropdown-item" href="#" onclick="show_entrance();">Войти</a>
									<a class="dropdown-item" href="/user/logout/" onclick="show_registration();">Зарегистрироваться</a>

								</div>
							</li>

						@endif

					</ul>

					{!! Form::open(array('action' => 'SearchController@everything', 'class' => 'form-inline my-2 my-lg-0', 'id' => 'search_form', 'method' => 'GET')) !!}
					{!! Form::text(
                        'query',
                        $value = Input::get('query', ''),
                        $attributes = array(
                            'placeholder' => 'Искать',
                            'class' => 'form-control mr-sm-2',
                            'id' => 'search'
                        )
                    ) !!}
					<!--button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button-->
					{!! Form::close() !!}

				</div>

			</nav>

			@if (!Auth::check())
			<div id="entrance_block">

				{!! Form::open(array('action' => 'UserController@login', 'id' => 'entrance_form', 'class' => 'entrance_form', 'method' => 'POST')) !!}

					<p>{!! Form::text('email', $value = null, $attributes = array('placeholder' => 'Логин или e-mail', 'class' => 'full')) !!}</p>
					<p>{!! Form::password('password', $attributes = array('placeholder' => 'Пароль', 'class' => 'full')) !!}</p>
					<p>{!! Form::submit('Войти', $attributes = array('class' => 'full')) !!}</p>

				{!! Form::close() !!}

				<p>
					<span class="symlink"
					onclick="window.open('https://oauth.vk.com/authorize?client_id=4591194&redirect_uri=https://buhurt.ru/user/vk_auth&scope=email&display=popup');">
						Вконтакте
					</span>
				</p>

			</div>

			<div id="registration_block">

				{!! Form::open(array('action' => 'UserController@store', 'id' => 'registration_form', 'class' => 'registration_form', 'method' => 'POST')) !!}

					<p>{!! Form::text('email', $value = null, $attributes = array('placeholder' => 'E-mail', 'class' => 'full', 'autocomplete' => 'off')) !!}</p>
					<p>{!! Form::text('username', $value = null, $attributes = array('placeholder' => 'Логин', 'class' => 'full', 'autocomplete' => 'off')) !!}</p>
					<p>{!! Form::password('password', $attributes = array('placeholder' => 'Пароль', 'class' => 'full', 'autocomplete' => 'off')) !!}</p>
					<!--p-->{!! Recaptcha::render(array('theme' => 'clean', 'lang' => 'ru')) !!}<!--/p-->
					<p>{!! Form::submit('Зарегистрироваться', $attributes = array('class' => 'full')) !!}</p>

				{!! Form::close() !!}

				<p>
					<span class="symlink"
					onclick="window.open('https://oauth.vk.com/authorize?client_id=4591194&redirect_uri=https://buhurt.ru/user/vk_auth&scope=email&display=popup');">
						Вконтакте
					</span>
				</p>

			</div>
			@endif

			<script>
				$(document).ready(function(){

					$('#search').autocomplete({
						source: "{!! URL::action('SearchController@everything_json') !!}", // url-адрес
						minLength: 3, // минимальное количество для совершения запроса
                        delay: 500,
                        select: function( event, ui ) {
                            $('#search').val(ui.item.value);
                            $('#search_form').submit();
                        }
					});

                    @if(Auth::check())
                    $('.rating').rating({
                        fx: 'full',
                        url: '/rates/rate',
                        stars: '10',
                        callback: function(responce){
                            //this.vote_success.fadeOut(2000);

                            $.post('/achievements', {}, function(data) {
                                //console.log(data);

                                show_popup(data);

                            }, 'json');
                        }
                    });
                    @endif

                    <?php
                        $message = Session::get('message');
                    ?>

                    @if(isset($message))
                        @if(!empty($message))
                            var popup_message = JSON.parse('{"msg_type":"message", "message":"{{$message}}", "msg_img":""}');
                            //console.log(popup_message);
                            show_popup(popup_message);
                        @endif
                    @else
                        //console.log('No message');
                    @endif
					
				});
				
					$(window).scroll(function(){
						if ($(window).scrollTop() > 400) {
							$('#side_panel').addClass('side_panel_fixed');
						} else {
							$('#side_panel').removeClass('side_panel_fixed');
						}
					});
            </script>

    	</header>

		<main role="main" class="container p-5">
