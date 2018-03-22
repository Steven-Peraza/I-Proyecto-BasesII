<?php

function conexion($bd,$usuario,$pass,$ip,$puerto){
	//phpinfo();
    $serverName = "$ip"; 
	$uid = "$usuario";   
	$pwd = "$pass";  
	$databaseName = "$bd"; 

	$connectionInfo = array( "UID"=>$uid,                            
							 "PWD"=>$pwd,                            
							 "Database"=>$databaseName); 

	/* Connect using SQL Server Authentication. */  
	$conn = sqlsrv_connect( $serverName, $connectionInfo);  

//        or die('No se pudo conectar: ' . mysql_error());
    
	if (!$conn) {
		die('Algo fue mal mientras se conectaba a MSSQL');
	}
	return $conn;
}
