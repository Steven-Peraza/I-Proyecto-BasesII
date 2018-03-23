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
else if(($_GET['fun'] == 'CmbFGS') || ($_GET['fun'] == 'CmbFGSM')){
        $cmamo2 = FGselection();
		echo $cmamo2;
    }
else if($_GET['fun'] == 'CmbArch'){
        $cmamo2 = FNselection();
		echo $cmamo2;
    }
	//llamada al procedimiento almacenado de creacion de filegroups...
	else if(($_GET['fun'] == 'FGDB') &&( isset($_GET['fgname']) && !empty($_GET['fgname']))){
        NewFileGroups($_GET['bd'],$_GET['fgname']);
    }
	//llamada al procedimiento almacenado de creacion de discos extra de archivos...
	else if(($_GET['fun'] == 'NFDB')&&( isset($_GET['fgname']) && !empty($_GET['fgname'])
									&&( isset($_GET['fname']) && !empty($_GET['fname'])
									&&( isset($_GET['size']) && !empty($_GET['size'])
									&&( isset($_GET['max']) && !empty($_GET['max'])
									&&( isset($_GET['grogro']) && !empty($_GET['grogro'])
									&&( isset($_GET['bd']) && !empty($_GET['bd'])
									&&( isset($_GET['path']) && !empty($_GET['path'])
	)))))))){
        NewFiles($_GET['bd'],$_GET['fname'],$_GET['path'],$_GET['size'],$_GET['max'],$_GET['grogro'],$_GET['fgname']);
    }
	else if(($_GET['fun'] == 'MFDB') &&( isset($_GET['fname']) && !empty($_GET['fname'])
									&&( isset($_GET['bd']) && !empty($_GET['bd'])))){
		ModiFiles($_GET['bd'],$_GET['fname'],$_GET['nsize'],$_GET['nmax'],$_GET['ngrogro'],$_GET['nfn']);
		}
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

//funcion que selecciona los fgs a utilizar...
function FGselection(){
	global $conn, $cmamo;
	$SQL = "SELECT name from sys.filegroups";
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

//funcion que selecciona los files a utilizar...
function FNselection(){
	global $conn, $cmamo;
	$SQL = "select name from sys.database_files";
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
	global $conn;
	/*'test','ACM1PT2','C:\Users\Steven\Desktop\TEC\Semestre 5\Bases II',5, 
								20, 5, 'Soy_un_manco_en_Dota'*/
	$SQL = "exec DiscosPlusPlus ?,?,?,?,?,?,?";
		// Execute query:
	$stmt = sqlsrv_prepare( $conn, $SQL, array(&$bd,&$fn,&$ruta,&$size, 
								&$max, &$grogro, &$fg));
		// Execute query:
	if( sqlsrv_execute( $stmt ) === false ) {
          die( print_r( sqlsrv_errors(), true));
    }
	echo "Disco creado correctamente...";
}

function ModiFiles($bd,$fname,$nsize,$nmax,$ngrogro,$nfn){
	global $conn;
	//-----------------------------------------------------------
	if ($nsize != null && $nmax == null && $ngrogro == null && $nfn == null){
		$SQL = "exec AlterDiscosSize ?,?,?";
		// Execute query:
		$stmt = sqlsrv_prepare( $conn, $SQL, array(&$bd,&$fname,&$nsize));
			// Execute query:
		if( sqlsrv_execute( $stmt ) === false ) {
			  die( print_r( sqlsrv_errors(), true));
		}
		echo "Disco modificado correctamente...";
	}
	//----------------------------------------------------------
	else if ($nsize == null && $nmax != null && $ngrogro == null && $nfn == null){
		$SQL = "exec AlterDiscosMax ?,?,?";
		// Execute query:
		$stmt = sqlsrv_prepare( $conn, $SQL, array(&$bd,&$fname,&$nmax));
			// Execute query:
		if( sqlsrv_execute( $stmt ) === false ) {
			  die( print_r( sqlsrv_errors(), true));
		}
		echo "Disco modificado correctamente...";
	}
	//---------------------------------------------------------
	else if ($nsize == null && $nmax == null && $ngrogro != null && $nfn == null){
		$SQL = "exec AlterDiscosGroGro ?,?,?";
			// Execute query:
		$stmt = sqlsrv_prepare( $conn, $SQL, array(&$bd,&$fname,&$ngrogro));
			// Execute query:
		if( sqlsrv_execute( $stmt ) === false ) {
			  die( print_r( sqlsrv_errors(), true));
		}
		echo "Disco modificado correctamente...";
	}
	//---------------------------------------------------------
	else if ($nsize == null && $nmax == null && $ngrogro == null && $nfn != null){
		$SQL = "exec AlterDiscosName ?,?,?";
		// Execute query:
		$stmt = sqlsrv_prepare( $conn, $SQL, array(&$bd,&$fname,&$nfn));
			// Execute query:
		if( sqlsrv_execute( $stmt ) === false ) {
			  die( print_r( sqlsrv_errors(), true));
		}
		echo "Disco modificado correctamente...";
	}
	//----------------------------------------------------------
	else{
		$SQL = "exec AlterDiscosAll ?,?,?,?,?,?";
		// Execute query:
	$stmt = sqlsrv_prepare( $conn, $SQL, array(&$bd,&$fname,&$nsize, 
								&$nmax, &$ngrogro, &$nfn));
		// Execute query:
	if( sqlsrv_execute( $stmt ) === false ) {
          die( print_r( sqlsrv_errors(), true));
    }
	echo "Disco modificado correctamente...";
	}
	
}

function KDA ($fileName){
	
	global $conn,$cmamo;

	$SQL = "exec CaracSize ?";
	$stmt = sqlsrv_prepare( $conn, $SQL, array(&$fileName));
		// Execute query:
	if( sqlsrv_execute( $stmt ) === false ) {
          die( print_r( sqlsrv_errors(), true));
    }
	do {
		   while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
			   // Loop through each result set and add to result array
			   $cmamo = $cmamo . ",". $row['Total de MB'];
		   }
		} while (sqlsrv_next_result($resultado));
	
	$SQL = "exec CaracGrowth ?";
	$stmt = sqlsrv_prepare( $conn, $SQL, array(&$fileName));
		// Execute query:
	if( sqlsrv_execute( $stmt ) === false ) {
          die( print_r( sqlsrv_errors(), true));
    }
	do {
		   while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
			   // Loop through each result set and add to result array
			   $cmamo = $cmamo . ",". $row['Growth'];
		   }
		} while (sqlsrv_next_result($resultado));	
	$SQL = "exec CaracMax ?";
	$stmt = sqlsrv_prepare( $conn, $SQL, array(&$fileName));
		// Execute query:
	if( sqlsrv_execute( $stmt ) === false ) {
          die( print_r( sqlsrv_errors(), true));
    }
	do {
		   while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
			   // Loop through each result set and add to result array
			   $cmamo = $cmamo . ",". $row['MB Max'];
		   }
		} while (sqlsrv_next_result($resultado));
		
		
	$SQL = "exec CaracDisp ?";
	$stmt = sqlsrv_prepare( $conn, $SQL, array(&$fileName));
		// Execute query:
	if( sqlsrv_execute( $stmt ) === false ) {
          die( print_r( sqlsrv_errors(), true));
    }
	do {
		   while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
			   // Loop through each result set and add to result array
			   $cmamo = $cmamo . ",". $row['Total de MB desocupados'];
		   }
		} while (sqlsrv_next_result($resultado));
		
		
	$SQL = "exec CaracUsa ?";
	$stmt = sqlsrv_prepare( $conn, $SQL, array(&$fileName));
		// Execute query:
	if( sqlsrv_execute( $stmt ) === false ) {
          die( print_r( sqlsrv_errors(), true));
    }
	do {
		   while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
			   // Loop through each result set and add to result array
			   $cmamo = $cmamo . ",". $row['Total de MB ocupados'];
		   }
		} while (sqlsrv_next_result($resultado));
	
	return $cmamo;
	}
