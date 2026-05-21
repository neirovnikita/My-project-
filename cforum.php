<?php
require_once ('system/func.php');
auth(); // Закроем от не авторизованных
# Настройки #
$id = num($_GET['id']);
if($id)$clan = $db->query("SELECT * FROM `clans` WHERE `id` = '".$id."'")->fetch_assoc();
else $clan = $db->query("SELECT * FROM `clans` WHERE `id` = '".$user['id_clan']."'")->fetch_assoc();
## Топик ##
$cforum_topic = num($_GET['cforum_topic']);
if($cforum_topic){
$cforum_topic = $db->query("SELECT * FROM `cforum_topic` WHERE `id` = '".$cforum_topic."'")->fetch_assoc();
$cforum_sub = $db->query("SELECT * FROM `cforum_sub` WHERE `id` = '".$cforum_topic['id_forum']."'")->fetch_assoc();
$clan = $db->query("SELECT * FROM `clans` WHERE `id` = '".$cforum_sub['id_clan']."'")->fetch_assoc();
# Ошибки #
if(!$cforum_topic){
$_SESSION['msg'] = 'Такой топик не существует';
header('Location: /corp/forum/');
exit();
}
if(isset($_GET['delete_post']) and $user['clan_rang'] > 3 and $user['id_clan'] == $clan['id']){
$db->query("DELETE FROM `cforum_comments` WHERE `id` = '".$_GET['delete_post']."'");
$_SESSION['msg'] = 'Пост удален';
header('Location: ?');
exit();
}
if(isset($_GET['delete']) and $user['clan_rang'] > 3 and $user['id_clan'] == $clan['id']){
$title = 'Удалить топ?';
require_once ('system/header.php');
echo "<div class='content'>";
echo "<li><a href='?delete_ok'>Да, удалить</a></li>";
echo "<li><a href='?'>Нет, отмена</a></li>";
echo "</div>";
require_once ('system/footer.php');
exit();
}
if(isset($_GET['delete_ok']) and $user['clan_rang'] > 3 and $user['id_clan'] == $clan['id']){
$db->query("DELETE FROM `cforum_topic` WHERE `id` = '".$cforum_topic['id']."'");
$_SESSION['msg'] = 'Топик удален';
header("Location: /corp/forum/".$cforum_topic['id_forum']."");
exit();
}
if(isset($_GET['close']) and $user['clan_rang'] > 3 and $user['id_clan'] == $clan['id']){
$db->query("UPDATE `cforum_topic` SET `close` = '1' WHERE `id` = '".$cforum_topic['id']."'");
$_SESSION['msg'] = "Топик успешно закрыт";
header("Location: ?");
exit();
}
if(isset($_GET['no_close']) and $user['clan_rang'] > 3 and $user['id_clan'] == $clan['id']){
$db->query("UPDATE `cforum_topic` SET `close` = '0' WHERE `id` = '".$cforum_topic['id']."'");
$_SESSION['msg'] = "Топик успешно открыт";
header("Location: ?");
exit();
}
if(isset($_GET['top']) and $user['clan_rang'] > 3 and $user['id_clan'] == $clan['id']){
$db->query("UPDATE `cforum_topic` SET `top` = '1' WHERE `id` = '".$cforum_topic['id']."'");
$_SESSION['msg'] = "Топик успешно закреплен";
header("Location: ?");
exit();
}
if(isset($_GET['no_top']) and $user['clan_rang'] > 3){
$db->query("UPDATE `cforum_topic` SET `top` = '0' WHERE `id` = '".$cforum_topic['id']."'");
$_SESSION['msg'] = "Топик успешно откреплен";
header("Location: ?");
exit();
}

if(isset($_GET['text'])){
if(isset($_POST['text'])){
$text = text($_POST['text']);
$ban = $db->query('SELECT * FROM `ban` WHERE `id_user` = "'.$myID.'" AND `last` > "'.time().'" ORDER BY `id` DESC LIMIT 1')->fetch_assoc();  
if(strlen($text) < 3 or strlen($text) > 5000)$err = 'Длина сообщения должна быть в пределах 3 - 5000 символов';
if($ban)$err = "На вас наложен бан, осталось ".tl($ban['last']-time());
if(!isset($err)){
$db->query("INSERT INTO `cforum_comments` SET `id_topic` = '".$cforum_topic['id']."', `id_user` = '".$myID."', `text` = '".$text."' , `time` = '".time()."'");
$db->query("UPDATE `cforum_topic` SET `onlick` = '".time()."' WHERE `id` = '".$cforum_topic['id']."'");
mysql_query("UPDATE `users` SET `ruby` = '".text($user['ruby']+1)."' WHERE `id` = '".$myID."'");
$_SESSION['msg'] = 'Комментарий успешно добавлен, вы получаете 1 <img src="/img/ruby.png" alt="$" width="16" height="16"/>';
$db->query("UPDATE `users_count` SET `сforum` = '0'");
header('Location: ?');
exit();
}else{
$_SESSION['msg'] = $err;
header('Location: ?page=end');
exit();
}
}else{
$_SESSION['msg'] = 'Ведите текст';
header('Location: ?page=end');
exit();
}
}
if(isset($_GET['sett_topic_post'])){
if(isset($_POST['text']) && isset($_POST['name'])){
$text = text($_POST['text']);
$name = text($_POST['name']);
if(strlen($text) < 3 or strlen($text) > 5000)$err = 'Длина сообщения должна быть в пределах 3 - 5000 символов';
if(strlen($name) < 3 or strlen($name) > 40)$err = 'Длина названия должна быть в пределах 3 - 40 символов';
if(!isset($err)){
$db->query("UPDATE `cforum_topic` SET `text` = '".$text."', `name` = '".$name."' WHERE `id` = '".$cforum_topic['id']."'");
$db->query("UPDATE `users_count` SET `сforum` = '0'");
$_SESSION['msg'] = 'Топик успешно изменен';
header('Location: ?');
exit();
}else{
$_SESSION['msg'] = $err;
header('Location: ?');
exit();
}
}else{
$_SESSION['msg'] = 'Заполните поля';
header('Location: ?');
exit();
}
}

$title = 'Форум - '.$cforum_topic['name'];
require_once ('system/header.php');
if($user['clan_rang'] > 3 and $user['id_clan'] == $clan['id'] or $myID == $cforum_topic['id_user']){
if(isset($_GET['sett_topic'])){
echo "<div class='content'>";
echo '<form name="text" method="post" action="?sett_topic_post">';
echo "Название:<br><input type='text' name='name' placeholder='Введите название...' value='".$cforum_topic['name']."'><br>";
echo 'Сообщение:<br><textarea rows="5" id="textarea" style="width: 95%;" name="text" placeholder="Введите сообщение..." maxlength="5000">'.$cforum_topic['text'].'</textarea><br>';
echo '<input class="btni" type="submit" value="Изменить">|<a class="btni" href="?">Отмена</a>';
echo "</form>";
echo "</div>";
require_once ('system/footer.php');
exit();
}
}
$ank = $db->query("SELECT * FROM `users` WHERE `id` = '".$cforum_topic['id_user']."' LIMIT 1")->fetch_assoc();
$cforum_sub = $db->query("SELECT * FROM `cforum_sub` WHERE `id` = '".$cforum_topic['id_forum']."'")->fetch_assoc();
echo "<div class ='content'><center><a href='/corp/forum/".$cforum_sub['id']."'>".$cforum_sub['name']."</a> | ".$cforum_topic['name']."</div></center>";
echo "<div class='bordered'>";
echo icons_user($ank['id'])." <a href='/profile/".$ank['id']."'>".$ank['login']."</a>, ".vremja($cforum_topic['time'])."<br>".text_msg($cforum_topic['text']);
echo "</div>";
if(($user['clan_rang'] > 3 and $user['id_clan'] == $clan['id']) or $myID == $cforum_topic['id_user']){
echo'<a class="btnl mt4" href="?sett_topic" class="btn">Редактировать</a>';
if($cforum_topic['close'] == 0 and $user['clan_rang'] > 3 and $user['id_clan'] == $clan['id'])echo'<a class="btnl mt4" href="?close" class="btn">Закрыть</a>';
if($cforum_topic['close'] == 1 and $user['clan_rang'] > 3 and $user['id_clan'] == $clan['id'])echo'<a class="btnl mt4" href="?no_close" class="btn">Открыть</a>';
if($cforum_topic['top'] == 0 and $user['clan_rang'] > 3 and $user['id_clan'] == $clan['id'])echo'<a class="btnl mt4" href="?top" class="btn">Прикрепить</a>';
if($cforum_topic['top'] == 1 and $user['clan_rang'] > 3 and $user['id_clan'] == $clan['id'])echo'<a class="btnl mt4" href="?no_top" class="btn">Открепить</a>';
if($user['clan_rang'] > 3 and $user['id_clan'] == $clan['id'])echo'<a class="btnl mt4" href="?delete" class="btn">Удалить топик</a>';

}
$set['p_str'] = 10;
$k_post = $db->query("SELECT * FROM `cforum_comments` WHERE `id_topic` = '".$cforum_topic['id']."'")->num_rows;
echo "<div class='bordered'><center>Комментарии [".$k_post."]</center><br>";
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = $db->query("SELECT * FROM `cforum_comments` WHERE `id_topic` = '".$cforum_topic['id']."' ORDER BY `id` ASC LIMIT $start, $set[p_str]");
while ($post = $q->fetch_assoc()){
$ank = $db->query("SELECT * FROM `users` WHERE `id` = '".$post['id_user']."' LIMIT 1")->fetch_assoc();
echo icons_user($ank['id'])." <a href='/profile/".$ank['id']."' >".$ank['login']."</a>, ".vremja($post['time'])." ";
if($ank['id'] != $myID and $cforum_topic[close] == 0)echo "<a href='?to=".$ank['id']."&page=end'>[Отв]</a>";
echo "<br>".text_msg($post['text']);
if($user['clan_rang'] > 3 && $user['id_clan'] == $clan['id'])echo " <a href='?delete_post=".$post['id']."'><font color='red'>[×]</font></a>";
echo "<br>";
}
if($k_post>10){
str('?',$k_page,$page); // Вывод страниц
}
echo "</div>";
echo "<div class='content'>";
if($cforum_topic['close'] == 0){
if(isset($_GET['to'])){
$opponent = $db->query("SELECT * FROM `users` WHERE `id` = '".num($_GET['to'])."' LIMIT 1")->fetch_assoc();
echo '<form name="text" method="post" action="?text">';
echo '<textarea rows="5" id="textarea" style="width: 95%;" name="text" maxlength="5000">'.$opponent['login'].', </textarea><br>';
echo '<input class="btni" type="submit" value="Ответить">';
echo "</form>";
}else{
echo '<form name="text" method="post" action="?text">';
echo '<textarea rows="5" id="textarea" style="width: 95%;" name="text" placeholder="Введите сообщение..." maxlength="5000"></textarea><br>';
echo '<input class="btni" type="submit" value="Отправить">';
echo "</form>";
}
}else{
echo "<font class='item-5'>Топик закрыт...</font>";
}
echo "</div>";
}else{
## Раздел ##
$id = num($_GET['id']);
if($id){
$cforum_sub = $db->query("SELECT * FROM `cforum_sub` WHERE `id` = '".$id."'")->fetch_assoc();
# Ошибки #
if(!$cforum_sub){
$_SESSION['msg'] = 'Такой раздел не существует';
header('Location: /corp/forum/');
exit();
}
$clan = $db->query("SELECT * FROM `clans` WHERE `id` = '".$cforum_sub['id_clan']."'")->fetch_assoc();
if($cforum_sub['gb'] == 0 and $user['id_clan'] != $clan['id']){
require_once ('system/header.php');
echo "<div class='block'><h1>Раздел только для корпорации</h1></div>";
require_once ('system/footer.php');
exit();
}
if(isset($_GET['red_razdel_post'])){
if(isset($_POST['name'])){
$name = text($_POST['name']);
$gb = num($_POST['gb']);
$clan_rang = num($_POST['clan_rang']);
if(strlen($name) < 3 or strlen($name) > 40)$err = 'Длина названия должна быть в пределах 3 - 40 символов';
if(!isset($err)){
$db->query("UPDATE `cforum_sub` SET `name` = '$name', `gb` = '$gb', `clan_rang` = '$clan_rang' WHERE `id` = ".$cforum_sub['id']." LIMIT 1")or die(mysql_error());
$_SESSION['msg'] = 'Раздел успешно изменён';
header('Location: ?');
exit();
}else{
$_SESSION['msg'] = $err;
header('Location: ?red_razdel');
exit();
}
}else{
$_SESSION['msg'] = 'Заполните поля';
header('Location: ?red_razdel');
exit();
}
}
if(isset($_GET['red_razdel']) and $user['clan_rang'] == 5 and $user['id_clan'] == $clan['id']){
$title = 'Редактирование раздела';
require_once ('system/header.php');
echo "<div class='block center'>";
echo '<form name="text" method="post" action="?red_razdel_post">';
echo "Название<br /><input type='text' name='name' value='".$cforum_sub['name']."'><br>";
echo 'Доступность:<br /><select name="gb"><option value="0">Только для клана</option><option value="1">Для всех</option></select><br/>';
echo 'Права:<br /><select name="clan_rang"><option value="1">Для всех</option><option value="3">Для Аукционеров</option><option value="4">Для Заместителей</option></select><br/>';
echo '<input class="btni" type="submit" value="Изменить">|<a class="btni" href="?">Отмена</a>';
echo "</form>";
echo "</div>";
require_once ('system/footer.php');
exit();
}
if(isset($_GET['new_topic_post'])){
if(isset($_POST['name']) && isset($_POST['text'])){
$name = text($_POST['name']);
$text = text($_POST['text']);
$ban = $db->query('SELECT * FROM `ban` WHERE `id_user` = "'.$myID.'" AND `last` > "'.time().'" ORDER BY `id` DESC LIMIT 1');  
$ban = $ban->fetch_assoc();
if(strlen($name) < 3 or strlen($name) > 40)$err = 'Длина названия должна быть в пределах 3 - 40 символов';
if(strlen($text) < 3 or strlen($text) > 5000)$err = 'Длина сообщения должна быть в пределах 3 - 5000 символов';
if($ban)$err = "На вас наложен бан, осталось ".tl($ban['last']-time());
if(!isset($err)){
if($forum['id'] == 1)$db->query("UPDATE users SET news_read = '1'");
$db->query("INSERT INTO `cforum_topic` SET `id_forum` = '".$cforum_sub['id']."', `id_user` = '".$myID."', `name` = '".$name."', `text` = '".$text."', `time` = '".time()."', `onlick` = '".time()."'");
$_SESSION['msg'] = 'Топик успешно создан';
$db->query("UPDATE `users_count` SET `сforum` = '0'");
header('Location: ?');
exit();
}else{
$_SESSION['msg'] = $err;
header('Location: ?new_topic');
exit();
}
}else{
$_SESSION['msg'] = 'Заполните поля';
header('Location: ?new_topic');
exit();
}
}
$title = 'Форум - '.$cforum_sub['name'];
require_once ('system/header.php');
if(isset($_GET['new_topic']) and $user['clan_rang'] >= $cforum_sub['clan_rang']){
echo'<div class="bordered"><center>';
echo '<form name="text" method="post" action="?new_topic_post">';
echo "Название:<input type='text' name='name' placeholder='Введите название...'><br>";
echo 'Сообщение:<br><textarea rows="5" id="textarea" style="width: 95%;" name="text" placeholder="Введите сообщение..." maxlength="5000"></textarea><br>';
echo '<input class="btni" type="submit" value="Создать">|<a class="btni" href="?">Отмена</a>';
echo "</form>";
echo "</div>";
require_once ('system/footer.php');
exit();
}
$set['p_str'] = 10;
$k_post = $db->query("SELECT * FROM `cforum_topic` WHERE `id_forum` = '".$cforum_sub['id']."'")->num_rows;
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = $db->query("SELECT * FROM `cforum_topic` WHERE `id_forum` = '".$cforum_sub['id']."' ORDER BY `top` DESC, `onlick` DESC LIMIT $start, $set[p_str]");

if($user['id_clan'] == $clan['id'] and $user['clan_rang'] >=  $cforum_sub['clan_rang']) echo "<a class='btnl mt4' href='?new_topic' >Создать новый топик</a>";
if($k_post == 0)echo "<div class='feedback'>В данном разделе нет топиков...</div>";
while ($post = $q->fetch_assoc()){
echo "<a class='btnl mt4' href='/corp/forum/sub/".$post['id']."?page=end'>";
if($post['close'] == 1 and $post['top'] == 1)echo "<img src='/img/topic_closed.png' alt='*' width='20' height='20'/><b>".$post['name']."</b>";
elseif($post['close'] == 1)echo "<img src='/img/topic_closed.png' alt='*' width='20' height='20'/>".$post['name']."";
elseif($post['top'] == 1)echo "<img src='/img/topic_attached.png' alt='*' width='20' height='20'/><b>".$post['name']."</b>";
else echo "".$post["name"]."";
echo "</a>";
}

if($k_post>10){

str('?',$k_page,$page);

}
if($user['clan_rang'] == 5 and $user['id_clan'] == $clan['id'])echo "<a class='btnl mt4' class='link' href='?red_razdel'> Редактировать раздел</a>";
echo "<a class='btnl mt4' class='link' href='/corp/forum/'>Вернуться в форум</a>";
}else{
if(isset($_GET['new_razdel_post'])){
if(isset($_POST['name'])){
$name = text($_POST['name']);
$gb = num($_POST['gb']);
$clan_rang = num($_POST['clan_rang']);
if(strlen($name) < 3 or strlen($name) > 40)$err = 'Длина названия должна быть в пределах 3 - 40 символов';
if(!isset($err)){
mysql_query("INSERT INTO `cforum_sub` SET `id_clan` = '".$clan['id']."', `name` = '".$name."', `gb` = '".$gb."', `clan_rang` = '".$clan_rang."'")or die(mysql_error());
$_SESSION['msg'] = 'Раздел успешно создан';
header('Location: ?');
exit();
}else{
$_SESSION['msg'] = $err;
header('Location: ?new_razdel');
exit();
}
}else{
$_SESSION['msg'] = 'Заполните поля';
header('Location: ?new_razdel');
exit();
}
}
if(isset($_GET['new_razdel']) and $user['clan_rang'] == 5 and $user['id_clan'] == $clan['id']){
$title = 'Форум клана - новый раздел';
require_once ('system/header.php');
echo '<form name="text" method="post" action="?new_razdel_post">';
echo'<div class="bordered"><center>';
echo "Название<br /><input type='text' name='name' placeholder='Введите название...'><br>";
echo 'Доступность:<br /><select name="gb"><option value="0">Только для клана</option><option value="1">Для всех</option></select><br/>';
echo 'Права:<br /><select name="clan_rang"><option value="1">Для всех</option><option value="3">Для Аукционеров</option><option value="4">Для Заместителей</option></select><br/>';
echo '<input class="btni" type="submit" value="Создать">|<a class="btni" href="?">Отмена</a>';
echo '</div>';
echo "</form>";

require_once ('system/footer.php');
exit();
}
## Форум ##
$title = 'Форум клана';
require_once ('system/header.php');
$k_post = $db->query("SELECT * FROM `cforum_sub` WHERE `id_clan` = '".$clan['id']."'")->num_rows;
$q = $db->query("SELECT * FROM `cforum_sub` WHERE `id_clan` = '".$clan['id']."' ORDER BY `id` ASC");
if($k_post == 0)echo "<div class='feedback'>Нет разделов...</div>";
echo "<div class='content'>";
while ($post = $q->fetch_assoc()){
echo "<a class='btnl mt4' href='/corp/forum/".$post['id']."' class='btn'><img src='/img/folder.png' alt='*' width='20' height='20'/> ".$post['name']."</a>";
}

if($user['id_clan'] == $clan['id'] and $user['clan_rang'] == 5)echo "<li><a class='btnl mt4' class='btn' href='?new_razdel'>Создать новый раздел</a></li>";
echo "<a class='btnl mt4' href='/corp/' class='btn'> Вернуться назад</a>";
echo "</div>";
echo"<div class ='bordered'><center>Последние Обновления</center>";
echo '</div>';
$q = mysql_query("SELECT * FROM `cforum_topic` WHERE `id_clan` = '".$clan['id']."' ORDER BY  `onlick` DESC LIMIT 5");
while ($post = mysql_fetch_assoc($q)){
echo "<a class='btnl mt4' href='/corp/forum/sub/".$post['id']."?page=end'>";
if($post['close'] == 1 and $post['top'] == 1)echo "<img src='/img/topic_closed.png' alt='*' width='20' height='20'/><b>".$post['name']."</b>";
elseif($post['close'] == 1)echo "<img src='/img/topic_closed.png' alt='*' width='20' height='20'/>".$post['name']."";
elseif($post['top'] == 1)echo "<img src='/img/topic_attached.png' alt='*' width='20' height='20'/><b>".$post['name']."</b>";
else echo "".$post["name"]."";
echo "</a>";
}


}
}
if (isset($user))
mysql_query("UPDATE `users_count` SET `cforum` = '1' WHERE `id_user` = '$user[id]'");
require_once ('system/footer.php');
?>