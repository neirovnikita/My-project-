<?php
require_once ('system/func.php');
auth(); // Закроем от не авторизованных
## Топик ##
 
$topic = abs(intval($_GET['topic']));
if($topic){
$topic = mysql_fetch_assoc(mysql_query("SELECT * FROM `topic` WHERE `id` = '".$topic."'"));
# Ошибки #
if(!$topic){
$_SESSION['msg'] == 'Такой топик не существует';
header('Location: /forum/');
exit();
}
if(text(isset($_GET['delete_post'])) and $user['access'] > 0){
mysql_query("DELETE FROM `topic_msg` WHERE `id` = '".text($_GET['delete_post'])."'");
$_SESSION['msg'] = 'Пост удален';
header('Location: ?');
exit();
}
if(text(isset($_GET['delete'])) and $user['access'] > 0){
$title = 'Удалить топ?';
require_once ('system/header.php');
echo "<div class='content'></div>";
echo "<div class='bordered'><center>";
echo'<a class="btni" href="?delete_ok" style="margin-top: 3px; width: 140px;"> Да, удалить</a><br>';
echo "<a href='?' class='grey' data-ajax>Нет, отмена</a>";
echo "</div></center>";
require_once ('system/footer.php');
break;
}
if(text(isset($_GET['delete_ok'])) and $user['access'] > 0){
mysql_query("DELETE FROM `topic` WHERE `id` = '".text($topic['id'])."'");
$_SESSION['msg'] = 'Топик удален';
header("Location: /forum/".$topic['id_forum']."");
exit();
}
if(text(isset($_GET['close'])) and $user['access'] > 0){
mysql_query("UPDATE `topic` SET `close` = '1' WHERE `id` = '".text($topic['id'])."'");
$_SESSION['msg'] = "Топик успешно закрыт";
header("Location: ?");
exit();
}
if(isset($_GET['no_close']) and $user['access'] > 0){
mysql_query("UPDATE `topic` SET `close` = '0' WHERE `id` = '".text($topic['id'])."'");
$_SESSION['msg'] = "Топик успешно открыт";
header("Location: ?");
exit();
}
if(text(isset($_GET['top'])) and $user['access'] > 0){
mysql_query("UPDATE `topic` SET `top` = '1' WHERE `id` = '".text($topic['id'])."'");
$_SESSION['msg'] = "Топик успешно закреплен";
header("Location: ?");
exit();
}
if(text(isset($_GET['no_top'])) and $user['access'] > 0){
mysql_query("UPDATE `topic` SET `top` = '0' WHERE `id` = '".text($topic['id'])."'");
$_SESSION['msg'] = "Топик успешно откреплен";
header("Location: ?");
exit();
}
if(isset($_GET['text'])){
if(isset($_POST['text'])){
$text = text($_POST['text']);
$ban = mysql_query('SELECT * FROM `ban` WHERE `id_user` = "'.$myID.'" AND `last` > "'.time().'" ORDER BY `id` DESC LIMIT 1');  
$ban = mysql_fetch_array($ban);
if(strlen($text) < 3 or strlen($text) > 5000)$err = 'Длина сообщения должна быть в пределах 3 - 5000 символов';
if($ban)$err = "На вас наложен бан, осталось ".tl($ban['last']-time());

$time = mysql_query("SELECT * FROM `topic_msg` WHERE `id_user` = '".$user['id']."' ORDER BY `time` DESC");
while($t = mysql_fetch_assoc($time)){  
$forum_antispam = mysql_fetch_assoc(mysql_query("SELECT * FROM `guard` WHERE `id` = '1' "));
$timeout = $t['time'];
if((time()-$timeout) < $forum_antispam['antoflood']) {
$err = 'Защита: Антифлуд. Попробуйте отправить сообщение позже.';
}
}


if(!isset($err)){
mysql_query("INSERT INTO `topic_msg` SET `id_topic` = '".text($topic['id'])."', `id_user` = '".text($myID)."', `text` = '".text($text)."' , `time` = '".text(time())."'");
mysql_query("UPDATE `topic` SET `onlick` = '".time()."' WHERE `id` = '".$topic['id']."'");
mysql_query("UPDATE `users_count` SET `forum` = '0'");
mysql_query("UPDATE `users` SET `ruby` = '".text($user['ruby']+1)."' WHERE `id` = '".$myID."'");
$_SESSION['msg'] = 'Комментарий успешно добавлен, вы получаете 1 <img src="/img/ruby.png" alt="$" width="16" height="16"/>';
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
if(strlen($name) < 5 or strlen($name) > 50)$err = 'Длина названия должна быть в пределах 5-50 символов';

if(!isset($err)){
mysql_query("UPDATE `topic` SET `text` = '".text($text)."', `name` = '".text($name)."' WHERE `id` = '".$topic['id']."'");
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
$title = 'Форум | '.$topic['name'];
require_once ('system/header.php');
if($user['access'] > 0 or $myID == $topic['id_user']){
if(isset($_GET['sett_topic'])){
echo "<div class='bordered'><center>";
echo '<form name="text" method="post" action="?sett_topic_post">';
echo "Название:<br><input type='text' name='name' placeholder='' value='".$topic['name']."'><br>";
echo 'Сообщение:<br><textarea rows="5" id="textarea" style="width: 95%;" name="text" maxlength="5000" minlength="0" placeholder="Введите сообщение...">'.text($topic['text']).'</textarea><br>';
echo '<input class="btni" type="submit" value="Изменить"><a href="?">Отмена</a>';
echo "</form>";
echo "</div></center>";
require_once ('system/footer.php');
exit();
}
}
$ank = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = ".$topic['id_user']." LIMIT 1"));
$forum = mysql_fetch_assoc(mysql_query("SELECT * FROM `forum` WHERE `id` = '".$topic['id_forum']."'")); 
echo "<div class ='content'><center><a href='/forum/".$forum['id']."'>".$forum['name']."</a> | ".$topic['name']."</div></center>";
echo "<div class='bordered'>";
echo icons_user($ank['id'])." <a href='/profile/".$ank['id']."'>".$ank['login']."</a>, ".vremja($topic['time'])."<br>".text_msg($topic['text']);
echo "</div>";
if($user['access'] > 0 or $myID == $topic['id_user']){
echo'<a class="btnl mt4" href="?sett_topic" class="btn">Редактировать</a>';
if($topic['close'] == 0 and $user['access'] > 0)echo'<a class="btnl mt4" href="?close" class="btn">Закрыть</a>';
if($topic['close'] == 1 and $user['access'] > 0)echo'<a class="btnl mt4" href="?no_close" class="btn">Открыть</a>';
if($topic['top'] == 0 and $user['access'] > 0)echo'<a class="btnl mt4" href="?top" class="btn">Прикрепить</a>';
if($topic['top'] == 1 and $user['access'] > 0)echo'<a class="btnl mt4" href="?no_top" class="btn">Открепить</a>';
if($user['access'] > 0)echo'<a class="btnl mt4" href="?delete" class="btn">Удалить топик</a>';
echo "</div>";
}
echo"<div class='content'></div><div class='bordered'>"; 
$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `topic_msg` WHERE `id_topic` = '".$topic['id']."'"),0);

$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `topic_msg` WHERE `id_topic` = '".$topic['id']."' ORDER BY `id` ASC LIMIT ".$start.", ".$set['p_str']."");
while ($post = mysql_fetch_assoc($q)){
$ank = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = ".$post['id_user']." LIMIT 1"));
echo icons_user($ank['id'])." <a href='/profile/".$ank['id']."' >".$ank['login']."</a>, ";
if($ank['id'] != $myID and $topic['close'] == 0)echo "<a href='?to=".$ank['id']."&page=end'>(»)</a> ";
echo" ".vremja($post['time'])."";
echo "<br>".text_msg($post['text']);
if($user['access'] > 0)echo " <a href='?delete_post=".$post['id']."'><font color='red'>(x)</font></a>";
echo"<br><div class='content'></div>";
}
if($k_post > 10){
str('?',$k_page,$page); // Вывод страниц
}else{
}
echo'<center>';
if($topic['close'] == 0){
if(isset($_GET['to'])){
$opponent = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = ".$_GET['to']." LIMIT 1"));
echo '<form name="text" method="post" action="?text">';

 echo '<div class="center mt4">Сообщение:<textarea rows="5" id="textarea" style="width: 95%;" name="text" maxlength="5000" minlength="0"> '.$opponent['login'].', </textarea><br>';
echo '<input class="btni" type="submit" value="Отправить">';
echo "</div></form>";
}else{
echo '<form name="text" method="post" action="?text">';
echo '<div class="center mt4">Сообщение:<br><textarea rows="5" id="textarea" style="width: 95%;" name="text" maxlength="5000" minlength="0"></textarea><br>';
echo '<input class="btni" type="submit" value="Отправить">';
echo "</div></form>";
}
}else{
echo "<div class='feedback'>Топик закрыт.</div>";
}
echo "</div>";

}else{
## Раздел ##
$id = abs(intval($_GET['id']));
if($id){
$forum = mysql_fetch_assoc(mysql_query("SELECT * FROM `forum` WHERE `id` = '".$id."'"));
# Ошибки #
if(!$forum){
$_SESSION['msg'] == 'Такой раздел не существует';
header('Location: /forum/');
exit();
}
if(isset($_GET['new_topic_post'])){
if(isset($_POST['name']) && isset($_POST['text'])){
$name = text($_POST['name']);
$text = text($_POST['text']);
$ban = mysql_query('SELECT * FROM `ban` WHERE `id_user` = "'.$myID.'" AND `last` > "'.time().'" ORDER BY `id` DESC LIMIT 1');  
$ban = mysql_fetch_array($ban);
if(strlen($name) < 5 or strlen($name) > 50)$err = 'Длина названия должна быть в пределах 5 - 50 символов';
if(strlen($text) < 3 or strlen($text) > 5000)$err = 'Длина сообщения должна быть в пределах 3 - 5000 символов';
if($ban)$err = "На вас наложен бан, осталось ".tl($ban['last']-time());
if(!isset($err)){
if($forum['id'] == 1)mysql_query("UPDATE `users` SET `news_read` = '1'");
mysql_query("INSERT INTO `topic` SET `id_forum` = '".text($forum['id'])."', `id_user` = '".text($myID)."', `name` = '".text($name)."', `text` = '".text($text)."', `time` = '".text(time())."', `onlick` = '".text(time())."'");
mysql_query("UPDATE `users_count` SET `forum` = '0'");

$_SESSION['msg'] = 'Топик успешно создан';
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
$title = 'Форум | '.$forum['name'];
require_once ('system/header.php');
if(isset($_GET['new_topic']) and $user['access'] >= $forum['access']){
echo '<form name="text" method="post" action="?new_topic_post">';
echo'<div class="bordered"><center>';
echo "Название:<br><input type='text' name='name' placeholder=''><br>";
echo 'Сообщение:<br><textarea rows="5" id="textarea" style="width: 95%;" name="text" maxlength="5000" minlength="0" ></textarea><br>';
echo '<input class="btni" type="submit" value="Создать"><a href="?">Отмена</a>';
echo "</form>";
echo "</div>";
require_once ('system/footer.php');
exit();
}
$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `topic` WHERE `id_forum` = '".$forum['id']."'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `topic` WHERE `id_forum` = '".$forum['id']."' ORDER BY `top` DESC, `onlick` DESC LIMIT ".$start.", ".$set['p_str']."");
echo "<div class='content'>";
echo "<a href='/forum/'>форум</a></div>";
if($user['access'] >= $forum['access'])echo'<a class="btnl mt4" href="?new_topic" class="btn"><img src="/img/folder.png" alt="*" width="20" height="20"/> Создать новый топик</a>';
if($k_post == 0)echo'<div class="feedback">В данном разделе нет топиков.</div>';
while ($post = mysql_fetch_assoc($q)){
if($post['close'] == 1 and $post['top'] == 1)echo'<a class="btnl mt4" href="/forum/sub/'.$post['id'].'?page=end" class="btn"><img src="/img/topic_closed.png" alt="*" width="20" height="20"/> <b>'.$post['name'].'</b></a>';
elseif($post['close'] == 1)echo'<a class="btnl mt4" href="/forum/sub/'.$post['id'].'?page=end" class="btn"><img src="/img/topic_closed.png" alt="*" width="20" height="20"/> '.$post['name'].'</a>';
elseif($post['top'] == 1)echo'<a class="btnl mt4" href="/forum/sub/'.$post['id'].'?page=end" class="btn"><img src="/img/topic_attached.png" alt="*" width="20" height="20"/> <b>'.$post['name'].'</b></a>';
else echo'<a class="btnl mt4" href="/forum/sub/'.$post['id'].'?page=end" class="btn"><img src="/img/folder.png" alt="*" width="20" height="20"/> '.$post['name'].'</a>';
}
echo "</div>";
if($k_post > 10){
echo "<div class='block'>";
str('?',$k_page,$page); // Вывод страниц
echo "</div>";
}else{
}
}else{
## Форум ##
$title = 'Форум';
require_once ('system/header.php');
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum`"),0);
$q = mysql_query("SELECT * FROM `forum` ORDER BY `id` ASC");
if($k_post == 0)echo "<div class='feedback'>Нет разделов...</div>";
while ($post = mysql_fetch_assoc($q)){
echo'<a class="btnl mt4" href="/forum/'.$post['id'].'" class="btn"><img src="/img/folder.png" alt="*" width="20" height="20"/> '.$post['name'].'</a>';
}
echo "</div>";


echo"<div class ='bordered'><center>Последние Обновления</center>";
echo '</div>';
$q = mysql_query("SELECT * FROM `topic` ORDER BY  `onlick` DESC LIMIT 5");
while ($post = mysql_fetch_assoc($q)){
if($post['close'] == 1 and $post['top'] == 1)echo'<a class="btnl mt4" href="/forum/sub/'.$post['id'].'?page=end" class="btn"><img src="/img/topic_closed.png" alt="*" width="20" height="20"/> <b>'.$post['name'].'</b></a>';
elseif($post['close'] == 1)echo'<a class="btnl mt4" href="/forum/sub/'.$post['id'].'?page=end" class="btn"><img src="/img/topic_closed.png" alt="*" width="20" height="20"/> '.$post['name'].'</a>';
elseif($post['top'] == 1)echo'<a class="btnl mt4" href="/forum/sub/'.$post['id'].'?page=end" class="btn"><img src="/img/topic_attached.png" alt="*" width="20" height="20"/> <b>'.$post['name'].'</b></a>';
else echo'<a class="btnl mt4" href="/forum/sub/'.$post['id'].'?page=end" class="btn"><img src="/img/folder.png" alt="*" width="20" height="20"/> '.$post['name'].'</a>';
}
}
}
if (isset($user))
mysql_query("UPDATE `users_count` SET `forum` = '1' WHERE `id_user` = '$user[id]'");
require_once ('system/footer.php');
?>