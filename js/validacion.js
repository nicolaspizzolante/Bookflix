function tieneNumero(str) {
  return (/[0-9]/.test(str));
}

function soloNumeros(str){
	return (/^[0-9\s]+$/.test(str));
}

function validarPassword(pass){
	return pass.length >= 8 && tieneNumero(pass);
}

function validarLogin (form){
	if(form.email.value == '' || form.contrasenia.value == ''){
		alert ("Ingrese ambos datos.");
	  	return false;
	}
}

function validarPublicacion(form){
	if (form.texto.value.length == 0){
		alert("La publicacion debe tener al menos un caracter.");
		return false;
	}
	
	if(form.texto.value.length > 140) {
		alert("La publicacion no puede tener mas de 140 caracteres.");
		return false;
	}
	
	return true;
}

function validarCambioPass(form){
	var errores = '';
	
	if(!validarPassword(form.nueva_pass.value)){
		errores += '<li>La contraseña debe tener al menos 8 caracteres alfanumericos.</li>';
	}

	if(form.nueva_pass.value != form.repetir_pass.value){
		errores+= '<li>Las contraseñas deben coincidir.</li>';
	}
	
	if(errores){
		document.getElementById('errores').innerHTML = errores;
		document.getElementById('errores').style.display = 'block';
		return false;
	}
	
	return true;
}

function soloAlfabeticos(str){
	return (/^[A-Za-z\s]+$/.test(str));
}

function esAlfaNumerico(str){
	return (/^[A-Za-z0-9\s]+$/.test(str));
}

function validarRegistro(form){
	var errores = '';

	if(!form.email.value.includes("@")){
		errores += '<li>Ingrese una direccion de email valida.</li>';
	}

	if((form.nombre.value == '') || (!soloAlfabeticos(form.nombre.value))){
		errores += '<li>Ingrese un nombre valido.</li>';
	}

	if((form.apellido.value == '') || (!soloAlfabeticos(form.apellido.value))){
		errores += '<li>Ingrese un apellido valido.</li>';
	}

	if(!validarPassword(form.contrasenia.value)){
		errores += '<li>La contraseña debe tener al menos 8 caracteres alfanumericos y un numero.</li>';
	}

	if (form.contrasenia.value != form.confirmar_pass.value) {
		errores += '<li>Las contraseñas deben coincidir.</li>';
	}

	if (!soloNumeros(form.numero_tarjeta.value) || form.numero_tarjeta.value.length != 16){
		errores += '<li>Ingrese un numero de tarjeta válido</li>';
	}

	if (!soloNumeros(form.codigo_tarjeta.value) || form.codigo_tarjeta.value.length != 3) {
		errores += '<li>Ingrese un codigo de tarjeta válido</li>';
	}

	if (!soloNumeros(form.mes_vencimiento.value)
		|| form.mes_vencimiento.value < 1 
		|| form.mes_vencimiento.value > 12) {
		errores += '<li>Ingrese un mes de vencimiento válido</li>';
	}

	if (!soloNumeros(form.anio_vencimiento.value)
		|| form.anio_vencimiento.value.length > 2
		|| form.anio_vencimiento.value < 20) {
		errores += '<li>Ingrese un año de vencimiento válido</li>';
	}

	if ((form.nombre_tarjeta.value == '') || (!soloAlfabeticos(form.nombre_tarjeta.value))) {
		errores += '<li>Ingrese un nombre de tarjeta válido</li>';
	}
	
	if(errores){
		document.getElementById('errores').innerHTML = errores;
		document.getElementById('errores').style.display = 'block';
		return false;
	}

	return true;
}

function validarEditar(form){
	errores = '';

	if(!form.email.value.includes("@")){
		errores += '<li>Ingrese una direccion de email valida.</li>';
	}

	if((form.nombre.value == '') || (!soloAlfabeticos(form.nombre.value))){
		errores += '<li>Ingrese un nombre valido.</li>';
	}

	if((form.apellido.value == '') || (!soloAlfabeticos(form.apellido.value))){
		errores += '<li>Ingrese un apellido valido.</li>';
	}

	if(errores){
		document.getElementById('errores').innerHTML = errores;
		document.getElementById('errores').style.display = 'block';
		return false;
	}

	return true;
}

function validarEditarTarjeta(form){
	var errores = '';

	if (!soloNumeros(form.numeroTarjeta.value) || form.numeroTarjeta.value.length != 16){
		errores += '<li>Ingrese un numero de tarjeta válido</li>';
	}

	if (!soloNumeros(form.codigoTarjeta.value) || form.codigoTarjeta.value.length != 3) {
		errores += '<li>Ingrese un codigo de tarjeta válido</li>';
	}

	if (!soloNumeros(form.mes_vencimiento.value)
		|| form.mes_vencimiento.value < 1 
		|| form.mes_vencimiento.value > 12) {
		errores += '<li>Ingrese un mes de vencimiento válido</li>';
	}

	if (!soloNumeros(form.anio_vencimiento.value)
		|| form.anio_vencimiento.value.length > 2
		|| form.anio_vencimiento.value < 20) {
		errores += '<li>Ingrese un año de vencimiento válido</li>';
	}

	if ((form.nombreTarjeta.value == '') || (!soloAlfabeticos(form.nombreTarjeta.value))) {
		errores += '<li>Ingrese un nombre de tarjeta válido</li>';
	}
	
	if(errores){
		document.getElementById('errores').innerHTML = errores;
		document.getElementById('errores').style.display = 'block';
		return false;
	}

	return true;

}

function validarBusqueda(form){
	if (form.busqueda.value == ''){
		alert("Inserte al menos un caracter.");
		return false;
	}
	return true;
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth()+1),
        day = '' + d.getDate(),
		year = d.getFullYear(),
		hour = d.getHours(),
		minutes = d.getMinutes(),
		seconds = d.getSeconds();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
		day = '0' + day;
	if (hour.length < 2) 
		hour = '0' + hour;
	if (minutes.length < 2) 
		minutes = '0' + minutes;
	if (seconds.length < 2) 
		seconds = '0' + seconds;
	
	aux = [year, month, day].join('-');
	aux = aux + 'T' +[hour,minutes,seconds].join(':');
	return aux;
}


function validarMetadatos(form){
	var errores = '';

	if(form.titulo.value == ''){
		errores += "<li>El título del libro no puede estar vacio.</li>"
	}

	if ((!soloNumeros(form.isbn.value)) || (form.isbn.value == '') || !((form.isbn.value.length <= 13) && form.isbn.value.length >=10)){
		errores += "<li>Ingrese un ISBN valido.</li>"
	}

	if((form.autor.value == '') || (form.genero.value == '') || (form.editorial.value == '')){
		errores += "<li>Los campos genero, autor y editorial no pueden estar vacios.</li>"
	}

	if(form.sinopsis.value == ''){
		errores += "<li>La sinopsis del libro no puede ser vacia.</li>"
	}

	if (errores) {
		document.getElementById('errores').innerHTML = errores;
		document.getElementById('errores').style.display = 'block';
		return false;
	}

	return true;
}

function validarNovedad(form){
	var errores = '';

	if((form.titulo.value == '') || (form.descripcion.value == '')){
		errores += "<li>Los campos titulo y descripcion no pueden estar vacios.</li>"
	}
	
	if (errores) {
		document.getElementById('errores').innerHTML = errores;
		document.getElementById('errores').style.display = 'block';
		return false;
	}

	return true;
}

function validarLibro(form){
	var errores = '';

	if(form.pdf.value == ''){
		errores += "<li>Ingrese un archivo pdf</li>"
	}else{
		if(form.pdf.value.substring(form.pdf.value.length-3,form.pdf.value.length) != 'pdf'){
			errores += "<li>El tipo del archivo debe ser pdf</li>"
		}
	}

	if(form.fechaPublicacion.value == ''){
		errores += "<li>Ingrese una fecha de publicacion</li>"
	}else{
		actual = new Date();
		console.log(actual);
		console.log(formatDate(actual));
		console.log(form.fechaPublicacion.value);
		if(form.fechaPublicacion.value < formatDate(actual)){
			errores += "<li>La fecha de publicacion debe ser posterior o igual a la actual</li>"
		}
	}

	if((form.fechaVencimiento.value != '') && ((form.fechaVencimiento.value < formatDate(new Date())) || (form.fechaVencimiento.value <= form.fechaPublicacion.value))){
		errores += "<li>La fecha de vencimiento debe ser posterior a fecha de publicacion y a la fecha actual</li>"
	}

	if (errores) {
		document.getElementById('errores').innerHTML = errores;
		document.getElementById('errores').style.display = 'block';
		return false;
	}
	return true;
}