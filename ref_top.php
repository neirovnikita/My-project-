<style>
.count {
float: right;
padding: 1px 9px;
}
</style>

<?php
require_once ('system/func.php');
$title = 'Реферальная ссылка';
require_once ('system/header.php');
auth(); // Закроем от не авторизованных

echo'<div class="feedback">';
echo'<center>Рейтинг Рефералов <br>Приглашайте своих друзей, знакомых на <b>RAYB.MOBI</b> по вашей уникальной реферальной ссылке.  <br> <div class="minor">>><a href="/ref.php"><b>Узнать свою реф.ссылку</b></a><<</div></center>';
echo'</div>';
$sql = mysql_query("SELECT users.*, COUNT(asadal_refferal.id) AS cnt FROM users JOIN asadal_refferal ON (users.id = asadal_refferal.ykogo) GROUP BY users.id order by `cnt` desc limit 12");

while($row = mysql_fetch_assoc($sql)) {
echo "<div class='content'></div><div class='bordered'>";

echo icons_user($row['id'])." <a href='/profile/".$row['id']."/'>".$row['login']."</a>";
echo "<div class='count'>".$row['cnt']." <img src='/img/avatars/avatar.png' alt='*' width='20' height='20'/></div></div>";
}

require_once ('system/footer.php');


?>