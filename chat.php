<?php

require_once ('system/func.php');
$title = 'Общий чат';
require_once ('system/header.php');
auth(); // Закроем от гостей
// Очищаем уведомления об ответах
if (isset($user))
mysql_query("UPDATE `users_count` SET `chat` = '1' WHERE `id_user` = '$user[id]'");
if(isset($_GET['text'])){
if(isset($_POST['text'])){

$text = text($_POST['text']);

$ban = mysql_query('SELECT * FROM `ban` WHERE `id_user` = "'.$myID.'" AND `last` > "'.time().'" ORDER BY `id` DESC LIMIT 1');
$ban = mysql_fetch_array($ban);
if(strlen($text) < 2 or strlen($text) > 1200)$err = 'Длина сообщения должна быть в пределах 2-500 символов';
if($ban)$err = "На вас наложен, бан осталось ".tl($ban['last']-time());

$time = mysql_query("SELECT * FROM `chat` WHERE `id_user` = '".$user['id']."' ORDER BY `time` DESC");
while($t = mysql_fetch_assoc($time)){  
$forum_antispam = mysql_fetch_assoc(mysql_query("SELECT * FROM `guard` WHERE `id` = '1' "));
$timeout = $t['time'];
if((time()-$timeout) < $forum_antispam['antoflood']) {
$err = 'Защита: Антифлуд. Попробуйте отправить сообщение позже.';
}
}

if(!$err){
mysql_query("INSERT INTO `chat` SET `id_user` = '".text($user['id'])."', `time` = '".time()."', `text` = '".text($text)."'");
mysql_query("UPDATE `users_count` SET `chat` = '0'");
mysql_query("UPDATE `users` SET `ruby` = '".text($user['ruby']+1)."' WHERE `id` = '".$myID."'");
$_SESSION['msg'] = 'Сообщение отправлено, вы получаете 1 <img src="/img/ruby.png" alt="$" width="16" height="16"/> ';
header('Location: /chat');
exit();
}else{
$_SESSION['msg'] = $err;
header('Location: /chat');
exit();
}
}else{
$_SESSION['msg'] = 'Введите сообщение';
header('Location: /chat');
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
echo "<a href='/chat' class='grey' data-ajax>Нет, отмена</a>";
echo "</div></center>";
require_once ('system/footer.php');
exit();
}
if(isset($_GET['delete_chat']) and $user['access'] > 0){
mysql_query("DELETE FROM `chat`");$_SESSION['msg'] = 'Чат очищен';
mysql_query("UPDATE `users_count` SET `chat` = '1'");
$text = "Очистил чат [url=/profile/".$user['id']."]".$user['login']."[/url].";
mysql_query("INSERT INTO `chat` SET `id_user` = '2', `time` = '".time()."', `text` = '".text($text)."'");
header('Location: ?');exit();}
if(isset($_GET['delete_post']) and $user['access'] > 0){
mysql_query("DELETE FROM `chat` WHERE `id` = '".text($_GET['delete_post'])."'");
$_SESSION['msg'] = 'Пост удален';
header('Location: ?');
exit();
}
echo "<div class='content'></div>";

if(isset($_GET['to'])){
echo "<form name='form' method='post' action='?text='".intval($_GET['to'])."'><div class='fight center'>".bbpanel('form', 'text')."</div><script type='text/javascript'>
		                  function ctrlEnter(event, formElem)
		                    {
		                      if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD)))
		                         {
		                            formElem.form.submit();
		                         }
		                    }
		                   </script>
";
$opponent = $db->query("SELECT * FROM `users` WHERE `id` = '".intval($_GET['to'])."' LIMIT 1")->fetch_assoc();
echo '<div class="center mt4">Сообщение:<textarea rows="5" name="text"  style="width:100% ;resize:vertical;" placeholder="Введите сообщение..." onkeypress="ctrlEnter(event, this);"> '.$opponent['login'].', </textarea><br>';
echo'<input type="hidden" name="to" value="'.intval($_GET['to']).'">';
echo "<input type='submit' class='btni' value='Отправить'>
</form></div></center>";
}else{
echo "<form name='form' method='post' action='?text'><div class='fight center'>".bbpanel('form', 'text')."</div><script type='text/javascript'>
		                  function ctrlEnter(event, formElem)
		                    {
		                      if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD)))
		                         {
		                            formElem.form.submit();
		                         }
		                    }
		                   </script>";
echo "<textarea name='text'  style='width:100% ;resize:vertical;' placeholder='Введите сообщение...' rows='5' onkeypress='ctrlEnter(event, this);'></textarea><br>";
echo "<br><center><input type='submit' class='btni' value='Отправить'>";
if($user['access'] > 0)echo " <a href='?del' class='btni'>Очистить чат</a></center>";
echo "</form></div></center>";
}

$online = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `online` > '".(time()-300)."' AND `fix_mesto` = '".$title."'"),0);
echo "</div>";

echo "<div class='content'></div>";
$set['p_str'] = 20;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `chat`"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `chat` ORDER BY `id` DESC LIMIT ".$start.", ".$set['p_str']."");
if($k_post == 0) echo "<div class='feedback'>Сообщений не найдено. Будешь первым?</div>";
echo"<div class ='comm_answer_title'>";
while($post = mysql_fetch_assoc($q)) {
$ank = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = ".$post['id_user']." LIMIT 1"));
if($ank['access'] == 3){ 
echo "<span class='item-2'> </span>"; 
echo '<font color="#2A8D9C">[Sys.Bot]</font>';
}else
if($ank['access'] == 2){ 
echo "<span class='item-2'> </span>"; 
echo '<font color="32CD3200FF7F">[АДМ]</font>';
}elseif($ank['access'] == 1){ 
echo "<span class='item-1'></span>"; 
echo '<font color="BC8F8F">[МД]</font>';
}else{ 
echo "<span class='white'></span>"; 
} 

if($ank['ava'] == NULL){
echo ''.$ava.'<img src="/img/noavatar.png" alt="$" width = "20" ></a> ';
}
else
{
echo ''.$ava.'<img src="/img/avatar/'.text($ank['ava']).'" alt="$" width = "20" ></a> ';
}   
echo icons_user($ank['id'])." <a href='/profile/".$ank['id']."'>".$ank['login']." </a>";


 



if($myID != $ank['id'])echo " <a href='?to=".$ank['id']."'>(»)</a>".time_last($post['time'])."";
echo':';
echo " ".text_msg($post['text']);
if($user['access'] > 0)echo " <a href='?delete_post=".$post['id']."'><font color='red'>(x)</font></a>";
echo'<br><hr>';
}
echo "</div>";
if($k_post > 20){
str('?',$k_page,$page); // Вывод страниц
}else{}
require_once ('system/footer.php');
?>