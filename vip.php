<?php
require_once ('system/func.php');
auth(); // Закроем от не авторизованных
$title = 'VIP статус';
require_once ('system/header.php');
echo'<div class="lent mlra w80">
<div class="bl-ttl"><div class="te"><div class="ttl">
		'.$title.'
		</div></div></div>';
if(isset($_POST['kup'])){
$day = num($_POST['day']);
if($day == '0')$err='Введите число';
if(!preg_match("#^([0-9])+$#ui", $day))$err='Введите число';
if($user['vip_time']!='0')$err='У вас уже есть VIP статус';
$cena=$user['ruby']-50*$day;
$cp = 50*$day;
if($user['ruby']< $cp)$err='Недостаточно рубинов';
$time = time()+ 86400*$day;
if(!$err){
$db->query("UPDATE `users` SET `ruby`= '$cena', `vip_time`='$time', `vip`='1' WHERE `id`='$user[id]'");
$_SESSION['msg'] = 'VIP куплен';
header('Location: /main');
exit();
}else{
header('Location: /vip.php');
$_SESSION['msg'] = $err;
exit();
}
}
echo "<div class='block'> 
Особенности VIP :
<b><li>Бонус 50% к заработанному серебру!</li><br>
<li>В два раза увеличена ловушка астралов</li><br>
<li>Продажа / разбор астралов в один клик</li></b> 
<br><br>
<br><ul class='hint'><li>Цена : 1 день - 50 золотых</li></ul>";
if($user['vip_time']!='0')
{
echo '<font color="#933">У вас уже есть VIP статус! <br> Окончание: '.vremja($user['vip_time']).'</font></div></div>';
require_once ('system/footer.php');
exit();
}
echo '<center> <form action="" method="POST">
На сколько дней желаете купить?<br>
<input type="text" name ="day"> <br>
<input type="submit" class="btn" name="kup" value="Купить"> 
</form></div></div>';
require_once ('system/footer.php');
?>