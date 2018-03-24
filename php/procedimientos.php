<?php 
include 'conexion.php';
//Elegir si cargar la master o una base de datos especifica

//se decalran un par de vars que se utilizarán en todos o la mayoría de las sig funciones
$conn = null;
$cmamo = "";

/*Aclaración*/
//los isset y los empty son para aveiguar que las variables enviadas de parametros estén declaradas y no sean null

//se pregunta si la operación por realizar es de tipo 'bd', la cual es la primera operación por
//realizar y la más importante: la conexion

if( isset($_GET['bd']) && !empty($_GET['bd'])
){
	//se revisa si todos los datos necesarios para realizar la conexion se encuentran bien definidas y se llama a la conexion...
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

//en estas se retorna un valor que se almaca en la var 'cmamo2' y se le hace un echo para ser recibida en el .js y luego en el .html
//si la funcion 'fun' es de tipo 'CmbBD' se llama a la funcion de selección de DB's
if(isset($_GET['fun'])){
    if($_GET['fun'] == 'CmbBD'){
        $cmamo2 = DBselection();
		echo $cmamo2;
    }
//si la funcion 'fun' es de tipo 'CmbFGS' o 'CmbFGSM'  se llama a la funcion de selección de FG's
else if(($_GET['fun'] == 'CmbFGS') || ($_GET['fun'] == 'CmbFGSM')){
        $cmamo2 = FGselection();
		echo $cmamo2;
    }
//si la funcion 'fun' es de tipo 'CmbArch'  se llama a la funcion de selección de Files
else if(($_GET['fun'] == 'CmbArch') || ($_GET['fun'] == 'CmbArchM')){
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
	//llamada al procedimiento almacenado de modificación de discos extra de archivos...
	else if(($_GET['fun'] == 'MFDB') &&( isset($_GET['fname']) && !empty($_GET['fname'])
									&&( isset($_GET['bd']) && !empty($_GET['bd'])))){
		ModiFiles($_GET['bd'],$_GET['fname'],$_GET['nsize'],$_GET['nmax'],$_GET['ngrogro'],$_GET['nfn']);
		}
	//llamada al procedimiento almacenado de recolección de estadísticas de discos extra de archivos...
	else if($_GET['fun'] == 'GGWP'){
        $cmamo2 = KDA($_GET['fname']);
		echo $cmamo2;
    }
}

//funcion que selecciona la db a utilizar...
function DBselection(){
	//se utilizan las vars globales de conexion y cmamo para guardar la info retornada en el query...
	global $conn, $cmamo;
	//var que guarda el query por realizar
	$SQL = "SELECT name from sys.databases";
		// Execute query:
		$resultado = sqlsrv_query($conn,$SQL) 
			or die('A error occured: ' . mysql_error());
		// ciclo que toma el resultado del query y almacena los valores concatenados en la var cmamo...
        do {
		   while ($row = sqlsrv_fetch_array($resultado,SQLSRV_FETCH_ASSOC)) {
			   $cmamo = $cmamo . ",". $row['name'];
		   }
		} while (sqlsrv_next_result($resultado));
		
		//se retorna el resultado concatenado del query...
		return $cmamo;
}

//funcion que selecciona los fgs a utilizar...
function FGselection(){
	//se utilizan las vars globales de conexion y cmamo para guardar la info retornada en el query...
	global $conn, $cmamo;
	//var que guarda el query por realizar
	$SQL = "SELECT name from sys.filegroups";
	// Execute query:
		$resultado = sqlsrv_query($conn,$SQL) 
			or die('A error occured: ' . mysql_error());
		// ciclo que toma el resultado del query y almacena los valores concatenados en la var cmamo...
        do {
		   while ($row = sqlsrv_fetch_array($resultado,SQLSRV_FETCH_ASSOC)) {
			   $cmamo = $cmamo . ",". $row['name'];
		   }
		} while (sqlsrv_next_result($resultado));
		
		return $cmamo;
}

//funcion que selecciona los files a utilizar...
function FNselection(){
	//se utilizan las vars globales de conexion y cmamo para guardar la info retornada en el query...
	global $conn, $cmamo;
	//var que guarda el query por realizar
	$SQL = "select name from sys.database_files";
	// Execute query:
		$resultado = sqlsrv_query($conn,$SQL) 
			or die('A error occured: ' . mysql_error());
		
		// ciclo que toma el resultado del query y almacena los valores concatenados en la var cmamo...
        do {
		   while ($row = sqlsrv_fetch_array($resultado,SQLSRV_FETCH_ASSOC)) {
			   $cmamo = $cmamo . ",". $row['name'];
		   }
		} while (sqlsrv_next_result($resultado));

		return $cmamo;
}

//funcion que crea un nuevo filegroup...
function NewFileGroups($bd,$fgn){
	//se utilizan la var global de conexion 
	global $conn;
	//se llama al procedimiento almacenado dentro de la DB elegida de Creacion de Filegroups
	$SQL = "exec FilegroupsPlusPlus ?,?";
	//se prepara un array con los parametros por enviar al proc almacenado
	$stmt = sqlsrv_prepare( $conn, $SQL, array(&$bd,&$fgn));
		// Execute query:
	if( sqlsrv_execute( $stmt ) === false ) {
          die( print_r( sqlsrv_errors(), true));
    }
	//mensaje en consola que indica exito!
	echo "Filegroup Creado correctamente! Yay!";

}	



//funcion que crea un nuevo file...
function NewFiles($bd,$fn,$ruta,$size,$max,$grogro,$fg){
	
	global $conn;
	//se utilizan la var global de conexion
	$SQL = "exec DiscosPlusPlus ?,?,?,?,?,?,?";
	//se prepara un array con los parametros por enviar al proc almacenado
	$stmt = sqlsrv_prepare( $conn, $SQL, array(&$bd,&$fn,&$ruta,&$size, 
								&$max, &$grogro, &$fg));
		// Execute query:
	if( sqlsrv_execute( $stmt ) === false ) {
          die( print_r( sqlsrv_errors(), true));
    }
	//mensaje en consola que indica exito!
	echo "Disco creado correctamente...";
}

/* funciones de moficacion variables según los parámetros enviados... */

function ModiFiles($bd,$fname,$nsize,$nmax,$ngrogro,$nfn){
	global $conn;
	//-----------------------------------------------------------
	//en caso de que solo se quiera cambiar el tamaño del archivo
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
	//en caso de que solo se quiera cambiar el tamaño max del archivo
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
	//en caso de que solo se quiera cambiar el growth del archivo
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
	//en caso de que solo se quiera cambiar el nombre lógico del archivo
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
	//si se ingresan todos los parámetros se ingresa al alterar todos que modifica todos los atributos del archivo
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
//funcion que retorna los valores necesarios para las estadísticas requeridas para este trabajo
//solamente se requiere del nombre del archivo a saber sus datos..
function KDA ($fileName){
	//vars globales de conexion y para retornar concatenando valores
	global $conn,$cmamo;
	//se hace el query que busca el tamaño del archivo en cuestión
	$SQL = "exec CaracSize ?";
	$stmt = sqlsrv_prepare( $conn, $SQL, array(&$fileName));
		// Execute query:
	if( sqlsrv_execute( $stmt ) === false ) {
          die( print_r( sqlsrv_errors(), true));
    }
	do {
		   while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
				//se concatena el resultado del query y se continua con el siguiente query
			   $cmamo = $cmamo . ",". $row['Total de MB'];
		   }
		} while (sqlsrv_next_result($stmt));
	//se hace el query que busca el growth del archivo en cuestión
	$SQL = "exec CaracGrowth ?";
	$stmt = sqlsrv_prepare( $conn, $SQL, array(&$fileName));
		// Execute query:
	if( sqlsrv_execute( $stmt ) === false ) {
          die( print_r( sqlsrv_errors(), true));
    }
	do {
		   while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
			   //se concatena el resultado del query y se continua con el siguiente query
			   $cmamo = $cmamo . ",". $row['Growth'];
		   }
		} while (sqlsrv_next_result($stmt));
	//se hace el query que busca el tamaño maximo del archivo en cuestión		
	$SQL = "exec CaracMax ?";
	$stmt = sqlsrv_prepare( $conn, $SQL, array(&$fileName));
		// Execute query:
	if( sqlsrv_execute( $stmt ) === false ) {
          die( print_r( sqlsrv_errors(), true));
    }
	do {
		   while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
			   //se concatena el resultado del query y se continua con el siguiente query
			   $cmamo = $cmamo . ",". $row['MB Max'];
		   }
		} while (sqlsrv_next_result($stmt));
		
	//se hace el query que busca el tamaño disponible del archivo en cuestión
	$SQL = "exec CaracDisp ?";
	$stmt = sqlsrv_prepare( $conn, $SQL, array(&$fileName));
		// Execute query:
	if( sqlsrv_execute( $stmt ) === false ) {
          die( print_r( sqlsrv_errors(), true));
    }
	do {
		   while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
			   //se concatena el resultado del query y se continua con el siguiente query
			   $cmamo = $cmamo . ",". $row['Total de MB desocupados'];
		   }
		} while (sqlsrv_next_result($stmt));
		
	//se hace el query que busca el tamaño usado del archivo en cuestión
	$SQL = "exec CaracUsa ?";
	$stmt = sqlsrv_prepare( $conn, $SQL, array(&$fileName));
		// Execute query:
	if( sqlsrv_execute( $stmt ) === false ) {
          die( print_r( sqlsrv_errors(), true));
    }
	do {
		   while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
			   //se concatena el resultado del query y se continua con el siguiente query
			   $cmamo = $cmamo . ",". $row['Total de MB ocupados'];
		   }
		} while (sqlsrv_next_result($stmt));
	
	return $cmamo;
	}
