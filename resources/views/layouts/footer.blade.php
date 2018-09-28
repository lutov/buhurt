
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
			<script type="text/javascript" >
                (function (d, w, c) {
                    (w[c] = w[c] || []).push(function() {
                        try {
                            w.yaCounter25959328 = new Ya.Metrika({
                                id:25959328,
                                clickmap:true,
                                trackLinks:true,
                                accurateTrackBounce:true
                            });
                        } catch(e) { }
                    });

                    var n = d.getElementsByTagName("script")[0],
                        s = d.createElement("script"),
                        f = function () { n.parentNode.insertBefore(s, n); };
                    s.type = "text/javascript";
                    s.async = true;
                    s.src = "https://mc.yandex.ru/metrika/watch.js";

                    if (w.opera == "[object Opera]") {
                        d.addEventListener("DOMContentLoaded", f, false);
                    } else { f(); }
                })(document, window, "yandex_metrika_callbacks");
			</script>
			<noscript><div><img src="https://mc.yandex.ru/watch/25959328" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
			<!-- /Yandex.Metrika counter -->

			<script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

                ga('create', 'UA-101861790-1', 'auto');
                ga('send', 'pageview');

			</script>

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

			<!--div id="popup" class="badge-info p-2 w-25" style="position: fixed; bottom: 0; right: 0;"></div-->

		</footer>

	</body>

</html>