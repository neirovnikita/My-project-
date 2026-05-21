<?php
require_once ('system/func.php');
$title = 'Онлайн игра Рейб';
if ($user['pass'] == NULL)echo "<div class='feedback'><div class='minor mt4'>
Внимание!!!</br> На сайте меняется система паролей! Укажите новый пароль!<br><span>".$clan['name']."</span><br>
<a href='/up_pass.php' class='btni'>Указать пароль/Информация</a>
</div></div>";
require_once ('system/header.php');
auth(); // Закроем от не авторизованных

$rooms = mysql_fetch_assoc(mysql_query("SELECT * FROM `room_users` WHERE `id_user` = '".$myID."' ORDER by id DESC  "));

if ($rooms[id_room] != NULL){
$rooms_new = text($rooms['pay']*10);
$rooms_id = text($rooms['id_room']+1);
if ($rooms[id_room] == 2)
$room_sek = text($rooms['id_room']*2);
else
$room_sek = text($rooms['pay_money_sek']*2);
}else{
$rooms_new = text(1);
$rooms_id = text(1);
$room_sek = text(1);
}
$acp = mysql_fetch_array(mysql_query("SELECT SUM(angels) FROM `users` WHERE `id_clan` = '".$user['id_clan']."'"));
if($user['id_clan'] >= 1 and $acp[0] >= 100){
$kol = text($acp[0]/100);
$p = text($kol*$user['x2']*$user['g_x2']);
}else{
if($user['angels'] < 100){
$p = text($user['x2']*$user['g_x2']);
}else{
$koll = text($user['angels']/100);
$p = text($koll*$user['x2']*$user['g_x2']);    
}
}

if(isset($_GET['up_auto'])){
$dirs = mysql_query("SELECT * FROM `room_users` WHERE `id_user` = '".$myID."' ORDER BY `id_room` DESC");
while($post = mysql_fetch_assoc($dirs)) {

$dirs2 = mysql_query("SELECT * FROM `room_users` WHERE `id_user` = '".$myID."' and `id_room` = '".$post['id_room']."' ORDER BY `id_room` DESC");
while($post2 = mysql_fetch_assoc($dirs2)) {
	$up_count2 = ceil(($user['gold']/$post2['pay_update']-1));
if ($up_count2 <= 1500)
		$up_count = ceil(($user['gold']/$post2['pay_update']-1));
else	
		$up_count = text(1500);
$x10_up_cena = text($post2['pay_update']*$up_count);
$x10_level = text($up_count);
$x10_up_sek = text($post2['pay_money_sek_up']*$up_count);
$x10_pay_up = text($post2['pay']*$up_count);
$tss = $user['gold']-$x10_up_cena;

if ($user['gold'] > $tss && $post2[level] <= 1000000) {
mysql_query("UPDATE `room_users` SET `level` = `level`+$x10_level,`money_sek`=`money_sek`+'".text($x10_up_sek)."', `give_money_sek`=`give_money_sek`+('".text($x10_up_sek)."'), `pay_update` =`pay_update`+'".text($x10_pay_up)."'  WHERE  `id_user` = '".text($myID)."'  and`id_room` = '".text($post2['id_room'])."'");
mysql_query("UPDATE `users` SET `gold` = '".text($money_updater[0])."' WHERE `id` = '".$myID."'");

header("Location: ".$_SERVER['HTTP_REFERER']);
}else{
header("Location: ".$_SERVER['HTTP_REFERER']);
}
}
}
}
if(isset($_GET['up_x10'])){
$room = mysql_fetch_assoc(mysql_query("SELECT * FROM `room_users` WHERE `id_user` = '".text($myID)."' AND `id_room` = '".text($_GET['up_x10'])."'"));

$room_pay10 = text($room[pay_update]+($room[pay_update] +($room[pay_update]*2)+($room[pay_update]*3)+($room[pay_update]*4)+($room[pay_update]*5)+($room[pay_update]*6)+($room[pay_update]*7)+($room[pay_update]*8)+($room[pay_update]*9)+($room[pay_update]*10)));
$x10_up_cena = text($room_pay10);
$x10_up_sek = text($room[pay_money_sek_up] *10);
$x10_level = text(10); 
$x10_pay_up = text($room[pay] * 10);


while ($user['gold'] > $x10_up_cena && $room['level'] <= 1000000) {
mysql_query("UPDATE `room_users` SET `level` = `level`+$x10_level,`money_sek`=`money_sek`+'".text($x10_up_sek)."', `give_money_sek`=`give_money_sek`+('".text($x10_up_sek)."'), `pay_update` =`pay_update`+'".text($x10_pay_up)."'  WHERE  `id_user` = '".text($myID)."'  and`id_room` = '".text($_GET['up_x10'])."'");
mysql_query("UPDATE `users` SET `gold` = `gold`-'".text($x10_up_cena)."' WHERE `id` = '".text($myID)."'");
header ("Location: ".$_SERVER['HTTP_REFERER']);
break;

  }
}

if(isset($_GET['up_x100'])){
$room = mysql_fetch_assoc(mysql_query("SELECT * FROM `room_users` WHERE `id_user` = '".$myID."' AND `id_room` = '".text($_GET['up_x100'])."'"));

$room_pay100 = text($room[pay_update]+($room[pay_update] +($room[pay_update]*2)+($room[pay_update]*3)+($room[pay_update]*4)+($room[pay_update]*5)+($room[pay_update]*6)+($room[pay_update]*7)+($room[pay_update]*8)+($room[pay_update]*9)+($room[pay_update]*10))*10);
$x100_up_cena = text($room_pay100);
$x100_up_sek = text($room[pay_money_sek_up] *100);
$x100_level = text(100); 
$x100_pay_up = text($room[pay] * 100);


while ($user['gold'] > $x100_up_cena && $room['level'] <= 1000000) {
mysql_query("UPDATE `room_users` SET `level` = `level`+$x100_level,`money_sek`=`money_sek`+'".text($x100_up_sek)."', `give_money_sek`=`give_money_sek`+('".text($x100_up_sek)."'), `pay_update` =`pay_update`+'".text($x100_pay_up)."'  WHERE  `id_user` = '".text($myID)."'  and`id_room` = '".text($_GET['up_x100'])."'");
mysql_query("UPDATE `users` SET `gold` = `gold`-'".text($x100_up_cena)."' WHERE `id` = '".text($myID)."'");
header ("Location: ".$_SERVER['HTTP_REFERER']);
break;

  }
}

if(isset($_GET['up_room'])){
$room = mysql_fetch_assoc(mysql_query("SELECT * FROM `room_users` WHERE `id_user` = '".text($myID)."' AND `id_room` = '".text($_GET['up_room'])."'"));
	if($user['gold'] <= $room['pay_update']){
	$_SESSION['msg'] = "Не достаточно средств на балансе";
header ("Location: ".$_SERVER['HTTP_REFERER']);
exit();	
	}

mysql_query("UPDATE `room_users` SET `level` = `level`+1,`money_sek`=`money_sek`+'".text($room['pay_money_sek_up'])."', `give_money_sek`=`give_money_sek`+'".text($room['pay_money_sek_up'])."', `pay_update` =`pay_update`+'".text($room['pay'])."'  WHERE `id_user` = '".text($myID)."' AND `id_user` = '".text($myID)."'  and `id_room` = '".text($_GET['up_room'])."'");
mysql_query("UPDATE `users` SET `gold` = `gold`-'".$room['pay_update']."' WHERE `id` = '".$myID."'");
header ("Location: ".$_SERVER['HTTP_REFERER']);
}
if(isset($_GET['by_room'])){
	if($user['gold'] < $rooms_new){
$_SESSION['msg'] = "Произошла ошибка.Попробуйте позже";
header('Location: /game/ ');
exit();
}
mysql_query("UPDATE `users` SET `gold` = `gold`-$rooms_new WHERE `id` = '".$myID."'");
mysql_query("INSERT INTO `room_users` SET `id_user` = '".text($myID)."' , `money_sek` = '".text($room_sek)."', `give_money_sek`='".text($room_sek*$p)."',`pay_money_sek_up` = '".text($room_sek)."', `pay` = '".text($rooms_new)."', `pay_update` = '".text($rooms_new)."' ,`id_room` = '".text($rooms_id)."',`pay_money_sek` = '".text($room_sek)."' ");
$_SESSION['msg'] = "Вы успешно купили новую комнату";
header('Location: /game/ ');
}
echo' <div class="content"></div>';
$clan_memb = mysql_fetch_array(mysql_query("SELECT * FROM `clan_memb` WHERE `id_user` = '".$myID."' ORDER BY `id` DESC LIMIT 1"));
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clans` WHERE `id` = '".$clan_memb['id_clan']."' ORDER BY `id` DESC LIMIT 1"));
if($clan_memb){
if(isset($_GET['clan_memb_net'])){
mysql_query("DELETE FROM `clan_memb` WHERE `id` = '".$clan_memb['id']."'");
header('Location:/'); 
exit(); 
}
if(text(isset($_GET['clan_memb_ok'])) and $user['side'] == $clan['side']){
mysql_query("insert into `clan_histor` set `id_clan` = '".text($clan['id'])."', `data` = '".time()."', `text` = '<a href=/profile/".text($clan_memb['id_user2']).">".text($clan_memb['login'])."</a> Принял <a href=/profile/".text($myID).">".text($user['login'])."</a>'");
mysql_query("UPDATE `users` SET `id_clan` = '".text($clan['id'])."', `clan_rang` = '1' WHERE `id` = '".text($myID)."'");
mysql_query("DELETE FROM `clan_memb` WHERE `id_user` = '".$myID."'");
header('Location: /corp/');
exit();
}
echo "<div class='feedback'><div class='minor mt4'>";
echo "Вас приглашают в корпорацию<br><span>".$clan['name']."</span><br>";
echo "<a href='?clan_memb_ok' class='btni'>Принять заявку</a><br><a href='?clan_memb_net'>Отменить</a>"; 
echo "</div></div>";
}
require_once ('bonus.php');
$news = mysql_query("SELECT * FROM `topic` WHERE `id_forum` = '1' ORDER BY `id` DESC LIMIT 1");
$news = mysql_fetch_array($news);
if($news){
if(text(isset($_GET['header_news']))){
mysql_query("update `users` set `news_read` = '0' where (`id` = '".$myID."')");
header("Location: /forum/sub/".$news['id']."?page=end");
exit();
}
if($user['news_read'] == 1 && $news['time'] > time()-86400){
echo "<div class='bordered'><center>";
echo "<div class='admin'>".$news['name']."</div>";
echo'<a class="btni" href="?header_news" style="margin-top: 3px; width: 140px;"> Перейти к новости</a><br>';
echo "<a href='?news_read' class='grey' data-ajax>Скрыть</a>";
echo "</div><div class='content'></div></center>";
}
}

echo'<div class="center">
<a href="/game/?up_auto" marsgame_rewrite_money="'.n_f($rooms_new).'" id="mbut" class="btni" style="display: inline-block; width: 158px; margin: 4px;"> <span>Автопрокачка</span> </a>
</div>';

if($user['gold'] >= $rooms_new){
echo'<div class="center">
<a href="/game/?by_room" marsgame_rewrite_money="'.n_f($rooms_new).'" id="mbut" class="btni" style="display: inline-block; width: 158px; margin: 4px;"> <span>Новый бизнес <img src="/img/money_36.png" alt="$" width="16" height="16"/>'.n_f($rooms_new).'</span> </a>
</div>';
}else{
echo'<div class="center">
<span class="btni" style="display: inline-block; width: 158px; margin: 4px;"> <span>Новый бизнес <img src="/img/money_36.png" alt="$" width="16" height="16"/>'.n_f($rooms_new).'</span> </span>
</div>';
}

$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `room_users` WHERE `id_user` = '".$myID."'"),0);
 
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `room_users` WHERE `id_user` = '".$myID."' ORDER BY `id` DESC LIMIT ".$start.", ".$set['p_str']."");
if ($k_post <= 0){
echo'<div class="feedback"><div class="minor mt4">У вас нет Бизнеса. Нажмите Новый бизнес, чтобы начать зарабатывать.</div></div>';
}
while($post = mysql_fetch_assoc($q)) {
	
	$rooms = mysql_fetch_assoc(mysql_query("SELECT * FROM `room_users` WHERE `id_room` = '".$post[id_room]."' ORDER by id_room DESC LIMIT 1 "));
	$x10_up_cena = text($room_pay10);
$room_pay10 = text($post[pay_update]+($post[pay_update]+($post[pay_update]*2)+($post[pay_update]*3)+($post[pay_update]*4)+($post[pay_update]*5)+($post[pay_update]*6)+($post[pay_update]*7)+($post[pay_update]*8)+($post[pay_update]*9)+($post[pay_update]*10)));
$room_pay100 = text($post[pay_update]+($post[pay_update]+($post[pay_update]*2)+($post[pay_update]*3)+($post[pay_update]*4)+($post[pay_update]*5)+($post[pay_update]*6)+($post[pay_update]*7)+($post[pay_update]*8)+($post[pay_update]*9)+($post[pay_update]*10))*10);

	if($user['gold'] > $post[pay_update] && $post[level] <= 1000000){
echo'
<div style="margin: 0 0 0 0; position: relative;">
<div style="display: inline;" class="fl"> 
<div class="left small_mode_1" style="padding:0 0 0 0;margin: 0 0 0 0;">
<p class="col"><b>'.$post[id_room].'</b></p> </div>
<div class="left small_mode_1" style="margin: 0 0 0 0;"> <a href="/game/?up_room='.$post[id_room].'" class="btni small_mode_1 marsgame_rewrite_money" style="width:100px;" marsgame_rewrite_money="'.n_f($gold).'" > 
<img src="/img/money_36.png" alt="$" width="16" height="16"/> <span>'.n_f($post[pay_update]).'</span> </a> ';
if($user['gold'] > $room_pay10 && $post['level'] <= 1000000){
echo '<a href="/game/?up_x10='.$post[id_room].'" marsgame_rewrite_money="'.n_f($gold).'" style="padding-left: 4px;padding-right: 4px;" class="btni small_mode_1 marsgame_rewrite_money">
x10</a>';
}else{
echo '<span href="/game/up/10/'.$id.'/" marsgame_rewrite_money="'.n_f($gold).'" style="padding-left: 4px;padding-right: 4px;" class="btni small_mode_1 marsgame_rewrite_money">x10</span>';
}
if($user['gold'] > $room_pay100 && $post['level'] <= 1000000){
echo '<a href="/game/?up_x100='.$post[id_room].'" marsgame_rewrite_money="'.n_f($gold).'" style="padding-left: 4px;padding-right: 4px;" class="btni small_mode_1 marsgame_rewrite_money">
x100</a>';
}else{
echo '<span href="/game/up/100/'.$id.'/" marsgame_rewrite_money="'.n_f($gold).'" style="padding-left: 4px;padding-right: 4px;" class="btni small_mode_1 marsgame_rewrite_money">x100</span>';
}
echo ' </div> </div>'; 

echo '<span class="small tbrown" style="float: right;"> 
<img src="/img/money_36.png" alt="$" width="16" height="16"/> 
<span>'.n_f($post['money_sek']).' в сек</span>
<span class="center biss_right">'.$post['level'].'</span> 
</span> <div class="cb"></div> </div>';
}else{
echo'
<div style="margin: 0 0 0 0; position: relative;"> 
<div style="display: inline;" class="fl"> 
<div class="left small_mode_1" style="padding:0 0 0 0;margin: 0 0 0 0;"> 
<p class="col"><b>'.$post[id_room].'</b>
</p></div>
<div class="left small_mode_1" style="margin: 0 0 0 0;"> 
<span href="/game/up/'.$post[id_room].'/" class="btni small_mode_1 marsgame_rewrite_money" style="width:100px;" marsgame_rewrite_money="'.n_f($gold).'" > 
<img src="/img/money_36.png" alt="$" width="16" height="16"/> <span>'.n_f($post[pay_update]).'</span></span>
<span href="/game/up/10/'.$id.'/" marsgame_rewrite_money="'.n_f($gold).'" style="padding-left: 4px;padding-right: 4px;" class="btni small_mode_1 marsgame_rewrite_money">x10</span>
<span href="/game/up/100/'.$id.'/" marsgame_rewrite_money="'.n_f($gold).'" style="padding-left: 4px;padding-right: 4px;" class="btni small_mode_1 marsgame_rewrite_money">x100</span>
  </div> </div> 
<span class="small tbrown" style="float: right;"> <img src="/img/money_36.png" alt="$" width="16" height="16"/> 
<span>'.n_f($post['money_sek']).' в сек</span><span class="center biss_right">'.$post['level'].'</span> </span> <div class="cb"></div> </div>';
}
}
if($k_post > 10){
str('?',$k_page,$page); // Вывод страниц
}

require_once ('system/footer.php');
?>