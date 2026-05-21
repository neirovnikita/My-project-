<?
    
require_once ('system/func.php'); 
$title = '<center>Список смайлов'; 
require_once ('system/header.php'); 
auth(); // Закроем от не авторизованных
$id = intval($_GET['id']); 
$opponent = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` = '".$id."' LIMIT 1"));  
?>

<div class='title'><?=$title?></div>

<div class='line'></div>
<center><a href="/chat"><< Назад</a></center>
<div class="feedback"><img src='/img/smiles/mini_ded.gif' alt=':@)'/> :@) :ded <br/></div>
<div class="feedback"><img src='/img/smiles/mini_angel.gif' alt='O:-)'/> O:-) 0:-) О:-) <br/></div>
<div class="feedback"><img src='/img/smiles/mini_diablo.gif' alt=']:-)'/> ]:-) ]:-] <br/></div>
<div class="feedback"><img src='/img/smiles/mini_blush.gif' alt=':$'/> :$ :-$ :-[ <br/></div>
<div class="feedback"><img src='/img/smiles/mini_lol.gif' alt=':))'/> :)) :-)) -)) =)) <br/></div>
<div class="feedback"><img src='/img/smiles/mini_ulibka.gif' alt=':)'/> :) :-) =) <br/></div>
<div class="feedback"><img src='/img/smiles/mini_podmigivanie.gif' alt=';)'/> ;) ;-) <br/></div>
<div class="feedback"><img src='/img/smiles/mini_spin.gif' alt=':-D'/> :-D :-d :D :d ))) <br/></div>
<div class="feedback"><img src='/img/smiles/mini_yazyk.gif' alt=':-Р'/> :-Р :-р :-P :-p :P :p <br/></div>
<div class="feedback"><img src='/img/smiles/mini_sad.gif' alt=':('/> :( :-( <br/></div>
<div class="feedback"><img src='/img/smiles/mini_cry.gif' alt=':'('/> :'( :'-( <br/></div>
<div class="feedback"><img src='/img/smiles/mini_dovolen.gif' alt=':]'/> :] :-] <br/></div>
<div class="feedback"><img src='/img/smiles/mini_hm.gif' alt=':-/'/> :-/ :-\ <br/></div>
<div class="feedback"><img src='/img/smiles/mini_krut.gif' alt='8-)'/> 8-) 8) <br/></div>
<div class="feedback"><img src='/img/smiles/mini_kiss.gif' alt=':*'/> :* :-* <br/></div>
<div class="feedback"><img src='/img/smiles/mini_crazy.gif' alt='%)'/> %) %-) <br/></div>
<div class="feedback"><img src='/img/smiles/mini_chok.gif' alt=':-о'/> :-о :-О :-o :-O О.о o.О O_o o_O <br/></div>
<div class="feedback"><img src='/img/smiles/mini_bye.gif' alt='О^'/> О^ O^ o^ <br/></div>
<div class="feedback"><img src='/img/smiles/mini_good.gif' alt=':Оb'/> :Оb :Ob :ob <br/></div>
<div class="feedback"><img src='/img/smiles/mini_fingal.gif' alt='6-('/> 6-(<br/></div>
<div class="feedback"><img src='/img/smiles/mini_gigi.gif' alt='%-E'/> %-E %-Е <br/></div>
<div class="feedback"><img src='/img/smiles/mini_gig.gif' alt=':gigi'/> :gigi<br/></div>
<div class="feedback"><img src='/img/smiles/mini_bravo.gif' alt=':bravo'/> :bravo :браво <br/></div>
<div class="feedback"><img src='/img/smiles/mini_heart.gif' alt=':heart'/> :heart :сердце <br/></div>
<div class="feedback"><img src='/img/smiles/mini_fig.gif' alt=':fig'/> :fig :фиг <br/></div>
<div class="feedback"><img src='/img/smiles/mini_rose.gif' alt=':rose'/> :rose :роза @-- <br/></div>
<div class="feedback"><img src='/img/smiles/mini_palci.gif' alt=':krut'/> :krut :крут <br/></div>
<div class="feedback"><img src='/img/smiles/mini_friends.gif' alt='dOOb'/> dOOb doob d00b <br/></div>
</div><div class='mini-line'></div><div class='menuList'>


<?

require_once ('system/footer.php');
  


?>