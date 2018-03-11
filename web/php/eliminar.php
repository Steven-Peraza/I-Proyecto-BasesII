<?php  
include 'conexion.php';

$db=conectar();

if( isset($_GET['idusu']) && !empty($_GET['idusu']) &&
	isset($_GET['contusu']) && !empty($_GET['contusu'])
)
{
	if( issert($_GET['id']) && !empty($_GET['id']) ||
		issert($_GET['idInv']) && !empty($_GET['idInv']) ||
		issert($_GET['idpro']) && !empty($_GET['idpro'])
	)
	{
		if(issert($_GET['op']) && !empty($_GET['op']))
		{
			if($_GET['op'] == "Prod")
			{
				elimProd($_GET['idpro']);
			}
			else if($_GET['op'] == "Pers")
			{
				elimPers($_GET['id']);
			}
			else if($_GET['op'] == "Inve")
			{
				elimInve($_GET['idInv']);
			}
		}
	}
	else
	{
		echo "Faltan datos";
	}
}
else
{
	echo "Por favor escriba su usuario en la parte superior";
}

function elimProd($id){
	$transSent= $db->prepare("elimproducto($id);");
	if ($transSent->execute())
    {
    echo "('error':0, 'mensaje':'registro Eliminado')";
    }
	else
		{
    	$error=$transSent->errorInfo()[2];
    	echo "('error':1, 'mensaje':'$error'";
	}
}

function elimPers($id){
	$transSent= $db->prepare("elimpersona($id);");
	if ($transSent->execute())
    {
    echo "('error':0, 'mensaje':'registro Eliminado')";
    }
	else
		{
    	$error=$transSent->errorInfo()[2];
    	echo "('error':1, 'mensaje':'$error'";
	}
}

function elimInve($id){
	$transSent1= $db->prepare("eliminveprodu($id);");
	$transSent2= $db->prepare("eliminven($id);");
	if ($transSent1->execute())
    {
    echo "('error':0, 'mensaje':'registros inveprodu Eliminados')";
    }
	else
		{
    	$error=$transSent->errorInfo()[2];
    	echo "('error':1, 'mensaje':'$error'";
	}
	if($transSent2->execute())
	{
		echo "('error':0, 'mensaje':'registro inve Eliminado')";
    }
	else
	{
    	$error=$transSent->errorInfo()[2];
    	echo "('error':1, 'mensaje':'$error'";
	}
}
?>