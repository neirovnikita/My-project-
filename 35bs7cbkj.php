<?php
require_once ('system/func.php');
auth(); // Закроем от гостей
access(2);

## Платежи  ##
if(isset($_GET['payment_ok'])){
$title = 'Список платежей';
require_once ('system/header.php');
echo "<div class='block'>".ico('icons','arrow.png')." <a href='?payment'>Все операции</a> | Успешные</div>";
$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `worldkassa` WHERE `time_oplata` > '0'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `worldkassa` WHERE `time_oplata` > '0' ORDER BY `id` DESC LIMIT ".$start.", ".$set[p_str]."");
echo "<div class='block'>";
if($k_post == 0) echo "Опираций не найдено...";
while($post = mysql_fetch_assoc($q)) {
$ank = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = ".$post[id_user]." LIMIT 1"));
if($post['time_oplata'] > 0)$status = "<font color='green'>Оплаченно</font>";
else $status = "<font color='red'>Не оплаченно</font>";
echo "Оплата счета #".$post['id_bill'].", ".vremja($post[time])."<br>Пакупатель: ".icons_user($ank[id])." <a href='/profile/".$ank[id]."'>".$ank[login]."</a><br>Сумма: ".$post[summa]."Rub.<br>Статус: ".$status."<hr>";
}
if($k_post > 10){
str('?payment_ok&',$k_page,$page); // Вывод страниц
}else{
}
echo "</div>";
echo "<a href='?' class='link'>".ico('icons','arrow.png')." Вернуться назад</a>";
require_once ('system/footer.php');
break;
}

if(isset($_GET['gift'])){
$title = 'Добавить подарок';
require_once ('system/header.php');
echo "<div class='block'>Добавить подарок</div>";

if(isset($_REQUEST['submit'])) {

$maxsize = 1;
$size = $_FILES['filename']['size'];

$filetype = array ( 'jpg', 'png', 'jpeg', 'zip', 'rar' ); 
$upfiletype = substr($_FILES['filename']['name'],  strrpos( $_FILES['filename']['name'], ".")+1); 

if(!in_array($upfiletype,$filetype)) {
echo 'Такой формат запрещено загружать!';
require_once ('system/footer.php'); 
exit;
}

$files = ''.rand(12345,6789).'_'.rand(12345,6789).'_'.rand(12345,6789).'.'.$upfiletype.''; 

move_uploaded_file($_FILES['filename']['tmp_name'], "style/gift/".$files.""); 
mysql_query("INSERT INTO `asadal_gifta` SET `file` = '".$files."'");
echo 'Подарок успешно загружен</br><a href = "/ad1?gift">Назад</a>';
exit;
}

echo '<form action="" enctype="multipart/form-data" name="message" method="POST"><div class=block>Выберите файл: (jpg,png,jpeg,zip,rar)<br><input type="file" name="filename"/>';
echo "</br><input type='submit' name='submit' value='Добавить' /></form>";
}

if(isset($_GET['payment'])){
$title = 'Список платежей';
require_once ('system/header.php');
echo "<div class='block'>".ico('icons','arrow.png')." Все операции | <a href='?payment_ok'>Успешные</a></div>";
$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `worldkassa`"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `worldkassa` ORDER BY `id` DESC LIMIT ".$start.", ".$set[p_str]."");
echo "<div class='block'>";
if($k_post == 0) echo "Опираций не найдено...";
while($post = mysql_fetch_assoc($q)) {
$ank = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = ".$post[id_user]." LIMIT 1"));
if($post['time_oplata'] > 0)$status = "<font color='green'>Оплаченно</font>";
else $status = "<font color='red'>Не оплаченно</font>";
echo "Оплата счета #".$post['id_bill'].", ".vremja($post[time])."<br>Пакупатель: ".icons_user($ank[id])." <a href='/profile/".$ank[id]."'>".$ank[login]."</a><br>Сумма: ".$post."[summa]Rub.<br>Статус: ".$status."<hr>";
}
if($k_post > 10){
str('?payment&',$k_page,$page); // Вывод страниц
}else{
}
echo "</div>";
echo "<a href='?' class='link'>".ico('icons','arrow.png')." Вернуться назад</a>";
require_once ('system/footer.php');
break;
}

if(isset($_GET['payment'])){
$title = 'Список платежей';
require_once ('system/header.php');
echo "<div class='block'>".ico('icons','arrow.png')." Все операции | <a href='?worldkassa_ok'>Успешные</a></div>";
$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `worldkassa`"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `worldkassa` ORDER BY `id` DESC LIMIT ".$start.", ".$set[p_str]."");
echo "<div class='block'>";
if($k_post == 0) echo "Опираций не найдено...";
while($post = mysql_fetch_assoc($q)) {
$ank = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = ".$post[id_user]." LIMIT 1"));
if($post['time_oplata'] > 0)$status = "<font color='green'>Оплаченно</font>";
else $status = "<font color='red'>Не оплаченно</font>";
echo "Оплата счета #".$post['id_bill'].", ".vremja($post[time])."<br>Пакупатель: ".icons_user($ank[id])." <a href='/profile/".$ank[id]."'>".$ank[login]."</a><br>Сумма: ".$post[summa]."Rub.<br>Статус: ".$status."<hr>";
}
if($k_post > 10){
str('?payment&',$k_page,$page); // Вывод страниц
}else{
}
echo "</div>";
echo "<a href='?' class='link'>".ico('icons','arrow.png')." Вернуться назад</a>";
require_once ('system/footer.php');
break;
}


if(isset($_GET['flood'])){

if(isset($_POST['name'])){

$name = text($_POST['name']);

if($name == NULL){
$err = 'Введите время!';
}

if(!isset($err)){
mysql_query("UPDATE `guard` SET `antoflood` = '".$name."' WHERE `id` = '1'");

$_SESSION['msg'] = "Защита \"Антифлуд\" успешно установлена.";
header("Location: ?flood");
exit();
}else{
$_SESSION['msg'] = $err;
header('location: ?flood');
exit();
}

}

$title = 'Редактирование антифлуда';
require_once ('system/header.php');
echo "<div class='bordered'>"; 
echo "<div class='content'>";
echo "<form method='post' action='?flood'>";
echo "Введите время (в сек) за которое пользователь сможет оптправлять одно сообщение.</br>Оптимальное время: 3-5 сек:<br><input type='text' name='name'><br>";
echo "<input type='submit' class='btn' value='Изменить'>";
echo "</form>";
echo "</div></div>";
echo'<a class="btnl mt4" href="/ad1" class="btn">В админ панель</a>';
require_once ('system/footer.php');
break;
}

if(isset($_GET['ddos'])){

if(isset($_POST['name'])){

$name = text($_POST['name']);

if($name == NULL){
$err = 'Введите время!';
}

if(!isset($err)){
mysql_query("UPDATE `guard` SET `antidos` = '".$name."' WHERE `id` = '1'");

$_SESSION['msg'] = "Защита \"Антиддос\" успешно установлена.";
header("Location: ?ddos");
exit();
}else{
$_SESSION['msg'] = $err;
header('location: ?ddos');
exit();
}

}



$title = 'Редактирование антидоса';
require_once ('system/header.php');
echo "<div class='bordered'>"; 
echo "<div class='content'>";
echo "<form method='post' action='?ddos'>";
echo "Введите время (в мс) за которое гость может сделать запросов за одно обновление.</br>Оптимальное время: 0.5 сек:<br><input type='text' name='name'><br>";
echo "<input type='submit' class='btn' value='Изменить'>";
echo "</form>";
echo "</div></div>";
echo'<a class="btnl mt4" href="/ad1" class="btn">В админ панель</a>';
require_once ('system/footer.php');
break;
}

if(isset($_GET['spam'])){

if(isset($_POST['name'])){

$name = text($_POST['name']);

$sql = mysql_query("SELECT COUNT(`id`) FROM `words` WHERE `text` = '".$name."'"); 

if(mysql_result($sql, 0) > 0){
$error = 'Данное слово уже есть в списке запрещенных!';
}

if($name == NULL){
$err = 'Введите слово!';
}

if(!isset($err)){
mysql_query("INSERT INTO `words` SET `text` = '".$name."'");

$_SESSION['msg'] = "Слово \"".$name."\" успешно добавлено в список запрещенных слов.";
header("Location: ?spam");
exit();
}else{
$_SESSION['msg'] = $err;
header('location: ?spam');
exit();
}

}

$title = 'Редактирование антиспама';
require_once ('system/header.php');
echo "<div class='bordered'>"; 
echo "<div class='content'>";
echo "<form method='post' action='?spam'>";
echo "Введите слово, котрое будет заменяться на форуме символами ***.</br>Это могут быть маты,ссылки (http,www и т.д)</br><input type='text' name='name'><br>";
echo "<input type='submit' class='btn' value='Добавить'>";
echo "</form>";
echo "</div></div>";

echo '<a class="btnl mt4" href="#" class="btn">Все слова</div></a>';
echo '</div>';
$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `words`"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `words` ORDER BY `id` DESC LIMIT ".$start.", ".$set[p_str]."");
if($k_post == 0) echo "Слов не найдено...";
echo '</div>';
while($post = mysql_fetch_assoc($q)) {

echo "Слово:".$post['text']."</br>";
}
if($k_post > 10){
str('?spam&',$k_page,$page); // Вывод страниц
}else{
}

echo '</div>';

echo'<a class="btnl mt4" href="/ad1" class="btn">В админ панель</a>';
require_once ('system/footer.php');
break;
}

## Страница админки ##
$title = 'Настройки игры | Админка';
require_once ('system/header.php'); 
echo'<a class="btnl mt4" href="/ad1?payment" class="btn">Список платежей</a>';
echo'<a class="btnl mt4" href="/ad1?gift" class="btn">Добавить подарок</a>';

echo '</div></br>';

echo'<a class="btnl mt4" href="/ad1?ddos" class="btn">Управление антидосом</a>';
echo'<a class="btnl mt4" href="/ad1?flood" class="btn">Управление антифлудом</a>';
echo'<a class="btnl mt4" href="/ad1?spam" class="btn">Управление антиспамом</a>';

require_once ('system/footer.php'); 
?>
