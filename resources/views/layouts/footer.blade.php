
		</main> <!-- #main -->

		<footer class="footer text-muted font-small blue">

			<nav class="navbar navbar-expand-sm navbar-light bg-light">

				<div class="collapse navbar-collapse" id="navbarCollapse">

					🔞

					<ul class="navbar-nav mr-auto">

						<li class="nav-item">
							<a class="nav-link" href="/about/">О&nbsp;сайте</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" href="https://vk.com/free_buhurt" target="_blank">ВК</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" href="mailto:request@buhurt.ru">Обратная&nbsp;связь</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" href="/icons">Иконки</a>
						</li>

						@if (RolesHelper::isAdmin($request))
							<li class="nav-item">
								<a class="nav-link" href="/admin/add/">База</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="https://github.com/lutov/buhurt_database/" target="_blank">GitHub</a>
							</li>
						@endif

					</ul>

					<ul class="navbar-nav mr-auto d-none d-xl-flex">

						<li>{!! DummyHelper::getStats($request) !!}</li>

					</ul>

				</div>

				<div>© <!--a href="/user/1/profile"-->В. О. Шевченко<!--/a-->, 2014—{{date('Y')}}</div>

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

			</nav>

			<!-- Yandex.Metrika counter -->
			<noscript><div><img src="https://mc.yandex.ru/watch/25959328" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
			<!-- /Yandex.Metrika counter -->

			<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_title" aria-hidden="true" id="modal_block">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="modal_title">Сообщение</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body" id="modal_content">
							...
						</div>
						<!--div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
						</div-->
					</div>
				</div>
			</div>

		</footer>

	</body>

</html>