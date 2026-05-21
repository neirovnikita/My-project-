<?php
require_once ('system/func.php');
$title = 'Реферальная ссылка';
require_once ('system/header.php');
auth(); // Закроем от не авторизованных

echo'<div class="feedback">';

echo 'Реферальная ссылка';
echo'</div>';
$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `asadal_refferal` WHERE `ykogo` = '".$user['id']."'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `asadal_refferal` WHERE `ykogo` = '".$user['id']."' ORDER BY `id` DESC LIMIT ".$start.", ".$set[p_str]."");
echo "<div class='block'>";
echo '<b>Ваша реф.ссылка:</b> <input type="text" size = 23 value = "'.$_SERVER['SERVER_NAME'].'/registration?id='.$user['id'].'" /></br> ';
echo 'За каждого приведенного пользователя <img width="20" height="20" alt="" src="/img/ruby.png" title=""/> 50 рубинов<br>';
echo 'При накрутке - <b>рубины не зачисляються</b><br>';
if($k_post == 0) echo "Рефералов нет";

while($post = mysql_fetch_assoc($q)) {

$profile3 = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$post['kto']."'"));
    echo'<div class="bordered">';
echo 'Игрок: '.text($profile3['login']).' </br>Дата регистрации:' .vremja($post['time']).'</br>';
echo'</div>';
}

if($k_post > 10){
str('?',$k_page,$page); // Вывод страниц
}else{
}


echo "</div>";
require_once ('system/footer.php');


?>