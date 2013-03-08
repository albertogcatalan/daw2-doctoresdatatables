<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gestión Clínica</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/additional-methods.js"></script>
    <script type="text/javascript" src="js/messages_es.js"></script>
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
      }
      .error {
          color: #c43c35;
      }
    </style>
    <link rel="stylesheet" type="text/css" href="css/jquery.gritter.css"/>
    <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css"/>
    
    
    <script type="text/javascript" src="js/bootstrap.min.js"></script> 
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/jquery.gritter.js"></script>
    <script type="text/javascript" src="js/DT_bootstrap.js"></script>
    
     
    
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
    <script>
            $(document).ready(function() {
                             
               
               oTable = $('#mitabla').dataTable( {
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "php/listar.php",
                    "aoColumns": [
                        { "mData": "doctor",
                            "mRender": function ( data, type, full ) {
                               return '<a data-toggle="modal" class="editarbtn" data-target="#actualizarModal" href="#?id_doctor=' + full.id_doctor + '">'+data+'</a>';
                               
                            }
                        },
                        { "mData": "numcolegiado",
                             "sWidth": "100px"},
                        { "mData": "clinica",
                          "mRender": function ( data, type, full ) {
                              var arrayLi = new Array();
                              var datos = new Array();
                               arrayLi = data.split(",");
                             
                               for (var i=0;i < arrayLi.length;i++){
                                    datos[i] = "<li>"+arrayLi[i]+"</li>";
                               }
                               
                               //si tiene varias clinicas
                               if (datos.length > 1){
                                   datos = datos.join(" ");
                               }
                               
                               return '<ul>'+ datos + '</ul>';
                               
                            },
                            "sWidth": "400px"
                        },
                        { "mData": "id_clinica",
                            "bVisible":false},
                        {
                            "mData": "id_doctor",
                            "mRender": function ( data, type, full ) {     
                               return '<a href="#?id_doctor?='+data+'"><button class="btn btn-info editarbtn" data-target="#actualizarModal" data-toggle="modal">Editar</button></a>';
                            },
                            "bSortable": false, 
                            "bSearchable": false,
                            "sWidth": "60px"
                        },
                        {
                            "mData": "id_doctor",
                            "mRender": function ( data, type, full ) {  
                                
                               return '<a href="#?id_doctor?='+data+'"><button class="btn btn-danger borrarbtn" data-target="#borrarModal" data-toggle="modal">Borrar</button></a>';
                            },
                            "bSortable": false, 
                            "bSearchable": false,
                            "sWidth": "60px"
                        }
                        
                    
                ]
       
            });
            
        
         $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: "php/clinicas.php",
                    async: false,
                    error: function(xhr, status, error) {
                           
                    },
                    success: function(data) {
                            
	                    $("#id_clinicas").empty();
	                    $.each(data, function() {
	                        $("#id_clinicas").append(
	                        $('<option></option>').val(this.id_clinica).html(this.nombre)
	                        );
	                    }); 
	                }
	    });
            
        $("#form").validate({
            rules : {
                    nombre : {
                            required : true,
                            lettersonly : true
                    },
                    numcolegiado : {
                            digits: true
                    },
                    "id_clinicas[]" : {
                            required : true

                    }
                    
                }
            
        });
        
        $("#mitabla").on('click',".editarbtn", function (e) {
            e.preventDefault();
            
            var nRow = $(this).parents('tr')[0];	
            var aData = oTable.fnGetData(nRow);
            $("#id_doctor").val(aData.id_doctor);
            $("#nombre").val(aData.doctor);
            $("#numcolegiado").val(aData.numcolegiado);
            var seleccionados = aData.id_clinica.split(",");
            $("#id_clinicas").val(seleccionados);
            
        });
        
        $("#mitabla").on('click',".borrarbtn", function (e) {
            e.preventDefault();
            var nRow = $(this).parents('tr')[0];	
            var aData = oTable.fnGetData(nRow);
            $("#id_doctorB").val(aData.id_doctor);
        });
        
        $("#actualizarModal").on('click',"#accionGuardarReset", function (e) {    
            e.preventDefault();
            $("#form")[0].reset();
        });
        
        $("#actualizarModal").on('click',".close", function (e) {    
            e.preventDefault();
            $("#form")[0].reset();
        });
          
        
        $("#borrarModal").on('click',"#accionBorrar", function (e) {    
        	e.preventDefault();
           
            var id_doctor = $("#id_doctorB").val();
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "php/borrar.php",
                async: false,
                data: {id_doctor: id_doctor },
                error: function(xhr, status, error) {
                    $('#borrarModal').modal('hide');
                    $.gritter.add({
                                    title: 'Notificación',
                                    text: 'Error: <b>'+error+'</b></br>Estado: <b>'+status+'</b></br>Codigo: <b>'+xhr+'</b>',
                                    image: 'images/error.png',
                                    sticky: false,
                                    time: '3000'
                            });
                },
                success: function(data) {
                    $('#borrarModal').modal('hide');
                    $.gritter.add({
                                    title: 'Notificación',
                                    text: 'El registro ha sido borrado correctamente',
                                    image: 'images/ok.png',
                                    sticky: false,
                                    time: '3000'
                         });
                    
                    oTable.fnDraw();
                   
                    
                }
                       
            });
            return false;
                   
        });
          
          
        $("#actualizarModal").on('click',"#accionGuardar", function (e) {    
                e.preventDefault();
         
            var id_doctor = $("#id_doctor").val();
            var nombre =$("#nombre").val();
            var numcolegiado = $("#numcolegiado").val();
            var id_clinicas = $("#id_clinicas").val();

            
              if ($("#form").validate().form()){
                  
                
                if (id_doctor == ""){
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: "php/insertar.php",
                        async: false,
                        data: { nombre: nombre, numcolegiado: numcolegiado, id_clinicas:id_clinicas},
                        error: function(xhr, status, error) {
                            $('#actualizarModal').modal('hide');
                            $.gritter.add({
                                        title: 'Notificación',
                                        text: 'Error: <b>'+error+'</b></br>Estado: <b>'+status+'</b></br>Codigo: <b>'+xhr+'</b>',
                                        image: 'images/error.png',
                                        sticky: false,
                                        time: '3000'
                                });
                             $("#form")[0].reset();
                        },
                        success: function(data) {
                            $('#actualizarModal').modal('hide');
                            $.gritter.add({
                                        title: 'Notificación',
                                        text: 'El registro ha sido creado correctamente',
                                        image: 'images/ok.png',
                                        sticky: false,
                                        time: '3000'
                            });
                            $("#form")[0].reset();
                            oTable.fnDraw();

                        }
                    });
                } else {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: "php/guardar.php",
                        async: false,
                        data: { id_doctor: id_doctor, nombre: nombre, numcolegiado: numcolegiado, id_clinicas: id_clinicas},
                        error: function(xhr, status, error) {
                            $('#actualizarModal').modal('hide');
                            $.gritter.add({
                                        title: 'Notificación',
                                        text: 'Error: <b>'+error+'</b></br>Estado: <b>'+status+'</b></br>Codigo: <b>'+xhr+'</b>',
                                        image: 'images/error.png',
                                        sticky: false,
                                        time: '3000'
                                });
                             $("#form")[0].reset();
                        },
                        success: function(data) {
                            $('#actualizarModal').modal('hide');
                            $.gritter.add({
                                        title: 'Notificación',
                                        text: 'El registro ha sido actualizado correctamente',
                                        image: 'images/ok.png',
                                        sticky: false,
                                        time: '3000'
                            });
                            oTable.fnDraw();
                            $("#form")[0].reset();

                        }
                    });

                }
                return false;
            
              }
                   
        });
    });
</script>
    
</head>
<body>

    

    <div class="container">

      <!-- Main hero unit for a primary marketing message or call to action -->
     
        <h1>Doctores</h1>
        <p><button type="button" data-toggle="modal" data-target="#actualizarModal" class="btn btn-large btn-primary">Añadir doctor</button></p>
        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="mitabla">
    	
            <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Nº colegiado</th>
                            <th>Clínica</th>
                            <th></th>
                            <th>Editar</th>
                            <th>Borrar</th>
                        </tr>
                    </thead>
        <tbody>
        	
        </tbody>
    </table>

    <div id="actualizarModal" class="modal hide ">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3 id="myModalLabel">Datos de Doctor</h3>
        </div>
        <div class="modal-body">
            <form id="form" name="form" action="" class="miform" method="post">
            	<fieldset id="fieldset-formdoctor" class="form-horizontal">

			        <input type="hidden" name="id_doctor" value="" id="id_doctor">
			        <div class="control-group">
			        	<label for="nombre" class="control-label required">Nombre:</label>
			        	<div class="controls">
				        	<input type="text" name="nombre" id="nombre" value="" class="required" maxlength="50">
				        </div>
				    </div>
			        
			        <div class="control-group">
			        	<label for="numcolegiado" class="control-label optional">Nº de Colegiado:</label>
			        	<div class="controls">
				        	<input type="text" name="numcolegiado" id="numcolegiado" value="" maxlength="10">
				        </div>
				    </div>
			        
			        <div class="control-group">
			        	<label for="id_clinicas" class="control-label required">Clínicas:</label>
			        	<div class="controls">
				        	<select name="id_clinicas[]" id="id_clinicas" size="10" style="width:300px;" multiple="multiple">
			
				        	</select>
			        	</div>
			        </div>
			     </fieldset>
			</form>       
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" id="accionGuardarReset">Cancelar</button>
            <button id="accionGuardar" class="btn btn-primary">Guardar</button>
        </div>
    </div>
         
    <div id="borrarModal" class="modal hide fade">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Borrar Doctor</h3>
        </div>
        <div class="modal-body">
             <input type="hidden" name="id_doctorB" value="" id="id_doctorB">
            <p>¿Seguro que quieres borrar el registro?</p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal">Cancelar</button>
            <button id="accionBorrar" class="btn btn-primary">Borrar</button>
        </div>
    </div>
         

   
      <hr>

      <footer>
        <p>&copy; Company 2012</p>
      </footer>

    </div> <!-- /container -->


</body>
</html>