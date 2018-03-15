<?php 
include 'conexion.php';

//Elegir si cargar la master o una base de datos especifica
if( isset($_GET['bd']) && !empty($_GET['bd'])
){
    if(	isset($_GET['usuario']) && !empty($_GET['usuario']) &&
    	isset($_GET['pass']) && !empty($_GET['pass'])
    ){
    	$db=petidicionConectar();
    }else{
    	echo "Introduzca un usuario y contraseña"
    }
}else{
    $db = predeterminadaConexion();
}


if(isset($_GET['fun'])){
    if($_GET['fun'] == 'CmbBD'){
        $resultado = mysql_result(mysql_query("select * from sys.databases"),0);
        echo $resultado;
    }
}