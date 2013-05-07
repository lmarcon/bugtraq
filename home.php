<?php
if( !isset( $_COOKIE['order'] ) )
{
	header( 'Location: setcookie.php' ); 
	die();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title>LdPHQ Bugtracker v0.2</title>
 <link rel='stylesheet' type='text/css' href='include/style.css' />
 <script language="javascript" type="text/javascript" src="md5.js"></script>
 </head>
 <body>

<?php

include( "db/config.inc" );
include( "db/Db.php" );
/* create table bugz ( id serial, dataora char(12), author varchar, status varchar, title text, description text, priorita varchar, lastupdate char(12) ); */

function formatDataOra( $str )
{
	// da YYYYMMDDhhmm a DD/MM/YYYY - HH:MM
	return substr( $str, 6, 2 ).'/'.substr( $str, 4, 2 ).'/'.substr( $str, 0, 4 ).' - '.substr( $str, 8, 2 ).':'.substr( $str, 10, 2 );
}

$res = $db->select( "SELECT count(id) FROM bugz WHERE status NOT LIKE 'BUG/fixed' AND status NOT LIKE 'TODO/implementato'" );

?>
<div align="center">
<div id="menuTop"><a href="?insert">inserisci nuovo</a> | <a href="?">ricerca/visualizza</a></div><br />
<div id="title">BugTraq</div>
(<?php echo $res[0]['count']; ?>) bug(s) ancora da correggere - progetto SMB.
<br />
<br />

<?php

if( isset( $_GET['insert'] ) )
	include( "insert.php" );
elseif( isset( $_GET['bug_id'] ) )
{
	$res = $db->select( "SELECT * FROM bugz WHERE id = {$_GET['bug_id']}" );
?>
<fieldset><legend>dettagli:</legend>
<div align="left">
<form name="stat" action="update_status.php" method="POST">
<font size="+1"><b><?php echo $res[0]['title']; ?></b></font><br /><br />
<b>BUG ID:</b> #<?php echo $res[0]['id']; ?><br />
<b>autore:</b> <?php echo $res[0]['author']; ?><br />
<b>data inserimento:</b> <?php echo formatDataOra($res[0]['dataora']); ?><br />
<b>ultimo aggiornamento:</b> <?php echo formatDataOra($res[0]['lastupdate']); ?><br />
<b>priorit&agrave;: </b><?php echo $res[0]['priorita']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [cambia:
 <select name="priorita" onchange="window.document.stat.submit();">
  <option></option>
  <option>0 - NONE</option>
  <option>1 - LOW</option>
  <option>2 - MEDIUM</option>
  <option>3 - HIGH</option>
 </select>
<br />
<b>stato:</b> <?php echo $res[0]['status']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [cambia:
 <select name="status" onchange="window.document.stat.submit();">
  <option></option>
  <option>BUG/unfixed</option>
  <option>BUG/wait for Checco</option>
  <option>BUG/work in progress</option>
  <option>BUG/fixed</option>
  <option></option>
  <option>TODO/non implementato</option>
  <option>TODO/wait for Checco</option>
  <option>TODO/work in progress</option>
  <option>TODO/implementato</option>
 </select>
 <input type="hidden" name="bug_id" value="<?php echo $_GET['bug_id']; ?>">]
</form>
<br />
<b>descrizione:</b><br />
<br />
<?php
echo nl2br( $res[0]['description'] );
?>
</div>
<script language="javascript" type="text/javascript">
function delCom( id_c, bug_id )
{
	var pass = prompt( 'password per eliminare il commento: ' );
	
	if( hex_md5( pass ) == '7e5a85f1ad0238931f0b527626550ba8' )
		window.document.location = "delete_comment.php?id_c=" + id_c + "&bug_id=" + bug_id;
}
</script>
</fieldset>
<?php

$res = $db->select( "SELECT * FROM commentz WHERE id = {$_GET['bug_id']}" );

foreach( $res as $t )
	echo "<br /><fieldset><legend>{$t['author']} - ".formatDataOra($t['dataora'])."</legend><div align='left'>".nl2br( $t['comment'] )."
	<hr style='border: 1px solid #777;' /><a class='lnk2' href='javascript:delCom({$t['id_c']}, {$_GET['bug_id']})'><code>&lt;elimina commento&gt;</code></a></div>
	</fieldset>";

?>
<script language="javascript" type="text/javascript">
function show( id )
{
	if( window.document.getElementById( id ) )
		window.document.getElementById( id ).style.display = 'inline';
}
function hide( id )
{
	if( window.document.getElementById( id ) )
		window.document.getElementById( id ).style.display = 'none';
}
function goTo( where )
{
	window.document.location = '#' + where;
}
</script>
<br />
<?php

$r = $db->select( "SELECT count(id_c) FROM commentz WHERE id = {$_GET['bug_id']}" );

echo "<br /><b>{$r[0]['count']}</b> commenti per questo bug.<br />";
?>
<br />
<a class="lnk" href="javascript:show('ins_com');goTo('bottom');">inserisci commento</a><br />
<div style="display:none;" id="ins_com">
<fieldset><legend>commento</legend>
<form name="comment" action="action_comment.php" method="POST">
<b>autore:</b><br />
<input type="text" name="author" size="52" value="<?php echo (isset( $_COOKIE['author'] )?$_COOKIE['author']:''); ?>"><br />
<b>commento:</b><br />
<textarea name="comment" cols="50" rows="6"></textarea><br />
<input type="hidden" name="bug_id" value="<?php echo $_GET['bug_id']; ?>"><br />
<input type="submit" name="send_comment" value="invia commento">
</form>
</fieldset>
</div>
<?php
}
else
{
	
	$sortby = $_COOKIE['order'];
	$dir = $_COOKIE['orderdir'];
	$nextdir = $_COOKIE['orderdir'] == 'DESC' ? 'ASC' : 'DESC';

	$results = "<table class='resT'>
	<tr class='header'>
	 <td>ID</td>
	 <!--<td><a class='lnk' href='setcookie.php?O=dataora&amp;D=".(($_COOKIE['order'] == 'dataora')?$nextdir:'').(isset($_GET['showFixed'])?"&amp;showFixed":"")."'>data</a></td>-->
	 <td><a class='lnk' href='setcookie.php?O=lastupdate&amp;D=".(($_COOKIE['order'] == 'lastupdate')?$nextdir:'').(isset($_GET['showFixed'])?"&amp;showFixed":"")."'>ultimo agg.</a></td>
	 <td><a class='lnk' href='setcookie.php?O=priorita&amp;D=".(($_COOKIE['order'] == 'priorita')?$nextdir:'').(isset($_GET['showFixed'])?"&amp;showFixed":"")."'>priorit&agrave;</a></td>
	 <td><a class='lnk' href='setcookie.php?O=status&amp;D=".(($_COOKIE['order'] == 'status')?$nextdir:'').(isset($_GET['showFixed'])?"&amp;showFixed":"")."'>stato</a></td>
	 <td>autore</td>
	 <td>titolo</td>
	 <td>commenti</td>
	</tr>
	 ";
	  
	 if( isset( $_GET['showFixed'] ) )
		$query = "SELECT * FROM bugz WHERE status LIKE 'BUG/fixed' OR status LIKE 'TODO/implementato' ORDER BY $sortby $dir";
	 else
		$query = "SELECT * FROM bugz WHERE status NOT LIKE 'BUG/fixed' AND status NOT LIKE 'TODO/implementato' ORDER BY $sortby $dir";
	
	$res = $db->select( $query );
	$i = 0;
	foreach( $res as $t )
	{
		$r = $db->select( "SELECT count(id_c) FROM commentz WHERE id = {$t['id']}" );
		$trclass = ($i == true)?'trEven':'trOdd';
		
		$results .= "<tr class='tr".substr( $t['priorita'], 0, 1 )."'>
				<td>#{$t['id']}</td>
				<td>".formatDataOra($t['lastupdate'])."</td>
				<td>{$t['priorita']}</td>
				<td>{$t['status']}</td>
				<td>{$t['author']}</td>
				<td><a class='lnk' href='?bug_id={$t['id']}'>{$t['title']}</a></td>
				<td>{$r[0]['count']}</td>
			     </tr>";
		$i++;
	}
	if( $i == 0 )
		$results .= "<tr><td colspan='7' align='center'><i>nessun risultato.</i></td></tr>";
		
	$results .= "</table><br />";
	
	if( isset( $_GET['showFixed'] ) )
		$results .= "<a class='lnk' href='?'>mostra quelli da fixare/implementato</a>";
	else
		$results .= "<a class='lnk' href='?showFixed'>mostra quelli gi&agrave; fixati/implementati</a>";
	

?>
<div style="display:none">
<fieldset><legend>ricerca/visualizza</legend>
ricerca nel db:
<input type="text" name="q">
<input type="submit" name="search" value="cerca">
</fieldset>
<br />
</div>
<fieldset><legend>risultati</legend>
<?php echo $results; ?>
</fieldset>
<?php
}
?>
</div>
<p>
 <a href="http://validator.w3.org/check?uri=referer"><img border="0"
    src="http://www.w3.org/Icons/valid-html401"
    alt="Valid HTML 4.01!" height="31" width="88"></a>
 <a href="http://jigsaw.w3.org/css-validator/"><img style="border:0;width:88px;height:31px"
    src="http://jigsaw.w3.org/css-validator/images/vcss" 
    alt="Valid CSS!"></a>
</p>
<a name="bottom">&nbsp;</a>
 </body>
</html>
