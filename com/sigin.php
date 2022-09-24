<?php

//Variables de Registro.
$pHost=$_POST['txtHost'];
$pPort=$_POST['txtPort'];
$pDbname=$_POST['txtDbname'];
$pUser=$_POST['txtUser'];
$pPass=$_POST['txtPassword'];

$conection = pg_connect("host=localhost port=5432 dbname=CTEC user=postgres password=12345");

$query = "Select * from admi.usuarios";
$sql = pg_query($conection, $query);

if ($sql){
    echo("Bienvenido user $pUser");
}

?>