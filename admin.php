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

?>

<div id="menuTop"><a href="index.php">home page</a> | <a href="selectProject.php">seleziona progetto</a></div><br />
<div id="title">BugTraq</div>

<div align='center'>
<fieldset><legend><a class='lnkWhite' href='admin.php'>amministrazione</a></legend>
<?php

$section = !empty( $_GET['section'] ) ? $_GET['section'] : 'admin';
switch( $section )
{
	default:
	case 'admin':
		echo "<a href='?section=users'>gestione utenti</a> - <a href='?section=projects'>gestione progetti</a>";
		break;

	case 'users':
		echo "gestione utenti<br /><br />";

		break;

	case 'projects':
		echo "gestione progetti<br /><br />";
		break;
}
?>
</fieldset>
</div>



<!--
<p>
 <a href="http://validator.w3.org/check?uri=referer"><img border="0"
    src="http://www.w3.org/Icons/valid-html401"
    alt="Valid HTML 4.01!" height="31" width="88"></a>
 <a href="http://jigsaw.w3.org/css-validator/"><img style="border:0;width:88px;height:31px"
    src="http://jigsaw.w3.org/css-validator/images/vcss" 
    alt="Valid CSS!"></a>
</p>-->
<a name="bottom">&nbsp;</a>
 </body>
</html>
