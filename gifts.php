<?php
require_once ('system/func.php');
require_once ('system/header.php');
auth(); // Закроем от не авторизованных

$cenaGift = 250; // СТОИМОМСТЬ ПОДАРКА

if(isset($_GET['g'])){

$id = abs(intval($_GET['id']));
$profile = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$id."'"));

if(!$profile){
echo 'Такой игрок не существует';
require_once ('system/footer.php');
exit();
}
echo'<div class="feedback">';
echo 'Подарки игрока: '.$profile['login'].'';
echo'</div>';



$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `asadal_gift` WHERE `komy` = '".$id."'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `asadal_gift` WHERE `komy` = '".$id."' ORDER BY `id` DESC LIMIT ".$start.", ".$set[p_str]."");
if($k_post == 0) echo "Подарков нет";
while($post = mysql_fetch_assoc($q)) {
    
$g = mysql_fetch_assoc(mysql_query("SELECT * FROM `asadal_gifta` WHERE `id` = '".$post['gift']."'"));
$g2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$post['otkogo']."'"));
echo'<div class="bordered">';
echo '<img src = "/style/gift/'.$g['file'].'" width = "80"> От: '.$g2['login'].'</div></a>';
}



if($k_post > 10){
str('/gifts/?my&',$k_page,$page); // Вывод страниц
}else{
}

}

if(isset($_GET['my'])){
echo'<div class="feedback">';
echo 'Мои подарки';
echo'</div>';
$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `asadal_gift` WHERE `komy` = '".$user['id']."'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `asadal_gift` WHERE `komy` = '".$user['id']."' ORDER BY `id` DESC LIMIT ".$start.", ".$set[p_str]."");
echo "<div class='block'>";
if($k_post == 0) echo "Подарков нет";
while($post = mysql_fetch_assoc($q)) {
$g = mysql_fetch_assoc(mysql_query("SELECT * FROM `asadal_gifta` WHERE `id` = '".$post['gift']."'"));
$g2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$post['otkogo']."'"));
echo '<img src = "/style/gift/'.$g['file'].'" width = "80"> От: '.$g2['login'].'</a></br>';
}

if($k_post > 10){
str('/gifts/?my&',$k_page,$page); // Вывод страниц
}else{
}

}

if(isset($_GET['gift'])){

$id = abs(intval($_GET['id']));
$gift = abs(intval($_GET['gift']));
$gifts = mysql_fetch_assoc(mysql_query("SELECT * FROM `asadal_gifta` WHERE `id` = '".$gift."'"));
$profile = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$id."'"));

if(!$profile){
echo 'Такой игрок не существует';
require_once ('system/footer.php');
exit();
}

if(!$gifts){
echo 'Такого подарка не существует';
require_once ('system/footer.php');
exit();
}

if($user['id'] == $id){
    echo'<div class="feedback">';
echo 'Вы не можете сделать подарок самому себе.';
echo'</div>';
require_once ('system/footer.php');
exit();
}

if($user['ruby'] < $cenaGift){
echo 'Стоимость это подарка: '.$cenaGift.' рубинов!';
require_once ('system/footer.php');
exit();
}

mysql_query("UPDATE `users` SET `ruby` = '".text($user['ruby']-$cenaGift)."' WHERE `id` = '".$user[id]."' LIMIT 1");
mysql_query("INSERT INTO `asadal_gift` SET `gift` = '".text($gift)."',`komy` = '".text($id)."',`otkogo` = '".$user['id']."'");
echo'<div class="feedback">';
echo 'Вы успешно сделали подарок игроку: '.$profile['login'].'';
echo'</div>';
require_once ('system/footer.php');
exit();
}

if(isset($_GET['do'])){

$id = abs(intval($_GET['id']));
$profile = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$id."'"));

if(!$profile){
echo 'Такой игрок не существует';
require_once ('system/footer.php');
exit();
}
echo'<div class="feedback">';
echo '<div class=block>Сделать подарок игроку:<b> '.$profile['login'].'</b></br>Выберите подарок:</div>';
echo'</div>';


$gift = mysql_query("SELECT * FROM `asadal_gifta` ORDER BY `id` DESC");
while($b = mysql_fetch_assoc($gift))
{
echo'<div class="bordered">';
echo '<img src = "/style/gift/'.$b['file'].' " width = "80"> Цена: 250 рубинов [<a href = "/gifts/?gift='.$b['id'].'&id='.$id.'">подарить</a>]</a></br>';
echo'</div>';
}

}

require_once ('system/footer.php');

?>
