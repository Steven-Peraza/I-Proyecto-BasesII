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
	document.getElementById('tabsPersona').style.display = "none";
	document.getElementById('tabInventario').style.display = "none";
	document.getElementById('tabFactura').style.display = "none";
	document.getElementById('tabProducto').style.display = "none";
    document.getElementById('consultitas').style.display = "none";
	cmbMarca('marcas');
    cmbMarca('marcasC1');
	cmbSector('sectores');
	cmbColor('colores');
    cmbColor('coloresC1');
	cmbProductos('CmbPro');
	cmbProductos('ComboProductos');
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

function cmbSector(cmbname){
	cambioEstado = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            cmbSectores=document.getElementById(cmbname);
            nodoPadre_sector=cmbSectores.parentNode;
            nodoPadre_sector.removeChild(cmbSectores);
            var nodoSelect = document.createElement("select");
            nodoSelect.id=cmbname;
            nodoPadre_sector.appendChild(nodoSelect);
            obj_sectores=eval('('+this.responseText+')');
            for (var i in obj_sectores)
            {
                var nodoOption = document.createElement("option");
                var textnode = document.createTextNode(obj_sectores[i].nombresector);
                nodoOption.value = obj_sectores[i].idsector;
                nodoOption.appendChild(textnode);
                nodoSelect.appendChild(nodoOption);
            }
        }
    };
	cmbAjax('cmbSectores',null, cambioEstado);
}

function cmbColor(cmbname){
	cambioEstado = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            cmbColor=document.getElementById(cmbname);
            nodoPadre_colors=cmbColor.parentNode;
            nodoPadre_colors.removeChild(cmbColor);
            var nodoSelect = document.createElement("select");
            nodoSelect.id=cmbname;
            nodoPadre_colors.appendChild(nodoSelect);
            obj_colors=eval('('+this.responseText+')');
            for (var i in obj_colors)
            {
                var nodoOption = document.createElement("option");
                var textnode = document.createTextNode(obj_colors[i].nombrecolor);
                nodoOption.value = obj_colors[i].idcolor;
                nodoOption.appendChild(textnode);
                nodoSelect.appendChild(nodoOption);
            }
        }
    };
	cmbAjax('cmbColor',null, cambioEstado);
}

function cmbProductos(cmbname){
	cambioEstado = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            cmbProdu=document.getElementById(cmbname);
            nodoPadre_produ=cmbProdu.parentNode;
            nodoPadre_produ.removeChild(cmbProdu);
            var nodoSelect = document.createElement("select");
            nodoSelect.id=cmbname;
            nodoPadre_produ.appendChild(nodoSelect);
            obj_produ=eval('('+this.responseText+')');
            for (var i in obj_produ)
            {
                var nodoOption = document.createElement("option");
                var textnode = document.createTextNode(obj_produ[i].descripcion);
                nodoOption.value = obj_produ[i].idproducto;
                nodoOption.appendChild(textnode);
                nodoSelect.appendChild(nodoOption);
            }
        }
    };
	cmbAjax('cmbProdu',null, cambioEstado);
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