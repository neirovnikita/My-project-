<style>
.count {
float: right;
padding: 1px 9px;
}
</style>

<?php
require_once ('system/func.php');
$title = 'Кто онлайн';
require_once ('system/header.php');
auth(); // Закроем от гостей

if(isset($_GET['admins'])){

$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `access` > '1'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `users` WHERE `access` > '1' ORDER BY `access` DESC LIMIT ".$start.", ".$set['p_str']."");
echo "<div class='bordered'>Администрация сайта:</br>";
while($post = mysql_fetch_assoc($q)) { 
if($post['access'] == 3){ 
echo "<span class='item-2'> </span>"; 
echo '<font color="#2A8D9C">[Sys.Bot]</font>';
}else
if($post['access'] == 2){ 
echo "<span class='item-2'> </span>"; 
echo '<font color="32CD3200FF7F">[АДМ]</font>';
}else{ 
echo "<span class='white'></span>"; 
}
$image = icons_user($post['id']);
$money_sek = n_f($money_updater[0]*$post['x2']*$post['g_x2']);
echo $image." <a href='/profile/".$post['id']."'>".$post['login']."</a>   <a href='/mail/".$post['id']."'><img src='/img/mail_white.png' width='22' height='22' alt=''/></a><br>";
}
if($k_post > 10){ 
str('admins/?',$k_page,$page); // Вывод страниц
}else{
}
echo '</div>';
echo'<a class="btnl mt4" href="?md" class="btn">Модераторы</a>';
require_once ('system/footer.php');
exit();
}

if(isset($_GET['md'])){

$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `access` = '1'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `users` WHERE `access` = '1' ORDER BY `access` DESC LIMIT ".$start.", ".$set['p_str']."");
echo "<div class='bordered'>Модераторы сайта:</br>";
while($post = mysql_fetch_assoc($q)) { 
if($post['access'] == 1){ 
echo "<span class='item-1'></span>"; 
echo '<font color="BC8F8F">[МД]</font>';
}else{ 
echo "<span class='white'></span>"; 
}
$image = icons_user($post['id']);
$money_sek = n_f($money_updater[0]*$post['x2']*$post['g_x2']);
echo $image." <a href='/profile/".$post['id']."'>".$post['login']."</a>   <a href='/mail/".$post['id']."'><img src='/img/mail_white.png' width='22' height='22' alt=''/></a><br>";
}
if($k_post > 10){ 
str('admins/?',$k_page,$page); // Вывод страниц
}else{
}
echo '</div>';
echo'<a class="btnl mt4" href="?admins" class="btn">Администрация</a>';
require_once ('system/footer.php');
exit();
}

if(isset($_GET['search'])){
if(isset($_REQUEST['search_nick'])){
$login = text($_POST['login']);
if(empty($login)){
$_SESSION['msg'] = 'Введите Ник';
header("Location: ?search");
exit();
}else{
$sql = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `login` = '".$login."' LIMIT 1"));
header("Location: /profile/".$sql['id']."");
exit();
}
}
echo "<div class='bordered center'>";
echo '<form action="?search" method="post">';
echo 'Ник игрока:<br><input class="center" type="text" name="login" maxlength="50" value="" /><br/>';
echo '<input type="submit" name="search_nick" class="btni" value="Найти игрока">';

echo '</form>';
echo "</div>";
require_once ('system/footer.php');
exit();
}
echo "<div class='feedback'><center>Игроки Онлайн</center>";
echo "</div>";
$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `online` > '".(time()-600)."'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `users` WHERE `online` > '".(time()-600)."' ORDER BY `gold` DESC LIMIT ".$start.", ".$set['p_str']."");
while($post = mysql_fetch_assoc($q)) {
    echo "<div class='bordered'>";
    
    
if($ank['ava'] == NULL){
echo ''.$ava.'<img src="/img/noavatar.png" alt="$" width = "20" ></a> ';
}
else
{
echo ''.$ava.'<img src="/img/avatar/'.text($ank['ava']).'" alt="$" width = "20" ></a> ';
}    
$image = icons_user($post['id']);
echo $image." <a href='/profile/".$post['id']."'>".$post['login']."</a><div class='count'>";echo '<img src="/img/angel48.png" alt="$" width="16" height="16"> <span><font color="green">'.n_f($post['angels']).'</font></div></span><br>';
if($post['status'] == NULL){
echo '<font color="green">'.$status.'</font></a>';
}
else
{
echo '<font color="green">'.text_msg($post['status']).' '.$status.'</font></a>';
}

    





echo '</div>';
}




if($k_post > 10){ 
str('?',$k_page,$page); // Вывод страниц
}else{
}
echo "</div>";
echo'<a class="btnl mt4" href="?search" class="btn"> Поиск</a>';
require_once ('system/footer.php');


?>
