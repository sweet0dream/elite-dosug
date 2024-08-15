  </main>
  <footer class="py-3 font2 bg-dark">
    <div class="container">
      <div class="row g-1">
        <div class="col-12 col-md-3">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a href="/" class="nav-link p-1 text-white" title="Проститутки <?= $city['value'][1]?>">Проститутки <?= $city['value'][1] ?></a>
            </li>
            <li class="nav-item">
              <a href="/ind/" class="nav-link p-1 text-white" title="Индивидуалки <?= $city['value'][1]?>">Индивидуалки <?= $city['value'][1] ?></a>
            </li>
            <li class="nav-item">
              <a href="/sal/" class="nav-link p-1 text-white" title="Интим Салоны <?= $city['value'][1]?>">Интим салоны <?= $city['value'][1] ?></a>
            </li>
            <li class="nav-item">
              <a href="/man/" class="nav-link p-1 text-white" title="Мужчины по вызову <?= $city['value'][1]?>">Мужчины по вызову <?= $city['value'][1] ?></a>
            </li>
            <li class="nav-item">
              <a href="/tsl/" class="nav-link p-1 text-white" title="Трансы <?= $city['value'][1]?>">Трансы <?= $city['value'][1] ?></a>
            </li>
            <li class="nav-item">
              <a href="/placement/" class="nav-link p-1 text-white" rel="noidex,nofollow">Размещение рекламы</a>
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
          <div class="w-100 pt-2 text-secondary">
            <?php
              foreach ($city['offer'] as $offer) {
                $linkOffers[] = '<a href="https://' . $offer['domain'] . '.elited.ru" target="_blank" class="text-white">Проститутки ' . $offer['value'][1] . '</a>';
              }
            ?>
            В других городах: <?= implode(' / ', $linkOffers) ?>
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="text-center text-white my-2">Категория информационных материалов этого сайта <strong>18+</strong>.</p>
          <p class="text-center text-white my-2">Этот сайт использует куки (COOKIES) файлы собирая предпочтения посетителей, это важно знать.</p>
          <div class="w-100 text-center">
            <img width="88" height="31" alt="" border="0" src="https://yandex.ru/cycounter?https://<?= $city['domain'] ?>.elited.ru&theme=dark&lang=ru"/>
<?php
  if (isset($counter[$city['domain']])) {
?>

<script>
( function () {
        var loadedTLAnalytics = false,timerId;
      if ( navigator.userAgent.indexOf( 'YandexMetrika' ) > -1 ) {
             loadTLAnalytics();
      } else {
             window.addEventListener( 'scroll', loadTLAnalytics, { passive: true } );
             window.addEventListener( 'touchstart', loadTLAnalytics, { passive: true } );
             document.addEventListener( 'mouseenter', loadTLAnalytics, { passive: true } );
            document.addEventListener( 'click', loadTLAnalytics, { passive: true } );
            document.addEventListener( 'DOMContentLoaded', loadFallback, { passive: true } );
     }
 function loadFallback() {
           timerId = setTimeout( loadTLAnalytics, 5000 );
 } 
function loadTLAnalytics( e ) {
     if ( loadedTLAnalytics ) {
          return;
     }
    setTimeout(function () {
      (function(m,e,t,r,i,k,a) { m[i]=m[i]||function() { (m[i].a=m[i].a||[]).push(arguments) }; m[i].l=1*new Date(); for (var j = 0; j < document.scripts.length; j++) { if (document.scripts[j].src === r) { return; } } k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a) } ) (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym"); ym( <?= $counter[$city['domain']] ?>, "init", { clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true, ecommerce:"dataLayer", triggerEvent:true } )
     },0);
   loadedTLAnalytics = true;
   clearTimeout( timerId );
   window.removeEventListener( 'scroll', loadTLAnalytics, { passive: true} );
   window.removeEventListener( 'touchstart', loadTLAnalytics, {passive: true} );
   document.removeEventListener( 'mouseenter', loadTLAnalytics, { passive: true } );
   document.removeEventListener( 'click', loadTLAnalytics, { passive: true } );
   document.removeEventListener( 'DOMContentLoaded', loadFallback, { passive: true } );
 }
} )();
</script>
<img src="https://informer.yandex.ru/informer/<?= $counter[$city['domain']] ?>/2_1_EFEFEFFF_EFEFEFFF_0_pageviews" />

<?php
  }
?>
          </div>
          <p class="text-center text-white mt-2">Интим портал <br />&laquo;Элит Досуг <?= $city['value'][0] ?>&raquo; <br />работает с 2010 года <br>
          <a href="/agreement/" class="text-white">Пользовательсткое соглашение</a><br /><a href="#" class="text-white">Менеджер по размещению рекламы</a></p>
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