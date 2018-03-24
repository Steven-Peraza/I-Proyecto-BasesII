var x;
var flag = "";

//Datos con los que comienza por defecto el usuario
function defaultPag(){
    document.getElementById('CrearArchivos').style.display = "none";
    document.getElementById('FGS').style.display = "none";
    document.getElementById('ModificarArchivos').style.display = "none";
    document.getElementById('Graficos').style.display = "none";
}

function updateCombos(){
    cmbBases('CmbFGS');
    cmbBases('CmbFGSM');
    cmbBases('CmbArch');
    cmbBases('CmbArchM');

}

//Disaparecer el cuadro de logeo despues de logearse
function logDisapper(v_div){
    if(flag != "Conexion Completa"){
        document.getElementById(v_div).style.display = "none";
    }else{
        document.getElementById(v_div).style.display = "block";
    }
}

function appearDiv(v_div){
    var divs = document.getElementsByName('divCam');
    for (var i = 0; i < divs.length; i++) {
        if(divs[i].id != v_div){
             document.getElementById(divs[i].id).style.display = "none";
        }else{
            document.getElementById(v_div).style.display = "block";
        }
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
            obj_marcas=this.responseText.split(",");
            for (var i = 1; i < obj_marcas.length; i++)
            {
                var nodoOption = document.createElement("option");
                var textnode = document.createTextNode(obj_marcas[i]);
                nodoOption.value = obj_marcas[i];
                nodoOption.appendChild(textnode);
                nodoSelect.appendChild(nodoOption);
            }
			flag = obj_marcas[0];
			console.log(flag);
        }
    };
	cmbAjax(cmbname,null, cambioEstado);
}


function fillGrafic(cmbname,fun){
	cambioEstado = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            obj_marcas=this.responseText.split(",");
			console.log(this.responseText);
			clearInterval(x);
            grafic(cmbname, parseFloat(obj_marcas[1]), parseFloat(obj_marcas[3]), parseFloat(obj_marcas[5]), 
					parseFloat(obj_marcas[4]), parseFloat(obj_marcas[2]));
            update(cmbname, parseFloat(obj_marcas[1]), parseFloat(obj_marcas[3]), parseFloat(obj_marcas[5]), 
                    parseFloat(obj_marcas[4]), parseFloat(obj_marcas[2]));
        }
    };
	cmbAjax(fun,null, cambioEstado);
}

function grafic(gfname, tamAct, tamMax, tamUso, tamDis, grogro) {
        chart = new CanvasJS.Chart("grafic", {
            animationEnabled: true,
            title:{
                text: "Graficos del Archivo " + gfname,
                horizontalAlign: "center"
            },
			 subtitles:[
				{
					text: "Tamaño Actual: "+ tamAct+ "MB",
					horizontalAlign: "left",
					fontSize: 15
				},
				 
				{
					text: "Tamaño Maximo: "+ tamMax + "MB",
					horizontalAlign: "left",
					fontSize: 15
				},
				{
					text: "Crecimiento: "+ grogro + "MB",
					horizontalAlign: "left",
					fontSize: 15
				}
				],
            data: [{
                type: "doughnut",
                startAngle: 60,
                radius: "90%",
                innerRadius: "55%",
                indexLabelFontSize: 17,
                explodeOnClick: true,
                indexLabel: "{label} - #percent%",
                toolTipContent: "<b>{label}:</b> {y} (#percent%)",
                dataPoints: [
                    { y: tamUso, label: "Espacio Usado" },
                    { y: tamDis, label: "Espacio Disponible" }
                ]
            }]
        });
        chart.render();
}

function update(gfname, tamMax, tamUso, tamDis, grogro){
    x = setInterval(function() {
        grafic(gfname, tamMax, tamUso, tamDis, grogro);
    }, 12000);
}