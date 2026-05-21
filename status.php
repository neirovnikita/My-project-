<?php
#ASADAL
require_once ('system/func.php');
require_once ('system/header.php');
auth(); // Закроем от не авторизованных

if(isset($_GET['new'])){

echo'<div class="feedback">';
echo 'Смена статуса</div>';

if(isset($_REQUEST['submit']))
{

$text = text($_POST['text']);

if(empty($text)){
echo 'Введите статус!</br>';
require_once ('system/footer.php');
exit();
}

mysql_query("UPDATE `users` SET `status` = '".text($text)."' WHERE `id` = '".$user['id']."' LIMIT 1");
echo 'Вы успешно сменили статус!</br>';
require_once ('system/footer.php');
exit();

}

echo '
<center><form method="POST" action="">Статус:<br/><input type="text" name="text" maxlength="100" value="'.text($user['status']).'" /><br/>';
echo'<input type="submit" name="submit" class="btni" value="Сменить статус" /></br>
</form></div></center>';
echo'</div>';

}
else
{
header('Location: /game');
require_once ('system/footer.php');
exit();
}

require_once ('system/footer.php');

?>

