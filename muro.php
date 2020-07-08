<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}

	include 'views/header.php';
?>

<div class="container">
	<!-- Datos del usuario loggeado -->
	
	<div class="datos"><?php echo "Datos personales"; ?></div>

	<table class="table table-striped table-dark">
	<tbody>
	<tr>
      <td>Nombre</td>
      <td><p><?php echo $_SESSION['usuario']['nombre'];?></p></td>
	</tr>
	<tr>
      <td>Apellido</td>
      <td><p><?php echo $_SESSION['usuario']['apellido'];?></p></td>
    </tr>
    <tr>
      <td>Email</td>
      <td><p><?php echo $_SESSION['usuario']['email'];?></p></td>
    </tr>
    <tr>
      <td>Contraseña</td>
      <td><p>**********</p></td>
    </tr>
 	</tbody>
	</table>

<form action="editar.php">
<div class="editar-perfil">
	<input type="submit" value="Editar perfil">
</div>
</form>

<div class="borrar-cuenta">
<?php if(!$autenticador->esAdmin()){?>
	<button id="btn-borrar">Eliminar cuenta</button>
<?php }?>
</div>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
var idUsuario = <?php echo $_SESSION['usuario']['id']?>;

$("#btn-borrar").on("click", function(){
	Swal.fire({
		title: '¿Seguro desea eliminar su cuenta?',
		text: "¡Esta acción no se puede revertir!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: '¡Sí, borrar!',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.value) {
			$.ajax({
				url: "bajaUsuario.php?id_user=" + idUsuario,
				context: document.body
			}).done(() => {
				Swal.fire(
					'¡Borrado!',
					'Cuenta eliminada',
					'success'
				).then(() =>{
					window.location.href = "index.php";
				});
			});
		}
	})
});

</script>

	<?php if (isset($_SESSION['errores'])): ?>
		<ul id="errores" class="errores_mensaje" style="display:block;">
			<?php 
				echo $_SESSION['errores']; 
				unset($_SESSION['errores']);
			?>
		</ul>
	<?php endif ?>

</div>

<?php include 'views/footer.php'; ?>