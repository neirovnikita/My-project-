<?php
require_once ('system/func.php');
auth(); // Закроем от не авторизованных
switch(intval($_GET['mod'])){
default:
break;
case 'id_f':
$title = 'Форум';
require_once ('system/header.php');
$k_post = $db->query("SELECT * FROM `cforum_sub` WHERE `id_clan` = '".intval($_GET['id'])."'")->num_rows;
$q = $db->query("SELECT * FROM `cforum_sub` WHERE `id_clan` = '".intval($_GET['id'])."' and `gb` = '1' ORDER BY `id` ASC");

if($k_post == 0){echo "<div class='feedback'>Нет разделов...</div>";
require_once ('system/footer.php');
exit();
}
while ($post = $q->fetch_assoc()){
echo "<a class='btnl mt4' href='/corp/forum/".$post['id']."' class='btn'>".$post['name']."</a>";
}
echo "</div>";
echo "<a class='btnl mt4' href='/corp/".intval($_GET['id'])."/' class='btn'> Вернуться назад</a>";
break;
}
require_once ('system/footer.php');
?>