const formBuscar= document.querySelector('#formulario-buscar');

eventListener();
function eventListener() {
    //inputBuscar.addEventListener('input', validarInputBuscar);
    formBuscar.addEventListener('submit', textoValido);
}

function textoValido(e){
    if ( $("#buscar").val().trim().length === 0 ) {
        e.preventDefault();
        swal({
            title: 'Error',
            text: 'El campo contiene espacios y está vacío',
            type: 'error'
        })
      }
    
}