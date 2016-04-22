<html>
<head> 
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Principal prueba</title>
 <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
</head>
<body>
<div class="container">
<!--mennu principal-->
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
     <div> 
      <h4 class="navbar-text"> <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>&nbsp;&nbsp;
        <small>Repositorio: <b><a href="<?= base_url() ?>index.php/dpbxrepo/principal" title="Principal"><?php echo $empresa->display_name; ?></a></b>&nbsp;/&nbsp;
        <?= $arbol ?> 
        </small>
       </h4>
    </div>
    </div>
    <h2 class="navbar-text navbar-right" data-toggle="tooltip" data-placement="left" title="Agregar nuevo proyecto">
      <a href="#nuevorepo" data-toggle="modal"  >
      <span class="glyphicon glyphicon-plus-sign" ></span></a>&nbsp;
    </h2>

  </div>
</nav>
<div class="row">
  <?php if ($historial!=null): ?>
<div class="col-md-12">
  <div class="list-group">
  <?php foreach ($historial as $key => $value ): ?>
  
  <a  class="list-group-item">
     <form  id="<?= $key.$value['name'] ?>" action="<?= base_url() ?>index.php/dpbxrepo/dowload" method="post">
          <input type="hidden" name="download_file" value="/<?= $value['directorio'] ?>">
      </form><!-- fin form actualizar -->

    <div class="row">
     <div class="col-md-8">
     <h5>Archivo: <?= $value['name']  ?></h5>
     <h5><small>Modificacion:<?= $value['date'] ?></small></h5>
     </div>
     <div class="col-md-4" align="right">
        <button onclick="subform('<?= $key.$value['name']   ?>')" class="btn btn-default btn-sm">
        <span class="glyphicon glyphicon-download" aria-hidden="true"></span>&nbsp;Descargar
        </button>
     </div>
    </div>
  </a>

  <?php endforeach ?>
</div>
</div>
  <?php else: ?>
    <p>Sin historal</p>
  <?php endif ?>
</div><!--fin contenido-->
<!--modal-->
<div class="modal fade" id="nuevorepo" tabindex="-1" role="dialog" aria-labelledby="nuevorepo" >
  <div class="modal-dialog" role="document">
    <!-- formulario-->
    <form method="post" action="<?php echo base_url(); ?>index.php/dpbxrepo/newrepo" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="nuevorepolabel">Nuevo proyecto</h4>
      </div>
      <div class="modal-body">
        <p>Evita repetir el nombre de los proyectos para no confundirte</p>
          <div class="form-group">
            <label for="recipient-name" class="control-label">NOMBRE:</label>
            <input type="text" class="form-control input-sm" id="repositorio-name" name="repositorio-name" required/>
          </div>
          <tr>
          <div class="form-group">
            <div class="row">
            <div class="col-md-12">
            <label for="message-text" class="control-label">Descripción</label>
            <textarea class="form-control input-sm" id="message-text" name="repositorio-descripcion"></textarea>
            </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
            <div class="col-md-12">
            <label for="InputFile">Subir Archivo</label>
            <input type="file" id="InputFile" name="inputFile">
            <p class="help-block">Solo archivos de lectura ejemplo(.pdf.doc.epub.ptt.)</p>
            </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>
        <button  class="btn btn-primary btn-sm" type="submit">Crear repositorio</button>
      </div>
    </div>
  </form><!--fin de form-->
  </div>
</div><!--fin modal-->

</div><!-- fin container-->
  <!-- Ficheros JS necesarios para que funcione-->
<script type="text/javascript" src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="/tlacuachetest/js/bootstrap.min.js"></script>
    <script>

    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
    
    function subform(formu) {
    document.getElementById(formu).submit();
    }

    </script>
    <script> 
    // using JQUERY's ready method to know when all dom elements are rendered
    $( document ).ready(function () {
      // set an on click on the button
      $("#button").click(function () {
        // get the time if clicked via an ajax get queury
        // see the code in the controller time.php
        $.get("<?php echo base_url(); ?>/index.php/dpbxrepo/newrepo", function (time) {
          // update the textarea with the time
          $("#text").html("Time on the server is:" + time);
        });
      });
    });

  </script>

</body>
</html>


