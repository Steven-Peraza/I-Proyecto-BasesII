<?php 
include 'conexion.php';

//Elegir si cargar la master o una base de datos especifica
if($_GET['bd']) && !empty($_GET['bd']){
    $db=petidicionConectar();
}else{
    $db = predeterminadaConexion();
}


if(isset($_GET['fun'])){
    if($_GET['fun'] == 'CmbBD'){
        $resultado = mysql_result(mysql_query("select * from sys.databases"),0);
        echo $resultado;
    }
}