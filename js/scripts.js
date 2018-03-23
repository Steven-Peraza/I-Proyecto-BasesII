//Datos con los que comienza por defecto el usuario
function defaultPag(){
    document.getElementById('Esqueleto').style.display = "none"; //Para mostrar o no un div
    document.getElementById('CrearArchivos').style.display = "none";
    document.getElementById('FGS').style.display = "none";
    
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
             document.getElementById(v_div).style.display = "none";
        }else{
            document.getElementById(v_div).style.display = "block";
        }
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
