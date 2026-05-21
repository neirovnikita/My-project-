<?php
#ASADAL
require_once ('system/func.php');
require_once ('system/header.php');
auth(); // Закроем от не авторизованных

$ruby = 250; // ЦЕНА АВАТАРКИ

if(isset($_GET['new'])){

echo'<div class="feedback">';
echo 'Смена аватара</div>';
$maxsize = 2;
if(isset($_REQUEST['submit']))
{

$text = text($_POST['text']);

$size = $_FILES['filename']['size'];
$ftype = $_FILES['filename']['type'];

if(!@file_exists($_FILES['filename']['tmp_name'])) {
echo 'Вы не выбрали файл!</div>';
require_once ('system/footer.php');
exit();
}

if ($size > (1048576 * $maxsize)) {
echo 'Максимальный размер файла '.$maxsize.'мб!/div>';
require_once ('system/footer.php');
exit();
}


$filetype = array ( 'jpg', 'png', 'jpeg'); 
$upfiletype = substr($_FILES['filename']['name'],  strrpos( $_FILES['filename']['name'], ".")+1); 
  

if(!in_array($upfiletype,$filetype)) {
echo 'К загрузке разрешены файлы форматом JPG,PNG,JPEG!</div>';
require_once ('system/footer.php');
exit();
}

if($user['ruby'] < $ruby){
echo 'У вас нет столько рубинов!';
require_once ('system/footer.php');
exit();
}

$files = ''.rand(1234,6789).'.'.$upfiletype.'';

move_uploaded_file($_FILES['filename']['tmp_name'], "img/avatar/".text($files)."");  

mysql_query("UPDATE `users` SET `ruby` = `ruby` -'".text($ruby)."' WHERE `id` = '".$user['id']."' LIMIT 1");
mysql_query("UPDATE `users` SET `ava` = '".text($files)."' WHERE `id` = '".$user['id']."' LIMIT 1");
echo 'Вы успешно сменили аватар!</br>';
require_once ('system/footer.php');
exit();

}

echo 'Стоимость смены аватарки: '.$ruby.' рубинов</br><form action="" method="post" enctype="multipart/form-data"> 
Выберите файл (до'.$maxsize.'мб):<br><center><input type="file" name="filename"/><br>   
<input type="submit" class="btni" value="Загрузить" name="submit"/> </center>
</form></div></div></a>';

}
else
{
header('Location: /game');
require_once ('system/footer.php');
exit();
}

require_once ('system/footer.php');

?>

