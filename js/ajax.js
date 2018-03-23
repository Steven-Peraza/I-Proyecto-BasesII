function getAjax(url,tipo,parametros,idNotificacion)
{
	console.log("quiero dormir... XD");
    borrarNotificaciones(idNotificacion);
    var usuario = "&usuario="+document.getElementById('idusu').value
                 +"&pass="+document.getElementById('contraUsu').value
                 +"&bd="+document.getElementById('CmbBD').value;
    url = 'php/procedimientos.php?' + url + usuario;
	console.log(url);
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
	console.log("gg ajax");
}

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


function borrarNotificaciones(idNotificacion)
{
    document.getElementById(idNotificacion).innerHTML = "";
}

function logMsj(){
     document.getElementById('cabeza').innerHTML = "Usuario: "+ document.getElementById('idusu').value 
                                                 +" IP: "+document.getElementById('ip').value
                                                 +":"+document.getElementById('puerto').value
                                                 +" Base de Datos: " + document.getElementById('CmbBD').value;
}