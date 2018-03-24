//Crear una conexion con el php para realizar las funciones
function getAjax(url,tipo,parametros,idNotificacion)
{
    borrarNotificaciones(idNotificacion);
    var usuario = "&usuario="+document.getElementById('idusu').value
                 +"&pass="+document.getElementById('contraUsu').value
                 +"&bd="+document.getElementById('CmbBD').value;
    url = 'php/procedimientos.php?' + url + usuario;
    logMsj();

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
    {
        if(this.readyState == 4 && this.status == 200)                                 
        {
            
            document.getElementById(idNotificacion).innerHTML = this.responseText;
        }
    };
    xhttp.open(tipo, url,true);
    xhttp.send(parametros);
}


//Crear una conexion con el php para llenar los combos de datos
function cmbAjax(url,parametros, cambioEstado)
{
    url = 'php/procedimientos.php?fun=' + url + "&usuario="+document.getElementById('idusu').value
                 +"&pass="+document.getElementById('contraUsu').value
                 +"&bd="+document.getElementById('CmbBD').value
                 +"&ip="+document.getElementById('ip').value
                 +"&puerto="+document.getElementById('puerto').value;
    logMsj();
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = cambioEstado;
    xhttp.open('GET',url,true);
    xhttp.send(parametros);
}

//Borrar la informacion que tenga la "consola", es decir, el parrafo llamado notificacarC
function borrarNotificaciones(idNotificacion)
{
    document.getElementById(idNotificacion).innerHTML = "";
}

//Actualizar con la base de datos y usuario ingresado actualmente
function logMsj(){
     document.getElementById('cabeza').innerHTML = "Usuario: "+ document.getElementById('idusu').value 
                                                 +" IP: "+document.getElementById('ip').value
                                                 +":"+document.getElementById('puerto').value
                                                 +" Base de Datos: " + document.getElementById('CmbBD').value;
}