<?php 
include 'conexion.php';
//Elegir si cargar la master o una base de datos especifica

$db = null;
$cmamo = "";
if( isset($_GET['bd']) && !empty($_GET['bd'])
){
    if(	isset($_GET['usuario']) && !empty($_GET['usuario']) &&
    	isset($_GET['pass']) && !empty($_GET['pass'])		&&
    	isset($_GET['ip']) && !empty($_GET['ip'])		&&
    	isset($_GET['puerto']) && !empty($_GET['puerto'])
    ){
    	$db = conexion($_GET['bd'],$_GET['usuario'],$_GET['pass'],$_GET['ip'],$_GET['puerto']);
    }else{
    	echo "Introduzca un usuario y contraseña";
    }
}else{
	echo "no encontró la base";
}	

if(isset($_GET['fun'])){
    if($_GET['fun'] == 'CmbBD'){
        $cmamo2 = DBselection();
		echo $cmamo2;
    }
	//llamada al procedimiento almacenado de creacion de discos extra de archivos...
	else if($_GET['fun'] == 'FilesDB'){
        $SQL = "DiscosPlusPlus (@nombreDB nvarchar(50),@fileName nvarchar(50),@rutaFis nvarchar(100),@size int, 
								@maxsize int, @growth int, @fg nvarchar(50))";
		// Execute query:
		$resultado = sqlsrv_query($db,$SQL) 
			or die('A error occured: ' . mysql_error());
		
		//$row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
        do {
		   while ($row = sqlsrv_fetch_array($resultado,SQLSRV_FETCH_ASSOC)) {
			   // Loop through each result set and add to result array
			   $cmamo = $cmamo . ",". $row['name'];
		   }
		} while (sqlsrv_next_result($resultado));
		
		//print_r ($cmamo);
		return $cmamo;
    }
}

//funcion que selecciona la db a utilizar...
function DBselection(){
	global $db, $cmamo;
	$SQL = "SELECT name from sys.databases";
		// Execute query:
		$resultado = sqlsrv_query($db,$SQL) 
			or die('A error occured: ' . mysql_error());
		
		//$row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
        do {
		   while ($row = sqlsrv_fetch_array($resultado,SQLSRV_FETCH_ASSOC)) {
			   // Loop through each result set and add to result array
			   $cmamo = $cmamo . ",". $row['name'];
		   }
		} while (sqlsrv_next_result($resultado));
		
		//print_r ($cmamo);
		return $cmamo;
}