  </main>
  <footer class="py-3 font2 bg-dark">
    <div class="container">
      <div class="row g-1">
        <div class="col-12 col-md-3">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a href="/" class="nav-link p-1 text-white">Проститутки <?= $city['value'][1] ?></a>
            </li>
            <li class="nav-item">
              <a href="/ind/" class="nav-link p-1 text-white">Индивидуалки <?= $city['value'][1] ?></a>
            </li>
            <li class="nav-item">
              <a href="/sal/" class="nav-link p-1 text-white">Интим салоны <?= $city['value'][1] ?></a>
            </li>
            <li class="nav-item">
              <a href="/man/" class="nav-link p-1 text-white">Мужчины по вызову <?= $city['value'][1] ?></a>
            </li>
            <li class="nav-item">
              <a href="/tsl/" class="nav-link p-1 text-white">Трансы <?= $city['value'][1] ?></a>
            </li>
            <li class="nav-item">
              <a href="/placement/" class="nav-link p-1 text-white">Размещение рекламы</a>
            </li>
          </ul>
        </div>
        <div class="col-12 col-md-6">
          <div class="row g-1">
            <div class="col-12 col-lg-6">
              <?= isset($telegramChannelInfo) ? viewWidgetTelegramFooter($telegramChannelInfo) : '' ?>
            </div>
            <div class="col-12 col-lg-6"></div>
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="text-center text-white my-2">Категория информационных материалов этого сайта <strong>18+</strong>.</p>
          <p class="text-center text-white my-2">Этот сайт использует куки (COOKIES) файлы собирая предпочтения посетителей, это важно знать.</p>
          <div class="w-100 text-center">
            <img width="88" height="31" alt="" border="0" src="https://yandex.ru/cycounter?https://sar1.elited.ru&theme=dark&lang=ru"/>
            <?= isset($city['counter']) ? $city['counter'] : '' ?>
          </div>
          <p class="text-center text-white mt-2">Интим портал &laquo;Элит Досуг&raquo; работает с 2010 года <br>
          <a href="/agreement/" class="text-white">Cоглашение</a> <span class="text-muted">/</span> <a href="#" class="text-white">Администрация</a></p>
        </div>
      </div>
    </div>
  </footer>
  <script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
  </script>
</body>
</html>