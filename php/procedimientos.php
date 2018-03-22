<?php 
include 'conexion.php';
print_r('hola');
//Elegir si cargar la master o una base de datos especifica

$db = null;

if( isset($_GET['bd']) && !empty($_GET['bd'])
){
	print_r('hola2');
    if(	isset($_GET['usuario']) && !empty($_GET['usuario']) &&
    	isset($_GET['pass']) && !empty($_GET['pass'])		&&
    	isset($_GET['ip']) && !empty($_GET['ip'])		&&
    	isset($_GET['puerto']) && !empty($_GET['puerto'])
    ){
		print_r('hola3');
    	$db = conexion($_GET['bd'],$_GET['usuario'],$_GET['pass'],$_GET['ip'],$_GET['puerto']);
    	echo "C mamo";
    }else{
    	echo "Introduzca un usuario y contraseña";
    }
}	

if(isset($_GET['fun'])){
	print_r('VS');
    if($_GET['fun'] == 'CmbBD'){
        $SQL = "SELECT name from sys.databases";
		$result = array();
		// Execute query:
		$resultado = sqlsrv_query($db,$SQL) 
			or die('A error occured: ' . mysql_error());
		
		//$row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
        do {
		   while ($row = sqlsrv_fetch_array($resultado,SQLSRV_FETCH_ASSOC)) {
			   // Loop through each result set and add to result array
			   $result[] = $row;
		   }
		} while (sqlsrv_next_result($resultado));
		print_r($result);
		/*else{
			return $result;
		}*/
    }
}