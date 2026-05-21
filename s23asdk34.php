<?php
require_once ('system/func.php');
auth(); // Закроем от гостей
access(2);

## Бонус  ##
if(isset($_GET['number_format'])){
if(isset($_POST['col1']) && isset($_POST['text'])){
$col1 = text($_POST['col1']);
$text = text($_POST['text']);
$nnf = mysql_query("SELECT * FROM `number_format`"); 
$nnf = mysql_fetch_array($nnf);
$c = 1000;
$sql = "INSERT INTO `number_format` SET `col1` = '".$col1."', `col2` = '".$col1*$c."', `text` = '".$text."'";
$query = mysql_query($sql);
}
$title = 'Формат цифр';
require_once ('system/header.php');
echo "<div class='bordered'>"; 
echo "<div class='content'>";

echo "Последняе значение: ".$nnf['col2']."";

echo "<form method='post' action='?number_format'>";
echo "Введите кол-во:<br><input type='text' name='col1'><br>";
echo "Введите значение цифр:<br><input type='text' name='text'><br>";
echo "<input type='submit' class='btn' value='Добавить'>";
echo "</form>";
echo "</div></div>";
echo'<a class="btnl mt4" href="/ad2" class="btn">В админ панель</a>';
require_once ('system/footer.php');
break;
}
## Бонус  ##
if(isset($_GET['bonus'])){
if(isset($_POST['name'])){
$name = text($_POST['name']);
$sql = "UPDATE users SET ruby = ruby+'".$name."'";
$query = mysql_query($sql);
}
$title = 'Бонус';
require_once ('system/header.php');
echo "<div class='bordered'>"; 
echo "<div class='content'>";
echo "<form method='post' action='?bonus'>";
echo "Введите кол-во:<br><input type='text' name='name'><br>";
echo "<input type='submit' class='btn' value='Выдать'>";
echo "</form>";
echo "</div></div>";
echo'<a class="btnl mt4" href="/ad2" class="btn">В админ панель</a>';
require_once ('system/footer.php');
break;
}
## Выдача рубинов  ##
if(isset($_GET['getruby'])){
if(isset($_POST['name']) &&
isset($_POST['id'])){
$name = text($_POST['name']);
$id = text($_POST['id'] );
$sql = "UPDATE users SET ruby = ruby+'".$name."' WHERE `id` = '".$id."'";
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
echo'<a class="btnl mt4" href="/ad2" class="btn">В админ панель</a>';
require_once ('system/footer.php');
break;
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
## Сменить ранг  ##
if(isset($_GET['rang'])){
if(isset($_POST['id'])){
$id = text($_POST['id']);
mysql_query("UPDATE `users` SET  `clan_rang` = '5' WHERE `id` = '".$id."' ");
$_SESSION['msg'] = 'Вы успешно выдали владельца'; 
header('Location: ?rang'); 
exit();
}
$title = 'Дать владельца';
require_once ('system/header.php');
echo "<div class='bordered'>"; 
echo "<div class='content'>";
echo "<form method='post' action='?rang'>";
echo "Введите ид пользователя:<br><input type='text' name='id'><br>";
echo "<input type='submit' class='btn' value='Выдать владельца'>";
echo "</br>
Смена ранга в два этапа. </br> 1. дать владельца. </br> 2. снять владельца.</form>";
echo "</div></div>";
echo'<a class="btnl mt4" href="/ad2?rang1" class="btn">Следующий шаг</a>';
echo'<a class="btnl mt4" href="/ad2" class="btn">В админ панель</a>';
require_once ('system/footer.php');
break;
}
## Сменить ранг 1 ##
if(isset($_GET['rang1'])){
if(isset($_POST['id'])){
$id = text($_POST['id']);
mysql_query("UPDATE `users` SET  `clan_rang` = '4' WHERE `id` = '".$id."' ");
$_SESSION['msg'] = 'Вы успешно сняли владельца'; 
header('Location: ?rang1'); 
exit();
}
$title = 'Снять владельца. ';
require_once ('system/header.php');
echo "<div class='bordered'>"; 
echo "<div class='content'>";
echo "<form method='post' action='?rang1'>";
echo "Введите ид пользователя:<br><input type='text' name='id'><br>";
echo "<input type='submit' class='btn' value='Снять владельца'>";
echo " </form>";
echo "</div></div>";
echo'<a class="btnl mt4" href="/ad2?rang" class="btn">назад</a>';
echo'<a class="btnl mt4" href="/ad2" class="btn">В админ панель</a>';
require_once ('system/footer.php');
break;
}
## Дать Адм\Мд  ##
if(isset($_GET['adm'])){
if(isset($_POST['id']) && isset($_POST['pr'])){
$id = text($_POST['id']);
$pr = text($_POST['pr']);
mysql_query("UPDATE `users` SET  `access` = '".$pr."' WHERE `id` = '".$id."' ");
$_SESSION['msg'] = 'Вы успешно сменили должность'; 
header('Location: ?adm'); 
exit();
}
$title = 'Дать владельца';
require_once ('system/header.php');
echo "<div class='bordered'>"; 
echo "<div class='content'>";
echo "<form method='post' action='?adm'>";
echo "Введите ид пользователя:<br><input type='text' name='id'><br>";
echo "Введите права:<br><input type='text' name='pr'><br>";
echo "<input type='submit' class='btn' value='Сменить'>";
echo "</br>
<b>Права:</b> Админ = 2. </br> МД = 1 <br> Игрок = 0</form>";
echo "</div></div>";
echo'<a class="btnl mt4" href="/ad2" class="btn">В админ панель</a>';
require_once ('system/footer.php');
break;
}

## обнуление всех пользователей  ##
if(isset($_GET['udelall'])){
 if(isset($_POST['id'])){
$id = text($_POST['id']);
$sql = "UPDATE `users` SET `gold` = '2', `angels_bonus` = '0', `ruby` = '500', `angels` = '0', `x2` = '1', `dubl` = '1', `gold_dubl` = '1000000000' ";
$query = mysql_query($sql);
$_SESSION['msg'] = 'Вы успешно обнулили всех'; header('Location: ?udelall'); exit();
}
$title = 'Обнуление всех пользователя';
require_once ('system/header.php');
echo "<div class='bordered'>"; 
echo "<div class='content'>";
echo "<form method='post' action='?udelall'>";
echo "Введите слово Обнулить:<br><input type='text' name='id'><br>";
echo "<input type='submit' class='btn' value='Обнулить'>";
echo "</br>
Обнуление в два этапа. </br> 1. сброс всего кроме бизнесов. </br> 2. сброс бизнесов.</form>";
echo "</div></div>";
echo'<a class="btnl mt4" href="/ad2?udelall_2" class="btn">Следующий шаг</a>';
echo'<a class="btnl mt4" href="/ad2" class="btn">В админ панель</a>';
require_once ('system/footer.php');
break;
}
## обнуление всех бизнесов   ##
if(isset($_GET['udelall_2'])){

 if(isset($_POST['id'])){
$id = text($_POST['id']);
$sql = " DELETE FROM `room_users`";
$query = mysql_query($sql);
$_SESSION['msg'] = 'Вы успешно сбросили все бизнеса'; header('Location: ?udelall_2'); exit();

}
$title = 'Обнуление всех бизнесов. ';
require_once ('system/header.php');
echo "<div class='bordered'>"; 
echo "<div class='content'>";
echo "<form method='post' action='?udelall_2'>";
echo "Введите слово Обнулить:<br><input type='text' name='id'><br>";
echo "<input type='submit' class='btn' value='Обнулить'>";
echo " </br>после нажатия на Обнулить пользователя обнулён полностью, </form>";
echo "</div></div>";
echo'<a class="btnl mt4" href="/ad2?udelall" class="btn">назад</a>';
require_once ('system/footer.php');
break;
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
$title = 'Настройки игры | Админка';
require_once ('system/header.php'); 
echo'<a class="btnl mt4" href="/ad2?bonus" class="btn">Бонус</a>';
echo'<a class="btnl mt4" href="/ad2?getruby" class="btn">Дать рубины</a>';
echo'<a class="btnl mt4" href="/ad2?delruby" class="btn">Снять рубины</a>';
echo'<a class="btnl mt4" href="/ad2?rang" class="btn">Сменить ранг</a>';
echo'<a class="btnl mt4" href="/ad2?adm" class="btn">Дать адм\мд</a>';
echo'<a class="btnl mt4" href="/ad2?number_format" class="btn">Формат цифр</a>';
echo'<a class="btnl mt4" href="/ad2?udelall" class="btn">Обнуление всех Пользователей</a>';
echo'<a class="btnl mt4" href="/ad2?sett_user" class="btn">Редактор игрока</a>';
echo'<a class="btnl mt4" href="/ad2?users" class="btn">Ищим игрока</a>';
require_once ('system/footer.php'); 
?>
