<?php
require_once ('system/func.php');
require_once ('system/header.php');
auth(); // Закроем от не авторизованных

echo'<a class="btni" href="/freinds/?my" class="btn"><img src="/img/friends_all.png" alt="*" width="20" height="20"/> Все друзья</a>';
echo'<a class="btni" href="/freinds/?online" class="btn"><img src="/img/friends_add.png" alt="*" width="20" height="20"/> Друзья онлайн</a>';
echo'<a class="btni" href="/freinds/?my_z" class="btn"><img src="/img/friends_add.png" alt="*" width="20" height="20"/> Заявки в друзья</a><br>';

if(isset($_GET['del'])){

$id = abs(intval($_GET['del']));
$fr = mysql_fetch_assoc(mysql_query("
SELECT * FROM `asadal_friends` WHERE `id` = '".$id."' and `activate` = '1' and `ykogo` = '".$user['id']."'  or `id` = '".$id."' and `activate` = '1' and `kto` = '".$user['id']."'  " ));

if($fr == 0){
                echo'<div class="feedback">';
echo 'Такой заявки не существует';
echo'</div>';
require_once ('system/footer.php');
exit();
}

mysql_query("DELETE FROM `asadal_friends`  WHERE `id` = '".$fr['id']."'");
            echo'<div class="feedback">';
echo 'Вы успешно удалили игрока из списка друзей.';
echo'</div>';
require_once ('system/footer.php');
exit();

}

if(isset($_GET['online'])){

$q2 = mysql_query("SELECT * FROM `asadal_friends` WHERE `kto` = '".$user['id']."' and `activate` = '1' or `ykogo` = '".$user['id']."' and `activate` = '1' ORDER BY `id` DESC");

while($post2 = mysql_fetch_assoc($q2)) {
$us2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$post2['kto']."' or `id` = '".$post2['ykogo']."' "));
mysql_query("UPDATE `asadal_friends` SET `online` = '".text($us2['online'])."' WHERE `kto` = '".text($post2['kto'])."' or `ykogo` = '".text($post2['ykogo'])."'");
}

$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `asadal_friends` WHERE `kto` = '".$user['id']."' and `activate` = '1'  and `online` > '".(time()-300)."' or `ykogo` = '".$user['id']."' and `activate` = '1'  and `online` > '".(time()-300)."'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `asadal_friends` WHERE  `online` > '".(time()-300)."' and `kto` = '".$user['id']."' and `activate` = '1' or  `online` > '".(time()-300)."' and `ykogo` = '".$user['id']."' and `activate` = '1' ORDER BY `online` DESC LIMIT $start, $set[p_str]");
echo "<div class='block'>";
if($k_post == 0) echo "Друзей в онлайне нет";
while($post = mysql_fetch_assoc($q)) {

if($post['kto'] == $user['id']){ $abc = $post['ykogo']; } else { $abc = $post['kto']; }

$us = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$abc."'"));
echo'<div class="bordered">';
$ank = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$post['in']."' LIMIT 1"));
echo icons_user($us['id']);
echo '<a href = "/profile/'.$us['id'].'">'.$us['login'].'</a> <a href = "/mail/'.$us['id'].'"><img src="/img/mail_white.png" alt="*" width="20" height="20"/></a> </br>Посл.посещение: '.vremja($us['online']).'</br>Доход: <img src="/img/money_36.png" alt="o" width="20" height="20"/> '.n_f(($us['money_sek']*$us['x2'])*$us['g_x2']).' в сек <a href = "/freinds/?del='.$post['id'].'">[X]</a></br>';
echo'</div>';

}

if($k_post > 10){
str('/freinds/?online&',$k_page,$page); // Вывод страниц
}else{
}

}

if(isset($_GET['my'])){

$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `asadal_friends` WHERE `kto` = '".$user['id']."' and `activate` = '1' or `ykogo` = '".$user['id']."' and `activate` = '1'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `asadal_friends` WHERE `kto` = '".$user['id']."' and `activate` = '1' or `ykogo` = '".$user['id']."' and `activate` = '1' ORDER BY `id` DESC LIMIT $start, $set[p_str]");
echo "<div class='block'>";
if($k_post == 0) echo "Друзей нет";
while($post = mysql_fetch_assoc($q)) {

if($post['kto'] == $user['id']){ $abc = $post['ykogo']; } else { $abc = $post['kto']; }

$us = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$abc."'"));
echo'<div class="bordered">';
$ank = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$post['in']."' LIMIT 1"));
echo icons_user($us['id']);
echo '<a href = "/profile/'.$us['id'].'">'.$us['login'].'</a> <a href = "/mail/'.$us['id'].'"><img src="/img/mail_white.png" alt="*" width="20" height="20"/></a> </br>Доход: <img src="/img/money_36.png" alt="o" width="20" height="20"/> '.n_f(($us['money_sek']*$us['x2'])*$us['g_x2']).' в сек <a href = "/freinds/?del='.$post['id'].'">[X]</a></br>';
echo'</div>';

}

if($k_post > 10){
str('/freinds/?my&',$k_page,$page); // Вывод страниц
}else{
}

}

if(isset($_GET['pr'])){

$id = abs(intval($_GET['pr']));
$fr = mysql_fetch_assoc(mysql_query("SELECT * FROM `asadal_friends` WHERE `id` = '".$id."' and `activate` = '0' and `ykogo` = '".$user['id']."'"));

if($fr == 0){
echo 'Такой заявки не существует';
require_once ('system/footer.php');
exit();
}

mysql_query("UPDATE `asadal_friends` SET `activate` = '1' WHERE `id` = '".$id."'");
echo 'Вы успешно приняли заявку';
require_once ('system/footer.php');
exit();




mysql_query("UPDATE `asadal_friends` SET `activate` = '1' WHERE `id` = '".$id."'");
        echo'<div class="feedback">';
echo 'Вы успешно приняли заявку';
echo'</div>';
require_once ('system/footer.php');
exit();

}

if(isset($_GET['del_f'])){

$id = abs(intval($_GET['del_f']));
$fr = mysql_fetch_assoc(mysql_query("SELECT * FROM `asadal_friends` WHERE `id` = '".$id."' and `ykogo` = '".$user['id']."' or `id` = '".$id."' and `kto` = '".$user['id']."' "));

if($fr == 0){
echo 'Такой заявки не существует';
require_once ('system/footer.php');
exit();
}

mysql_query("DELETE FROM `asadal_friends` WHERE `id` = '".$id."'");
echo 'Вы успешно отклонили заявку';
require_once ('system/footer.php');
exit();

}

if(isset($_GET['my_z'])){

$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `asadal_friends` WHERE `ykogo` = '".$user['id']."' and `activate` = '0' "),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `asadal_friends` WHERE `ykogo` = '".$user['id']."' and `activate` = '0' ORDER BY `id` DESC LIMIT $start, $set[p_str]");
echo "<div class='block'>";
if($k_post == 0) echo "Заявок нет";
while($post = mysql_fetch_assoc($q)) {
if($post['kto'] == $user['id']){ $abc = $post['ykogo']; } else { $abc = $post['kto']; }

$us = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$abc."'"));
echo'<div class="bordered">';
$ank = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$post['in']."' LIMIT 1"));
echo icons_user($us['id']);
echo ''.$us['login'].' <a href = "/freinds/?pr='.$post['id'].'">[принять заявку]</a> | <a href = "/freinds/?del_f='.$post['id'].'">[отклонить заявку]</a></br>';
echo'</div>';
}

if($k_post > 10){
str('/freinds/?my_z&',$k_page,$page); // Вывод страниц
}else{
}

}

if(isset($_GET['add'])){

$id = abs(intval($_GET['id']));
$profile = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$id."'"));

if(!$profile){
    echo'<div class="feedback">';
echo 'Такой игрок не существует';
echo'</div>';
require_once ('system/footer.php');
exit();
}

if($user['id'] == $id){
        echo'<div class="feedback">';
echo 'Вы не можете добавить себя в друзья';
echo'</div>';
require_once ('system/footer.php');
exit();
}

$asadal_friends = mysql_fetch_assoc(mysql_query("SELECT * FROM `asadal_friends` WHERE `kto` = '".$user['id']."' and `ykogo` = '".$id."' and `activate` = '0'"));

if($asadal_friends == 0) {
mysql_query("INSERT INTO `asadal_friends` SET `kto` = '".text($user['id'])."' , `ykogo` = '".text($id)."'");
        echo'<div class="feedback">';
echo 'Заявка отправлена игроку: '.$profile['login'].'';
echo'</div>';
require_once ('system/footer.php');
exit();
}
else
{
mysql_query("DELETE FROM `asadal_friends`  WHERE `id` = '".text($asadal_friends['id'])."'");
        echo'<div class="feedback">';
echo 'Вы удалили из списка друзей игрока: '.$profile['login'].'';
echo'</div>';
require_once ('system/footer.php');
exit();
}
}

require_once ('system/footer.php');

?>