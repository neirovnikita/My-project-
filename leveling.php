<?php
require_once ('system/func.php');
$title = 'Автопрокачка';
require_once ('system/header.php');
auth();
$cq = mysql_fetch_assoc(mysql_query("SELECT * FROM `clans` WHERE `id` = '".$user['id_clan']."'")); 
if($user['id_clan'] >= 1 and $cq >= 1){
$p = ($cq['angels']*$user['x2']*$user['g_x2']);
}else{
if($user['angels'] < 1){
$p = ($user['x2']*$user['g_x2']);
}else{
$p = ($user['angels']*$user['x2']*$user['g_x2']);    
}
}
#############Trec80 <-> Прокачка
if (isset($_GET['ok'])){
#############Trec80 <-> Если услуга не активна, перекидываем на страницу покупки
if ($user['auto_load'] < time()){
	  $_SESSION['msg'] = 'Услуга не активна!';
  header('Location: /leveling.php');
 exit();
}
#############Trec80 <-> Совершаем прокачку
$dirs = mysql_query("SELECT * FROM `room_users` WHERE `id_user` = '".$user['id']."' ORDER BY `id_up`");
while($post = mysql_fetch_assoc($dirs)) {
#############Trec80 <-> если 2000
	$cols = ceil($user['gold']/$post['gold']);
	if ($cols > 2000) {
	$col = ceil($user['gold']/$post['gold']);
	}else{
	$col = 2000; 
	}
#############Trec80 <-> Сами ограничения и запись
if ($post['gold'] < $user['gold']){
mysql_query("UPDATE `room_users` SET `gold` = `gold`+'".text($post['up_gold']*$col)."', `money_sek` = `money_sek`+'".text$p*$post['item'])*$col."', `level` = `level`+'".text($col)."' WHERE `id` = '".$post['id']."'");
mysql_query("UPDATE `users` SET `gold` = `gold`-'$col', `money_sek` = `money_sek`+'".text($post['item']*$col)*$p."' WHERE `id` = '".$user['id']."' LIMIT 1");
echo ''.$post[id].' '.$col.'</br>';
#############Trec80 <-> Перенос на главную
///header('Location: /');
}
///header('Location: /');
}	
}else
#############Trec80 <-> Покупка
if (text(isset($_GET['buy']))){
#############Trec80 <-> Условия
if($user['ruby'] < '500')$err = 'У вас не достаточно рубинов';
if ($user['auto_load'] > time())$err = 'У вас уже кативна данная услуга';
#############Trec80 <-> выводим ошибку если условия не соблюдены/Покупаем если все ОК
     if ($err){
  $_SESSION['msg'] = $err;
  header('Location: /leveling.php');
              }else{
$time_count = (time()+604800);
mysql_query("UPDATE `users` SET `auto_load` = '$time_count',`ruby` = `ruby`-500 WHERE `id` = '".$user['id']."'");
  $_SESSION['msg'] = 'Услуга приобретена успешно';
  header('Location: /leveling.php');
}
}else{
#############Trec80 <-> Главная
echo '<div class="btnl mt4"><font color="white">Автопрокачка</font></div>';
echo '<div class="bordered">';
echo 'После покупки услуги <font color="green">"Автопрокачка"</font> вы сможете одним действием равномерно расходовать свой баланс на все бизнесы.</br>';
echo 'Стоимость услуги: 500<img width="20" height="20" alt="" src="/img/ruby.png" title=""/></img> на 7 дней</br>';
echo '</div>';
//Если меньше то выводим купить, если больше то выводим что услуга активна
if ($user['auto_load'] < time()){
echo '<div class="bordered">
<center>
<form method="post" action="?buy">
<input class="btni" value="Активировать" type="submit" />
</form></div>';
}else{
echo '<div class="bordered">Услуга достуна до: '.vremja($user['auto_load']).'</div>';	
}
}
require_once ('system/footer.php');
?>