/************************************************************************************
								start: imprimir correu
*************************************************************************************/

usuario="info"
dominio="omniasolutions.es"
conector="@"
function dame_correo(){
	return usuario + conector + dominio
}
function escribe_enlace_correo(){
	document.write("<a href='mailto: " + dame_correo() + "'>" + 'info@omniasolutions.es' + "</a>")
}
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
/************************************************************************************
								end: imprimir correu
*************************************************************************************/

/************************************************************************************
								start: imprimir any
*************************************************************************************/

function any(){
	today = new Date();
  	start = new Date(today.getFullYear(),00,01);
  	document.write(today.getFullYear())
}
/************************************************************************************
								end: imprimir any
*************************************************************************************/

/************************************************************************************
                                                    start: mostrar i ocultar divs
*************************************************************************************/
	
function mostrarOcultar(id) {
	var elemento = document.getElementById(id);

	var enlace = document.getElementById('meta');

	if(elemento.style.display == "" || elemento.style.display == "none") {
		elemento.style.display = "block";
		enlace.innerHTML = 'Contenido de metadatos <button type="button" class="close" onClick="mostrarOcultar(\'content_metas\'); return false;"><i class="icon-btn-arrow-toggle2"></i></button>';
	}
	else {
		elemento.style.display = "none";
		enlace.innerHTML = 'Contenido de metadatos <button type="button" class="close" onClick="mostrarOcultar(\'content_metas\'); return false;"><i class="icon-btn-arrow-toggle"></i></button>';
	}
}

function mostrarOcultarLegal(id) {
	
	var elemento = document.getElementById(id);

	var enlaceLegal = document.getElementById('legal');

	if(elemento.style.display == "" || elemento.style.display == "none") {
		elemento.style.display = "block";
		enlaceLegal.innerHTML = 'Texto Legal<button type="button" class="close" onClick="mostrarOcultarLegal(\'content_metas\'); return false;"><i class="icon-btn-arrow-toggle2"></i></button>';
	}else {
			
		elemento.style.display = "none";
		enlaceLegal.innerHTML = 'Texto Legal <button type="button" class="close" onClick="mostrarOcultarLegal(\'content_metas\'); return false;"><i class="icon-btn-arrow-toggle"></i></button>';
        }
	
}


/************************************************************************************
						      end: mostrar i ocultar divs
*************************************************************************************/
function disabledChecbox(){
	var element = document.getElementById('noticia');
	
	var element2 = document.getElementById('seccions');
	
	if(element.checked){
		element2.style.display='none';
		return false;
	}else{
		element2.style.display='block';
	}
}