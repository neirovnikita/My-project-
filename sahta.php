<?
require_once ('system/func.php');
auth();
$title = 'Шахта';
require_once ('system/header.php');
 $sahta = mysql_fetch_assoc(mysql_query("SELECT * FROM `sahta` WHERE `id_user` = '$user[id]' LIMIT 1"));
function time_count($timediff , $as = 0 , $ass = 0, $asss = 0, $assss = 0 , $text_view = 0, $text ='')	{
$oneMinute=60;
$oneHour=60*60;
$oneDay=60*60*24;
$dayfield=floor($timediff/$oneDay);
$hourfield=floor(($timediff-$dayfield*$oneDay)/$oneHour);
$minutefield=floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour)/$oneMinute);
$secondfield=floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour-$minutefield*$oneMinute));
		
		if ($as == true && $dayfield != 0)
		{
			$d="$dayfield д.";
		}else{
			$d= NULL;	
		}
		if ($ass == true  && $hourfield != 0)
		{
			$h=" $hourfield ч. ";
		}else{
			$h= NULL;
		}
		if ($asss == true && $minutefield != 0)
		{
			$m=" $minutefield м. ";
		}else{
	        $m= NULL;
		}
		if ($assss == true && $secondfield != 0)
		{
			$s="	 ".$secondfield." с.";
		}else{
			$s= NULL;	
		}
		
       if ($d <0 || $h < 0 || $m < 0 || $s < 0){
		   if ($text_view == true ){
		   if ($text == NULL)
			$view= 'Время истекло';
		else
			$view= $text;	
		   }else{
			   $view = NULL;
		   }
	   }else{
	 $view = $d . $h . $m . $s;	 
	   }
return  $view;
}
if(isset($_GET['goaway'])){
if ($sahta['time'] == 0)$err= 'Не доступно';
if ($sahta['time'] > time())$err= 'Не доступно';
if(!$err){
$ruby = rand($sahta['rand1'],$sahta['rand2']);
$times_doh = (time()+300);
mysql_query("update `sahta` set `time`= '0' where (`id_user` = '".$myID."')");
mysql_query("update `users` set `ruby` = `ruby`+$ruby where (`id` = '".$myID."')");
	$_SESSION['msg'] = '<font color="green">Ваш шахтер, добыл для вас: '.$ruby.' Рубиков!</font>';
header('Location: ?');
}else{
	$_SESSION['msg'] = $err;
header('Location: ?');	
}
}
if(isset($_GET['go'])){
if ($sahta['otdih'] > time())$err= 'Не доступно';
if(!$err){
$times_doh = (time()+60);
$times_otd = (time()+120);
mysql_query("update `sahta` set `time`= '$times_doh', `otdih` = '$times_otd' where (`id_user` = '".$myID."')");
	$_SESSION['msg'] = '<font color="green">Шахтер отправлен, за рубинами!</font>';
header('Location: ?');
}else{
	$_SESSION['msg'] = $err;
header('Location: ?');	
}	
}
if ($sahta['level'] == 1){
	$level = 2;
	$rand1 = 50;
	$rand2 = 100;
	$cena = 300;
}
if ($sahta['level'] == 2){
	$level = 3;
	$rand1 = 100;
	$rand2 = 150;
	$cena = 500;
}
if ($sahta['level'] == 3){
	$level = 4;
	$rand1 = 150;
	$rand2 = 200;
	$cena = 750;
}
if ($sahta['level'] == 4){
	$level = 5;
	$rand1 = 200;
	$rand2 = 300;
	$cena = 1000;
}
if(isset($_GET['up'])){
if ($sahta['level'] >= 5){
		$_SESSION['msg'] = '<font color="green">Максимальный Уровень</font>';
header('Location: ?');	
}
echo"<div class ='bordered'>";
echo 'Вы действительно хотите поднять уровень?</br>';
echo 'Уровень вашей шахты будет '.$level.'</br>';
echo 'Доход: от '.$rand1.' до '.$rand2.'</br>';	
echo 'Цена за '.$level.' уровень:  '.$cena.' рубинов</br>';
echo '<center><a class="btni" href="?up_ok">Поднять</a> <a class="btni" href="?">Отмена</a></center>';
echo '</div>';
}
if(isset($_GET['up_ok'])){
	if ($sahta['level'] >= 5)$err = 'Максимальный Уровень';
		if($cena > $user['ruby'])$err = 'У вас не достаточно Рубинов';
if(!$err){
mysql_query("update `sahta` set `level`= '$level', `rand1` = '$rand1', `rand2` = '$rand2' where (`id_user` = '".$myID."')");
mysql_query("update `users` set `ruby` = `ruby`-$cena where (`id` = '".$myID."')");
		$_SESSION['msg'] = '<font color="green">Уровень шахты повышен</font>';
header('Location: ?');
}else{
$_SESSION['msg'] = $err;
header('Location: ?');	
}
}
echo"<div class ='bordered'>";
echo '<center>Уровень: '.$sahta['level'].'';  echo ' Доход: от '.$sahta['rand1'].' до '.$sahta['rand2'].' Рубинов</center>'; 
echo '<center><img src="sahta.png" width="200"></center>';
echo '<center>';
if ($sahta['time'] > time() || $sahta['time'] == 0){
	if ($sahta['time'] > time()){
		echo 'До сбора '.time_count($sahta['time']-time(),1,1,1,1).'</br>';
	}else
	if ($sahta['otdih'] > time()){
		echo 'Шахтер отдохнет через '.time_count($sahta['otdih']-time(),1,1,1,1).'</br>';
	}else{
	echo '<a class="btni" href="?go">Отправить Шахтера</a> ';
	}
}else{
		echo '<a class="btni" href="?goaway">Собрать Доход</a> ';
}

if ($sahta['level'] < 5)echo '<a class="btni" href="?up">Поднять Уровень</a> ';
echo '</center>';
echo '</div>';
require_once ('system/footer.php');
?>