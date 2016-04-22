<html>
<head> 
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Principal prueba</title>
 <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">

<!-- Loading -->
<link rel="stylesheet" href="<?= base_url() ?>css/iosOverlay.css">

</head>
<body>
<div class="container">
<!--mennu principal-->
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
     <div> 
      <h4 class="navbar-text"> <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>&nbsp;&nbsp;
        <small>Repositorio: <b><a href="<?= base_url() ?>index.php/dpbxrepo/inicio" title="Principal"><?php echo $empresa->display_name; ?></a></b>
          <?= $arbol ?></small>
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
    <div class="col-md-12">
        <button  class="btn btn-default" aria-label="Left Align" href="#nuevorepo" data-toggle="modal" >
        <span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>&nbsp;Nuevo Documento
        </button>
    </div>
</div>
<hr />
<div class="row">
  <?php if ($listado!=null): ?>
    
  <?php foreach ($listado as $key => $value): ?>

  <div class="panel  panel-primary">
  <div class="panel-heading"> <?php echo $value->nombre ?></div>
          <div class="panel-body">
             <div class="col-md-6">
               <p><span class="glyphicon glyphicon-file" aria-hidden="true"></span>
                Archivo: <?php echo $value->nombre.'.'.$value->ext ;?>
                <br>
                <small>Última modificación <?php echo  $value->date; ?></small> 
              </p>
             </div>
             <div class="col-md-6" align="right">
               <button onclick="subform('<?= $key.$value->nombre ?>')" class="btn btn-default btn-sm btn-loading">
                <span class="glyphicon glyphicon-download" aria-hidden="true"></span>&nbsp;Descargar
                </button>

              <button type="button" class="btn btn-default btn-sm inline-block" data-toggle="modal" data-target="#modal<?= $key; ?>">
                <span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span>&nbsp;Actualizar
              </button>

               <a class="collapsed btn btn-default btn-sm btn-loading" href="<?= base_url() ?>index.php/dpbxrepo/inicio<?= $value->history ?>">
                  <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>&nbsp;Historial
              </a>

               <a class="collapsed btn btn-info btn-sm btn-loading" href="<?= base_url() ?>index.php/dpbxrepo/inicio<?= $value->info ?>">
                  <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>&nbsp;INFO
              </a>

             </div>
          </div>
        </div><!--fin del panel-->

 
<!--form uddate-->
     <div class="modal fade" tabindex="-1" role="dialog" id="modal<?php echo $key; ?>">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Actualizar <?= $value->nombre ?></h4>
          </div>
          <div class="modal-body">
            <form action="<?php echo base_url() ?>index.php/dpbxrepo/update" method="post" enctype="multipart/form-data" accept-charset="utf-8">
              <input type="hidden" name="dir_name" value="<?php echo $value->docs ?>">
              <input type="hidden" name="dir_history" value="<?php echo $value->history ?>">
              <input type="hidden" name="doc_name" value="<?php echo $value->nombre.'.'.$value->ext ;?>">
              <input type="hidden" name="nota_actual" value="<?= $value->nota ?>">
              <input type="hidden" name="directorio" value="<?= $directorio ?>">
              <div class="row">
                <div class="col-md-2">
                  <label for="inputFile">
                    Archivo:
                  </label>
                </div>
                <div class="col-md-10">
                 <input type="file" name="inputFile" id="file<?php echo $key; ?>" required>
                </div>
              </div>
               <div class="form-group">
                 <label for="nota">
                   Nota:
                 </label>
                <textarea name="nota" class="form-control" rows="3"></textarea>
               </div>
             <button type="submit" class="btn btn-default text-right" onclick="update('#file<?php echo $key; ?>')" >Subir</button>
           </form>
          </div>
         </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <form  id="<?= $key.$value->nombre ?>" action="<?= base_url() ?>index.php/dpbxrepo/dowload" method="post">
      <input type="hidden" name="download_file" value="<?= $value->docs ?>">
    </form><!-- fin form actualizar -->

   <?php endforeach ?>

  <?php endif ?>
</div><!--fin contenido-->
</div><!--fin contenido-->

<!--modal-->
<div class="modal fade" id="nuevorepo" tabindex="-1" role="dialog" aria-labelledby="nuevorepo" >
  <div class="modal-dialog" role="document">
    <!-- formulario-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="nuevorepolabel">Nuevo proyecto</h4>
      </div>
    <form method="post" action="<?php echo base_url(); ?>index.php/dpbxrepo/newrepo" enctype="multipart/form-data" id="formnew">
      <div class="modal-body">
        <p>Evita repetir el nombre de los proyectos para no confundirte</p>
        <div id="mensajemodal">
        </div>
          <div class="form-group">
            <input type="hidden" name="directorio" id="directorio" value="<?= $directorio ?>">
            <label for="recipient-name" class="control-label">NOMBRE:</label>
            <input type="text" class="form-control input-sm" id="repositorio-name" name="repositorio-name" required/>
          </div>
          <tr>
          <div class="form-group">
            <div class="row">
            <div class="col-md-12">
            <label for="message-text" class="control-label">Descripción</label>
            <textarea class="form-control input-sm" id="message-text" name="repositorio-descripcion" required></textarea>
            </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
            <div class="col-md-12">
            <label for="InputFile">Subir Archivo</label>
            <input type="file" id="InputFile" name="inputFile" required>
            <p class="help-block">Solo archivos de lectura ejemplo(.pdf.doc.epub.ptt.)</p>
            </div>
            </div>
          </div>
      </div>
    </form><!--fin de form-->
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="cancelar">Cancelar</button>
        <button  class="btn btn-primary btn-sm" id='repoBoton' >Crear repositorio</button>
   </div>
    </div>
  </div>
</div><!--fin modal-->

    
</div><!-- fin container-->

  <!-- Ficheros JS necesarios para que funcione-->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="<?= base_url() ?>js/bootstrap.min.js"></script> 

 <!-- ficheros para loading-->
 <script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>


    <script>

     $('#formnew').keypress(function(e){   
    if(e == 13){
      return false;
      }
      });

      $('input').keypress(function(e){
        if(e.which == 13){
          return false;
        }
      });

    function update(id){
     if($(id).val()!="") {  
        loading(); 
      }
    }
    
    function subform(formu) {
      document.getElementById(formu).submit();
    }

    $('.btn-loading').on('click', function() {
          var $this = $(this);
        $this.button('loading');
          setTimeout(function() {
             $this.button('reset');
         }, 2000);
      });
    function loading(){
      var opts = {
      lines: 13, // The number of lines to draw
      length: 11, // The length of each line
      width: 5, // The line thickness
      radius: 17, // The radius of the inner circle
      corners: 1, // Corner roundness (0..1)
      rotate: 0, // The rotation offset
      color: '#FFF', // #rgb or #rrggbb
      speed: 1, // Rounds per second
      trail: 60, // Afterglow percentage
      shadow: false, // Whether to render a shadow
      hwaccel: false, // Whether to use hardware acceleration
      className: 'spinner', // The CSS class to assign to the spinner
      zIndex: 2e9, // The z-index (defaults to 2000000000)
      top: 'auto', // Top position relative to parent in px
      left: 'auto' // Left position relative to parent in px
      };
      var target = document.createElement("div");
      document.body.appendChild(target);
      var spinner = new Spinner(opts).spin(target);
      iosOverlay({
      text: "Generando",
      duration: 100e3,
      spinner: spinner
      });
    }
 
    // using JQUERY's ready method to know when all dom elements are rendered
    $( document ).ready(function () {
      // set an on click on the button
      $("#repoBoton").click(function () {
       var slug = function(str) {
        var $slug = '';
        var trimmed = $.trim(str);
        $slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
        replace(/-+/g, '-').
        replace(/^-|-$/g, '');
        return $slug.toLowerCase();
        }
        $.get("<?php echo base_url(); ?>index.php/dpbxrepo/buscar"+ 
          $("#directorio").val()+'/'+slug($("#repositorio-name").val()), function (buscar) {
           if (buscar=='error') {
            $("#mensajemodal").html("<div class='alert alert-warning sm' role='alert' > Ya cuenta con un archivo con el mismo nombre</div> ");
           setTimeout(function() {
             $("#mensajemodal").html('<div id="mensajemodal"></div>')
              }, 2000);
           } else if(buscar=='correcto'){
                if($("#InputFile").val()=="") {  
                      alert("El archivo es obligatorio");  
                    } else{
                    loading();
                    $('#formnew').submit();
                    $('#repositorio-name,#InputFile, #message-text,#repoBoton, #cancelar').attr('disabled','');
                  }
              }
           else{
             $("#mensajemodal").html("<div class='alert alert-warning sm' role='alert' >Necesita asignar un nombre</div> ");
              setTimeout(function() {
             $("#mensajemodal").html('<div id="mensajemodal"></div>')
              }, 2000);
           };
        });
      });
    });
  </script>

<!-- script loading --> 
<script src="<?= base_url() ?>js/iosOverlay.js"></script> 
<script src="<?= base_url() ?>js/spin.min.js"></script> 
<script src="<?= base_url() ?>js/prettify.js"></script> 
<script src="<?= base_url() ?>js/custom.js"></script> 

</body>
</html>


