
		</main> <!-- #main -->

		<footer class="footer container-fluid text-muted font-small blue pt-4 mt-4">

			<nav class="navbar fixed-bottom navbar-expand-sm navbar-dark bg-dark">

				<div class="collapse navbar-collapse" id="navbarCollapse">

					<ul class="navbar-nav mr-auto">

						<li class="nav-item">
							<a class="nav-link" href="/about/">О сайте (FAQ)</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" href="https://vk.com/free_buhurt" target="_blank">Группа ВК</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" href="mailto:request@buhurt.ru">Жалобы и предложения</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" href="/icons">Авторы иконок</a>
						</li>

						@if (RolesHelper::is_admin())
							<li class="nav-item">
								<a class="nav-link" href="/admin/add/">Пополнение базы</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="https://github.com/lutov/buhurt_database/" target="_blank">Базы на GitHub</a>
							</li>
						@endif

						<li>{!! DummyHelper::getStats() !!}</li>

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

			<div id="popup"></div>

		</footer>

	</body>

</html>