function tieneNumero(str) {
  return (/[0-9]/.test(str));
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
		errores += '<li>La contraseña debe tener al menos 8 caracteres alfanumericos.</li>';
	}

	if (form.contrasenia.value != form.confirmar_pass.value) {
		errores += '<li>Las contraseñas deben coincidir.</li>'
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

function validarBusqueda(form){
	if (form.busqueda.value == ''){
		alert("Inserte al menos un caracter.");
		return false;
	}
	return true;
}
