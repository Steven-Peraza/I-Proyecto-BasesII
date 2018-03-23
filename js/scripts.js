//Datos con los que comienza por defecto el usuario
function defaultPag(){
    document.getElementById('CrearArchivos').style.display = "none";
    document.getElementById('FGS').style.display = "none";
    document.getElementById('ModificarArchivos').style.display = "none";

}

//Disaparecer el cuadro de logeo despues de logearse
function logDisapper(v_div){
    if(true == true){
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

function fillGrafic(cmbname){
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
            grafic(cmbname, obj_marcas[0], obj_marcas[2], obj_marcas[4], obj_marcas[3], obj_marcas[1]);
        }
    };
	getAjax(cmbname,null, cambioEstado);
}

function grafic(gfname, tamAct, tamMax, tamUso, tamDis, grogro) {
        chart = new CanvasJS.Chart("grafic", {
            animationEnabled: true,
            title:{
                text: "Graficos del Archivo" + gfname + "MB",
                horizontalAlign: "center"
            },/*
            subtitles:[{
                text1: "Tamaño máximo: " + tamMax + "MB \n"
                      +"Espacio usado: " + tamUso + "MB \n"
                      +"Espacio disponible: " + tamDis + "MB \n"
                      +"Crecimiento: " + grogro + "MB",
                horizontalAlign: "left",
            }],*/
			 subtitles:[
				{ //OJO
					text: "Tamaño Actual: "+ tamUso + "MB",
					horizontalAlign: "left"
				//Uncomment properties below to see how they behave
				//fontColor: "red",
				//fontSize: 30
				},
				 
				{
					text: "Tamaño Maximo: "+ tamMax + "MB",
					horizontalAlign: "left"
					//Uncomment properties below to see how they behave
					//fontColor: "red",
					//fontSize: 30
				},
				{
					text: "Crecimiento: "+ grogro + "MB",
					horizontalAlign: "left"
					//Uncomment properties below to see how they behave
					//fontColor: "red",
					//fontSize: 30
				}
				],
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
                    { y: parseInt(tamUso), label: "Espacio Usado" },
                    { y: parseInt(tamDis), label: "Espacio Disponible" }
                ]
            }]
        });
        chart.render();
}

function update(gfname, tamMax, tamUso, tamDis, grogro){
    var x = setInterval(function() {
        grafic(gfname, tamMax, tamUso, tamDis, grogro);
    }, 12000);
}