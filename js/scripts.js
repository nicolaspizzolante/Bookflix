const formBuscar= document.querySelector('#formulario-buscar');

eventListener();
function eventListener() {
    //inputBuscar.addEventListener('input', validarInputBuscar);
    formBuscar.addEventListener('submit', textoValido);
    document.querySelector('.lista-comentarios').addEventListener('click', borrarComentario);
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

function borrarComentario(e) {
    e.preventDefault();
    console.log(e.target.classList.contains('fa-trash'));
    if(e.target.classList.contains('fa-trash')) {
        swal({
            title: 'Seguro(a)?',
            text: "Esta acción no se puede deshacer",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, borrar!',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.value) {
                //traversing
                let comentarioEliminar= e.target.parentElement.parentElement.parentElement.parentElement.parentElement;
                //borrar de la base de datos
                eliminarComentarioDB(comentarioEliminar);
                //borrar del html
                //comentarioEliminar.remove();
                console.log(comentarioEliminar);
                
              swal(
                'Eliminado!',
                'La tarea fue eliminada!.',
                'success'
              )
            }
          })
    }
}

function eliminarComentarioDB(comentarioEliminar) {
    console.log(comentarioEliminar);
    let idComentario = comentarioEliminar.id.split(':');
    //crear llamado ajax
    let xhr = new XMLHttpRequest();

    //informacion 
    let datos = new FormData();
    datos.append('id', idComentario[1]);
    
    //abrir la conexion
    xhr.open('POST', 'eliminarComentario.php', true);

    //on load
    xhr.onload = function () {
        if(this.status === 200){
            console.log(JSON.parse(xhr.responseText));
            const respuesta= JSON.parse(xhr.responseText);
            let idLibro = respuesta.idLibro;
            if (respuesta.respuesta=== 'correcto') {
                setTimeout(() => {
                    window.location.href= `libro.php?id=${idLibro}`;
                }, 0);
            }
            
        }
    }

    //enviar la peticion
    xhr.send(datos);
}