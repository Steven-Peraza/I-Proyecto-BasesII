<?php


function predeterminadaConexion($bd,$usuario,$pass){
	//phpinfo();
    $serverName = "localhost"; 
	$uid = "sa";   
	$pwd = "Nanaki8448";  
	$databaseName = "master"; 

	$connectionInfo = array( "UID"=>$uid,                            
							 "PWD"=>$pwd,                            
							 "Database"=>$databaseName); 

	/* Connect using SQL Server Authentication. */  
	$conn = sqlsrv_connect( $serverName, $connectionInfo);  

//        or die('No se pudo conectar: ' . mysql_error());
    
	if ($conn) {
		echo 'Connected successfully';
}
        
        
    if (!$conn) {
			die('Algo fue mal mientras se conectaba a MSSQL');
}
return $conn;
}
