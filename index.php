<?php
session_start();
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
  <title>LdPHQ Bugtracker v0.21</title>
 <link rel='stylesheet' type='text/css' href='include/style.css' />
 <script language="javascript" type="text/javascript" src="include/js/calendarDateInput.js"></script>
 <script language="javascript" type="text/javascript" src="include/js/generic.js"></script>
 </head>
 <body>

<?php

include( "db/config.inc" );
include( "include/generic.inc" );
include( "db/Db.php" );

if( !empty( $_SESSION['auth'] ) )
{
    echo "
        <div id='menuTop'>current user: <i>{$_SESSION['auth']['name']} {$_SESSION['auth']['surname']}</i> - current project: <i>".(empty( $_SESSION['id_project'])?"nessuno":$_SESSION['name_project'])."</i></div><br /><br />
        <a href='?section=selectProject'>seleziona un progetto</a> -
        <a href='?section=admin'>amministrazione</a><br /><br />
        ";

    if( !empty( $_GET['section'] ) )
        include( 'sections/'.$_GET['section'].'.php' );
    else
        if( !empty( $_SESSION['id_project'] ) )
            include( 'sections/showProject.php' );
}
else
{
    echo "
        <form name='login' action='action.php' method='post'>
        <input type='hidden' name='action' value='login'>
        <input type='hidden' name='redirect' value=''>
        <table align='center' border='0'>
        <tr><td>email</td><td>pwd</td></tr>
        <tr><td><input type='text' name='email'></td><td>
        <input type='password' name='pwd'></td>
        </tr>
        <tr><td colspan='2'><input type='submit' value='login'></td></tr>
        </table>
        </form>

    ";
}
?>

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
