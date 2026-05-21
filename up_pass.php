<?php
require_once ('system/func.php');
$title = 'Настройки';
require_once ('system/header.php');
auth(); // Закроем от гостей
if ($user['pass'] != NULL)header('Location: /');

if(isset($_POST['add'])){
$pass = text($_POST['pass']);
$secret = text($_POST['secret']);
if(empty($pass)) $err = 'Введите пароль';
else
if(empty($pass)) $secret = 'Введите секретное слово';
elseif(mb_strlen($pass) > 20 or mb_strlen($pass) < 6) $err = 'Пароль не может быть короче 6 и длиннее 20 символов';
elseif(mb_strlen($secret) > 20 or mb_strlen($secret) < 3) $err = 'Секретное слово не может быть короче 3 и длиннее 20 символов';

if(!$err){
mysql_query("UPDATE `users` SET `pass` = '".$pass."',`secret` = '".$secret."' WHERE `id` = '".$myID."'");

$_SESSION['msg'] = "Сохранено!";
header('Location: /');
exit();
}else{
$_SESSION['msg'] = $err;
header('Location: ?');
exit();
}
}
echo '<font color="red">Все поля являются обязательными для заполнения!</br>Данный раздел, доступен только 1 раз, до указания пароля и секретного слова, после вы не сможете зайти на данную страницу!!</font>
</br>
После ввода этих данных, послежующая авторизация на сайте будет проходить с новым указанным пароелм, ПАРОЛЕМ который вы указали на данной странице.. Если ваша авторизация слетела, это означает что необходимо авторизироваться с новым паролдем!</br>Новые пароли вступят в силу завтра вечером!</br> Секретное слово, Необходимо для восстановления пароля через администрацию!</br>
';
echo "<div class='content'>";
echo '<form action="" method="post">';
echo 'Введите пароль: (От 6 до 20 символов)<br><input type="password" name="pass" maxlength="50" value="" /><br/>';
echo 'Введите секретное слово: (От 3 до 20 символов)<br><input type="text" name="secret" maxlength="50" value="" /><br/>';
echo '<input type="submit" name="add" class="btn" value="Подтверить">';
echo '</form>';
echo "</div>";


require_once ('system/footer.php');
?>