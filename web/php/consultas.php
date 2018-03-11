<?php 
include 'conexion.php';

$db=conectar();

if( isset($_GET['idusu']) && !empty($_GET['idusu']) &&
	isset($_GET['contusu']) && !empty($_GET['contusu'])
)
{
	if(isset($_GET['op']) && !empty($_GET['op']))
	{
		if( isset($_GET['marca']) && !empty($_GET['marca']) &&
			isset($_GET['color']) && !empty($_GET['color']) &&
			isset($_GET['gen']) && !empty($_GET['gen']) && 
			$_GET['op'] == "C1";
		)
		{
			consulta1($_GET['marca'],$_GET['color'],$_GET['gen']);
		}
		else if(isset($_GET['monto']) && !empty($_GET['monto']) && 
			$_GET['op'] == "C2"
		)
		{
			consulta2($_GET['monto']);
		}
		else if(isset($_GET['nombre']) && !empty($_GET['nombre']) && 
			$_GET['op'] == "C3"
		)
		{
			consulta3($_GET['nombre']);
		}
		else if($_GET['op'] == "C4")
		{
			consulta4();
		}
		else if($_GET['op'] == "C5")
		{
			consulta5();
		}
	}
}
else
{
	echo "Por favor escriba su usuario en la parte superior";
}

function consulta1($marca, $color, $gen)
{
	global $db;
    $res = $db->query("SELECT * FROM consulta1('$marca', '$color', '$gen');");
    $result=$res->fetchALL(PDO::FETCH_ASSOC);
    if (!$result)
        $result=[];
    echo json_encode($result);
}

function consulta2($monto)
{
	global $db;
    $res = $db->query("SELECT * FROM consulta2($monto);");
    $result=$res->fetchALL(PDO::FETCH_ASSOC);
    if (!$result)
        $result=[];
    echo json_encode($result);
}

function consulta3($nombre)
{
	global $db;
    $res = $db->query("SELECT * FROM consulta3('$nombre');");
    $result=$res->fetchALL(PDO::FETCH_ASSOC);
    if (!$result)
        $result=[];
    echo json_encode($result);
}

function consulta4()
{
	global $db;
    $res = $db->query("SELECT * FROM consulta4();");
    $result=$res->fetchALL(PDO::FETCH_ASSOC);
    if (!$result)
        $result=[];
    echo json_encode($result);
}

function consulta5()
{
	global $db;
    $res = $db->query("SELECT * FROM consulta5();");
    $result=$res->fetchALL(PDO::FETCH_ASSOC);
    if (!$result)
        $result=[];
    echo json_encode($result);
}

?>