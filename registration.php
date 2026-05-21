<?php
require_once ('system/func.php');
$title = 'Регистрация';
require_once ('system/header.php');

noauth(); // Закроем от не авторизованных
if (isset($_GET['id'])){
$refs = '?id='.intval($_GET['id']);

}else{
$refs = '?';

}
echo '<div class="bordered"><center>';
echo ' <img src="/style/images/space.jpg" alt="" style="width:100%;"/>';
if(isset($_REQUEST['success'])){
	$name = text($_POST['name']);
	$pass = text($_POST['pass']);
	$repass = text($_POST['repass']);
	$sex = text($_POST['sex']);
	$side = text($_POST['side']);
	$mail = text($_POST['mail']);
$sql = mysql_query("SELECT COUNT(`id`) FROM `users` WHERE `login` = '".$name."'");  // Доступность Ника
	$query = mysql_query("SELECT COUNT(`id`) FROM `users` WHERE `email` = '".$mail."'");  // Доступность почты
	
	$sqlip = mysql_query("SELECT COUNT(`id`) FROM `users` WHERE `ip` = '".text($_SERVER["REMOTE_ADDR"])."'"); 
	
if(empty($name)) $err = 'Введите Ник';
	elseif(empty($pass)) $err = 'Введите пароль';
	elseif(empty($repass)) $err = 'Введите пароль еще раз';
	elseif(empty($mail)) $err = 'Введите почтовый ящик';
	elseif (!preg_match('|^[a-z0-9\-]+$|i', $pass)) $err = 'Кириллица в пароле запрещена';
elseif(mysql_result($sql, 0) > 0) $err = 'Такой ник уже занят';
elseif(mysql_result($sqlip, 0) > 0) $err = 'Вы уже зарегистрированы на сайте!';
	elseif(mysql_result($query, 0) > 0) $err = 'Такой почтовый ящик уже используется';
elseif(mb_strlen($name) > 20 or mb_strlen($name) < 3) $err = 'Ник не может быть короче 3 и длиннее 20 символов';
	elseif(mb_strlen($pass) > 20 or mb_strlen($pass) < 3) $err = 'Пароль не может быть короче 3 и длиннее 20 символов';
	elseif($pass != $repass) $err = 'Пароли не совпадают';
	elseif($name == $pass) $err = 'Логин и пароль не должны совпадать';
	if(!$err){
if(mysql_query("INSERT INTO `users` SET `login` = '".$name."', `pass` = '".$pass."', `email` = '".$mail."', `sex` = '".$sex."', `registr` = '".time()."', `gold` = '1', `ruby` = '5'")){
mysql_query("INSERT INTO `buld` SET `level`= '1', `dohod` = '3600', `otdeh` = '43200', `id_user` = '".$myID."'");
$id = mysql_insert_id();
if (isset($_GET['id'])){
mysql_query("INSERT INTO `asadal_refferal` SET `kto` = '".text($id)."', `ykogo` = '".intval($_GET['id'])."', `ip` = '".text($_SERVER["REMOTE_ADDR"])."', `time` = '".text(time())."'");
mysql_query("UPDATE `users` SET `ruby` = `ruby` +1 WHERE `id` = '".intval($_GET['id'])."' LIMIT 1");
mysql_query("update `users` set `id_partner` = '".intval($_GET['id'])."' where (`id` = '".$id."')");

}
}
		/* ПРИСВАИВАЕМ КУКИ */		
		setcookie('login', $name, time()+86400*365, '/');
		setcookie('pass', $pass, time()+86400*365, '/');
		header('location: /');
		exit();
		}else{
		$_SESSION['msg'] = $err;
		header('location: '.$refs.'');
		exit();
		}
}

#ASADAL start
if (isset($_GET['id'])){
$profile2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".intval($_GET['id'])."'"));
echo ' <div><img src="/style/images/space.jpg" alt="" style="width:100%;"/></div>';
if($profile2 != 0){
    
echo '<div class="podmenu">Ваш реферал: <b>'.text($profile2['login']).'</b></a></div>';
}
}
#ASADAL end

echo ' 
	<form action="" method="post">
Ник:<br/>
<input type="text" name="name" maxlength="50" value="" placeholder="Введите Ник..." /><br/>
Пол:<br />
<select name="sex"><option value="m">Мужской</option><option value="w">Женский</option></select><br/>
		Пароль:<br/>
		<input type="password" name="pass" maxlength="50" value="" placeholder="Введите пароль..." /><br/>
		Пароль еще раз:<br/>
		<input type="password" name="repass" maxlength="50" value="" placeholder="Введите пароль еще раз..." /><br/>
E-mail:<br/>
<input type="text" name="mail" maxlength="50" value="" placeholder="Введите почтовый ящик..." /><br/>
<div class="minor mt4">E-mail необходим для восстановления пароля. Если Вы их не укажете, или укажете неверно, то восстановление пароля будет невозможно.</div>
		
		<input type="submit" value="Регистрация" name="success" class="btn"/>
	</form>
';
echo '</div></center><a class="btnl mt4" href="/" class="btn">В начало</a>';
require_once ('system/footer.php');

?>