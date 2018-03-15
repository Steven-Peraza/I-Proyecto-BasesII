<?php


function predeterminadaConexion(){
    $db = mysql_connect('localhost', 'sa', 'kezito123A')
        or die('No se pudo conectar: ' . mysql_error());
    
        echo 'Connected successfully';
        
        mysql_select_db('master', $db) or die('No se pudo seleccionar la base de datos');
}

function petidicionConectar($bd, $usuario, $pass)
{
    $db = mysql_connect('localhost', '$usuario', '$pass')
    or die('No se pudo conectar: ' . mysql_error());

    echo 'Connected successfully';
    
    mysql_select_db('$bd') or die('No se pudo seleccionar la base de datos');
}
?>