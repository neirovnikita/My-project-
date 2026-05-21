<?php
$ruby =  mt_rand(10,50);
if($user['bonus_time'] < time()){
$db->query("UPDATE `users` SET `ruby` = '".($user['ruby'] + $ruby)."', `bonus_time` = '".(time() + 86000)."' WHERE `id` = '".$user['id']."'");
$_SESSION['msg'] = 'Ежедневный подарок: '.$ruby.' рубинов';
header('Location: ?');
exit();
}
?>