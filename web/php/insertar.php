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
		insertarPersona($_GET['id'],$_GET['nombre'],$_GET['apellido1'],$_GET['apellido2'],$_GET['correo'],$_GET['tipo'],$_GET['contra1'],$_GET['contra2'],$_GET['montoSalario'],$_GET['fechaPago'],$_GET['tipoPago'],$_GET['telefono']);
	}
	else if( isset($_GET['idInv']) && !empty($_GET['idInv']) &&
			 isset($_GET['fechaReg']) && !empty($_GET['fechaReg']) &&
			 isset($_GET['idPro']) && !empty($_GET['idPro']) &&
			 isset($_GET['cant']) && !empty($_GET['cant'])
	)
	{
		insertarInventario($_GET['idusu'],$_GET['idInv'],$_GET['fechaReg'],$_GET['idPro'],$_GET['cant']);
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
		insertarProducto($_GET['idpro'], $_GET['precio'], $_GET['desprodu'], $_GET['marca'], $_GET['sector'], $_GET['color'],$_GET['gen'], $_GET['tipoProdu'], $_GET['talla'], $_GET['garantia']);
	}
	else if( isset($_GET['idFac']) && !empty($_GET['idFac']) &&
			 isset($_GET['fechaFac']) && !empty($_GET['fechaFac']) &&
			 isset($_GET['detalle']) && !empty($_GET['detalle']) &&
			 isset($_GET['idprod']) && !empty($_GET['idprod']) &&
			 isset($_GET['monto']) && !empty($_GET['monto']) &&
			 isset($_GET['cliente']) && !empty($_GET['cliente']) &&
			 isset($_GET['cantFac']) && !empty($_GET['cantFac'])
	)
	{
		insertarFactura($_GET['idFac'],$_GET['idusu'],$_GET['fechaFac'],$_GET['idprod'],$_GET['detalle'],$_GET['monto'],$_GET['cliente'],$_GET['cantFac']);
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

function insertarPersona($p_id,$p_nombre,$p_apellido1,$p_apellido2,$p_correo,$p_tipo,$p_contra1, $p_contra2,$s_monto,$s_fechaP,$s_tipoP,$t_telefono)
{
    global $db;
    if($p_contra1 == $p_contra2)
	{
		$transSent= $db->prepare("select InsertPersona($p_id,'$p_contra1', '$p_nombre','$p_apellido1','$p_apellido2','$p_correo','$p_tipo','$t_telefono', $s_tipoP, $s_monto, '$s_fechaP');");
		if ($transSent->execute())
        {
        echo "('error':0, 'mensaje':'El registro de persona se ha insertado satisfactoriamente')";
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

function insertarInventario($i_id,$i_idinv,$i_fechaR,$ip_idpro,$ip_cant)
{
    global $db;
	$transSent= $db->prepare("select insertInvPro($i_idinv, $i_id,'$i_fechaR',$ip_idpro,$ip_cant);");
	if ($transSent->execute())
    {
    echo "('error':0, 'mensaje':'El registro de inventario se ha insertado satisfactoriamente')";
    }
	else
		{
    	$error=$transSent->errorInfo()[2];
    	echo "('error':1, 'mensaje':'$error'";
	}
}

function insertarProducto($v_idpro, $v_precio, $v_desPro, $v_marca, $v_sector, $v_color, $v_gen, $v_tipoprodu, $v_talla, $v_garantia)
{
	global $db;
	$transSent= $db->prepare("select inserproducto($v_idpro, $v_precio,'$v_desPro', $v_marca,'$v_garantia','$v_gen', $v_sector, $v_color,'$v_talla', $v_tipoprodu);");
	if ($transSent->execute())
    {
    echo "('error':0, 'mensaje':'El registro de producto se ha insertado satisfactoriamente')";
    }
	else
		{
    	$error=$transSent->errorInfo()[2];
    	echo "('error':1, 'mensaje':'$error'";
	}
}

function insertarFactura($v_idFac,$v_per,$v_fechaFac,$v_idprod,$v_detalle,$v_monto,$v_cliente, $v_cant)
{
	global $db;
	$transSent= $db->prepare("select insertarFac($v_idFac,$v_per,'$v_fechaFac',$v_idprod,'$v_detalle',$v_monto,'$v_cliente', $v_cant);");
	if ($transSent->execute())
    {
    echo "('error':0, 'mensaje':'El registro de factura se ha insertado satisfactoriamente')";
    }
	else
		{
    	$error=$transSent->errorInfo()[2];
    	echo "('error':1, 'mensaje':'$error'";
	}
}
?>