<h3>Добавить произведение</h3>

<div class="row">
	<div class="col-4 col-md-4">
		<div class="card @include('widgets.card-class') small">
			<div class="card-header"><a href="/q_add/books/?new_name={!! urlencode($search) !!}">Книгу</a></div>
			<ul class="list-group list-group-flush">
				<li class="list-group-item">
					<a href="/q_add/books/?new_name={!! urlencode($search) !!}&template=fiction_book">
						Фантастика и фэнтези
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/books/?new_name={!! urlencode($search) !!}&template=action_book">
						Детективы и боевики
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/books/?new_name={!! urlencode($search) !!}&template=adventure_book">
						Приключения и исторический роман
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/books/?new_name={!! urlencode($search) !!}&template=lovestory_book">
						Любовный роман
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/books/?new_name={!! urlencode($search) !!}&template=novel_book">
						Современная проза
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/books/?new_name={!! urlencode($search) !!}&template=nonfiction_book">
						Публицистика и нон-фикшен
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/books/?new_name={!! urlencode($search) !!}&template=poetry_book">
						Поэзия
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/books/?new_name={!! urlencode($search) !!}&template=comic_book">
						Комиксы и манга
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/books/?new_name={!! urlencode($search) !!}&template=marvel_book">
						Marvel Comics
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/books/?new_name={!! urlencode($search) !!}&template=dc_book">
						DC Comics
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/books/?new_name={!! urlencode($search) !!}&template=image_book">
						Image Comics
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/books/?new_name={!! urlencode($search) !!}&template=valiant_book">
						Valiant Comics
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="col-4 col-md-4">
		<div class="card @include('widgets.card-class') small">
			<div class="card-header"><a href="/q_add/films/?new_name={!! urlencode($search) !!}">Фильм</a></div>
			<ul class="list-group list-group-flush">
				<li class="list-group-item">
					<a href="/q_add/films/?new_name={!! urlencode($search) !!}&template=fiction_film">
						Фантастика
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/films/?new_name={!! urlencode($search) !!}&template=marvel_film">
						Marvel Comics
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/films/?new_name={!! urlencode($search) !!}&template=dc_film">
						DC Comics
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/films/?new_name={!! urlencode($search) !!}&template=fantasy_film">
						Фэнтези
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/films/?new_name={!! urlencode($search) !!}&template=drama_film">
						Драмы
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/films/?new_name={!! urlencode($search) !!}&template=melodrama_film">
						Мелодрамы
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/films/?new_name={!! urlencode($search) !!}&template=comedy_film">
						Комедии
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/films/?new_name={!! urlencode($search) !!}&template=family_film">
						Семейные
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/films/?new_name={!! urlencode($search) !!}&template=adventure_film">
						Приключения
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/films/?new_name={!! urlencode($search) !!}&template=detective_film">
						Детективы
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/films/?new_name={!! urlencode($search) !!}&template=action_film">
						Экшены
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/films/?new_name={!! urlencode($search) !!}&template=thriller_film">
						Триллеры
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/films/?new_name={!! urlencode($search) !!}&template=horror_film">
						Ужасы
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/films/?new_name={!! urlencode($search) !!}&template=series_film">
						Сериалы
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/films/?new_name={!! urlencode($search) !!}&template=animated_film">
						Мультфильмы
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/films/?new_name={!! urlencode($search) !!}&template=anime">
						Аниме
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="col-4 col-md-4">
		<div class="card @include('widgets.card-class') small">
			<div class="card-header"><a href="/q_add/games/?new_name={!! urlencode($search) !!}">Игру</a></div>
			<ul class="list-group list-group-flush">
				<li class="list-group-item">
					<a href="/q_add/games/?new_name={!! urlencode($search) !!}&template=action_game">
						Экшен
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/games/?new_name={!! urlencode($search) !!}&template=roleplay_game">
						Ролевые игры
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/games/?new_name={!! urlencode($search) !!}&template=strategy_game">
						Стратегии
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/games/?new_name={!! urlencode($search) !!}&template=quest_game">
						Приключения
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/games/?new_name={!! urlencode($search) !!}&template=arcade_game">
						Аркады
					</a>
				</li>
			</ul>
		</div>
		<div class="card @include('widgets.card-class') small mt-4">
			<div class="card-header"><a href="/q_add/albums/?new_name={!! urlencode($search) !!}">Альбом</a></div>
			<ul class="list-group list-group-flush">
				<li class="list-group-item">
					<a href="/q_add/albums/?new_name={!! urlencode($search) !!}&template=rock_album">
						Рок
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/albums/?new_name={!! urlencode($search) !!}&template=rap_album">
						Рэп
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/albums/?new_name={!! urlencode($search) !!}&template=pop_album">
						Поп-музыка
					</a>
				</li>
				<li class="list-group-item">
					<a href="/q_add/albums/?new_name={!! urlencode($search) !!}&template=electronic_album">
						Электроника
					</a>
				</li>
			</ul>
		</div>
		@if($isAdmin)
			<div class="card @include('widgets.card-class') mt-4">
				<div class="card-header"><a href="/q_add/memes/?new_name={!! urlencode($search) !!}">Мем</a></div>
			</div>
		@endif
	</div>
</div>
