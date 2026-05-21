<style>
.count {
float: right;
padding: 1px 9px;
}
</style>

<?php
require_once ('system/func.php');

if(isset($_GET['new_corp']) and $user['id_clan'] == 0){
if(isset($_GET['new_corp_post'])){
if(isset($_POST['name'])){
$name = text($_POST['name']);
if(strlen($name) < 4 or strlen($name) > 40)$err = 'Длина названия должна быть в пределах 4 - 40 символов';
if($user['ruby'] < 1000)$err = "Не хватает <img src='/img/ruby.png' alt='*' width='20' height='20'/> ".(1000-$user['ruby'])." рубинов";
if(!$err){
if(mysql_query("INSERT INTO `clans` SET `name` = '".$name."', `count` = '1', `date` = '".time()."'")){
$id = mysql_insert_id();
mysql_query("UPDATE `users` SET `clan_rang` = '5', `id_clan` = '".$id."', `ruby` = '".text($user['ruby']-1000)."' WHERE `id` = '".$myID."'");
header('Location: /corp/');
exit();
}
}else{
$_SESSION['msg'] = $err;
header('Location: ?new_clan');
exit();
}
}else{
$_SESSION['msg'] = 'Заполните поле';
header('Location: ?new_corp');
exit();
}
}
$title = 'Создание корпорации';
require_once ('system/header.php');
echo "<div class='content'></div><center>";
echo "<form method='post' action='?new_corp&new_corp_post'>";
echo "Название корпорации:<br><input type='text' name='name'>";
echo "<input type='submit' class='btn' value='Создать'><br><span class='info'>Стоимость корпорации: <img src='/img/ruby.png' alt='*' width='20' height='20'/> 1000 рубинов</span>
</form>";
echo "</div></conter>";
require_once ('system/footer.php');
exit();
}
$title = 'Рейтинг корпораций';
require_once ('system/header.php');
auth(); // Закроем от гостей
 echo "<center><img src='/img/angel48.png' alt='*' width='23' height='23'/> Рейтинг по корпоративным бизнес-ангелам</center>";
$set['p_str'] = 10;

$k_post = $db->query("SELECT COUNT(*) FROM `clans`")->num_rows;
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$ac1 = mysql_fetch_array(mysql_query("SELECT SUM(angels) FROM `users` WHERE `id_clan` = '".$profile['id_clan']."'"));
$q = $db->query("SELECT * FROM `clans` ORDER BY (rate_angels+0) DESC LIMIT ".$start.", ".$set['p_str']."");
while($post = $q->fetch_assoc()) {
$i++;
if($post[side] == 'good')$color = 'green';
else $color = '#FFBB19';
if($i+$start <= 3){
echo "<div class='content'></div><div class='bordered' style='border:1px solid ".$color.";'>";
echo "".($i+$start)." Место: <img src='/img/corp.png' alt='*' width='20' height='20'/> <a href='/corp/".$post['id']."/'>".$post['name']."</a>, <div class='count'><img src='/img/angel48.png' alt='*' width='20' height='20'/> ".n_f($post['rate_angels']).".";
echo "</div></div>";
}else{
echo "<div class='content'></div><div class='bordered'>";
echo "".($i+$start).". <img src='/img/corp.png' alt='*' width='20' height='20'/> <a href='/corp/".$post['id']."/'>".$post['name']."</a>, <div class='count'><img src='/img/angel48.png' alt='*' width='20' height='20'/> ".n_f($post['rate_angels']).".";
echo "</div></div>";
}
}

if($k_post > 10){
echo "<div class='block'>";
str('?',$k_page,$page); // Вывод страниц
echo"</div>";
}else{
}
echo "<li><a class='btnl mt4' href='/rating?angels' class='btn'>Рейтинг ангелов</li></a>";
echo "<li><a class='btnl mt4' href='/rating?biznes' class='btn'>По самому прибыльному бизнесу</li></a>";
echo "<li><a class='btnl mt4' href='/online.php?admins' class='btn'>Администрация сайта</li></a>";
if($user['id_clan'] == 0)echo'<a class="btnl mt4" href="?new_corp" class="btn"><img src="/img/corp2.png" alt="*" width="20" height="20"/> Создать корпорацию</a>';
echo "<div class='minor'>У членов корпорации прибыль увеличивают бизнес-ангелы всех участников.</div>";
require_once ('system/footer.php');
?>