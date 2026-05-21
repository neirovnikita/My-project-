<?php
require_once ('system/func.php');
auth(); // Закроем от не авторизованных

# Настройки #
$id = abs(intval($_GET['id']));
if($id){
$prof = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$id."'"));
# Ошибки #
if(!$prof){
$_SESSION['msg'] == 'Такой игрок не существует';
header('Location: /mail/');
exit();
}
if (isset($_GET['delete'])  && $_GET['delete']!='add')

{

$mess = mysql_fetch_assoc(mysql_query("SELECT * FROM `mail` WHERE `id` = '".intval($_GET['delete'])."' limit 1"));

if ($mess['in']==$user['id'] || $mess['out']==$user['id'])

{

if ($mess['unlink']!=$user['id'] && $mess['unlink']!=0)

mysql_query("DELETE FROM `mail` WHERE `id` = '".$mess['id']."'");

else

mysql_query("UPDATE `mail` SET `unlink` = '$user[id]' WHERE `id` = '$mess[id]' LIMIT 1");

$_SESSION['message'] = 'Сообщение удалено';

header("Location: ?");

exit;

}

}
if (isset($_GET['delete']) && $_GET['delete']=='add')

{


mysql_query("DELETE FROM `mail` WHERE `unlink` = '$prof[id]'  AND `in` = '$user[id]' AND `out` = '$prof[id]' OR `in` = '$prof[id]' AND `out` = '$user[id]' AND `unlink` = '$prof[id]'  ");

mysql_query("UPDATE `mail` SET `unlink` = '$user[id]' WHERE  `in` = '$user[id]' AND `out` = '$prof[id]' OR `in` = '$prof[id]' AND `out` = '$user[id]'");

$_SESSION['message'] = 'Сообщения удалены';

header("Location: /mail/".$prof['id']."");

exit;

}
if(isset($_GET['text'])){
if(isset($_POST['text'])){
$text = text($_POST['text']);
$ban = mysql_query('SELECT * FROM `ban` WHERE `id_user` = "'.$myID.'" AND `last` > "'.time().'" ORDER BY `id` DESC LIMIT 1');
$ban = mysql_fetch_array($ban);
if(strlen($text) < 3 or strlen($text) > 5000)$err = 'Длина сообщения должна быть в пределах 3 - 5000 символов';
if($ban)$err = "На вас наложен, бан осталось ".tl($ban[last]-time());

$time = mysql_query("SELECT * FROM `mail` WHERE `in` = '".$user['id']."' ORDER BY `time` DESC");
while($t = mysql_fetch_assoc($time)){  
$forum_antispam = mysql_fetch_assoc(mysql_query("SELECT * FROM `guard` WHERE `id` = '1' "));
$timeout = text($t['time']);
if((time()-$timeout) < $forum_antispam['antoflood']) {
$err = 'Защита: Антифлуд. Попробуйте отправить сообщение позже.';
}
}

if(!$err){
mysql_query("INSERT INTO `mail` SET `in` = '".text($myID)."', `out` = '".text($prof['id'])."', `text` = '".text($text)."' , `time` = '".text(time())."'");
$kont = mysql_fetch_assoc(mysql_query("SELECT * FROM `kont` WHERE `id_user` = '".$myID."' && `id_kont` = '".$prof['id']."' LIMIT 1"));
if($kont['id_kont'] != $prof['id']){
mysql_query("INSERT INTO `kont` SET `id_user` = '".text($prof['id'])."', `id_kont` = '".text($myID)."', `time` = '".text(time())."'");
mysql_query("INSERT INTO `kont` SET `id_user` = '".text($myID)."', `id_kont` = '".text($prof['id'])."', `time` = '".text(time())."'");
}else{
mysql_query("update `kont` set `time` = '".text(time())."' WHERE `id_user` = '".$myID."' && `id_kont` = '".text($prof['id'])."'");
mysql_query("update `kont` set `time` = '".text(time())."' WHERE `id_user` = '".text($prof['id'])."' && `id_kont` = '".text($myID)."'");
}
$_SESSION['msg'] = 'Сообщение успешно отправлено';
header("Location: /mail/".$prof['id']."");
exit();
}else{
$_SESSION['msg'] = $err;
header("Location: /mail/".$prof['id']."");
exit();
}
}else{
$_SESSION['msg'] == 'Введите сообщение';
header("Location: /mail/".$prof['id']."");
exit();
}
}
$title = 'Диалог с '.$prof['login'];
require_once ('system/header.php');
echo "<div class='content'>";
echo "<a href='/mail/".$prof['id']."' class=''>Обновить</a> | ";
echo "<a href='/mail/'>Почта</a> / <a href='/profile/".$prof['id']."'> ".$prof['login']."</a></div><div class='bordered'>";
echo "<form name='form' method='post' action='?text'><div class='fight center'>".bbpanel('form', 'text')."</div><script type='text/javascript'>
		                  function ctrlEnter(event, formElem)
		                    {
		                      if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD)))
		                         {
		                            formElem.form.submit();
		                         }
		                    }
		                   </script>
";
echo '<div class="center mt4">';
echo "<textarea name='text' style='width:95% ;resize:vertical;' placeholder='Введите сообщение...' rows='5' onkeypress='ctrlEnter(event, this);'></textarea><br>";
echo '<input class="btni" type="submit" value="Отправить">';

echo "</div></div></form><div class='content'></div>";

$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `mail` WHERE (`unlink` != '$user[id]' and `in` = '".$prof['id']."' && `out` = '".$user['id']."') or (`in` = '".$user['id']."' && `out` = '".intval($_GET['id'])."'  AND  `unlink` != '$user[id]')"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `mail` WHERE  (`unlink` != '$user[id]' and `in` = '".$prof['id']."' && `out` = '".$user['id']."') or (`in` = '".$user['id']."' && `out` = '".intval($_GET['id'])."'  AND  `unlink` != '$user[id]') ORDER BY `id` DESC LIMIT ".$start.", ".$set['p_str']."");
$ank = '0';
if($k_post == 0) echo "<div class='feedback'>Сообщений не найдено</div>";
mysql_query("update `mail` set `online` = '0' WHERE `in` = '".text($prof['id'])."' && `out` = '".text($myID)."'");
mysql_query("UPDATE `kont` SET `new_msg` = '0' WHERE `id_kont` = '".text($ank['id'])."' AND `id_user` = '".$myID."' LIMIT 1");
while($post = mysql_fetch_assoc($q)) {
echo'<div class="bordered">';
if($post['online'] == 1)$color = 'green';
else $color = '#A8A8A8';
$ank = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$post['in']."' LIMIT 1"));
###if($ank['ava'] == NULL){
###echo ''.$ava.'<img src="/img/noavatar.png" alt="$" width = "20" ></a> ';
###}
###else
###{
###echo ''.$ava.'<img src="/img/avatar/'.text($ank['ava']).'" alt="$" width = "20" ></a> ';
###}   
echo icons_user($ank['id'])." <a href='/profile/".$ank['id']."'>".$ank['login']."</a>, <font color='".$color."'>".time_last($post['time'])."</font><br>".text_msg($post['text'])."<br>";
echo '<a href="?delete='.$post[id].'">Удалить</a>';
echo "</div>";
echo'<div class="content"></div>';

}
echo "<a href='?delete=add'>Очистить почту</a><br />\n";

if($k_post > 10){
str('?',$k_page,$page); // Вывод страниц
}else{
}
echo "</div>";
echo "</a>";
}else{
$title = 'Почта';
require_once ('system/header.php');
$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `kont` WHERE `id_user` = '".$myID."'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `kont` WHERE `id_user` = '".$myID."' ORDER BY `time` DESC LIMIT ".$start.", ".$set['p_str']."");
if($k_post == 0)echo "<div class='feedback'>Вы еще не кому не писали</div>";
while ($post = mysql_fetch_assoc($q)){
$ank = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$post['id_kont']."' LIMIT 1"));
$mess = mysql_query("SELECT * FROM `mail` WHERE `in` = '".$user['id']."' && `out` = '".$ank['id']."' OR `in` = '".$ank['id']."' AND `out` = '".$user['id']."' ORDER BY `id` DESC LIMIT 1");
$msg = mysql_fetch_assoc($mess);
$new_msg = mysql_result(mysql_query("SELECT COUNT(*) FROM `mail` WHERE `out` = '".$user['id']."' && `in` = '".$ank['id']."' AND `online` = '1'"),0);
$online = mysql_result(mysql_query("SELECT COUNT(*) FROM `mail` WHERE `out` = '".$ank['id']."' && `in` = '".$user['id']."' AND `online` = '1'"),0);
echo"<div class='content'></div>"; 
echo "<div class='bordered'>";
echo icons_user($ank['id'])."".$ank['login'].", ".time_last($msg['time'])."<br>";
echo "<a href='/mail/".$ank['id']."'>"; 
echo' Читать ';
if($online > 0)echo "<font color='gren'>(»)</font>";
if($new_msg > 0)echo "<font color='red'>(»)</font>";
echo "</a></div>";
}
echo "</div>";
if($k_post > 10){
echo "<div class='block'>";
str('?',$k_page,$page); // Вывод страниц
echo "</div>";
}else{
}
}
require_once ('system/footer.php');
?>