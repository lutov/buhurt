
		</main> <!-- #main -->

		<footer class="footer text-muted font-small blue">

			<nav class="navbar navbar-expand-sm navbar-light bg-light">

				<div class="collapse navbar-collapse" id="navbarCollapse">

					<span title="Сайт предназначен исключительно для совершеннолетних">🔞</span>

					<ul class="navbar-nav mr-auto">

						<li class="nav-item"><a class="nav-link" href="/about/">О&nbsp;сайте</a></li>
						<li class="nav-item"><a class="nav-link" href="https://vk.com/free_buhurt" target="_blank">ВК</a></li>
						<li class="nav-item"><a class="nav-link" href="mailto:request@buhurt.ru">Обратная&nbsp;связь</a></li>
						<li class="nav-item"><a class="nav-link" href="/icons">Иконки</a></li>
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

				<div>2014—{{date('Y')}}</div>

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

			</nav>

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

			<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000" id="toast_block" style="position: fixed; bottom: 1rem; right: 1rem;">

				<div class="toast-header">

					<strong class="mr-auto" id="toast_title">Сообщение</strong>
					<small id="toast_timestamp"></small>
					<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="toast-body" id="toast_content">
					...
				</div>

			</div>

		</footer>

		<script type="text/javascript" src="/data/vendor/jquery/jquery-ui-1.12.1.custom/jquery-ui.min.js" defer></script>
		<script type="text/javascript" src="/data/vendor/bootstrap-4.3.1-dist/js/bootstrap.min.js" defer></script>
		<script type="text/javascript" src="/data/vendor/bootstrap-star-rating/js/star-rating.min.js" defer></script>
		@if(Request::is('*recommendations'))
			<script type="text/javascript" src="/data/vendor/rangeSlider/ion.rangeSlider-master/js/ion.rangeSlider.min.js" defer></script>
		@endif
		<script type="text/javascript" src="/data/js/app.min.js" defer></script>

		@if(Session::get('message'))
		<script>
			$(document).ready(function() {
				let popup_message = {type:"message", title: "Сообщение", message: "{!! Session::get('message') !!}", images:[]};
				show_popup(popup_message);
			});
		</script>
		@endif

		<!-- Yandex.Metrika counter -->
		<script type="text/javascript" >
			(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
				m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
			(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

			ym(25959328, "init", {
				clickmap:true,
				trackLinks:true,
				accurateTrackBounce:true
			});
		</script>
		<noscript><div><img src="https://mc.yandex.ru/watch/25959328" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- /Yandex.Metrika counter -->

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-101861790-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'UA-101861790-1');
		</script>

	</body>

</html>