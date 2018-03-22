var g_bd;
var g_usu;
var g_pass;
var g_ip;
var g_puerto;

function logData(bd, usu, pass, ip, puerto){
    g_bd = bd;
    g_usu = usu;
    g_pass = pass;
    g_ip = ip;
    g_puerto = puerto;
}

function defaultPag(){
	document.getElementById('Esqueleto').style.display = "none"; //Para mostrar o no un div
}

function cmbBases(cmbname){
	cambioEstado = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            CmbBD=document.getElementById(cmbname);
            nodoPadre_marcas=cmbMarcas.parentNode;
            nodoPadre_marcas.removeChild(cmbMarcas);
            var nodoSelect = document.createElement("select");
            nodoSelect.id=cmbname;
            nodoPadre_marcas.appendChild(nodoSelect);
            obj_marcas= this.responseText.split(",");
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

function appearDiv(check) {
    var ch = document.getElementById(check);
    if(ch.checked){
    	document.getElementById(ch.value).style.display = "block";
    }else{
    	document.getElementById(ch.value).style.display = "none";
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