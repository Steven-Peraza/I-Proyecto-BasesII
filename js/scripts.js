var g_bd = "";
var g_usu = "";
var g_pass = "";
var g_ip = "";
var g_puerto = "";
var conn = "on";

//Datos con los que comienza por defecto el usuario
function defaultPag(){
    document.getElementById('Esqueleto').style.display = "none"; //Para mostrar o no un div
    document.getElementById('CrearArchivos').style.display = "none";
}

//Guardar los datos de logeo globalmente
function logData(bd, usu, pass, ip, puerto){
    if(bd != null){
        g_bd = bd;
		console.log(bd);
    }
    if(usu != null){ 
        g_usu = usu;
    }
    if(pass != null){ 
        g_pass = pass;
    }
    if(ip != null){ 
        g_ip = ip;
    }
    if(puerto != null){ 
        g_puerto = puerto;
    }
    document.getElementById('cabeza').innerHTML = "Usuario: " + g_usu + " IP: " + ":" + g_ip + g_puerto + " Base de Datos: " + g_bd;
}

//Disaparecer el cuadro de logeo despues de logearse
function logDisapper(v_div){
    if(conn == "on"){
        document.getElementById(v_div).style.display = "none";
    }else{
        document.getElementById(v_div).style.display = "block";
    }
}

function appearDiv(v_div){
    var ch = document.getElementById(v_div);
    var btns = document.getElementsByName('');
    if(ch.checked && texto != "Consultas"){
        for (var i = 0; i < btns.length; i++) {
            btns[i].innerHTML = texto;
        }
        trans = texto;
    }
}


function camBtn(check, texto){
    var ch = document.getElementById(check);
    var btns = document.getElementsByName('btn');
    if(ch.checked && texto != "Consultas"){
        for (var i = 0; i < btns.length; i++) {
            btns[i].innerHTML = texto;
        }
        trans = texto;
    }   
}


function cmbBases(cmbname){
	cambioEstado = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            CmbBDs=document.getElementById(cmbname);
            nodoPadre_marcas=CmbBDs.parentNode;
            nodoPadre_marcas.removeChild(CmbBDs);
            var nodoSelect = document.createElement("select");
            nodoSelect.id=cmbname;
            nodoPadre_marcas.appendChild(nodoSelect);
			//document.getElementById('notificarC').innerHTML = this.responseText;
            obj_marcas=this.responseText.split(",");
            for (var i in obj_marcas)
            {
                var nodoOption = document.createElement("option");
                var textnode = document.createTextNode(obj_marcas[i]);
                nodoOption.value = obj_marcas[i];
                nodoOption.appendChild(textnode);
                nodoSelect.appendChild(nodoOption);
            }
        }
    };
	cmbAjax(cmbname,null, cambioEstado);
}

function grafic() {
        chart = new CanvasJS.Chart("grafic", {
            animationEnabled: true,
            title:{
                text: "Espacio del disco",
                horizontalAlign: "center"
            },
            data: [{
                type: "doughnut",
                startAngle: 60,
                radius: "90%",
                innerRadius: "55%",
                indexLabelFontSize: 17,
                explodeOnClick: false,
                indexLabel: "{label} - #percent%",
                toolTipContent: "<b>{label}:</b> {y} (#percent%)",
                dataPoints: [
                    { y: 4096, label: "Espacio Usado" },
                    { y: 1024, label: "Espacio Disponible" }
                ]
            }]
        });
        chart.render();
}

function update(){
    var x = setInterval(function() {
        grafic();
    }, 12000);
}
