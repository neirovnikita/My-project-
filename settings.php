<?php
require_once ('system/func.php');
$title = 'Настройки';
require_once ('system/header.php');
auth(); // Закроем от гостей
if(isset($_GET['exit'])){
setcookie('login', null, time()-86400*365, '/');
setcookie('pass', null, time()-86400*365, '/');
header('location: /');
exit;
}
if(isset($_GET['login'])){
if(isset($_REQUEST['add'])){
$login = text($_POST['login']);
$sql = mysql_query("SELECT COUNT(`id`) FROM `users` WHERE `login` = '".$login."'");  // Доступность логина
if(empty($login))$err = 'Введите логин';
if(mysql_result($sql, 0) > 0) $err = 'Такой логин уже занят';
if($user['ruby'] < 500) $err = 'На вашем счёте не достаточно рубинов для смены логина';
if(mb_strlen($login) > 20 or mb_strlen($login) < 3) $err = 'Логин не может быть короче 3 и длиннее 20 символов';
if(!$err){
mysql_query("UPDATE `users` SET `login` = '".text($login)."', `ruby` = '".text($user['ruby']-500)."' WHERE `id` = '".text($myID)."'");
setcookie('login', $login, time()+86400*365, '/');
$_SESSION['msg'] = "Ваш новый логин <b>".$login."</b>";
header('Location: ?');
exit();
}else{
$_SESSION['msg'] = $err;
header('Location: ?login');
exit();
}
}
echo "<div class='content'>";
if($user['ruby'] >= 500){
echo '<form action="" method="post">';
echo 'Новый логин:<br><input type="text" name="login" maxlength="50" value="" /><br/>';
echo '<input type="submit" name="add" class="btn" value="Продолжить">';
echo '</form>';
}else{
echo "На вашем счёте не достаточно рубинов для смены логина";
}
echo "</div>";
require_once ('system/footer.php');
break;
}
if(isset($_GET['sex_ok'])){
if($user['ruby'] < 250){
$_SESSION['msg'] = "Не хватает ".(250-$user['ruby'])." рубинов";
header('Location: ?side');
exit();
}
mysql_query("UPDATE `users` SET `sex` = '".text($user['sex'] == 'm' ? 'w' : 'm')."', `ruby` = '".text($user['ruby']-250)."' WHERE `id` = '".$myID."'");
$_SESSION['msg'] = 'Пол успешно изменен';
header('Location: ?sex');
exit();
}
if(isset($_GET['sex'])){
echo "<div class='content'>";
echo "<p>Текущий пол <span class='white'>".text($user['sex'] == 'm' ? 'Мужчина' : 'Женщина')."</span>! Вы действительно хотите сменить пол на <span class='info'>".text($user['sex'] == 'm' ? 'Женский' : 'Мужской')."</span></p>";
echo "<li><a href='?sex_ok'>Да, сменить</a></li>";
echo "<li><a href='?'>Нет, отмена</a></li>";
echo "</div>";
require_once ('system/footer.php');
break;
}
if(isset($_GET['pass'])){
if(isset($_REQUEST['add'])){
$mypass = text($_POST['mypass']);
$pass = text($_POST['pass']);
$repass = text($_POST['repass']);
if(empty($pass)) $err = 'Введите пароль';
elseif(empty($repass)) $err = 'Введите пароль еще раз';
elseif(empty($mypass)) $err = 'Введите старый пароль';
elseif(mb_strlen($pass) > 20 or mb_strlen($pass) < 3) $err = 'Пароль не может быть короче 3 и длиннее 20 символов';
elseif($pass != $repass) $err = 'Пароли не совпадают';
elseif($mypass != $user['pass']) $err = 'Старый пароль введён неверно!';
if(!$err){
mysql_query("UPDATE `users` SET `pass` = '".$pass."' WHERE `id` = '".$myID."'");
setcookie('pass', $pass, time()+86400*365, '/');
$_SESSION['msg'] = "Выш новый пароль <b>".$pass."</b>";
header('Location: ?');
exit();
}else{
$_SESSION['msg'] = $err;
header('Location: ?pass');
exit();
}
}
echo "<div class='content'>";
echo '<form action="" method="post">';
echo 'Старый пароль:<br><input type="password" name="mypass" maxlength="50" value="" /><br/>';
echo 'Новый пароль:<br><input type="password" name="pass" maxlength="50" value="" /><br/>';
echo 'Повторите новый пароль:<br><input type="password" name="repass" maxlength="50" value="" /><br/>';
echo '<input type="submit" name="add" class="btn" value="Продолжить">';
echo '</form>';
echo "</div>";
require_once ('system/footer.php');
break;
}
echo "<div class='content'>";
echo "Логин: <span class='white'>".$myLogin."</span><br> <a href='?login' class='btnl'>Изменить <img width='20' height='20' alt='' src='/img/ruby.png' title=''/> 500 </a>";
echo "Пол: <span class='white'>".($user['sex'] == 'm' ? 'Мужской' : 'Женский')."</span><br> <a href='?sex' class='btnl'>Изменить <img width='20' height='20' alt='' src='/img/ruby.png' title=''/> 250 </a><br>";
echo "<a href='?pass' class='btnl'> Изменить пароль</a><br>";
echo "</div>";
require_once ('system/footer.php');
?>