<?php
if( isset( $_GET['O'] ) )
{
	setcookie( 'order', $_GET['O'], time()+3600*24*365 ); // 1 anno bastera'.
	setcookie( 'orderdir', $_GET['D'], time()+3600*24*365 );
}
else // defaults
{
	if( !isset( $_COOKIE['order'] ) )
	{
		setcookie( 'order', 'lastupdate', time()+3600*24*365 );
		setcookie( 'orderdir', 'DESC', time()+3600*24*365 );
	}
}
	
header( 'Location: index.php' );
	
?>

