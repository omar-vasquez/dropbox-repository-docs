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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
<!--mennu principal-->
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
     <div> 
      <h4 class="navbar-text"> <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>&nbsp;&nbsp;
        <small>Repositorio: <b><a href="<?= base_url() ?>index.php/dpbxrepo/inicio" title="Principal"><?php echo $empresa->display_name; ?></a></b><?= $arbol ?></small>
       </h4>
    </div>
    </div>
    <h2 class="navbar-text navbar-right" data-toggle="tooltip" data-placement="left" title="Agregar nuevo proyecto">
      <a href="#nuevorepo" data-toggle="modal"  >
      <span class="glyphicon glyphicon-plus-sign" ></span></a>&nbsp;
    </h2>

  </div>
</nav>
<!--contenido-->
<div class="row">
  <?php if ($listado!=null): ?>
  <div class="col-md-12">
  <div class="list-group">
  <?php foreach ($listado as $key => $value): ?>
    <a class="list-group-item" href="<?= base_url().'index.php/dpbxrepo/inicio'.$value ?>">
      <h5>
      <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span>
      <?php $dato = explode('/',$value);  echo strtoupper( $dato[count($dato)-1]);  ?>
      </h5>
    </a>
  <?php endforeach ?>
  </div>
  </div>
  <?php endif ?>
  <!--anexo de un nuevo repositorio-->
  <div id="divreponevo">

  </div>

</div><!--fin contenido-->

<!--modal-->
<div class="modal fade" id="nuevorepo" tabindex="-1" role="dialog" aria-labelledby="nuevorepo" >
  <div class="modal-dialog" role="document">
    <!-- formulario-->
    <form method="post" action="<?php echo base_url(); ?>index.php/dpbxrepo/NewModulo" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="nuevorepolabel">Nuevo proyecto</h4>
      </div>
      <div class="modal-body">
        <p>Evita repetir el nombre de los proyectos para no confundirte</p>
          <div class="form-group">
            <label for="recipient-name" class="control-label">NOMBRE:</label>
            <input type="text" class="form-control input-sm" id="name_modulo" name="name_modulo"/>
          </div>
          <tr>
          <div class="form-group">
            <div class="row">
            <div class="col-md-12">
            <label for="message-text" class="control-label">Descripción</label>
            <textarea class="form-control input-sm" id="message-text" name="message-text"></textarea>
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


