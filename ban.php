<?php 
require_once ('system/func.php'); 
$title = 'Бан'; 
require_once ('system/header.php'); 
auth(); // Закроем от гостей 
access(1); // Ставим права 
$id = intval($_GET['id']); 
if(!$id)header("Location: /"); 
$opponent = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$id."' LIMIT 1")); 
if($user['access'] <= $opponent['access']){ 
header("Location: /profile/".$id.""); 
exit(); 
}elseif($id == $myID){ 
header("Location: /profile/"); 
exit(); 
}else{ 
if(isset($_GET['delete'])){ 
mysql_query("DELETE FROM `ban` WHERE `id` = '".text($_GET['delete'])."'"); 
$_SESSION['msg'] = "Нарушение успешно удалено"; 
header("Location: ?"); 
exit(); 
} 
if(isset($_GET['ok']) && isset($_POST['text']) && isset($_POST['last'])){ 
$text = text($_POST['text']); 
$last = num($_POST['last']); 
$ban = mysql_query('SELECT * FROM `ban` WHERE `id_user` = "'.$opponent['id'].'" AND `last` > "'.time().'" ORDER BY `id` DESC LIMIT 1'); 
$ban = mysql_fetch_array($ban); 
if(strlen($text) < 6 or strlen($text) > 300)$err = 'Длина причины должна быть в пределах 6-300 символов'; 
if($last < 1)$err = 'Время бана не может быть меньше часа'; 
if($ban)$err = 'Игрок уже забанен'; 
if(!$err){ 
mysql_query("INSERT INTO `ban` SET `id_user` = '".text($id)."', `id_admin` = '".text($myID)."', `last` = '".(time()+60*60*$last)."', `text` = '".$text."'"); 
$_SESSION['msg'] = 'Бан выполнен'; 
header("Location: /ban/".$id."/"); 
exit(); 
}else{ 
$_SESSION['msg'] = $err; 
header("Location: /ban/".$id."/"); 
exit(); 
} 
} 
echo "<div class='content'>"; 
echo "<p>Наложить бан на <b>".$opponent['login']."</b></p>"; 
echo "<form method='post' action='?ok'>"; 
echo "Причина бана:<br><input type='text' name='text' value = ''><br>"; 
echo "Время в часах:<br><input type='text' name='last' value = '1'><br>"; 
echo "<input type='submit' class='btn' value='Выполнить бан'>"; 
echo "</form>"; 

echo "</div>"; 
echo "<p>Нарушения:</p>"; 
echo "<div class='content'>"; 
$set['p_str'] = 10; 
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `ban` WHERE `id_user` = '".$id."'"),0); 
$k_page = k_page($k_post,$set['p_str']); 
$page = page($k_page); 
$start = $set['p_str']*$page-$set['p_str']; 
$q = mysql_query("SELECT * FROM `ban` WHERE `id_user` = '".$id."' ORDER BY `id` DESC LIMIT ".$start.", ".$set['p_str'].""); 
if($k_post == 0)echo "Нарушений ещё не было..."; 
while($post = mysql_fetch_assoc($q)) { 
$ank = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$post['id_admin']."' ORDER BY `id` DESC LIMIT 1")); 
echo icons_user($ank['id'])." <a href='/profile/".$ank['id']."'>".$ank['login']."</a> | <a href='?delete=".$post['id']."'>[х]</a>: ".text_msg($post['text'])."<br>"; 
if($post['last'] > time()){ 
echo "Активен, до окончания ".tl($post['last']-time()); 
}else{ 
echo "Не активен, истек ".vremja($post['last']); 
} 
echo "<hr>"; 
} 
str('?',$k_page,$page); // Вывод страниц 
echo "</div>"; 
} 
require_once ('system/footer.php'); 
?>