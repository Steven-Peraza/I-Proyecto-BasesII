<?php 
include 'conexion.php';

$db=conectar();

if(isset($_GET['fun'])){
	if($_GET['fun'] == "cmbMarcas"){
		global $db;
	    $res = $db->query("SELECT * FROM marca order by idmarca;");
	    $result=$res->fetchALL(PDO::FETCH_ASSOC);
	    if (!$result)
	        $result=[];
	    echo json_encode($result);
	}
	else if($_GET['fun'] == "cmbColor"){
		global $db;
	    $res = $db->query("SELECT * FROM color order by idcolor;");
	    $result=$res->fetchALL(PDO::FETCH_ASSOC);
	    if (!$result)
	        $result=[];
	    echo json_encode($result);
	}
	else if($_GET['fun'] == "cmbSectores"){
		global $db;
	    $res = $db->query("SELECT * FROM sector order by idsector;");
	    $result=$res->fetchALL(PDO::FETCH_ASSOC);
	    if (!$result)
	        $result=[];
	    echo json_encode($result);
	}
	else if($_GET['fun'] == "cmbProdu"){
		global $db;
	    $res = $db->query("SELECT * FROM producto order by idproducto;");
	    $result=$res->fetchALL(PDO::FETCH_ASSOC);
	    if (!$result)
	        $result=[];
	    echo json_encode($result);
	}
	else
	{
    	$error=$transSent->errorInfo()[2];
    	echo "('error':1, 'mensaje':'$error'";
	}
}

?>