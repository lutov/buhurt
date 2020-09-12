<!DOCTYPE html>
<html lang="ru">
    <head>	
        <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
        
        <title>@yield('title') | Бугурт</title>
        
		<meta name="keywords" content="@yield('keywords')" />
		<meta name="description" content="@yield('description')" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="shortcut icon" href="https://buhurt.ru/favicon.ico" type="image/x-icon">

		<link href="/data/vendor/bootstrap-4.3.1-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="/data/vendor/bootstrap-star-rating/css/star-rating.min.css" rel="stylesheet" type="text/css" />
		@if(Request::is('*recommendations'))
		<link href="/data/vendor/rangeSlider/ion.rangeSlider-master/css/ion.rangeSlider.css" rel="stylesheet" type="text/css" />
		<link href="/data/vendor/rangeSlider/ion.rangeSlider-master/css/ion.rangeSlider.skinFlat.css" rel="stylesheet" type="text/css" />
		@endif
		<!--link href="/data/css/main.min.css" rel="stylesheet" type="text/css" /-->
		<link href="/data/css/main.css?hash={!! date('His') !!}" rel="stylesheet" type="text/css" />

		<script type="text/javascript" src="/data/vendor/jquery/jquery-3.3.1.min.js"></script>

		<link rel="manifest" href="/manifest.json">
		<link rel="canonical" href="{{ url()->current() }}" />

	</head>
	
    <body class="bg-light">

    	<header>

			<nav class="navbar navbar-expand-md navbar-light bg-light border-bottom shadow fixed-top">

				<a class="navbar-brand" href="/">Бугурт</a>

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbar">

					<ul class="navbar-nav mr-auto">

						<li class="nav-item">
							<span class="d-none d-xl-inline">📖</span><a class="nav-link d-inline" href="/books/">Книги</a>
						</li>
						<li class="nav-item">
							<span class="d-none d-xl-inline">🎞</span><a class="nav-link d-inline" href="/films/">Фильмы</a>
						</li>
						<li class="nav-item">
							<span class="d-none d-xl-inline">🎮</span><a class="nav-link d-inline" href="/games/">Игры</a>
						</li>
						<li class="nav-item">
							<span class="d-none d-xl-inline">🎧</span><a class="nav-link d-inline" href="/albums/">Альбомы</a>
						</li>
						@if (RolesHelper::isAdmin($request))<li class="nav-item">
							<span class="d-none d-xl-inline">🤔</span><a class="nav-link d-inline" href="/memes/">Мемы</a>
						</li>@endif

						<li class="nav-item dropdown">
							<span class="d-none d-xl-inline">🗄</span>
							<a class="nav-link d-inline dropdown-toggle" href="#" id="add_sections" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Картотека</a>
							<div class="dropdown-menu" aria-labelledby="add_sections">

								<a class="dropdown-item" href="/persons/">Люди</a>
								<a class="dropdown-item" href="/bands/">Группы</a>
								<a class="dropdown-item" href="/companies/">Компании</a>
								<a class="dropdown-item" href="/countries/">Страны</a>
								<a class="dropdown-item" href="/platforms/">Платформы</a>
								<a class="dropdown-item" href="/genres/">Жанры</a>
								<a class="dropdown-item" href="/years/">Календарь</a>
								<a class="dropdown-item" href="/collections/">Коллекции</a>

							</div>
						</li>

					</ul>

					<ul class="navbar-nav ml-auto">

						<li>

							{!! Form::open(array(
								'action' => 'Search\SearchController@everything',
								'class' => 'form-inline my-2 my-lg-0',
								'id' => 'search_form',
								'method' => 'GET'
							)) !!}
								<div class="input-group input-group-sm">
									{!! Form::text(
										'query',
										$value = Input::get('query', ''),
										$attributes = array(
											'placeholder' => 'Поиск',
											'class' => 'form-control',
											'id' => 'search'
										)
									) !!}
									<div class="input-group-append">
										<button class="btn btn-outline-primary mr-sm-3 d-none d-xl-inline" type="submit">🔎</button>
									</div>
								</div>
							{!! Form::close() !!}

						</li>

					@if (Auth::check())
						<li class="nav-item dropdown justify-content-start align-self-center">
							<span class="d-none d-xl-inline">👤</span><a class="nav-link d-inline dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{!! Auth::user()->username !!}</a>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">

								<a class="dropdown-item" href="{!! URL::action('User\UserController@view', array(Auth::user()->id)) !!}">Профиль</a>
								<a class="dropdown-item" href="/events">Лента</a>
								<a class="dropdown-item" href="/advise">Совет</a>
								<a class="dropdown-item" href="/recommendations/">Случайный список</a>
								<a class="dropdown-item" href="/user/{!! Auth::user()->id !!}/recommendations">Рекомендации</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="/user/logout/">Выйти</a>

							</div>
						</li>
					@else
						<li class="nav-item dropdown justify-content-start align-self-center">
							<span class="d-none d-xl-inline">👥</span><a class="nav-link d-inline dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Авторизация</a>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">

								<a class="dropdown-item" href="/recommendations/">Рекомендации</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="/user/login/">Войти</a>
								<a class="dropdown-item" href="/user/register/">Зарегистрироваться</a>

							</div>
						</li>
					@endif
					</ul>

				</div>

			</nav>

    	</header>

		<div id="wrapper" class="container-fulid bg-secondary">
			<main role="main" class="container">
