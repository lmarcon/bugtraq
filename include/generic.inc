<?php

// yyyymmddhhmm to gg.mm.yyyy, hh:mm
function format_date_time( $str, $sep = '.', $sepdt = ', ' )
{
	return substr( $str, 6, 2 ).$sep.substr( $str, 4, 2 ).$sep.substr( $str, 0, 4 ).$sepdt.substr( $str, 8, 2 ).':'.substr( $str, 10, 2 );
}

function format_date( $date, $sep = '.' )
{
	// YYYYMMDD to DD.MM.YYYY
	return substr( $date, 6, 2 ).'.'.substr( $date, 4, 2 ).'.'.substr( $date, 0, 4 );
}

function unformat_date( $date, $sep = '.' )
{
	// DD.MM.YYYY to YYYYMMDD
	return substr( $date, 7, 4 ).substr( $date, 3, 2 ).substr( $date, 0, 2 );
}

?>
