<?php
define('PROTECTOR', 1);
require_once ('system/func.php');
$title = 'Вход';
require_once ('system/header.php');

noauth(); // Закроем от авторизованных

/* СЧЕТЧИКИ */
$registr = mysql_num_rows(mysql_query("SELECT * FROM `users`"));
if(isset($_REQUEST['success'])){
	$name = text($_POST['nickname']);
	$pass = text($_POST['password']);

	$sql = $db->query("SELECT `login`,`pass` FROM `users` WHERE `login` = '".$name."' and `pass`='".$pass."' LIMIT 1")->fetch_assoc();

	if(empty($name)) msg('Введите логин');
	elseif(empty($pass)) msg('Введите пароль');
	elseif($sql == 0) msg('Не правильный логин или пароль');
	else {
		setcookie('login', $name, time()+86400*365, '/');
		setcookie('pass', $pass, time()+86400*365, '/');
		header('location: /');
	}
}
echo ' <img src="/style/images/space2.jpg" alt="" style="width:100%;"/></div>';
echo '
<div class="bordered"><center>
<form action="" method="post">
Ник:<br/>
<input class="center" type="text" name="nickname" maxlength="100" value="" /><br/>
		Пароль:<br/>
<input class="center" type="password" name="password" maxlength="100" value="" /><br/>
		
<input type="submit" name="success" class="btn" value="Войти"></center></div><a class="btnl mt4" href="/password/" class="btn">Забыли пароль?</a><a class="btnl mt4" href="/" class="btn">В начало</a>
</form></div>
';
require_once ('system/footer.php');

?>