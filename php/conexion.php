<?php
// funcion que realiza la conexion con la base de datos
// recibe de parámetros:
//		el nombre de la DB a conectar
//		el nombre de usuario y pass del mismo
//		direccion IP de la conexion
// 		el puerto donde se conecta

function conexion($bd,$usuario,$pass,$ip,$puerto){
	//se reciben los parámetros y se les asigna a las sig vars
    $serverName = "$ip , $puerto"; 
	$uid = "$usuario";   
	$pwd = "$pass";  
	$databaseName = "$bd"; 

	//se hace un array con la información necesaria para realizar la conexión
	$connectionInfo = array( "UID"=>$uid,                            
							 "PWD"=>$pwd,                            
							 "Database"=>$databaseName); 

	/* Se realiza la conexión usando una autentificación SQL Server */  
	$conn = sqlsrv_connect( $serverName, $connectionInfo);  
	//en caso de que la conexión falle salta el mensaje de error...
	if (!$conn) {
		die('Algo fue mal mientras se conectaba a MSSQL');
	}
	print_r ("Conexion Completa");
	return $conn;
}
