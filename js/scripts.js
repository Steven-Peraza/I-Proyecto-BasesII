var trans;
function camCheck(cb1, cb2, cb3)
{
	var des1 = document.getElementById(cb1);
	var des2 = document.getElementById(cb2);
	var des3 = document.getElementById(cb3);

	des1.checked = false;
	des2.checked = false;
	des3.checked = false;
}

function defaultPag(){
	document.getElementById('Esqueleto').style.display = "none"; //Para mostrar o no un div
	cmbBases('CmbBD');
    grafic();
    update();
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
            obj_marcas=eval('('+this.responseText+')');
            for (var i in obj_marcas)
            {
                var nodoOption = document.createElement("option");
                var textnode = document.createTextNode(obj_marcas[i].nombremarca);
                nodoOption.value = obj_marcas[i].idmarca;
                nodoOption.appendChild(textnode);
                nodoSelect.appendChild(nodoOption);
            }
        }
    };
	cmbAjax('cmbBD',null, cambioEstado);
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