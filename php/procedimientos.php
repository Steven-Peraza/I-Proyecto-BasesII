<?php 
include 'conexion.php';
//Elegir si cargar la master o una base de datos especifica

$conn = null;
$cmamo = "";
if( isset($_GET['bd']) && !empty($_GET['bd'])
){
    if(	isset($_GET['usuario']) && !empty($_GET['usuario']) &&
    	isset($_GET['pass']) && !empty($_GET['pass'])		&&
    	isset($_GET['ip']) && !empty($_GET['ip'])		&&
    	isset($_GET['puerto']) && !empty($_GET['puerto'])
    ){
    	$conn = conexion($_GET['bd'],$_GET['usuario'],$_GET['pass'],$_GET['ip'],$_GET['puerto']);
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
	else if(($_GET['fun'] == 'FGDB') &&( isset($_GET['fgname']) && !empty($_GET['fgname']))){
        NewFileGroups($_GET['bd'],$_GET['fgname']);
    }
}

//funcion que selecciona la db a utilizar...
function DBselection(){
	global $conn, $cmamo;
	$SQL = "SELECT name from sys.databases";
		// Execute query:
		$resultado = sqlsrv_query($conn,$SQL) 
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


//funcion que crea un nuevo file...
function NewFileGroups($bd,$fgn){
	global $conn;

	$SQL = "exec FilegroupsPlusPlus ?,?";
	$stmt = sqlsrv_prepare( $conn, $SQL, array(&$bd,&$fgn));
		// Execute query:
	if( sqlsrv_execute( $stmt ) === false ) {
          die( print_r( sqlsrv_errors(), true));
    }
	echo "Filegroup Creado correctamente! Yay!";

}	



//funcion que crea un nuevo file...
function NewFiles($bd,$fn,$ruta,$size,$max,$grogro,$fg){
	global $conn, $cmamo;
	/*'test','ACM1PT2','C:\Users\Steven\Desktop\TEC\Semestre 5\Bases II',5, 
								20, 5, 'Soy_un_manco_en_Dota'*/
	$SQL = "exec DiscosPlusPlus ?,?,?,?,?,?,?";
		// Execute query:
	$stmt = sqlsrv_prepare( $conn, $SQL, array(&$bd,&$fn,&$ruta,&$size, 
								&$max, &$grogro, &$filegroup));
		// Execute query:
	if( sqlsrv_execute( $stmt ) === false ) {
          die( print_r( sqlsrv_errors(), true));
    }
	echo "Nuevo disco creado correctamente...";
}