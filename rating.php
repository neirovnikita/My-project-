<style>
.count {
float: right;
padding: 1px 9px;
}
</style>

<?php
require_once ('system/func.php');
$title = 'Рейтинг игроков';
require_once ('system/header.php');
auth();


if(isset($_GET['biznes'])){
    $pos = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `gold` > {$user['gold']}" ), 0, 0); 
    echo "<div class='feedback'>";
echo '<center>Ваше место в рейтинге: <font color="green"><b>'.$pos.'</b></font></center></div>';
$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `users`"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `users` ORDER BY (gold+0) DESC LIMIT ".$start.", ".$set['p_str']."");
while($post = mysql_fetch_assoc($q)) {
$i++;
echo "<div class='content'></div><div class='bordered'>";
echo "".($i+$start).".";

echo icons_user($post['id'])." <a href='/profile/".$post['id']."/'>".$post['login']."</a>";
echo "<div class='count'><img src='/img/money_36.png' alt='*' width='20' height='20'/> ".n_f($post['gold'])."</div></div>";
}
if($k_post > 10){
echo "<div class='block'>";
str('rating?biznes&',$k_page,$page); // Вывод страниц
echo"</div>";
echo "<li><a class='btnl mt4' href='/corps/' class='btn'>Рейтинг корпораций</li></a>";
echo "<li><a class='btnl mt4' href='/rating?angels' class='btn'>Рейтинг Ангелов</li></a>";
echo "<li><a class='btnl mt4' href='/online.php?admins' class='btn'>Администрация сайта</li></a>";
}else{
}
require_once ('system/footer.php');
exit();
}

if(isset($_GET['angels'])){
    $pos4 = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `angels` >= {$user['angels']}" ), 0, 0); 
    echo "<div class='feedback'>";
echo '<center>Ваше место в рейтинге: <font color="green"><b>'.$pos4.'</b></font></center></div>';
$set['p_str'] = 10;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `users`"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `users` ORDER BY (angels+0) DESC LIMIT ".$start.", ".$set['p_str']."");
while($post = mysql_fetch_assoc($q)) {
$i++;
echo "<div class='content'></div><div class='bordered'>";
echo "".($i+$start).".";
echo icons_user($post['id'])." <a href='/profile/".$post['id']."/'>".$post['login']."</a>, ";
echo "<div class='count'><img src='/img/angel48.png' alt='*' width='20' height='20'/> ".n_f($post['angels'])."</div></div>";
}
if($k_post > 10){
echo "<div class='block'>";
str('rating?angels&',$k_page,$page); // Вывод страниц
echo"</div>";
}else{
}
echo "<li><a class='btnl mt4' href='/corps/' class='btn'>Рейтинг корпораций</li></a>";
echo "<li><a class='btnl mt4' href='/rating?biznes' class='btn'>По самому прибыльному бизнесу</li></a>";
echo "<li><a class='btnl mt4' href='/online.php?admins' class='btn'>Администрация сайта</li></a>";
echo "</div>";
require_once ('system/footer.php');
exit();
}

?>