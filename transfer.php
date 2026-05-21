<?php
#ASADAL
require_once ('system/func.php');
require_once ('system/header.php');
auth(); // Закроем от не авторизованных

$id = abs(intval($_GET['id']));
$us = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$id."'"));

if($us == 0){
echo 'Такого игрока не существует!';
require_once ('system/footer.php');
exit();
}

if($user['id'] == $id){
echo 'Запрещено передавать рубины самому себе!';
require_once ('system/footer.php');
exit();
}

if(isset($_REQUEST['submit']))
{

$count = intval($_POST['count']);

if($count != '50' && $count != '100' && $count != '250' && $count != '500' && $count != '1000' && $count != '5000' ){
echo 'Неверное кол-во рубинов!';
require_once ('system/footer.php');
exit();
}

if($user['ruby'] < $count){
echo 'У вас нет столько рубинов!';
require_once ('system/footer.php');
exit();
}

mysql_query("UPDATE `users` SET `ruby` = `ruby` +'".text($count)."' WHERE `id` = '".text($us['id'])."' LIMIT 1");
mysql_query("UPDATE `users` SET `ruby` = `ruby` -'".text($count)."' WHERE `id` = '".text($user['id'])."' LIMIT 1");
$kont = mysql_fetch_assoc(mysql_query("SELECT * FROM `kont` WHERE `id_user` = '2' && `id_kont` = '".$us['id']."' LIMIT 1"));if($kont['id_kont'] != $us['id']){mysql_query("INSERT INTO `kont` SET `id_user` = '".$us['id']."', `id_kont` = '2', `time` = '".time()."'");mysql_query("INSERT INTO `kont` SET `id_user` = '2', `id_kont` = '".$us['id']."', `time` = '".time()."'");}else{mysql_query("update `kont` set `time` = '".time()."' WHERE `id_user` = '2' && `id_kont` = '".$us['id']."'");mysql_query("update `kont` set `time` = '".time()."' WHERE `id_user` = '".$us['id']."' && `id_kont` = '2'");}mysql_query("INSERT INTO `mail` (`in`, `out`, `text`, `time`) values('2', '".$us['id']."', '[b]Здраствуй ".$us['login'].", для вас перевод ".n_f($count)." рубинов, от ".$user['login']."[/b].', '".time()."')");
echo 'Вы успешно передали игроку: '.$us['login'].' '.text($count).' рубинов!</br>';
require_once ('system/footer.php');
exit();
}
echo'<div class="feedback">';
echo 'Передача рубинов игроку: <b>'.$us['login'].'</b></br></div>';

echo'<center><div class="bordered">';
echo 'У вас рубинов: <b>'.$user['ruby'].'</b></br>';
echo 'Выберите кол-во рубинов:</br>';

echo '
<form method="POST" action="">
<div class="podmenu">Кол-во:<br /><select name="count"><option value="50">50</option><option value="100">100</option><option value="250">250</option><option value="500">500</option><option value="1000">1000</option><option value="5000">5000</option></select></br>
<input type="submit" name="submit" value="Передать рубины" /></br>
</form></div></center>';
echo'</div>';
require_once ('system/footer.php');

?>

