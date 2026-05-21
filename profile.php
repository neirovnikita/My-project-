<?php
require_once ('system/func.php');
auth(); // Закроем от не авторизованных


# Настройки #
$id = abs(intval($_GET['id']));
if($id)$profile = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$id."'"));
else $profile = $user;
# Ошибки #
if(!$profile){
$_SESSION['msg'] == 'Такой игрок не существует';
header('Location: /online?search');
exit();
}
if(isset($_GET['clan_memb']) and $myID != $profile['id'] and $user['clan_rang'] > 2 and $profile['id_clan'] == 0 and $profile['side'] == $user['side']){ 
mysql_query("INSERT INTO `clan_memb` SET `id_user` = '".text($profile['id'])."', `id_user2` = '".text($myID)."', `login` = '".text($user['login'])."', `id_clan` = '".text($user['id_clan'])."'"); 
$_SESSION['msg'] = "Заявка отправлена!"; 
header('Location: ?'); 
exit(); 
} 

# юзер в клане или нет #


$acpp = mysql_fetch_array(mysql_query("SELECT SUM(angels) FROM `users` WHERE `id_clan` = '".text($profile['id_clan'])."'"));
$pms = ($money_updater[0]*$p);
$clp = $db->query("SELECT * FROM `clans` WHERE `id` = '".$profile['id_clan']."'")->fetch_assoc();
$cst = ($clp['stat']);
$pcms = (($pms*$cst)/100);

$money_sek_angel = ($pms+$pcms);
$mdsec = ($pms+$pcms);


# время в сикундах #
$tat = (tls(time()-$user[tangels]));
# время в секундах #


### Сбросы ####

if(isset($_GET['angl'])){
$id = abs(intval($_GET['angl']));
$title = 'Сброс ангелов?';
require_once ('system/header.php');
echo "<div class='content'></div>";
echo "<div class='bordered'><center>";
echo'<a class="btni" href="?angels" style="margin-top: 3px; width: 140px;"> Да, сбросить</a><br>';
echo "<a href='/profile/' class='grey' data-ajax>Нет, отмена</a>";
echo "</div></center>";
require_once ('system/footer.php');
exit();
}
if(text(isset($_GET['angels']))){
$id = abs(intval($_GET['angels']));
$angels_bonus = ($user['angels_bonus']+1);
$angels = text(ceil(100*sqrt(($money_sek_angel*$tat)/1000000000)));
$gold = text(2);
$money_sek = text(0);

if($money_sek_angel < 100){
$_SESSION['msg'] = "Ваш доход должен быть больше <img width='20' height='20' src='/img/money_36.png'> ".n_f(100)."";
header('Location: /profile/');
exit();
}else{
	
mysql_query("UPDATE `room_users` SET `level` = '0', `pay_update` =`pay`, `give_money_sek` = '0', `money_sek` = '0' WHERE `id_user` = '".$myID."'");
mysql_query("UPDATE `users` SET `angels_bonus` = '".text($user['angels_bonus']+1)."', `angels` = '".text($user['angels']+$angels)."', `gold` = '".$gold."', `tangels` = '".time()."' WHERE `id` = '".$myID."'");

header('Location: /profile/');
exit();
}
}
if(isset($_GET['angl_ruby'])){
$id = abs(intval($_GET['angl_ruby']));
$title = 'Сброс ангелов?';
require_once ('system/header.php');
echo "<div class='content'></div>";
echo "<div class='bordered'><center>";
echo'<a class="btni" href="?angels_ruby" style="margin-top: 3px; width: 140px;"> Да, за 250 <img src="/img/ruby.png" alt="$" width="20" height="20"></a><br>';
echo "<a href='/profile/' class='grey' data-ajax>Нет, отмена</a>";
echo "</div></center>";
require_once ('system/footer.php');
exit();
}
if(text(isset($_GET['angels_ruby']))){
$id = abs(intval($_GET['angels_ruby']));
$angels_bonus = text($user['angels_bonus']+1);
$angels = text(ceil(100*sqrt(($money_sek_angel*$tat)/1000000000)));

$ruby = 250;
if($user['ruby'] < $ruby){
$_SESSION['msg'] = "Не хватает <img width='20' height='20' src='/img/ruby.png'> ".n_f(($ruby-$user['ruby']))."";
header('Location: /profile/');
exit();
}else{
mysql_query("UPDATE users SET `angels_bonus` = '".text($user['angels_bonus']+1)."', `angels` = '".text($user['angels']+$angels)."', `tangels` = '".time()."', `ruby` = '".text($user['ruby']-$ruby)."' WHERE `id` = '".$myID."'");
$_SESSION['msg'] = 'Вы успешно сбросили бизнес за рубины';
header('Location: /profile/');
exit();
}
}
## dubl
if(isset($_GET['dubl'])){
$id = abs(intval($_GET['dubl']));

if($user['angels'] < ($user['gold_dubl'])){
header('Location: /profile/ ');
exit();
}else{
mysql_query("UPDATE `users` SET `angels` = '".text($user['angels']-$user['gold_dubl'])."', `dubl` = '".text($user['dubl']+1)."', `x2` = '".text($user['x2']*2)."', `gold_dubl` = '".text($user['gold_dubl']*10)."'  WHERE (`id` = '".$myID."')"); 
}

header('Location: /profile/ ');
exit();
}
## dubl end

## Гражданство
if(isset($_GET['g_buy'])){
$id = abs(intval($_GET['g_buy']));
$g_buy = mysql_fetch_assoc(mysql_query("SELECT * FROM `gr` WHERE `id_up` = '".$id."'"));

if($user['g_cena'] < ($dubl['ruby'])){
header('Location: /profile/ ');
exit();
}else{
$g_buy = mysql_fetch_assoc(mysql_query("SELECT * FROM `gr` WHERE `id_up` = '".$id."'"));
mysql_query("UPDATE `users` SET `ruby` = '".text($user['ruby']-$g_buy['g_cena'])."', `g_id` = '".text($user['g_id']+1)."', `g_x2` = '".text($g_buy['g_x2'])."', `g_cena` = '".text($user['g_cena']*2)."'  WHERE (`id` = '".$myID."')"); 
}

header('Location: /profile/ ');
exit();
}
## Гражданство конец 
## Переводы  ##
if(text(isset($_GET['perevod100']))){
if($user['ruby'] < 100){
$_SESSION['msg'] = "Не хватает <img width='20' height='20' src='/img/ruby.png'> ".n_f((100-$user['ruby']))."";
header('Location: /profile/');
exit();
}else{
mysql_query("UPDATE `users` SET `ruby` = '".text($profile['ruby']+100)."' WHERE (`id` = '".$id."')");
mysql_query("UPDATE `users` SET `ruby` = '".text($user['ruby']-100)."' WHERE (`id` = '".$myID."')");
$_SESSION['msg'] = "Вы переввели <img width='20' height='20' src='/img/ruby.png'> ".n_f(100)."";
header('Location: /profile/'.$id.'/');
exit();
}
}


if(text(isset($_GET['pay10']))){
$id = abs(intval($_GET['pay10']));
$pay_10 = ($money_sek_angel*86400);
if($user['ruby'] < 10){
$_SESSION['msg'] = "Не хватает <img width='20' height='20' src='/img/ruby.png'> ".n_f((10-$user['ruby']))."";
header('Location: /profile/');
exit();
}else{
mysql_query("UPDATE `users` SET `ruby` = '".text($user['ruby']-10)."', `gold` = '".text($user['gold']+$pay_10)."' WHERE (`id` = '".$myID."')");
$_SESSION['msg'] = "Вы купили <img width='20' height='20' src='/img/money_36.png'> ".n_f($pay_10)."";
header('Location: /profile/');
exit();
}
}
if(text(isset($_GET['pay30']))){
$id = abs(intval($_GET['pay30']));
$pay_30 = (($money_sek_angel*86400)*7);
if($user['ruby'] < 30){
$_SESSION['msg'] = "Не хватает <img width='20' height='20' src='/img/ruby.png'> ".n_f((30-$user['ruby']))."";
header('Location: /profile/');
exit();
}else{
mysql_query("UPDATE `users` SET `ruby` = '".text($user['ruby']-30)."', `gold` = '".text($user['gold']+$pay_30)."' WHERE (`id` = '".$myID."')");
$_SESSION['msg'] = "Вы купили <img width='20' height='20' src='/img/money_36.png'> ".n_f($pay_30)."";
header('Location: /profile/');
exit();
}
}
if(text(isset($_GET['pay90']))){
$id = abs(intval($_GET['pay90']));
$pay_90 = (($money_sek_angel*86400)*49);
if($user['ruby'] < 90){
$_SESSION['msg'] = "Не хватает <img width='20' height='20' src='/img/ruby.png'> ".n_f((90-$user['ruby']))."";
header('Location: /profile/');
exit();
}else{
mysql_query("UPDATE `users` SET `ruby` = '".text($user['ruby']-90)."', `gold` = '".text($user['gold']+$pay_90)."' WHERE (`id` = '".$myID."')");
$_SESSION['msg'] = "Вы купили <img width='20' height='20' src='/img/money_36.png'> ".n_f($pay_90)."";
header('Location: /profile/');
exit();
}
}
if(text(isset($_GET['pay270']))){
$id = abs(intval($_GET['pay270']));
$pay_270 = (($money_sek_angel*86400)*343);
if($user['ruby'] < 270){
$_SESSION['msg'] = "Не хватает <img width='20' height='20' src='/img/ruby.png'> ".n_f((270-$user['ruby']))."";
header('Location: /profile/');
exit();
}else{
mysql_query("UPDATE `users` SET `ruby` = '".text($user['ruby']-270)."', `gold` = '".text($user['gold']+$pay_270)."' WHERE (`id` = '".$myID."')");
$_SESSION['msg'] = "Вы купили <img width='20' height='20' src='/img/money_36.png'> ".n_f($pay_270)."";
header('Location: /profile/');
exit();
}
}
if(text(isset($_GET['pay810']))){
$id = abs(intval($_GET['pay810']));
$pay_810 = (($money_sek_angel*86400)*2401);
if($user['ruby'] < 810){
$_SESSION['msg'] = "Не хватает <img width='20' height='20' src='/img/ruby.png'> ".n_f((810-$user['ruby']))."";
header('Location: /profile/');
exit();
}else{
mysql_query("UPDATE `users` SET `ruby` = '".text($user['ruby']-810)."', `gold` = '".text($user['gold']+$pay_810)."' WHERE (`id` = '".$myID."')");
$_SESSION['msg'] = "Вы купили <img width='20' height='20' src='/img/money_36.png'> ".n_f($pay_810)."";
header('Location: /profile/');
exit();
}
}
if(text(isset($_GET['pay2430']))){
$id = abs(intval($_GET['pay2430']));
$pay_2430 = (($money_sek_angel*86400)*16807);
if($user['ruby'] < 2430){
$_SESSION['msg'] = "Не хватает <img width='20' height='20' src='/img/ruby.png'> ".n_f((2430-$user['ruby']))."";
header('Location: /profile/');
exit();
}else{
mysql_query("UPDATE `users` SET `ruby` = '".text($user['ruby']-2430)."', `gold` = '".text($user['gold']+$pay_2430)."' WHERE (`id` = '".$myID."')");
$_SESSION['msg'] = "Вы купили <img width='20' height='20' src='/img/money_36.png'> ".n_f($pay_2430)."";
header('Location: /profile/');
exit();
}
}
$title = $profile['login'];
require_once ('system/header.php');
$pay_30 = n_f((($money_sek_angel*86400)*7));
$pay_10 = n_f(($money_sek_angel*86400));
$pay_90 = n_f((($money_sek_angel*86400)*49));
$pay_270 = n_f((($money_sek_angel*86400)*343));
$pay_810 = n_f((($money_sek_angel*86400)*2401));
$pay_2430 = n_f((($money_sek_angel*86400)*16807));



echo'
<div id="user_disp2"></div><div class="content">
<div>
<span>
<span class="nobr">
<span><span class="nobr"><a class="avatar user" href="/profile/'.($profile['id']).' "><span style="display: inline-flex;">'; echo icons_user($profile['id']).'  '.$profile['login'].'  '; echo '</span></a></span></span>'; 
if($profile['id'] == $myID)echo'<img src="/img/ruby.png" width="20px" alt="ruby" /> '.n_f($profile['ruby']).' ';
if($user['access'] > $profile['access'] and $myID != $profile['id'])
    echo' <img src="/img/ruby.png" width="20px" alt="ruby" />'.n_f($profile['ruby']).' ';
    
echo'</span>
</span>
</div>
<div class="minor">';
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clans` WHERE `id` = '".$profile['id_clan']."' LIMIT 1")); 
switch($profile['clan_rang']){ 
case 1: 
$clan_rang = 'стажер'; 
break; 
case 2: 
$clan_rang = 'директор'; 
break; 
case 3: 
$clan_rang = 'акционер'; 
break; 
case 4: 
$clan_rang = 'заместитель'; 
break; 
case 5: 
$clan_rang = "<font color='green'>Владелец</font>"; 
break; 
} 

if($profile['id'] == $myID){
$ava = '<a href = "/avatar.php?new">';
$status = '<a href = "/status.php?new">[изменить]';
}

if($profile['ava'] == NULL){
echo ''.$ava.'<img src="/img/noavatar.png" alt="$" width = "150" ></a> ';
}
else
{
echo ''.$ava.'<img src="/img/avatar/'.text($profile['ava']).'" alt="$" width = "150" ></a> ';
}

echo '</div>';
echo "<div class='comm_link'>";
if($profile['status'] == NULL){
echo 'Статус: <font color="green">Нет статуса '.$status.'</font></a>';
}
else
{
echo 'Статус: <font color="green">'.text_msg($profile['status']).' '.$status.'</font></a>';
}
echo '</div></br>';
echo'<div class="save1">';
echo "Роль в игре: "; 
echo '<font color="#B22222">';
if($profile['access'] == 3){ 
echo "<span class='item-2'>Системный бот <b>Rayb.<font color='green'>Mobi</b></font></span>"; 
}else
if($profile['access'] == 2){ 
echo "<img src='/img/admin.png'  width='24' height='24'><span class='item-2'>Создатель <b>Rayb.<font color='green'>Mobi</b></font></span>"; 
}elseif($profile['access'] == 1){ 
echo "<img src='/img/moder.png'  width='24' height='24'><span class='item-1'>Модератор</span>"; 
}else{ 
echo "<span class='white'>Игрок</span>"; 
} 
echo '</font>';
echo '</br>';
$narush = mysql_result(mysql_query("SELECT COUNT(*) FROM `ban` WHERE `id_user` = '".$profile['id']."'"),0); 
if($profile['id'] != $myID)echo '<li><a href="/narush/'.$profile['id'].'/"> Нарушения ('.$narush.') </li></a>';
$money_updater_ank = mysql_fetch_array(mysql_query("SELECT SUM(money_sek) FROM room_users WHERE id_user = '".$profile['id']."'"));
$cu = mysql_fetch_assoc(mysql_query("SELECT * FROM `clans` WHERE `id` = '".$profile['id_clan']."'")); 
$ac11 = mysql_fetch_array(mysql_query("SELECT SUM(angels) FROM `users` WHERE `id_clan` = '".$profile['id_clan']."'"));
if($profile['id_clan'] >= 1 and $ac11[0] >= 100){
$kol = ($ac11[0]/100);
$u = text($kol*$profile['x2']*$profile['g_x2']);
}else{
if($profile['angels'] < 100){
$u = text($profile['x2']*$profile['g_x2']);
}else{
$koll = text($profile['angels']/100);
$u = text($koll*$profile['x2']*$profile['g_x2']);    
}
}
$pcc = ($money_updater_ank[0]*$u);
$pcms = (($pcc*$cst)/100);
echo '<center>
Доход <img src="/img/money_36.png" alt="$" width="16" height="16"> <span>'.n_f(($money_updater_ank[0]*$u)+$pcms).'</span> в сек
<br>Бизнес-ангелы <img src="/img/angel48.png" alt="$" width="16" height="16"> <span>'.n_f($profile['angels']).'</span><br>';

echo 'Последний сброс: <img src="/img/clock.png" alt="$" width="16" height="16"> <span> '.(tl(time()-$profile['tangels'])).'</span><br>';
if($profile['online'] > time()-300){ 
echo '<font color="green"><span>Сейчас на сайте</span></font>';
}else{
echo 'Последний вход: <img src="/img/clock.png" alt="$" width="16" height="16"> <span> '.(time_last($profile['online'])).' </span>';
}
 echo ' <br> <div>Начал игру: <img src="/img/clock.png" alt="$" width="16" height="16"> <span> '.time_last($profile['registr']).' </span> (ID: '.($profile['id']).' )</div>
</center>';



## Гражданство
switch($profile['g_id']){ 
case 1: 
$g_name = 'Прописку'; 
break; 
case 2: 
$g_name = 'Вид на жительство'; 
break; 
case 3: 
$g_name = 'Гражданство'; 
break; 
}
if($profile['id'] == $myID){
if($user['g_id'] < 4 && $user['g_id'] == $user['g_id']){
if($user['ruby'] > $user['g_cena']){
echo'<div class="center">
<a href="/gr/'.$user['g_id'].'/'.rand(1000,9999).'" class="btnl mt4"> <span>Оформить '.$g_name.' за <img src="/img/ruby.png" alt="$" width="16" height="16"/>'.n_f($user['g_cena']).'</span> </a>
</div>';
}else{
echo'<div class="center">
<span class="btnl mt4"> <span>Оформить '.$g_name.' за <img src="/img/ruby.png" alt="$" width="16" height="16"/>'.n_f($user['g_cena']).'</span> </span>
</div>';
}
}else{

}
}else{   
}
if($profile['g_id'] == 4){
echo'<div class="center">';
echo'<span><img src="/img/citizenship_64x64.png" alt="$" width="24" height="24"/>Гражданин Рейба</span>';
echo'</div>';
}
echo'</div>';

   

if($profile['id'] != $myID)echo'<a class="btnl mt4" href="/mail/'.$profile['id'].'" class="btn"><img src="/img/mail_white.png" alt="*" width="20" height="20"/> Написать сообщение</a>';

if($profile['id'] != $myID)echo'<a class="btnl mt4" href="/gifts/'.$id.'?do" class="btn"><img width="20" height="20" alt="" src="/img/podarok.png" title=""/> Подарить подарок '.$profile['login'].'</a>';
if($profile['id'] != $myID)echo'<a class="btnl mt4" href="/freinds/'.$id.'?add" class="btn"><img width="20" height="20" alt="" src="/img/friends.png" title=""/> Пригласить в друзья '.$profile['login'].'</a>';

$angels = text(ceil(100*sqrt(($money_sek_angel*$tat)/1000000000)));







if($profile['id'] == $myID and $money_sek_angel > 100 and $angels > 10)
echo'<a class="btnl mt4" href="/profile/?angl">Сбросить бизнесы и получить <img src="/img/angel48.png" alt="$" width="24" height="24"> '.n_f($angels).' </a>
';

if($profile['id'] == $myID and $money_sek_angel > 100 and $angels > 10)
echo'<a class="btnl mt4" href="/profile/?angl_ruby">Сбросить бизнесы за 250 <img src="/img/ruby.png" alt="$" width="24" height="24"> и получить <img src="/img/angel48.png" alt="$" width="24" height="24"> '.n_f($angels).' </a>
';


if($profile['id'] == $myID)echo'<a class="btnl mt4" href="/payment/" class="btn"><img width="20" height="20" alt="" src="/img/ruby.png" title=""/> Купить рубины</a>';



if($profile['id'] == $myID){
echo'<div class="center">';
echo'<a class="btni" style="min-width:135px;margin:4px;" href="/profile/pay/10/"><span><img src="/img/money_36.png" alt="$" width="16" height="16"/> <span>'.$pay_10.'</span></span> <span>за <span><img width="24" height="24" alt="рубины" src="/img/ruby.png" title="рубины"/><span>10</span></span></span></a>';
echo'<a class="btni" style="min-width:135px;margin:4px;" href="/profile/pay/30/"><span><img src="/img/money_36.png" alt="$" width="16" height="16"/> <span>'.$pay_30.'</span></span> <span>за <span><img width="24" height="24" alt="рубины" src="/img/ruby.png" title="рубины"/><span>30</span></span></span></a><br>';
echo'<a class="btni" style="min-width:135px;margin:4px;" href="/profile/pay/90/"><span><img src="/img/money_36.png" alt="$" width="16" height="16"/> <span>'.$pay_90.'</span></span> <span>за <span><img width="24" height="24" alt="рубины" src="/img/ruby.png" title="рубины"/><span>90</span></span></span></a>';
echo'<a class="btni" style="min-width:135px;margin:4px;" href="/profile/pay/270/"><span><img src="/img/money_36.png" alt="$" width="16" height="16"/> <span>'.$pay_270.'</span></span> <span>за <span><img width="24" height="24" alt="рубины" src="/img/ruby.png" title="рубины"/><span>270</span></span></span></a><br>';
echo'<a class="btni" style="min-width:135px;margin:4px;" href="/profile/pay/810/"><span><img src="/img/money_36.png" alt="$" width="16" height="16"/> <span>'.$pay_810.'</span></span> <span>за <span><img width="24" height="24" alt="рубины" src="/img/ruby.png" title="рубины"/><span>810</span></span></span></a>';
echo'<a class="btni" style="min-width:135px;margin:4px;" href="/profile/pay/2430/"><span><img src="/img/money_36.png" alt="$" width="16" height="16"/> <span>'.$pay_2430.'</span></span> <span>за <span><img width="24" height="24" alt="рубины" src="/img/ruby.png" title="рубины"/><span>2430</span></span></span></a>';
}else{
}


## Двойной доход
if($profile['id'] == $myID){
if($user['dubl'] < 70){
if($user['angels'] > $user['gold_dubl']*2){
echo'<div class="center">
<a href="/dx2/'.$user['dubl'].'/" marsgame_rewrite_money="'.n_f($user['gold_dubl']).'" class="btnl mt4"> <span>x2 <img src="/img/angel48.png" alt="$" width="20" height="20"/> '.n_f($user['gold_dubl']).'</span> </a>
</div>';
}else{
echo'<div class="center">
<span class="btnl mt4"> <span>x2 <img src="/img/angel48.png" alt="$" width="20" height="20"/> '.n_f($user['gold_dubl']).'</span> </span>
</div>';
}
}else{
echo'<div class="center">';
echo'<a class="subl" style="display: inline-block;min-width: 220px;">';
echo'<span>В разработке</span>';
echo'</a>';
echo'</div>';
echo'<div class="cb"></div>';
}
}else{    
}
if($profile['id'] != $myID)echo'<a class="btnl mt4" href="/gifts/'.$id.'?g" class="btn"><img width="20" height="20" alt="" src="/img/podarok.png" title=""/> Подарки игрока: '.$profile['login'].'</a>';
if($profile['id'] == $myID)echo'<a class="btnl mt4" href="/gifts/?my" class="btn"><img width="20" height="20" alt="" src="/img/podarok.png" title=""/> Мои подарки</a>';
if($profile['id'] == $myID)echo'<a class="btnl mt4" href="/freinds/?my" class="btn"><img width="20" height="20" alt="" src="/img/friends.png" title=""/> Мои друзья</a>';
if($profile['id'] == $myID)echo'<a class="btnl mt4" href="/ref.php" class="btn"><img width="20" height="20" alt="" src="/img/ref.png" title=""/> Мои реф.пользователи</a>';
if($profile['id'] != $myID)echo'<a class="btnl mt4" href="/transfer.php?id='.$id.'" class="btn"><img width="20" height="20" alt="" src="/img/ruby.png" title=""/> Передать рубины</a>';

echo'</div>';
if($clan){ 
echo'<a class="btnl mt4" href="/corp/'.$clan['id'].'/"><img src="/img/corp2.png" width="24" height="24" alt="" /> Корпорация "'.$clan['name'].'"</a><div class="minor center">Должность - '.$clan_rang.' | Ангела -  '.n_f($acpp[0]).'</div>';
}
$clan_col = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `id_clan` = '".$clan['id']."'"),0);
$clan = mysql_fetch_assoc(mysql_query("SELECT * FROM `clans` WHERE `id` = '".$user['id_clan']."'"));
$kol_user = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `id_clan` = '".$user['id_clan']."'"),0);
if($myID != $profile['id'] and $user['clan_rang'] > 2 and $profile['id_clan'] == 0 and $clan['max'] > $kol_user and mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_memb` WHERE `id_user` = '$id' AND `id_clan` = '".$user['id_clan']."'"),0) == 0){
echo "<li><a class='btnl mt4' href='?clan_memb' class='btn'>Пригласить в корпорацию</li></a>";}
if($user['access'] > $profile['access'] and $myID != $profile['id'])echo "<li><a class='btnl mt4' href='/ban/".$profile['id']."/' class='btn'><font color='black'><b>Бан</b></font></a></li>";
if($user['access'] > $profile['access'] and $myID != $profile['id'])echo "<div class='feedback'><li>IP-адрес: <span class='title'>".$profile[ip]."</span></li></div>";

if($profile['id'] == $myID) echo'<a class="btnl mt4" href="/narush/'.$profile['id'].'/" class="btn"> Нарушения ('.$narush.')</a>';
if($profile['id'] == $myID) echo'<a class="btnl mt4" href="/settings" class="btn"><span><img src="/img/accept.png" alt="$" width="16" height="16"/> <span> Настройки</a>';
if($profile['id'] == $myID) echo'<a class="btnl mt4" href="/ref_top.php" class="btn"><span><img src="/img/1.png" alt="$" width="16" height="16"/> <span> Рейтинг Рефералов</a>';
if($profile['id'] == $myID) echo'<a class="btnl mt4" href="/settings?exit" class="btn"><img width="20" height="20" alt="" src="/img/cross.png" title=""/> Выход из игры</a>';
require_once ('system/footer.php');
?>