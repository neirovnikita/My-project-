<?php
require_once ('system/func.php');
auth(); // Закроем от не авторизованных

# Настройки #
$id = abs(intval($_GET['id']));
if($id)$clan = mysql_fetch_assoc(mysql_query("SELECT * FROM `clans` WHERE `id` = '".$id."'"));
else $clan = mysql_fetch_assoc(mysql_query("SELECT * FROM `clans` WHERE `id` = '".$user['id_clan']."'"));
# Ошибки #
if(!$clan){
$_SESSION['msg'] == 'Такой корпорации не существует';
header('Location: /corps/');
exit();
}

### KP ####
$acorp = mysql_fetch_array(mysql_query("SELECT SUM(angels) FROM `users` WHERE `id_clan` = '".$clan['id']."'"));
$kol_user = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `id_clan` = '".$clan['id']."'"),0);

if ($user['online'] > 1){
	$times_up = ((time() - $user['online']) + ($acorp[0]-1));
mysql_query("UPDATE `clans` SET `rate_angels` = '".$times_up."' WHERE `id` = '".$clan['id']."'");
}

if(isset($_GET['chat']) and $user['id_clan'] == $clan['id']){
$title = 'Чат корпорации';
require_once ('system/header.php');
if(isset($_GET['text'])){
if(isset($_POST['text'])){
$text = text($_POST['text']);
$ban = mysql_query('SELECT * FROM `ban` WHERE `id_user` = "'.$myID.'" AND `last` > "'.time().'" ORDER BY `id` DESC LIMIT 1');
$ban = mysql_fetch_array($ban);
if(strlen($text) < 2 or strlen($text) > 500)$err = 'Длина сообщения должна быть в пределах 2-500 символов';
if($ban)$err = "На вас наложен, бан осталось ".tl($ban['last']-time());
if(!$err){
mysql_query("INSERT INTO `clanchat` SET `id_user` = '".$user['id']."', `time` = '".time()."', `text` = '".text($text)."', `id_clan` = '".$clan['id']."'");
$_SESSION['msg'] = 'Сообщение отправлено';
header('Location: /corp/chat/');
exit();
}else{
$_SESSION['msg'] = $err;
header('Location: /corp/chat/');
exit();
}
}else{
$_SESSION['msg'] = 'Введите сообщение';
header('Location: /corp/chat/');
exit();
}
}
if(isset($_GET['del'])){
$id = abs(intval($_GET['del']));
$title = 'Очистить чат?';
require_once ('system/header.php');
echo "<div class='content'></div>";
echo "<div class='bordered'><center>Очистить чат?<br>";
echo'<a class="btni" href="?delete_chat" style="margin-top: 3px; width: 140px;"> Да, очистить</a><br>';
echo "<a href='/corp/chat/' class='grey' data-ajax>Нет, отмена</a>";
echo "</div></center>";
require_once ('system/footer.php');
exit();
}
if(isset($_GET['delete_chat']) and $user['access'] > 0){
mysql_query("DELETE FROM `clanchat` WHERE `id_clan` = '".$clan['id']."'");$_SESSION['msg'] = 'Чат очищен';
mysql_query("UPDATE `users_count` SET `cchat` = '1'");
$text = "Очистил чат [url=/profile/".$user['id']."]".$user['login']."[/url].";
mysql_query("INSERT INTO `clanchat` SET `id_user` = '2', `time` = '".time()."', `text` = '".text($text)."', `id_clan` = '".$clan['id']."'");
header('Location: ?');
exit();
}
if(isset($_GET['delete_post']) and $user['clan_rang'] >= 4){
mysql_query("DELETE FROM `clanchat` WHERE `id` = '".text($_GET['delete_post'])."'");
$_SESSION['msg'] = 'Пост удален';
header('Location: ?');
exit();
}

echo "<div class='content'></div>";

if(text(isset($_GET['to']))){
echo "<form method='post' action='?text=".$_GET['to']."'>";
$opponent = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = ".$_GET['to']." LIMIT 1"));
echo "<div class='bordered'><center>";
echo '<div class="center mt4">Сообщение:<textarea rows="5" id="textarea" style="width: 95%;" name="text" maxlength="500" minlength="0"> '.$opponent['login'].', </textarea><br></center>';

if ($clan['id'] == $user['id_clan']  and $user['clan_rang'] >= 4)echo'<a class="btnl mt4" href="?del" class="btn"> Очистить чат</a>';
echo "<input class='btnl mt4' type='submit' class='btn' value='Отправить'>
</form></div>";

}else{
echo "<div class='bordered'><center>"; 
echo "<form method='post' action='?text'>";
echo '<div class="center mt4">Сообщение:<br><textarea rows="5" id="textarea" style="width: 95%;" name="text" maxlength="500" minlength="0"></textarea><br></center>';
if ($clan['id'] == $user['id_clan']  and $user['clan_rang'] >= 4)echo'<a class="btnl mt4" href="?del" class="btn"> Очистить чат</a>';
echo "<input class='btnl mt4' type='submit' class='btn' value='Отправить'>
</form></div>";
}

echo "</div>";
echo "<div class='content'></div>";
$set['p_str'] = 20;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `clanchat` WHERE `id_clan` = '".$clan['id']."'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `clanchat` WHERE `id_clan` = '".$clan[id]."' ORDER BY `id` DESC LIMIT ".$start.", ".$set['p_str']."");
echo"<div class ='bordered'>";
if($k_post == 0) echo "<div class='feedback'>Сообщений не найдено. Будешь первым?</div>";
while($post = mysql_fetch_assoc($q)) {
$ank = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$post['id_user']."' LIMIT 1"));
echo icons_user($ank['id'])." <a href='/profile/".$ank['id']."'>".$ank['login']."</a>";
if($myID != $ank['id'])echo " <a href='?to=".$ank['id']."'>(»)</a>";
echo':';
echo " ".text_msg($post['text']);
if($user['clan_rang'] >= 4)echo " <a href='?delete_post=".$post['id']."'><font color='red'>(x)</font></a>";
echo'<br>';
}
if($k_post > 20){
str('?',$k_page,$page); // Вывод страниц
}else{}
echo "</div>";
if (isset($user))
mysql_query("UPDATE `users_count` SET `cchat` = '1' WHERE `id_user` = '$user[id]'");
require_once ('system/footer.php');
exit();
}
## Авка кп

if (isset($_GET['upload_gerb'])){
	if($_FILES['ava']['error']>0)$err = 'Пустое тело загрузки';
if($_FILES['ava']['size']>(10000*10000))$err = 'Большой размер аватара';
$info=@getimagesize($_FILES['ava']['tmp_name']);
$fname=htmlspecialchars($_FILES['ava']['name']);
if($_FILES['ava']==NULL)$err = 'Аватар не загружен';
$t=strtolower(eregi_replace('^.*\.', NULL, $fname));
$xt=array ("jpg","jpeg","gif","png","webp");
if (!in_array($t, $xt))$err = 'Разрешено загружать только картинки, в формате jpg, jpeg, gif, png, webp';
if($clan['sclad_ruby'] < 1000)$err = "На складе нет 1к <img width='20' height='20' src='/img/ruby.png'> для смены Кланового герба!";
$tmp=$_FILES['ava']['tmp_name'];
$ra=mt_rand(0,25);
$fname=$clan['id'].'.'.$t;

if (!isset($err))
{
move_uploaded_file($tmp,'img/clangerb/'.$fname);
mysql_query("update `clans` set `gerb` = '".$fname."',`sclad_ruby` = '".($clan['sclad_ruby']-1000)."' where `id` = '".$clan['id']."'");
$_SESSION['msg'] = 'Клановый герб успешно установлен';
header('Location: /corp/');
exit();
}else{
$_SESSION['msg'] = $err;
header('Location: ?');
exit();
}
}


if(isset($_GET['gerb']) and $user['id_clan'] = $clan['id'] and $user['clan_rang'] == 5){
if(isset($_GET['gerb_kup'])){
$gerb = text($_GET['gerb_kup']);
if($clan['sclad_ruby'] < 1000){
$_SESSION['msg'] = "На складе нет 1к <img width='20' height='20' src='/img/ruby.png'> для смены Кланового герба!";
header('Location: ?');
exit();
}
mysql_query("update `clans` set `gerb` = '".$gerb.".png',`sclad_ruby` = '".text($clan['sclad_ruby']-1000)."' where (`id` = '".$clan['id']."')");

$_SESSION['msg'] = 'Клановый герб успешно установлен';
header('Location: /corp/');
exit();
}
$title = 'Магазин клановых гербов';
require_once ('system/header.php');
echo "<div class='block2'>";

echo "</br><div class='message'>Загрузить Аватар:</br><form method='post' enctype='multipart/form-data' action='?upload_gerb'>\n";
echo '
<input type="file"  name="ava"  accept="image/*,image/gif,image/png,image/jpeg" />
';
echo "<input value=\"Загрузить Аватар\" type=\"submit\" />\n";

echo "</div></div>";
echo "<div class='block'>Стоимость кланового герба составляет 1к <img width='20' height='20' src='/img/ruby.png'> </div>";
$clan = ''; require_once ('system/footer.php');
exit();
}
## КОнец Авки
if(isset($_GET['kazna']) and $user['id_clan'] == $clan['id']){
if(isset($_GET['ruby'])){
$title = 'Рейтинг по рубинам';
require_once ('system/header.php');
$set['p_str'] = 15;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `id_clan` = '".$clan['id']."' AND `clan_ruby` > '0'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `users` WHERE `id_clan` = '".$clan['id']."' AND `clan_ruby` > '0' ORDER BY `clan_ruby` DESC LIMIT ".$start.", ".$set['p_str']."");
echo "<div class='block'>";
if($k_post == 0)echo "Ещё не кто не сдал рубины в казну";
while($post = mysql_fetch_assoc($q)) {
echo icons_user($post['id'])." <a href='/profile/".$post['id']."'>".$post['login']."</a> ".ico('icons','gold.png')." ".n_f($post['clan_ruby'],1)." рубинов<br>";
}
if($k_post > 10){ 
str('?gold&',$k_page,$page); // Вывод страниц
}else{
}
echo "</div>";
echo "<a href='?' class='link'>".ico('icons','arrow.png')." Вернуться назад</a>";
require_once ('system/footer.php');
break;
}
if(isset($_GET['vznos'])){
if(isset($_POST['ruby'])){
$ruby = text($_POST['ruby']);
if($ruby > $user[ruby])$err = 'У вас не достаточно средств';
if($ruby < 0)$err = 'Ошибка взноса средств';
if(!$err){
mysql_query("update `users` set `gold` = '".text($user['ruby']-$ruby)."' `clan_ruby` = '".text($user['clan_ruby']+$ruby)."' where (`id` = '".$myID."')");
mysql_query("update `clans` set `gold` = '".text($clan['ruby']+$ruby)."' where (`id` = '".$clan['id']."')");
$_SESSION['msg'] = 'Склад клана успешно пополнена';
header('Location: ?');
exit();
}else{
$_SESSION['msg'] = $err;
header('Location: ?');
break;
}
}else{
$_SESSION['msg'] = 'Поля не заполнены';
header('Location: ?');
exit();
}
}
$title = 'Казна клана';
require_once ('system/header.php');
echo "<div class='block center'>";
echo n_f($clan['ruby']);
echo "<a href='?ruby' class='btn2'>Рейтинг рубинов</a>";
echo "</div>";
echo '<div class="line"></div><div class="footer small"><center>Пополнение казны</center></div><div class="line"></div>';
echo "<div class='block'>";
$last = $limit_clan_kazna['last']+60*60*24;
echo "<div class='center'>Лимит обновится через ".tl($last-time())."</div><hr>";
echo "<form method='post' action='?vznos'>";
echo ico('icons','gold.png')." Рубины:<br><input type='number' name='gold' value='".$limit_clan_kazna['ruby']."'>";
echo "<input type='submit' class='btn' value='Отправить'>
</form>";
echo "</div>";
echo "<a href='/corp/' class='link'> Вернуться назад</a>";
require_once ('system/footer.php');
break;
}

##### histor ####
	
if(isset($_GET['histor'])){
if(isset($_GET['delete_corp_histor']) and $user['id_clan'] == $clan['id'] and $user['clan_rang'] == 5){
mysql_query("DELETE FROM `clan_histor` WHERE `id_clan` = '".text($clan['id'])."'");
header('Location: ?');
exit();
}
$title = 'История';
require_once ('system/header.php');
echo "<div class='bordered'>"; 
echo "<div class='content'>";

$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_histor` WHERE `id_clan` = '".$user['id_clan']."' "),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `clan_histor` WHERE `id_clan` = '".$user['id_clan']."' ORDER BY (id) DESC LIMIT ".$start.", ".$set['p_str']."");
while($post = mysql_fetch_assoc($q)) {

echo "<div class='content'></div><div class='bordered'>";
echo "<div class='count'>";

echo vremja($post['data'])."  ".n_f($post['text']);
echo "</div></div>";
}

if($k_post > 10){

echo "<div class='block'>";

str('?',$k_page,$page); // Вывод страниц

echo"</div>";

}else{

}
if ($clan['id'] == $user['id_clan']  and $user['clan_rang'] == 5)echo'<a class="btnl mt4" href="?delete_corp_histor" class="btn"> Очистить историю</a>';


echo "</div></div>";
require_once ('system/footer.php');
exit();
}


### end histor###

##### setting ####
	
if(isset($_GET['setting']) and $user['id_clan'] == $clan['id'] and $user['clan_rang'] == 5){
$title = 'Настройки ';
require_once ('system/header.php');
echo "<div class='bordered'>"; 
echo "<div class='content'>";
if(isset($_REQUEST['add'])){
$name = text($_POST['name']);
$status = text($_POST['status']);
if(!$err){
mysql_query("UPDATE `clans` SET `name` = '".text($name)."', `status` = '".text($status)."' WHERE `id` = '".text($clan['id'])."'");
$_SESSION['msg'] = "Настройки сохранены";
header('Location: ?');
exit();
}else{
$_SESSION['msg'] = "Ошибка";
header('Location: ?');
exit();
}
}

echo '<form action="" method="post">';
echo 'Название кп:<br><input type="text" name="name" maxlength="50" value="'.text($clan['name']).'" /><br/>';
echo 'Статус кп:<br><input type="text" name="status" maxlength="50" value="'.text($clan['status']).'" /><br/>';

echo '<input type="submit" name="add" class="btni" value="Сохранить">';
echo '</form>';
echo "</div></div>";
require_once ('system/footer.php');
exit();

}


### end setting###

if(isset($_GET['mesto'])){
 $id = abs(intval($_GET['mesto']));
  
 if($clan['sclad_ruby'] < $clan['ruby_mesto']){
  $_SESSION['msg'] = "Не хватает <img width='20' height='20' src='/img/ruby.png'> ".n_f(($clan['ruby_mesto']-$clan['sclad_ruby']))."";
 header('Location: /corp/');
 exit();
 }else{$delen = ($clan['ruby_mesto']*1.40);$delen0 = ($delen^0);
mysql_query("UPDATE `clans` SET `max` = '".text($clan['max']+1)."', `sclad_ruby` = '".text($clan['sclad_ruby']-$clan['ruby_mesto'])."', `ruby_mesto` = '".($delen0)."' WHERE `id` = '".$user['id_clan']."' ");
$_SESSION['msg'] = "Вы успешно купили место.";
 header('Location: /corp/');
 exit();
 } }

if(isset($_GET['my_delete_ok']) and $user['id_clan'] == $clan['id'] and $user['clan_rang'] != 5){
mysql_query("UPDATE `users` SET `id_clan` = '0' WHERE `id` = '".$myID."'");
mysql_query("insert into `clan_histor` set `id_clan` = '".text($clan['id'])."', `data` = '".time()."', `text` = '<a href=/profile/".text($myID).">".text($user['login'])."</a> Покинул корпорацию.'");
header('Location: /');
exit();
}



if(isset($_GET['my_delete']) and $user['id_clan'] == $clan['id'] and $user['clan_rang'] != 5){
	
$title = 'Покинуть корпорацию';
require_once ('system/header.php');
echo "<div class='block center'>Вы уверены что хотите покинуть корпорацию?<br>";
echo "<a href='/corp/' class='btn2'>Нет, отмена</a><br>";
echo "<a href='?my_delete_ok'>Да, покинуть</a></div>";
require_once ('system/footer.php');
exit();
}
if(isset($_GET['user']) and $user['clan_rang'] >= 3 and $user['id_clan'] == $clan['id'] and $prof['clan_rang'] < $user['clan_rang']){
$id = num($_GET['user']);
$prof = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$id."'"));
if($user['id_clan'] == $prof['id_clan']){
$title = "Редактор - ".$prof['login']."";
require_once ('system/header.php');
echo "<div class='block'>";
switch($prof['clan_rang']){
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
echo icons_user($prof['id'])." <a href='/profile/".$prof['id']."'>".$prof['login']."</a> - ".$clan_rang."<br>";
if($prof['clan_rang'] != 5 and $prof['clan_rang'] < $user['clan_rang']){
if(isset($_GET['up'])){
if($prof['clan_rang'] != 4 and $prof['clan_rang'] < $user['clan_rang']){
mysql_query("UPDATE `users` SET `clan_rang` = '".text($prof['clan_rang']+1)."' WHERE `id` = '".$prof['id']."'");
mysql_query("insert into `clan_histor` set `id_clan` = '".text($clan['id'])."', `data` = '".time()."', `text` = '<a href=/profile/".text($myID).">".text($user['login'])."</a> Повысил в должносте <a href=/profile/".text($prof['id']).">".text($prof['login'])."</a>'");
}else{
	mysql_query("UPDATE `users` SET `clan_rang` = '5' WHERE `id` = '".$prof['id']."'");
	mysql_query("UPDATE `users` SET `clan_rang` = '4' WHERE `id` = '".$myID."'");
}
$_SESSION['msg'] = 'Игрок успешно повышен';
header('Location: ?');
exit();
}
if(isset($_GET['up_v']) and $prof['clan_rang'] != 5 and $prof['clan_rang'] < $user['clan_rang']){
echo '<center>Вы действительно хотите повысить ранг? В противном случаи вы потеряете свою корпорацию</br>';
echo "<a href='?up' class='link center'>Да</a> / <a href='?' class='link center'>нет</a></center>";
}else
if($prof['clan_rang'] != 4 and $prof['clan_rang'] < $user['clan_rang']){
echo "<hr><a href='?up' class='link center'>Повысить</a>";
}else{
	echo '<b>Следующим повышением, вы передадите привилегии <font color="red">Владельцa</b></font>';
	echo "<hr><a href='?up_v' class='link center'>Повысить</a>";

}
}
if($prof['clan_rang'] < $user['clan_rang'] && $prof['clan_rang'] >= 2){
if(isset($_GET['down'])){
mysql_query("UPDATE `users` SET `clan_rang` = '".text($prof['clan_rang']-1)."' WHERE `id` = '".$prof['id']."'");
mysql_query("insert into `clan_histor` set `id_clan` = '".text($clan['id'])."', `data` = '".time()."', `text` = '<a href=/profile/".text($myID).">".text($user['login'])."</a> Понизил в должносте <a href=/profile/".text($prof['id']).">".text($prof['login'])."</a>'");
$_SESSION['msg'] = 'Игрок успешно понижен';
header('Location: ?');
exit();
}
echo "<hr><a href='?down' class='link center'>Понизить</a>";
}
$clan = mysql_fetch_assoc(mysql_query("SELECT*  FROM `clans` WHERE `id` = '".$prof['id_clan']."'")); 
if($prof['clan_rang'] == 1 && $prof['clan_rang'] < $user['clan_rang'] ){
if(isset($_GET['delete'])){
mysql_query("UPDATE `users` SET `id_clan` = '0' WHERE `id` = '".$prof['id']."'");
mysql_query("insert into `clan_histor` set `id_clan` = '".text($clan['id'])."', `data` = '".time()."', `text` = '<a href=/profile/".text($myID).">".text($user['login'])."</a> Исключил <a href=/profile/".text($prof['id']).">".text($prof['login'])."</a>'");
$_SESSION['msg'] = 'Игрок успешно исключен';
header('Location: /corp/');
exit();
}
echo "<hr><a href='?delete' class='link center'>Исключить</a>";
}
echo "</div>";
require_once ('system/footer.php');
exit();
}else{
$_SESSION['msg'] = "Не шути с админом.";
header('Location: /profile/');
exit();
}
}
$title = $clan[name];
require_once ('system/header.php');
echo'<center>';
echo'<div class="menuverh">';
echo"Корпорация «".$clan['name']."»";
echo"<br>Бизнес-ангелы <img src='/img/angel48.png' alt='*' width='20' height='20'/> ".n_f($acorp[0])."</div>";
echo'<div class="menuverh">';
if($user['id_clan'] == $clan['id'])echo"Склад <img src='/img/ruby.png' alt='*' width='20' height='20'/> ".n_f($clan['sclad_ruby'])."</br>";
echo"К доходу + ".$clan['stat']." %</center></div>";
echo'</div>';
echo'<div class="menuverh">';
echo "<center>".text_msg($clan['status'])."</center>";
echo'</div>';

if($user['id_clan'] == $clan['id'] and $user['clan_rang'] == 5)echo "<a href='/corp/gerb/'><img src='/img/clangerb/$clan[gerb]'   height='160' style='width:100%;'></a>"; 
else echo "<img src='/img/clangerb/$clan[gerb]'  height='160' style='width:100%;'>";
if(isset($_GET['give_ruby_ok']) and $user['id_clan'] == $clan['id']){
	$giv_ruby = text($_POST['giv_ruby']);

if($giv_ruby < 0)$err = 'Ошибка взноса средств';

if($giv_ruby > $user['ruby'])$err = 'У вас не достаточно средств';
if(!$err){
mysql_query("update `clans` set `sclad_ruby` = '".text($clan['sclad_ruby']+$giv_ruby)."' where (`id` = '".text($clan['id'])."')");
mysql_query("update `users` set `ruby` = '".text($user['ruby']-$giv_ruby)."' where `id` = '".text($myID)."'");
mysql_query("insert into `clan_history` set `id_clan` = '".text($clan['id'])."', `login` = '".$user['login']."', `time` = '".time()."', `id_user` = '".text($myID)."', `ruby` = '".text($giv_ruby)."'");
$_SESSION['msg'] = 'Вы пополнили склад корпорации на '.$giv_ruby.' Рубина';
header('Location: /corp/give_ruby/');
}else{
$_SESSION['msg'] = $err;
header('Location: /corp/give_ruby/');
exit();
}
}
if(isset($_GET['delete_give']) and $user['id_clan'] == $clan['id'] and $user['clan_rang'] == 5){
mysql_query("DELETE FROM `clan_history` WHERE `id_clan` = '".text($clan['id'])."'");
header('Location: ?');
exit();
}	
if(isset($_GET['give_ruby']) and $user['id_clan'] == $clan['id']){
echo "<form method='post' action='?give_ruby_ok'>";
echo "<center>
На сколько хотите пополнить склад?</br>
Ваши <img src='/img/ruby.png' alt='*' width='20' height='20'/> ".$user['ruby']."<br>
<input type='number' name='giv_ruby' value=''><br>";
echo "<input type='submit' class='btni' value='Пополнить'>
</form>";


$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_history` WHERE `id_clan` = '".$user['id_clan']."' "),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `clan_history` WHERE `id_clan` = '".$user['id_clan']."' ORDER BY (ruby+0) DESC LIMIT ".$start.", ".$set['p_str']."");
while($post = mysql_fetch_assoc($q)) {
$i++;
echo "<div class='content'></div><div class='bordered'>";
echo "".($i+$start).".";
echo icons_user($post['id_user'])." <a href='/profile/".$post['id_user']."/'>".$post['login']."</a>, <div class='count'> ".n_f($post['ruby'])."<img src='/img/ruby.png' alt='*' width='20' height='20'/> Время:".vremja($post['time']);
echo "</div></div>";
}

if($k_post > 10){

echo "<div class='block'>";

str('?',$k_page,$page); // Вывод страниц

echo"</div>";

}else{

}
if ($clan['id'] == $user['id_clan']  and $user['clan_rang'] == 5)echo'<a class="btnl mt4" href="?delete_give" class="btn"> Очистить историю</a>';
require_once ('system/footer.php');
exit();
}
$users_count = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_count` WHERE `id_user` = '" .$user['id']."'"));


if ($users_count['cchat'] == 0) {
if($user['id_clan'] == $clan['id'])echo "<a class='btnl mt4' href='/corp/chat/' class='btn'><img src='/img/folder.png' alt='*' width='20' height='20'/> Чат<span style='
    margin: 0 auto;
    background-color: #de990e;  
	border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    border-bottom-style: solid;
    border-left-style: solid;
    border-right-style: solid;
    border-top-style: solid;
    border-bottom-width: 1px;
    border-left-width: 1px;
    border-right-width: 1px;
    border-top-width: 1px;
    text-decoration: none;
    text-align: center;
    color: black;
    font-size: 100%;
	border-color: #de990e;  
	position:absolute;
	'><b>+</b></span></a>";
}else{
if($user['id_clan'] == $clan['id'])echo "<a class='btnl mt4' href='/corp/chat/' class='btn'><img src='/img/folder.png' alt='*' width='20' height='20'/> Чат</a>";	
}
if($user['id_clan'] == $clan['id'])echo "<a class='btnl mt4' href='/corp/st' class='btn'> Статуя ".$clan[stat_level]." уровень</a>";
if ($users_count['cforum'] == 0) {
if($user['id_clan'] == $clan['id'])echo "<a class='btnl mt4' href='/corp/forum/' class='btn'> Форум корпорации<span style='
    margin: 0 auto;
    background-color: #de990e;  
	border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    border-bottom-style: solid;
    border-left-style: solid;
    border-right-style: solid;
    border-top-style: solid;
    border-bottom-width: 1px;
    border-left-width: 1px;
    border-right-width: 1px;
    border-top-width: 1px;
    text-decoration: none;
    text-align: center;
    color: black;
    font-size: 100%;
	border-color: #de990e;  
	position:absolute;
	'><b>+</b></span></a>";
}else{
if($user['id_clan'] == $clan['id'])echo "<a class='btnl mt4' href='/corp/forum/' class='btn'> Форум корпорации</a>";	
}

if($user['id_clan'] !== $clan['id'])echo "<a class='btnl mt4' href='/cforum_all.php?mod=id_f&id=".$clan['id']."' class='btn'> Форум корпорации</a>";


$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `id_clan` = '".$clan['id']."'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `users` WHERE `id_clan` = '".$clan['id']."' ORDER BY  `clan_rang` DESC, `angels` DESC LIMIT ".$start.", ".$set['p_str']."");
if($user['id_clan'] == $clan['id']){
echo'<a class="btnl mt4" href="" class="btn"><img src="/img/arrow_down2.png" alt="*" width="20" height="20"/> Состав корпорации '.$k_post.' / '.$clan[max].'</a>';
}else{
echo'<a class="btnl mt4" href="" class="btn"><img src="/img/arrow_up2.png" alt="*" width="20" height="20"/>Состав корпорации '.$k_post.'</a>';
}
echo "<div class='block'>";
while($post = mysql_fetch_assoc($q)) {
switch($post['clan_rang']){
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

echo '
<div class="comm_answer_main"><div style="text-align: left;margin-top:4px;"><span><span class="nobr"><a class="avatar user" href="/profile/'.$post['id'].'">'.icons_user($post['id']).'<span style="display: inline-flex;">'.$post['login'].'</span></a></span></span> - <span>'.$clan_rang.'</span>, <img src="/img/angel48.png" width="24" height="24" alt="*" /> <span>'.n_f($post['angels']).'</span>
<span class="fr">
<span style="padding-left: 8px;">';
if($myID != $post['id'] and $user['id_clan'] == $clan['id'] and $user['clan_rang'] >= 3)echo "<a href='/corp/user/".$post['id']."/'>Упр.</a>";  

echo '</span>
</span>
<div class="cb"></div>
</div></div>';
}
if($k_post > 10){ 
str('?',$k_page,$page); // Вывод страниц
}else{
}



##########TREC80 <-> Покинуть Корпорацию############
if(isset($_GET['delete_corp_ok']) and $user['id_clan'] == $clan['id'] and $user['clan_rang'] == 5 and $k_post == 1){
mysql_query("UPDATE `users` SET `id_clan` = '0', `clan_rang` = '0',`ruby` = '$user[ruby]'+500 WHERE `id` = '".$myID."'");
mysql_query("DELETE FROM `clans` WHERE `id` = '$clan[id]' LIMIT 1");
header('Location: /');
exit();
}
if(isset($_GET['delete_corp']) and $user['id_clan'] == $clan['id'] and $user['clan_rang'] == 5 and $k_post == 1){
$title = 'Покинуть корпорацию';
require_once ('system/header.php');
echo "<div class='block center'>Вы уверены что хотите удалить корпорацию?<br>Все ваши достижения будут удалены и вам вернут 500 Рубинов</br>";
echo "<a href='/corp/' class='btn2'>Нет, отмена</a><br>";
echo "<a href='?delete_corp_ok'>Да, удалить</a></div>";
require_once ('system/footer.php');
exit();
}
if(isset($_GET['send_ok']) and $user['id_clan'] == $clan['id'] and $user['clan_rang'] == 5){
$text = 	text($_POST['text']);
$q = mysql_query("SELECT * FROM `users` WHERE `id_clan` = '".$clan['id']."'");
while($post = mysql_fetch_assoc($q)) {
if ($user[id] != $post['id']){
$kont = mysql_fetch_assoc(mysql_query("SELECT * FROM `kont` WHERE `id_user` = '$myID' && `id_kont` = '".$post[id]."' LIMIT 1")); 
if($kont['id_kont'] == NULL){ 
mysql_query("INSERT INTO `kont` SET `id_user` = '".$post['id']."', `id_kont` = '".$myID."', `time` = '".time()."'"); 
mysql_query("INSERT INTO `kont` SET `id_user` = '".$myID."', `id_kont` = '".$post['id']."', `time` = '".time()."'"); 
}else{ 
mysql_query("update `kont` set `time` = '".time()."' WHERE `id_user` = '".$myID."' && `id_kont` = '".$post['id']."'"); 
mysql_query("update `kont` set `time` = '".time()."' WHERE `id_user` = '".$post['id']."' && `id_kont` = '".$myID."'"); 
} 
mysql_query("INSERT INTO `mail` (`in`, `out`, `text`, `time`) values('$user[id]', '$post[id]', 'Рассылка Корпорации:
$text', '$time')");
}
}
header('Location: ?');
$_SESSION['msg'] = 'Сообщения разосланы!';

}
if(isset($_GET['send_all']) and $user['id_clan'] == $clan['id'] and $user['clan_rang'] == 5){
$title = 'Рассылка';

require_once ('system/header.php');
echo 'Введите сообщение для рассылки:</br>';
echo "<form method='post' action='?send_ok'>";
echo '<div class="center mt4"><textarea rows="5" id="textarea" style="width: 95%;" name="text" maxlength="5000" minlength="0"></textarea><br>';
echo "<input type='submit' class='btni' value='Отправить'>
</form></div></center>";
require_once ('system/footer.php');
exit();
}
####################################################
echo "</div>";
if($user['id_clan'] == $clan['id'])echo'<a class="btnl mt4" href="/corp/give_ruby/" class="btn"><img src="/img/ruby.png" alt="*" width="20" height="20"/> Пополнить склад корпорации</a>';
if($user['id_clan'] == $clan['id'] and $user['clan_rang'] == 5)echo'<a class="btnl mt4" href="?send_all" class="btn"><img src="/img/mail_white.png" alt="*" width="20" height="20"/> Рассылка сообщений</a>';
if($user['id_clan'] == $clan['id'] and $user['clan_rang'] == 5)echo'<a class="btnl mt4" href="/corp/setting/" class="btn"> Настройки</a>';
if($user['id_clan'] == $clan['id'] and $user['clan_rang'] == 5)echo'<a class="btnl mt4" href="?mesto" class="btn"><img src="/img/accept.png" alt="*" width="20" height="20"/> Купить место ('.($clan['max']+1).')  <img src="/img/ruby.png" alt="*" width="20" height="20"/>'.$clan['ruby_mesto'].'</a>';
echo "</div>";
if($user['id_clan'] == $clan['id'])echo'<a class="btnl mt4" href="/corp/histor/" class="btn"><img src="/img/history2.png" alt="*" width="20" height="20"/> История корпорации</a>';
if($user['id_clan'] == $clan['id'] and $user['clan_rang'] != 5)echo'<a class="btnl mt4" href="?my_delete" class="btn"><img src="/img/cross.png" alt="*" width="20" height="20"/> Покинуть корпорацию</a>';
if ($user['id_clan'] == $clan['id'] and $k_post ==1  and $user['clan_rang'] == 5)echo'<a class="btnl mt4" href="?delete_corp" class="btn"><img src="/img/cross.png" alt="*" width="20" height="20"/> Удалить корпорацию</a>';


require_once ('system/footer.php');
?>