
<?php 
require_once ('system/func.php'); 
auth(); // Закроем от не авторизованных 
$title = 'Поддержка';
require_once ('system/header.php'); 
switch ($_GET['mode']){
default;
if($_GET['create']=="true"){
    
    $_title= $_POST['title'];

    $_sms= $_POST['sms'];

    if(empty($_title))$err="Пустой заголовок!";

    if(empty($_sms))$err="Пустой текст обращения!";

    if(strlen($_title)<5 OR strlen($_title)>25)$err="Длина заголовка от 5 до 25 символов";

    if(strlen($_sms)<50 OR strlen($_sms)>8048)$err="Длина сообщения от 50 до 8048 символов";

    if($err)
    {

        
                $_SESSION['msg'] = $err;
        header('location:/support.php');

        

    }elseif(!$err){

            mysql_query("INSERT INTO `ticket` SET `user`='".$user['id']."',`text`='$_sms',`title`='$_title',`status`='new'")OR DIE(mysql_error());

            

        $_SESSION['msg'] = 'Заявка успешно отправлена!';
        header('location:/support.php');

        

        



    }

    



}

?>
<div class='block center'>
<form action="?create=true" method="post">
 <input type="text" name="title"  placeholder="Заголовок"><br/>
<textarea name="sms" placeholder="Опишите Вашу проблему."/></textarea>

Тип вопроса:<br /><select name="text"><option value='1'>Обычный</option><option value='2'>Сотрудничество</option><option value='3'>Оплата</option>

  <input type="submit" class="btn" value="Отправить">
</form>
</div>
<?

$my_requests=mysql_query("SELECT * FROM `ticket` WHERE `user`='".$user['id']."'");
echo "<div class='block2'>";
if(mysql_num_rows($my_requests)==0)
{
?>
<h1>Нет заявок...</h1>
<?
}elseif(mysql_num_rows($my_requests)>0)
{
    while ($req=mysql_fetch_array($my_requests))
    {
        
        switch ($req[status])
        {
            case 'new';

            $status="<font color='yellow'>Ожидание ответа</font>";

            break;

            case 'read';

            $status="<font color='green'>Есть ответ</font>";

            break;

            case 'close';

            $status="<font color='red'>Закрыт</font>";

            break;

            case 'user';

            $status="<font color='red'>Ответили вы</font>";

            break;

        }

?>      
<li><a href='?mode=viev&id=<?=$req['id'];?>'><img src='/images/icons/arrow.png' alt='*'/><?=$req['title'];?>(<?=$status;?>)</a></li>
<?
    }
}
echo "</div>";
break;
case 'viev';

$id=trim(htmlspecialchars($_GET['id']));

    $tik=mysql_fetch_array(mysql_query("SELECT * FROM `ticket` WHERE `id`='$id'  and `user`='".mysql_real_escape_string($user['id'])."'"));


###начало##


##конец####






    if($tik){
        
                switch ($tik[status])
        {
            case 'new';

            $status="<font color='yellow'>Ожидание ответа</font>";

            break;

            case 'read';

            $status="<font color='green'>Есть ответ</font>";

            break;

            case 'close';

            $status="<font color='red'>Закрыт</font>";

            break;

            case 'user';

            $status="<font color='red'>Ожидание ответа</font>";

            break;

        }


if($_GET['close']=="true" && $tik['status']!="close")
{


mysql_query("UPDATE `ticket` SET `status`='close' WHERE `id`='".mysql_real_escape_string($tik['id'])."'");




$_SESSION['msg'] = 'Обращение закрыто!';
        header('location:/support.php');


}
        
        if($_GET['answer']=="true")
        {

                $sms= $_POST['sms'];
    
                if(strlen($sms)<5 OR strlen($sms)>2048)$err="Длина сообщения от 5 до 2048 символов";

                if($tik['status']=="close")$err="Тикет закрыт.";

                if($tik['status']=="user")$err="Анти-флуд.";

                if($err)
                {

                    $_SESSION['msg'] = $err;
        header('location:/support.php');
                }elseif(!$err)
                {

                    mysql_query("INSERT INTO `ticket_answer` SET `text`='".mysql_real_escape_string($sms)."',`type`='user',`ticket`='".mysql_real_escape_string($tik['id'])."'");

                    mysql_query("UPDATE `ticket` SET `status`='user' WHERE `id`='".mysql_real_escape_string($tik['id'])."'");

                    $_SESSION['msg'] = 'Сообщение добавлено!';
        header('location:/support.php');



                }



        }
    

        ?>

            <div class="block">
            
            Тема: <?=$tik['title'];?><br/>

                 
                
            Статус: <?=$status;?><br/>

                 

            Текст обращения: <?echo "".nl2br($tik['text'])."";?><br/>
            </div>
            <div class="block2">
            <li><a href='?mode=viev&id=<?=$tik['id'];?>&close=true'><?=ico('icons','arrow.png');?>Закрыть обращение</a></li>



            </div>
            <div class="block center">
            <Form action="?mode=viev&id=<?=$tik['id'];?>&answer=true" method="post"/>
            <textarea name="sms" placeholder="Ваш ответ..."></textarea>
              <input type="submit" class="btn" value="Отправить"/>
            </form>
            </div>
<?

    $answer=mysql_query("SELECT  * FROM `ticket_answer` WHERE `ticket`='".$tik['id']."' ORDER BY `id` DESC");

    if(mysql_num_rows($answer)==0){

        ?>

        <div class="block">
        <font color='#999'>Сообщений не найдено.</font>
        </div>
    
        <?
    
    }elseif(mysql_num_rows($answer)>0)
    {

            while ($feed=mysql_fetch_array($answer))
            {
              
                if($feed['type']=="admin")
                {

                    ?>
                    <div class="block">
                    <font color='#999'>Ответ Администрации:</font><br>
                    <?=$feed['text'];?>
                    </div>
                    
                    <?

                }elseif($feed['type']=="user")
                {

                    ?>
                    <div class="block">
                    <font color='#999'>Вы:</font><br>
                    <?=$feed['text'];?>
                    </div>
                    
                    <?


                }

            }        

    }
echo "<a href='/support.php' class='link'>".ico('icons','arrow.png')." Вернуться назад</a>";

    }elseif(!$tik)
    {

        header("Location:/support.php");

        exit;

    }


break;
case 'admin';

if($user['access']  == 2){






$id=trim(htmlspecialchars($_GET['id']));

    $tik=mysql_fetch_array(mysql_query("SELECT * FROM `ticket` WHERE `id`='$id'"));



####

switch
($tik[top_sms])
{
case '1';
$top_sms= "Oбычный";
break;
case '2';
$top_sms= "Сотрудничество";
break;
case '3';
$top_sms= "Оплата";
break;
} 
####

    if($tik){
        
                switch ($tik[status])
        {
            case 'new';

            $status="<font color='yellow'>Ожидание ответа</font>";

            break;

            case 'read';

            $status="<font color='green'>Есть ответ</font>";

            break;

            case 'close';

            $status="<font color='red'>Закрыт</font>";

            break;

            case 'user';

            $status="<font color='red'>Ответил игрок</font>";

            break;
        }

if($_GET['open']=="true")
{

mysql_query("UPDATE `ticket` SET `status`='read' WHERE `id`='".mysql_real_escape_string($tik['id'])."'");


}


if($_GET['delete']=="true")
{

mysql_query("DELETE FROM `ticket` WHERE `id`='".mysql_real_escape_string($tik['id'])."'");

mysql_query("DELETE FROM `ticket_answer` WHERE `ticket`='".mysql_real_escape_string($tik['id'])."'");



$_SESSION['msg'] = 'Тикет удален.';
        header('location:/support.php');


}



if($_GET['close']=="true" && $tik['status']!="close")
{


mysql_query("UPDATE `ticket` SET `status`='close' WHERE `id`='".mysql_real_escape_string($tik['id'])."'");




}
        
        if($_GET['answer']=="true")
        {

                $sms= $_POST['sms'];
    
                if(strlen($sms)<5 OR strlen($sms)>2048)$err="Длина сообщения от 5 до 2048 символов";

                if($tik['status']=="close")$err="Тикет закрыт.";

                

                if($err)
                {

                $_SESSION['msg'] = $err;
        header("location:/support.php?mode=admin&id=$tik[id]");
                }elseif(!$err)
                {

                    mysql_query("INSERT INTO `ticket_answer` SET `text`='".mysql_real_escape_string($sms)."',`type`='admin',`ticket`='".mysql_real_escape_string($tik['id'])."'");

                    mysql_query("UPDATE `ticket` SET `status`='read' WHERE `id`='".mysql_real_escape_string($tik['id'])."'");

                    $_SESSION['msg'] = 'Сообщение добавлено.';
        header("location:/support.php?mode=admin&id=$tik[id]");



                }



        }
    $ank = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `id` = '".$tik['user']."' LIMIT 1"));
echo "<div class='block'>
            Тема: $tik[title]<br/>
                        Оформил: ".icons_user($ank['id'])." <a href='/profile/$ank[id]'>$ank[login]</a><br/>
    
   Tип: $top_sms
<br/>






        Статус: $status<br/>
            Текст обращения: ".nl2br($tik['text'])."<br/>
            </div>
            
            <div class='block2'>
            <li><a href='?mode=admin&id=$tik[id]&close=true' class='button'>Закрыть обращение</a></li>
            <li><a href='?mode=admin&id=$tik[id]&delete=true' class='button'>Удалить обращение</a></li>
            <li><a href='?mode=admin&id=$tik[id]&open=true' class='button'>Открыть</a></li>
            </div>
                        <div class='block center'>
            <Form action='?mode=admin&id=$tik[id]&answer=true' method='post'>
            <textarea name='sms' placeholder='Ваш ответ'></textarea>
              <input type='submit' class='btn' value='Отправить'>
</form>";
echo "</div>";
    $answer=mysql_query("SELECT  * FROM `ticket_answer` WHERE `ticket`='".$tik['id']."' ORDER BY `id` DESC");

    if(mysql_num_rows($answer)==0){

        ?>

        <div class="block">
        <font color='#999'>Сообщений не найдено.</font>
        </div>
    
        <?
    
    }elseif(mysql_num_rows($answer)>0)
    {

            while ($feed=mysql_fetch_array($answer))
            {
              
                if($feed['type']=="admin")
                {

                    ?>
                    <div class="block">
                    <font color='#999'>Консультант:</font><br>
                    <?=$feed['text'];?>
                    </div>
                
                    <?

                }elseif($feed['type']=="user")
                {

                    ?>
                    <div class="block">
                    <font color='#999'>Пользователь:</font><br>
                    <?=$feed['text'];?>
                    </div>
                
                    <?


                }

            }
}
echo "<a href='/support.php?mode=viev_all' class='link'>".ico('icons','arrow.png')." Вернуться назад</a>";

    }elseif(!$tik)
    {

        header("Location:/support.php?mode=viwv_all");

        exit;

    }








}

break;
case 'viev_all';

if($user['access']  < 2){


    header("Location:/");

    exit;


}

$all=mysql_query("SELECT * FROM `ticket`  ORDER BY `id` DESC");

if(mysql_num_rows($all)==0)
{

?>

        <div class="block">
        <font color='#999'>Заявок нету</font>
        </div>
    
        <?

}elseif(mysql_num_rows($all)>0)
{

echo "<div class='block2'>";
    while ($at=mysql_fetch_array($all))
    {
      
                    switch ($at[status])
        {
            case 'new';

            $status="<font color='yellow'>Ожидание ответа</font>";

            break;

            case 'read';

            $status="<font color='green'>Есть ответ</font>";

            break;

            case 'close';

            $status="<font color='red'>Закрыт</font>";

            break;

            case 'user';

            $status="<font color='red'>Ответил игрок</font>";

            break;
        }

                ?>      
            
<li><a href='?mode=admin&id=<?=$at['id'];?>'><img src='/images/icons/arrow.png' alt='*'/><?=$at['title'];?> (<?=$status;?>)</a></li>
            

        <?
    



    }
echo "</div>";





}


break;


}

include './system/footer.php';

?>