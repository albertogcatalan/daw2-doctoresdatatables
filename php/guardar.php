<?php

require_once("mysql.php");

$aColumns = array( $_POST['id_clinica'], $_POST['nombre'], $_POST['numclinica'], $_POST['razonsocial'], $_POST['cif'],  $_POST['localidad'], $_POST['provincia'], $_POST['direccion'], $_POST['cp'],  $_POST['id_tarifa']);

if ( ! $gaSql['link'] = mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) )
	{
		fatal_error( 'Could not open connection to server' );
	}

if ( ! mysql_select_db( $gaSql['db'], $gaSql['link'] ) )
{
        fatal_error( 'Could not select database ' );
}
	
mysql_query('SET names utf8');
        
function fatal_error ( $sErrorMessage = '' )
	{
		header( $_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error' );
		die( $sErrorMessage );
	}     


$sQuery = "UPDATE clinicas SET nombre = '".$aColumns[1]."', numclinica = '".$aColumns[2]."', razonsocial = '".$aColumns[3]."', cif = '".$aColumns[4]."', localidad = '".$aColumns[5]."', provincia = '".$aColumns[6]."', direccion = '".$aColumns[7]."', cp = '".$aColumns[8]."', id_tarifa = ".$aColumns[9]." WHERE id_clinica = ".$aColumns[0];
//echo $sQuery;

$rResult = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );

echo $aColumns[0];
        
?>