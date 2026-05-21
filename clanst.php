<?php
require_once ('system/func.php');
$title = 'Статуя Корпорации';
require_once ('system/header.php');
auth();
echo'<div class="lent mlra w80">';
$clan = $db->query("SELECT * FROM `clans` WHERE `id` = '".$user['id_clan']."'")->fetch_assoc();
    function cost($i) {
        
        switch($i) {
          case 0:
           $cost = 1000;
           break;
        
          case 1:
           $cost = 1500;
           break;
        
          case 2:
           $cost = 2000;
           break;
          
          case 3:
           $cost = 2500;
           break;

          case 4:
           $cost = 3000;
           break;   
           
          case 5:
           $cost = 3500;
           break;        

          case 6:
           $cost = 4000;
           break;   

          case 7:
           $cost = 4500;
           break;

          case 8:
           $cost = 5000;
           break;   
           
          case 9:
           $cost = 5500;
           break;   

          case 10:
           $cost = 6000;
           break;   

          case 11:
           $cost = 6500;
           break;   

          case 12:
           $cost = 7000;
           break;   

          case 13:
           $cost = 7500;
           break;   

          case 14:
           $cost = 8000;
           break;   

          case 15:
           $cost = 8500;
           break;      
        }
        
        
global $user;
return $cost;


    }
if($user['id_clan'] == $clan['id']){
if(!$clan && $user['clan_rang'] < 3) {
header('location:/main');
 }
if($_GET['stat'] == train) {
    if($clan['stat_level'] != 15) {
    if($clan['sclad_ruby'] < cost($clan['stat_level'])) {

$_SESSION['msg'] = 'Недостаточно рубинов в складе клана!';
header('location:?');
}
else {
$db->query("update `clans` set `stat` = `stat` + 2,`stat_level` = `stat_level` + 1, `sclad_ruby` = `sclad_ruby` - '".cost($clan['stat_level'])."' where `id` = '".$user['id_clan']."'");
header('location:?');
}
}
}
}
$pms = ($money_updater[0]*$p);
$cst = ($clan[stat]);
$pcms = (($pms*$cst)/100);
if($user['id_clan'] == $clan['id'] and $user['clan_rang'] >= 4){
echo '<div class="feedback">В складе '.$clan[sclad_ruby].' <img width="24" height="24" src="/img/ruby.png"> <br>Статуя клана '.$clan[stat_level].' ур. из 15<br> + '.$clan[stat].' % к доходу. <br> ';

if($user['id_clan'] == $clan['id']){
echo '<a href="?stat=train" class="btni">Улучшить '.cost($clan['stat_level']).' <img width="20" height="20" src="/img/ruby.png"></a>';
}
}else{
header('Location: /corp/');
exit;
}
echo "<br><a href='/corp/' class='btni'> Вернуться</a>";
echo "</div></div>";

require_once ('system/footer.php');
?>