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
            if(this.responseText != ""){
                conn = "on";
            }
            document.getElementById(idNotificacion).innerHTML = this.responseText;
        }
    };
    xhttp.open(tipo, url,true);
    xhttp.send(parametros);
}

function cmbAjax(url,parametros, cambioEstado)
{
    url = 'php/procedimientos.php?fun=' + url + "&usuario="+g_usu
                 +"&pass="+g_pass
                 +"&bd="+g_bd
                 +"&ip="+g_ip
                 +"&puerto="+g_puerto;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = cambioEstado;
    xhttp.open('GET',url,true);
    xhttp.send(parametros);
}


function borrarNotificaciones(idNotificacion)
{
    document.getElementById(idNotificacion).innerHTML = "";
}
