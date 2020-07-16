<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado() || !$autenticador->esAdmin()) {
		header('Location: login.php');
		exit;
	} 
    include 'views/header.php';

    $db = conectar();

    $idlibro = $_GET["id"];

    $sql = "SELECT titulo, subidos, capitulos FROM libros WHERE id = $idlibro";
    $resultado = $db->query($sql);
    $libro = $resultado->fetch_assoc();

    

    $capitulos = $libro['capitulos'];
    $subidos = ($libro['subidos'] == null) ? 0 : $libro['subidos'];

    date_default_timezone_set('America/Argentina/Buenos_Aires');
?>
    <div class="container">

    <?php if (isset($_SESSION['errores'])): ?>
		<ul id="errores" style="display:block;">
			<?php 
				echo $_SESSION['errores']; 
				unset($_SESSION['errores']);
			?>
		</ul>
	<?php endif ?>

    <?php if (isset($_SESSION['exito'])): ?>
		<ul id="exito" style="display:block;">
			<?php 
				echo $_SESSION['exito']; 
				unset($_SESSION['exito']);
			?>
		</ul>
	<?php endif ?>
    <?php 
        //consulta para los capitulos cargados que ya estan cargados
           $sql2= "SELECT numero_capitulo FROM libros_pdf WHERE '$idlibro'= libro_id";
           $result_caps_cargados= $db->query($sql2)->fetch_all();
           //print_r($result_caps_cargados);
        //consulta para la cantidad total de capitulos para ese libro   
           $sql= "SELECT capitulos FROM libros WHERE '$idlibro'=id";
           $result_cant_caps = $db->query($sql)->fetch_assoc()['capitulos'];
          

        //array con total de capitulos del libro
        $arr1 = array();
        for ($i = 1; $i <= $result_cant_caps; $i++) {
            array_push($arr1, $i);
        }   
        //array con los valores para luego aplicar array_diff
        $arr2 =array();
        foreach ($result_caps_cargados as $val) {
            array_push($arr2,$val[0]);
        }
        
        //los capitulos que faltan o sea los que se pueden cargar
        $resultado_array = array_diff($arr1, $arr2);
        
    ?>
        
        <?php if($capitulos == 0){ ?>
            <h1>Cargar capitulo </h1>
        <?php }else{?>
            <h1>Cargar capitulo: </h1>
        <?php }?>
        <p id="descripcion-novedad">Seleccione archivo pdf y fechas para el libro"<?php echo $libro['titulo'] ?>"</p>
        <form action="validarCargaCapitulo.php?id=<?php echo $idlibro ?>" onsubmit="return validarLibro(this)" method="post" enctype="multipart/form-data">
           
           <?php if($capitulos == 0){ ?>
            <div class="input">
                <input type="number" id="cantidadCapitulos" name="cantidadCapitulos" placeholder="Cantidad de capitulos">
            </div>
           <?php }else{?>
            <select name="num_cap" id="num_cap">
                    <option disabled="disabled" selected value=""> Seleccione el capitulo a cargar</option>
                    <?php foreach($resultado_array as $val) { ?>
                        <option value="<?php echo $val?>"> <?php echo $val ?> </option>
                    <?php }?>
                </select>
            <?php } ?> 
            <div>
                <p>Fecha de publicacion (*)</p>
                <input type="datetime-local" name="fechaPublicacion" step="1" min=<?php $diaAnterior= date('Y-m-d',strtotime(date('Y-m-d')."- 0 days")); echo $diaAnterior.'T00:00:00';?> max="2100-12-31" value="<?php $diaAnterior= date('Y-m-d',strtotime(date('Y-m-d')."- 0 days")); $hora= date('H:i:s'); echo $diaAnterior."T".$hora;?>">
            </div>
            <div>
                <p>Fecha de vencimiento</p>
                <input type="datetime-local" name="fechaVencimiento" step="1" min=<?php $diaAnterior= date('Y-m-d',strtotime(date('Y-m-d')."- 0 days")); echo $diaAnterior.'T00:00:00';?> max="2100-12-31" value="">
            </div>

            <div class="input">
                Ingrese PDF: <input type="file" name="pdf" placeholder="PDF"> (*)
            </div>

            <div style="margin-bottom: 22px; margin-top:12px">
            <p> (*) Campos obligatorios</p>
            </div>

            <div class="botones">
                <div class="input">
                    <input type="submit" value="Ok">
                </div>
                <a href="libro.php?id=<?php echo $idlibro?>" id="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
	
	<ul id="errores" style="display:none"></ul>

<?php include 'views/footer.php'; ?> 