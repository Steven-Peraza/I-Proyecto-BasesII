<?php


function predeterminadaConexion(){
    $db = mysql_connect('172.24.74.40', 'sa', 'kezito123A')
        or die('No se pudo conectar: ' . mysql_error());
    
        echo 'Connected successfully';
        
        mysql_select_db('master', $db) or die('No se pudo seleccionar la base de datos');
}

function petidicionConectar()
{
    isset(  $_GET['usuario']) && !empty($_GET['usuario']
            $_GET['pass']) && !empty($_GET['pass']
            $_GET['bd']) && !empty($_GET['bd']
            
    ){
        $db = mysql_connect('172.24.74.40', 'usuario', 'pass')
        or die('No se pudo conectar: ' . mysql_error());
    
        echo 'Connected successfully';
        
        mysql_select_db('bd') or die('No se pudo seleccionar la base de datos');

    }
}
?>