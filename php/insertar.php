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

$nombre = $_POST["nombre"];
$numcolegiado = $_POST["numcolegiado"];
$id_clinicas = $_POST["id_clinicas"];

/*
 * SQL queries
 * Get data to display
 */

$sQuery = "select MAX(id_doctor) as id_doctor from doctores";
$rResult = mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());
$resultado = mysql_fetch_assoc($rResult);
$id = $resultado['id_doctor'];
$id += 1;


$sQuery = 'insert into doctores (id_doctor,nombre, numcolegiado) values (' .$id . ', "'.$nombre . '",' .$numcolegiado .' )';
$rResult = mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());

$numdoctor = $id+1;

	
for($i=0; $i < count($id_clinicas); $i++){
    $sQuery2 = 'insert into clinica_doctor (id_doctor, id_clinica, numdoctor) values (' .$id . ',' .$id_clinicas[$i] . ',' .$numdoctor .')';
    $rResult2 = mysql_query($sQuery2, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());
}


echo json_encode("ok");

?>