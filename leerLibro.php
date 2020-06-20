<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	} 
    include 'views/header.php';

    $db = conectar();
    $pdf_id = $_GET['id'];
    $usuario_id = $_SESSION['usuario']['id'];

    $sql = "SELECT pdf FROM libros_pdf WHERE id = $pdf_id";
    $resultado = $db->query($sql);
    $libro = $resultado->fetch_assoc()['pdf'];

    // traer numero de la ultima pagina
    $sql = "SELECT pagina FROM ultima_pagina WHERE pdf_id = '$pdf_id' AND usuario_id = '$usuario_id'";
    $resultado = $db->query($sql);
    $ultima_pagina = $resultado->fetch_assoc()['pagina'];
    
    $ultima_pagina = $ultima_pagina != null ? $ultima_pagina : '1';
?>

<style>
* {
  margin: 0;
  padding: 0;
}

html {
  padding-bottom:0;
}

.bar {
  display:flex;
  justify-content: center;
  color: #fff;
  margin: 5px 0;
}

.btn {
  color: #10d5f5;
  border: none;
  outline: none;
  cursor: pointer;
  background: none;
}

.btn i {
  font-size: 20px;
  color: #dc1025;
}

.btn:hover {
  opacity: 0.9;
}

.page-info {
  margin: .5rem;
}

.error {
  background: orangered;
  color: #fff;
  padding: 1rem;
}
</style>


<div class="bar">
  <button class="btn prev-page">
    <i class="fas fa-arrow-circle-left"></i>
  </button>
  <span class="page-info">
    <span class="page-num"></span> de <span class="page-count"></span>
  </span>
  <button class="btn next-page">
    <i class="fas fa-arrow-circle-right"></i>
  </button>
</div>

<div style="display:flex; justify-content:center;">
  <canvas id="pdf-render"></canvas>
</div>

<div class="bar">
  <button class="btn prev-page-bottom">
    <i class="fas fa-arrow-circle-left"></i>
  </button>
  <span class="page-info">
    <span class="page-num-bottom"></span> de <span class="page-count-bottom"></span>
  </span>
  <button class="btn next-page-bottom">
    <i class="fas fa-arrow-circle-right"></i>
  </button>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="http://mozilla.github.io/pdf.js/build/pdf.js"></script>

<script>
var BASE64_MARKER = ';base64,';

function convertDataURIToBinary(dataURI) {
  var base64Index = dataURI.indexOf(BASE64_MARKER) + BASE64_MARKER.length;
  var base64 = dataURI.substring(base64Index);
  var raw = window.atob(base64);
  var rawLength = raw.length;
  var array = new Uint8Array(new ArrayBuffer(rawLength));

  for(var i = 0; i < rawLength; i++) {
    array[i] = raw.charCodeAt(i);
  }
  return array;
}

var pdfAsDataUri = "data:application/pdf;base64,<?php echo base64_encode($libro) ?>";
var pdfAsArray = convertDataURIToBinary(pdfAsDataUri);

let pdfDoc = null,
  pageNum = Number(<?= $ultima_pagina ?>), // mostrar ultima pagina
  pageIsRendering = false,
  pageNumIsPending = null;

const scale = 1.25,
  canvas = document.querySelector('#pdf-render'),
  ctx = canvas.getContext('2d');

// Render the page
const renderPage = num => {
  pageIsRendering = true;

  // Get page
  pdfDoc.getPage(num).then(page => {
    // Set scale
    const viewport = page.getViewport({ scale });
    canvas.height = viewport.height;
    canvas.width = viewport.width;

    const renderCtx = {
      canvasContext: ctx,
      viewport
    };

    page.render(renderCtx).promise.then(() => {
      pageIsRendering = false;

      if (pageNumIsPending !== null) {
        renderPage(pageNumIsPending);
        pageNumIsPending = null;
      }
    });

    // Output current page
    document.querySelector('.page-num').textContent = num;
    document.querySelector('.page-num-bottom').textContent = num;
  });
};

// Check for pages rendering
const queueRenderPage = num => {
  if (pageIsRendering) {
    pageNumIsPending = num;
  } else {
    renderPage(num);
  }
};

// Show Prev Page
const showPrevPage = () => {
  if (pageNum <= 1) {
    return;
  }
  pageNum--;
  queueRenderPage(pageNum);
};

// Show Next Page
const showNextPage = () => {
  if (pageNum >= pdfDoc.numPages) {
    return;
  }
  pageNum++;
  queueRenderPage(pageNum);
};

// Get Document
pdfjsLib
  .getDocument(pdfAsArray)
  .promise.then(pdfDoc_ => {
    pdfDoc = pdfDoc_;

    document.querySelector('.page-count').textContent = pdfDoc.numPages;
    document.querySelector('.page-count-bottom').textContent = pdfDoc.numPages;

    renderPage(pageNum);
  })
  .catch(err => {
    // Display error
    const div = document.createElement('div');
    div.className = 'error';
    div.appendChild(document.createTextNode(err.message));
    document.querySelector('body').insertBefore(div, canvas);
    // Remove top bar
    document.querySelector('.bar').style.display = 'none';
  });

// Button Events
document.querySelector('.prev-page').addEventListener('click', function(){
  showPrevPage();
  guardarHistorial(<?= $pdf_id ?>, <?= $_SESSION['usuario']['id'] ?>);
  
  //TODO: guardar ultima pagina
  guardarUltimaPagina(<?= $pdf_id ?>, <?= $_SESSION['usuario']['id'] ?>, pageNum);
});
document.querySelector('.prev-page-bottom').addEventListener('click', function(){
  showPrevPage();
  guardarHistorial(<?= $pdf_id ?>, <?= $_SESSION['usuario']['id'] ?>);
  
  //TODO: guardar ultima pagina
  guardarUltimaPagina(<?= $pdf_id ?>, <?= $_SESSION['usuario']['id'] ?>, pageNum);
});
document.querySelector('.next-page').addEventListener('click', function(){
  showNextPage();
  guardarHistorial(<?= $pdf_id ?>, <?= $_SESSION['usuario']['id'] ?>);
  
  //TODO: guardar ultima pagina
  guardarUltimaPagina(<?= $pdf_id ?>, <?= $_SESSION['usuario']['id'] ?>, pageNum);
});
document.querySelector('.next-page-bottom').addEventListener('click', function(){
  showNextPage();
  guardarHistorial(<?= $pdf_id ?>, <?= $_SESSION['usuario']['id'] ?>);
  
  //TODO: guardar ultima pagina
  guardarUltimaPagina(<?= $pdf_id ?>, <?= $_SESSION['usuario']['id'] ?>, pageNum);
});

// Pasar de pagina con las flechas del teclado
document.addEventListener('keydown', function(e){
  if(e.key === "ArrowRight"){
    showNextPage();
    guardarHistorial(<?= $pdf_id ?>, <?= $_SESSION['usuario']['id'] ?>);
    
    // TODO: guardar ultima pagina
    guardarUltimaPagina(<?= $pdf_id ?>, <?= $_SESSION['usuario']['id'] ?>, pageNum);
  }

  if(e.key === "ArrowLeft"){
    showPrevPage();
    guardarHistorial(<?= $pdf_id ?>, <?= $_SESSION['usuario']['id'] ?>);
    
    // TODO: guardar ultima pagina
    guardarUltimaPagina(<?= $pdf_id ?>, <?= $_SESSION['usuario']['id'] ?>, pageNum);
  }
});

function guardarHistorial(pdf_id, usuario_id) {
  $.ajax({
    url: 'guardarhistorial.php?pdf_id=' + pdf_id + '&usuario_id=' + usuario_id,
    context: document.body
  }).done((e) => {
    console.log(e);
  });
}

function guardarUltimaPagina(pdf_id, usuario_id, pagina){
  $.ajax({
    url: 'guardarultimapagina.php?pdf_id=' + pdf_id + '&usuario_id=' + usuario_id + '&pagina=' + pagina,
    context: document.body
  }).done((e) => {
    console.log(e);
  });
}

guardarHistorial(<?= $pdf_id ?>, <?= $_SESSION['usuario']['id'] ?>);

</script>