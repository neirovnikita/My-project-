<?php
require_once ('system/func.php');
require_once ('system/header.php');

noauth(); // Закроем от авторизованных






echo '<div class="brd">

<div><img src="/style/images/start_logo.jpg" alt="" style="width:100%;"/></div>
<div class="feedback">
<span> <b>Основная цель игры</b> - повысить доход в секунду, развивая бизнесы.

</div>
<div>

<a class="btnl mt4" href="/registration">Регистрация [+5 <img src="/img/ruby.png" style="width:24px;"/>]</a>
<a class="btnl mt4" href="/login">Вход</a>
</div>
</div>



<center><script type="text/javascript" src="//yandex.st/share/share.js"
charset="utf-8"></script>
<div class="yashare-auto-init" data-yashareL10n="ru"
 data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir" data-yashareTheme="counter"

></div> </center>

';
require_once ('system/footer.php');
?>