function getAjax(url,tipo,parametros,idNotificacion)
{
    borrarNotificaciones(idNotificacion);
    var usuario = "&usuario="+document.getElementById('idusu').value
                 +"&pass="+document.getElementById('contraUsu').value
                 +"&bd="+g_bd;
    url = 'php/procedimientos.php?' + url + usuario;
    document.getElementById(idNotificacion).innerHTML = url;
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

function cmbAjax(url,parametros, cambioEstado)
{
    url = 'php/procedimientos.php?fun=' + url + "&usuario="+document.getElementById('idusu').value
                 +"&pass="+document.getElementById('contraUsu').value
                 +"&bd="+document.getElementById('CmbBD').value
				 +"&usuario="+document.getElementById('idusu').value
                 +"&ip="+document.getElementById('ip').value
                 +"&puerto="+document.getElementById('puerto').value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = cambioEstado;
    xhttp.open('GET',url,true);
    xhttp.send(parametros);
}


function borrarNotificaciones(idNotificacion)
{
    document.getElementById(idNotificacion).innerHTML = "";
}
