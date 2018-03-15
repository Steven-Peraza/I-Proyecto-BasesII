<?php 
include 'conexion.php';

$db=conectar();

if( isset($_GET['idusu']) && !empty($_GET['idusu']) &&
	isset($_GET['contusu']) && !empty($_GET['contusu'])
)
{
	if( isset($_GET['id']) && !empty($_GET['id']) &&
		isset($_GET['nombre']) && !empty($_GET['nombre']) &&
		isset($_GET['apellido1']) && !empty($_GET['apellido1']) &&
		isset($_GET['apellido2']) && !empty($_GET['apellido2']) &&
		isset($_GET['correo']) && !empty($_GET['correo']) &&
		isset($_GET['tipo']) && !empty($_GET['tipo']) &&
		isset($_GET['contra1']) && !empty($_GET['contra1']) &&
		isset($_GET['contra2']) && !empty($_GET['contra2']) &&
		isset($_GET['montoSalario']) && !empty($_GET['montoSalario']) &&
		isset($_GET['fechaPago']) && !empty($_GET['fechaPago']) &&
		isset($_GET['tipoPago']) && !empty($_GET['tipoPago']) &&
		isset($_GET['telefono']) && !empty($_GET['telefono'])
	)
	{
		modifPers($_GET['id'],$_GET['nombre'],$_GET['apellido1'],$_GET['apellido2'],$_GET['correo'],$_GET['tipo'],$_GET['contra1'],$_GET['contra2']);
	}
	else if( isset($_GET['idpro']) && !empty($_GET['idpro']) &&
			 isset($_GET['precio']) && !empty($_GET['precio']) &&
			 isset($_GET['desprodu']) && !empty($_GET['desprodu']) &&
			 isset($_GET['marca']) && !empty($_GET['marca']) &&
			 isset($_GET['sector']) && !empty($_GET['sector']) &&
			 isset($_GET['color']) && !empty($_GET['color']) &&
			 isset($_GET['gen']) && !empty($_GET['gen']) &&
			 isset($_GET['tipoProdu']) && !empty($_GET['tipoProdu']) &&
			 isset($_GET['talla']) && !empty($_GET['talla']) &&
			 isset($_GET['garantia']) && !empty($_GET['garantia'])
	)
	{
		modifProd($_GET['idpro'], $_GET['precio'], $_GET['desprodu'], $_GET['marca'], $_GET['sector'], $_GET['color'],$_GET['gen'], $_GET['tipoProdu'], $_GET['talla'], $_GET['garantia']);
	}
}
else
{
	echo "Por favor escriba su usuario en la parte superior";
}

function modifProd($v_idpro, $v_precio, $v_desPro, $v_marca, $v_sector, $v_color, $v_gen, $v_tipoprodu, $v_talla, $v_garantia)
{
	global $db;
	$transSent= $db->prepare("select actualizarproducto($v_idpro, $v_precio,'$v_desPro', $v_marca,'$v_garantia','$v_gen', $v_sector, $v_color,'$v_talla', $v_tipoprodu);");
	if ($transSent->execute())
    {
    echo "('error':0, 'mensaje':'El registro de producto se ha modificado satisfactoriamente')";
    }
	else
		{
    	$error=$transSent->errorInfo()[2];
    	echo "('error':1, 'mensaje':'$error'";
	}
}

function modifPers($p_id,$p_nombre,$p_apellido1,$p_apellido2,$p_correo,$p_tipo,$p_contra1, $p_contra2)
{
    global $db;
    if($p_contra1 == $p_contra2)
	{
		$transSent= $db->prepare("select actualizarpersona($p_id, '$p_contra1' ,'$p_nombre','$p_apellido1','$p_apellido2','$p_correo','$p_tipo');");
		if ($transSent->execute())
        {
        echo "('error':0, 'mensaje':'El registro de persona se ha modificado satisfactoriamente')";
        }
    	else
   		{
        	$error=$transSent->errorInfo()[2];
        	echo "('error':1, 'mensaje':'$error'";
    	}

	}
	else
	{
		echo "Las contraseñas no coinciden";
	}
}
?>