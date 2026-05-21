<?php
require_once ('system/func.php');
noauth(); // Только для гостей
$title = 'Восстановление пароля';
require_once ('system/header.php');
if(isset($_REQUEST['add'])){
$login = text($_POST['login']);
$mail = text($_POST['mail']);
$sql = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `login` = '".$login."' LIMIT 1"));
if(empty($login))$err = 'Введите Ник';
if(!$sql)$err = 'Игрок с таким Ником не существует';
if($mail != $sql['email'])$err = 'E-mail введён неверно';
if(!$err){
$rou = rand(100000, 900000);
mysql_query("UPDATE `users` SET `pass` = '".$rou."' WHERE `login` = '".$login."'");
$msg = 'Здравствуйте '.$login.'!

Вами была произведена операция по восстановлению пароля в онлайн игре Рейб

Ваши данные для входа в аккаунт:
-------------------------
Логин: '.$login.'
Пароль: '.$rou.'
-------------------------
Пароль сгенерирован автоматически, просим Вас после авторизации сменить его!';
$subject = 'Восстановление пароля';
mail($sql['email'],$subject,$msg,"From: password@rayb.mobi");
$_SESSION['msg'] = 'Письмо с новым паролем отправлено на вашу почту';
header('Location: ?');
exit();
}else{
$_SESSION['msg'] = $err;
header('Location: ?');
exit();
}
}
echo "<div class='bordered'><center>";
echo '<form action="?search" method="post">';
echo 'Ник:<br><input type="text" name="login" maxlength="50" value="" /><br/>';
echo 'E-mail:<br><input type="text" name="mail" maxlength="50" value="" /><br/>';
echo '<input type="submit" name="add" class="btn" value="Продолжить">';
echo '</form>';
echo '</div><a class="btnl mt4" href="/" class="btn">В начало</a>';
require_once ('system/footer.php');
?>