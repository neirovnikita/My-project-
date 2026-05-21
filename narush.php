<?php 
require_once ('system/func.php'); 
auth(); // Закроем от не авторизованных 
# Настройки # 
$id = text($_GET['id']); 
if($id)$opponent = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$id."'")); 
else $opponent = $user; 
# Ошибки # 
if(!$opponent){ 
$_SESSION['msg'] == 'Такой игрок не существует'; 
header('Location: /narush/'); 
exit(); 
} 
$title = "Нарушения"; 
require_once ('system/header.php'); 
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
echo icons_user($ank[id])." <a href='/profile/".$ank['id']."'>".$ank['login']."</a>: ".text_msg($post['text'])."<br>"; 
if($post[last] > time()){ 
echo "Активен, до окончания ".tl($post['last']-time()); 
}else{ 
echo "Не активен, истек ".vremja($post['last']); 
} 
echo "<hr>"; 
} 
str('?',$k_page,$page); // Вывод страниц 
echo "</div>"; 
if($myID != $opponent['id'])echo "<a class='btnl mt4' href='/profile/".$opponent['id']."' class='btn' > Вернуться к профилю</a>"; 
require_once ('system/footer.php'); 
?>