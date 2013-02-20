<?php

/* Database connection information */
require_once("mysql.php");

/*
 * Local functions
 */

function fatal_error($sErrorMessage = '') {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
    die($sErrorMessage);
}

/*
 * MySQL connection
 */
if (!$gaSql['link'] = mysql_pconnect($gaSql['server'], $gaSql['user'], $gaSql['password'])) {
    fatal_error('Could not open connection to server');
}

if (!mysql_select_db($gaSql['db'], $gaSql['link'])) {
    fatal_error('Could not select database ');
}

mysql_query('SET names utf8');


$id_doctor = $_POST["id_doctor"];
$nombre = $_POST["nombre"];
$numcolegiado = $_POST["numcolegiado"];
$id_clinicas = $_POST["id_clinicas"];

/*
 * SQL queries
 * Get data to display
 */


if ($numcolegiado == ""){
    $numcolegiado = 0;
}
$sQuery = "update doctores set nombre = '".$nombre."', numcolegiado = ".$numcolegiado." where id_doctor = ".$id_doctor."";
$rResult = mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());

$sQuery2 = "DELETE FROM clinica_doctor WHERE id_doctor =".$id_doctor;
$rResult2 = mysql_query($sQuery2, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());

$numdoctor = $id_doctor+1;

for($i=0; $i < count($id_clinicas); $i++){
    $sQuery3 = 'insert into clinica_doctor (id_doctor, id_clinica, numdoctor) values (' .$id_doctor . ',' .$id_clinicas[$i] . ',' .$numdoctor .')';
    $rResult3 = mysql_query($sQuery3, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());
}

echo json_encode("ok");

?>