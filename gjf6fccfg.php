<?php
require_once ('system/func.php');
auth(); // Закроем от гостей
access(1);

## Выдача рубинов  ##
if(isset($_GET['getruby'])){
if(isset($_POST['name']) &&
isset($_POST['id'])){
$name = text($_POST['name']);
$id = text($_POST['id'] );
$sql = "UPDATE users SET ruby = ruby+'".text($name)."' WHERE `id` = '".$id."'";
$kont = mysql_fetch_assoc(mysql_query("SELECT * FROM `kont` WHERE `id_user` = '2' && `id_kont` = '".$id."' LIMIT 1"));
if($kont['id_kont'] != $id){
mysql_query("INSERT INTO `kont` SET `id_user` = '".$id."', `id_kont` = '2', `time` = '".time()."'");
mysql_query("INSERT INTO `kont` SET `id_user` = '2', `id_kont` = '".$id."', `time` = '".time()."'");
}else{
mysql_query("update `kont` set `time` = '".time()."' WHERE `id_user` = '2' && `id_kont` = '".$id."'");
mysql_query("update `kont` set `time` = '".time()."' WHERE `id_user` = '".$id."' && `id_kont` = '2'");
}
mysql_query("INSERT INTO `mail` SET `in` = '2', `out` = '".$id."', `text` = 'Вам выдана ".n_f($name)." рубинов. [br]Приятной игры.[/br]' , `time` = '".time()."'");
$query = mysql_query($sql);
$_SESSION['msg'] = 'Выдано '.n_f($name).' рубинов.';
        header("Location: ?getruby");
        exit;
}

$title = 'Дать рубины';
require_once ('system/header.php');
echo "<div class='bordered'>"; 
echo "<div class='content'>";
echo "<form method='post' action='?getruby'>";
echo "Введите ид пользователя:<br><input type='text' name='id'><br>";
echo "Введите кол-во:<br><input type='text' name='name'><br>";
echo "<input type='submit' class='btn' value='Выдать'>";
echo "</form>";
echo "</div></div>";
echo'<a class="btnl mt4" href="/md1" class="btn">В админ панель</a>';
require_once ('system/footer.php');
exit;
}
 
## Снять рубинов  ##
if(isset($_GET['delruby'])){
if(isset($_POST['name']) &&
isset($_POST['id']) &&
isset($_POST['info'])){
$name = text($_POST['name']);
$id = text($_POST['id'] );
$info = text($_POST['info'] );
$sql = "UPDATE users SET ruby = ruby-'".text($name)."' WHERE `id` = '".$id."'";
$kont = mysql_fetch_assoc(mysql_query("SELECT * FROM `kont` WHERE `id_user` = '2' && `id_kont` = '".$id."' LIMIT 1"));
if($kont['id_kont'] != $id){
mysql_query("INSERT INTO `kont` SET `id_user` = '".$id."', `id_kont` = '2', `time` = '".time()."'");
mysql_query("INSERT INTO `kont` SET `id_user` = '2', `id_kont` = '".$id."', `time` = '".time()."'");
}else{
mysql_query("update `kont` set `time` = '".time()."' WHERE `id_user` = '2' && `id_kont` = '".$id."'");
mysql_query("update `kont` set `time` = '".time()."' WHERE `id_user` = '".$id."' && `id_kont` = '2'");
}
mysql_query("INSERT INTO `mail` SET `in` = '2', `out` = '".$id."', `text` = 'У вас было снято ".n_f($name)." рубинов. [br] Причина: ".text($info).". [/br]' , `time` = '".time()."'");
$query = mysql_query($sql);
$_SESSION['msg'] = 'Снято '.n_f($name).' рубинов.';
        header("Location: ?delruby");
        exit;
}

$title = 'Снять рубины';
require_once ('system/header.php');
echo "<div class='bordered'>"; 
echo "<div class='content'>";
echo "<form method='post' action='?delruby'>";
echo "Введите ид пользователя:<br><input type='text' name='id'><br>";
echo "Введите кол-во:<br><input type='text' name='name'><br>";
echo "Причина:<br><input type='text' name='info'><br>";
echo "<input type='submit' class='btn' value='Снять'>";
echo "</form>";
echo "</div></div>";
echo'<a class="btnl mt4" href="/md1" class="btn">В админ панель</a>';
require_once ('system/footer.php');
exit;
}

## Редактор игрока ##
if(isset($_GET['sett_user'])){

$ank = $db->query("SELECT * FROM `users` WHERE `id` = '".num($_GET['sett_user'])."'")->fetch_assoc();
if(isset($_POST['login'], $_POST['email'], $_POST['sex'], $_POST['access'], $_POST['ruby'], $_POST['status'], $_POST['pass'])){
$login = text($_POST['login']);
$login_sql = $db->query("SELECT * FROM `users` WHERE `login` = '$login' and `id` != '$ank[id]'");
$email = text($_POST['email']);
$email_sql = $db->query("SELECT * FROM `users` WHERE `email` = '$email' and `id` != '$ank[id]'");
$sex = text($_POST['sex']);
$access = num($_POST['access']);
$ruby = num($_POST['ruby']);
$status = num($_POST['status']);
$pass = num($_POST['pass']);


if($login_sql->num_rows > 0) $err = 'Логин занят';
if($email_sql->num_rows > 0) $err = 'E-mail занят';
if(!isset($err)){
$db->query("update `users` set `login` = '".$login."', `email` = '".$email."', `sex` = '".$sex."', `access` = '".$access."', `ruby` = '".$ruby."', `sttus` = '".$status."', `pass` = '".$pass."' where (`id` = '".$ank['id']."')");
$_SESSION['msg'] = 'Данные обновлены';
header("Location: ?sett_user=$ank[id]");
exit();
}else{
$_SESSION['msg'] = $err;
header("Location: ?sett_user=$ank[id]");
exit();
}
}
$title = 'Редактор '.$ank['login'];
require_once ('system/header.php');
echo "<div class='block'>";
echo "<form method='post' action='?sett_user=$ank[id]'>";
echo "Логин:<br><input type='text' name='login' value='$ank[login]'><br>";
echo "E-mail:<br><input type='text' name='email' value='$ank[email]'><br>";
echo "Пол: (m/w)<br><input type='text' name='sex' value='$ank[sex]'><br>";
echo "Должность:<br>";
echo '<select name="access">';
$dat = array('Игрок' => '0', 'Модератор' => '1', 'Администратор' => '2');
foreach ($dat as $key => $value) { 
echo ' <option value="'.$value.'"'.($value == $ank['access'] ? ' selected="selected"' : '') .'>'.$key.'</option>'; 
}
echo '</select><br/>';
echo 'Рубины:<br><input type="text" name="level" value="'. $ank['ruby'] .'"><br>';
echo 'Статус:<br><input type="text" name="str" value="'. $ank['status'] .'"><br>';
echo 'Пароль:<br><input type="text" name="str" value="'. $ank['pass'] .'"><br>';

echo "<input type='submit' class='btn' value='Изменить'> <a href='?users'>Отмена</a>";
echo "</form>";
echo "</div>";
require_once ('system/footer.php');
exit();
}

## Ищим игрока ##
if(isset($_GET['users'])){

if(isset($_POST['login'])){
$login = text($_POST['login']);
$sql = $db->query("SELECT * FROM `users` WHERE `login` = '$login'");
if($sql->num_rows == 0) $err = 'Игрок не найден';
if(!isset($err)){
$ank = $db->query("SELECT `id` FROM `users` WHERE `login` = '$login'")->fetch_assoc();
header("Location: ?sett_user=$ank[id]");
exit();
}else{
$_SESSION['msg'] = $err;
header('location: ?users');
exit();
}
}
$title = 'Редактор игрока';
require_once ('system/header.php');
echo "<div class='block'>";
echo "<form method='post' action='?users'>";
echo "Введите ник игрока:<br><input type='text' name='login'>";
echo "<input type='submit' class='btn' value='Редактировать'>";
echo "</form>";
echo "</div>";
echo "<a href='?' class='link'> Вернуться назад</a>";
require_once ('system/footer.php');
exit();
}
 
## Страница админки ##
$title = 'Панель Модератора';
require_once ('system/header.php'); 
echo'<a class="btnl mt4" href="/md1?getruby" class="btn">Дать рубины</a>';
echo'<a class="btnl mt4" href="/md1?delruby" class="btn">Снять рубины</a>';
echo'<a class="btnl mt4" href="/md1?sett_user" class="btn">Редактор игрока</a>';
echo'<a class="btnl mt4" href="/md1?users" class="btn">Ищим игрока</a>';
require_once ('system/footer.php'); 
?>
