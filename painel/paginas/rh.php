<?php 
@session_start();
$id_usuario = @$_SESSION['id'];
require_once("verificar.php");
$pag = 'rh';

if(@$rh == 'ocultar'){
	echo "<script>window.location='index'</script>";
	exit();
}


?>


<div class="justify-content-between">


<form method="post" action="rel/ponto_class.php" target="_blank">
<div class="row" style="background:white; padding:15px">	
	
	<div class="col-md-4">
									 
								<select class="form-select sel300" name="usuario_filtro" id="usuario_filtro" required style="width:100%;" onchange="trocarFuncionario()"> 
									<?php 
										$query = $pdo->query("SELECT * FROM usuarios where nivel != 'Cliente' and jornada_horas is not null order by nome asc");									
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									for($i=0; $i < @count($res); $i++){											
											?>	
										<option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome']  ?> - <?php echo $res[$i]['nivel']  ?></option>

									<?php  } ?>

								</select>
								
	</div>

	<div align="right" class="col-md-8">
			<button class="btn btn-primary btn-sm" type="submit" title="PDF da Folha de Ponto" style="padding:2px">
				<img src="images/pdf.png" width="30px" height="30px"> Gerar Relatório
			</button>
	</div>

	<input type="hidden" name="data_inicio_mes" id="data_inicio_mes">
	<input type="hidden" name="data_final_mes" id="data_final_mes"> 



	
<input type="hidden" name="dias_busca" id="dias_busca"> 
<input type="hidden" name="dias_busca_anterior" id="dias_busca_anterior">   
	
</div>

</form>

</div>


<div class="row">
<div class="col-md-5" >
<div class="agile-calendar">
			<div class="calendar-widget">
                                       
				<!-- grids -->
					<div class="agile-calendar-grid">
						<div class="page">
							
							<div class="w3l-calendar-left">
								<div class="calendar-heading">
									
								</div>
								<div class="monthly" id="mycalendar"></div>
							</div>
							
							<div class="clearfix"> </div>
						</div>
					</div>
			</div>
		</div>
</div>



<div class="col-xs-12 col-md-7 bs-example widget-shadow" style="padding:10px 5px; margin-top: 0px;" id="listar">
	
</div>
</div>



<!-- Modal -->


			<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel"><span id="nome-func"></span>Funcionário  ->  Data: <span id="data-dia"></span></h4>
				 <button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form method="post" id="form-text">
				<div class="modal-body">

					<div class="row" style="margin: -10px;">
						<div class="col-md-2">						
							
								<label>Entrada</label> 
								<input type="time" class="form-control" name="hora_entrada" id="hora_entrada" value="" required> 
												
						</div>	


						<div class="col-md-3" style="margin:0px; padding:0px">						
							
								<div align="center"><label>Almoço</label></div>
								<div class="row" style="margin:0px">
								<div class="col-md-6" style="padding:0px"> 
								<input type="time" class="form-control" name="entrada_almoco" id="entrada_almoco" value="" required>
								</div>

								<div class="col-md-6" style="padding:0px"> 
								<input type="time" class="form-control" name="saida_almoco" id="saida_almoco" value="" required>
								</div>
								</div>  
												
						</div>

										
						<div class="col-md-2">						
							
								<label>Saída</label> 
								<input type="time" class="form-control" name="hora_saida" id="hora_saida" value="" required> 
												
						</div>	

						<div class="col-md-1" style="margin-top: 25px">	
						<div class="form-check">
						  <input class="form-check-input" type="checkbox" value="Sim" name="folga" id="folga">
						  <label class="form-check-label" for="flexCheckDefault">
						   Folga
						  </label>
						</div>	
					</div>

							

						<div class="col-md-1" style="margin-top: 25px; margin-right: 10px">	
						<div class="form-check">
						  <input class="form-check-input" type="checkbox" value="Sim" name="feriado" id="feriado">
						  <label style="color:blue" class="form-check-label" for="flexCheckDefault">
						    Feriado
						  </label>
						</div>					
											
						</div>	

						<div class="col-md-1" style="margin-top: 25px">					
							<div class="form-check">
						  <input class="form-check-input" type="checkbox" value="Sim" name="falta" id="falta">
						  <label style="color:red" class="form-check-label" for="flexCheckDefault">
						    Falta
						  </label>
						</div>						
						</div>	

						<div class="col-md-1" style="margin-top: 20px" align="right">	
						<button type="submit" class="btn btn-primary">Salvar</button>
						</div>


					</div>

					

					<br>
					<input type="hidden" name="id" id="id">
					<input type="hidden" name="usuario" id="usuario">
					<input type="hidden" name="data_agenda" id="data_agenda" value="<?php echo date('Y-m-d') ?>">



					

					<small><div id="mensagem" align="center" class="mt-3"></div></small>					

				</div>




			</form>

		</div>
	</div>
</div>



<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
		$(document).ready(function() {	
			var idFunc = $('#usuario_filtro').val();
			listarFuncionario(idFunc);

			buscarData();
			buscarDataAnterior()
			listarCalendario();


			$("#usuario").val(idFunc);
			listar();
			});


		function trocarFuncionario(){
			var idFunc = $('#usuario_filtro').val();
			listarFuncionario(idFunc);
			$("#usuario").val(idFunc);
				listar();
				//listarCalendario();
			};		


			$("#folga").change(function() {
			  if ($(this).prop("checked") == true) {
			    $("#hora_entrada").val('00:00');
				$("#hora_saida").val('00:00');
				$("#entrada_almoco").val('00:00');
				$("#saida_almoco").val('00:00');
			  }
			});


			$("#falta").change(function() {
			  if ($(this).prop("checked") == true) {
			    $("#hora_entrada").val('00:00');
				$("#hora_saida").val('00:00');
				$("#entrada_almoco").val('00:00');
				$("#saida_almoco").val('00:00');
			  }
			});

			$("#feriado").change(function() {
			  if ($(this).prop("checked") == true) {
			   $("#hora_entrada").val('00:00');
				$("#hora_saida").val('00:00');
				$("#entrada_almoco").val('00:00');
				$("#saida_almoco").val('00:00');
			  }
			});
		
			


</script>


<script type="text/javascript">
	function listar(){

	var idFunc = $('#usuario_filtro').val();
	listarFuncionario(idFunc);

	var data = $("#data_agenda").val();
	let data_brasileira = data.split('-').reverse().join('/');
	$("#data-dia").text(data_brasileira);
	var usuario = $("#usuario_filtro").val();
	$("#data-modal").val(data);

	var data_inicio_mes = $("#data_inicio_mes").val();
	var data_final_mes = $("#data_final_mes").val();

	buscarData();
	buscarDataAnterior()

    $.ajax({
        url: 'paginas/' + pag + "/listar.php",
        method: 'POST',
        data: {data, usuario, data_inicio_mes, data_final_mes},
        dataType: "text",

        success:function(result){
            $("#listar").html(result);
        }
    });
}
</script>


<script type="text/javascript">
	$(document).ready(function() {
		$('.sel300').select2({
			
		});
	});
</script>


<script type="text/javascript">
	$(document).ready(function() {
		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});
	});
</script>


<script>

$("#form-text").submit(function () {
	event.preventDefault();
    
	var formData = new FormData(this);

	$.ajax({
		url: 'paginas/' + pag + "/inserir.php",
		type: 'POST',
		data: formData,

		success: function (mensagem) {
            $('#mensagem').text('');
            $('#mensagem').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {                    
                    $('#btn-fechar').click();
                    listar();                    
                } else {
                	$('#mensagem').addClass('text-danger')
                    $('#mensagem').text(mensagem)
                }

            },

            cache: false,
            contentType: false,
            processData: false,
            
        });

});

</script>


<script type="text/javascript">
	
	function listarFuncionario(id){

		document.getElementById('folga').checked = false;
        document.getElementById('feriado').checked = false;
        document.getElementById('falta').checked = false;


	var data = $("#data_agenda").val();	
    $.ajax({
        url: 'paginas/' + pag + "/listar-funcionarios.php",
        method: 'POST',
        data: {id, data},
        dataType: "text",

        success:function(result){
        	var array = result.split("-/");
            $('#nome-func').text(array[0]);
            $('#hora_entrada').val(array[1]);
            $('#hora_saida').val(array[2]);
            $('#entrada_almoco').val(array[3]);
            $('#saida_almoco').val(array[4]);
            $('#id').val(array[8]);
            
            if(array[5] == 'Sim'){
            	document.getElementById('folga').checked = true;
            }

            if(array[6] == 'Sim'){
            	document.getElementById('feriado').checked = true;
            }

            if(array[7] == 'Sim'){
            	document.getElementById('falta').checked = true;
            }
        }
    });
}

	function limparCampos(){
		$('#id').val('');
		$('#titulo').val('');		
		$('#descricao').val('');
		$('#hora').val('');				
		$('#data').val('<?=$data_atual?>');	
		nicEditors.findEditor("area").setContent('');	
		
	}


	function modalPonto(){	
		
		$('#modalForm').modal('show');
	}

	function editar(id, func, data){
		$("#data_agenda").val(data);
		$("#id").val(id);
		listarFuncionario(func);
		modalPonto();

	}


	function listarCalendario(){
		$.ajax({
        url: 'paginas/' + pag + "/listar-calendario.php",
        method: 'POST',
        data: $('#form').serialize(),
        dataType: "html",

        success:function(result){
            $("#calendario").html(result);
        }
    });
	}


	function buscarData(){
		var idFunc = $('#usuario_filtro').val();
		var data_inicio_mes = $("#data_inicio_mes").val();
		var data_final_mes = $("#data_final_mes").val();

		$.ajax({
				        url: 'paginas/' + pag + "/buscar-data.php",
				        method: 'POST',
				        data: {idFunc, data_inicio_mes, data_final_mes},
				        dataType: "text",

				        success:function(result){				        	
				        	$('#dias_busca').val(result)

				        }
				    });
	}


	function buscarDataAnterior(){
		var idFunc = $('#usuario_filtro').val();
		var data_inicio_mes = $("#data_inicio_mes").val();
		var data_final_mes = $("#data_final_mes").val();

		$.ajax({
				        url: 'paginas/' + pag + "/buscar-data-anterior.php",
				        method: 'POST',
				        data: {idFunc, data_inicio_mes, data_final_mes},
				        dataType: "text",

				        success:function(result){				        	
				        	$('#dias_busca_anterior').val(result)

				        }
				    });
	}

</script>



<script type="text/javascript">
		$(document).ready(function() {

			$('#mycalendar').monthly({
				mode: 'event',
				
			});

			$('#mycalendar2').monthly({
				mode: 'picker',
				target: '#mytarget',
				setWidth: '250px',
				startHidden: true,
				showTrigger: '#mytarget',
				stylePast: true,
				disablePast: true
			});

		switch(window.location.protocol) {
		case 'http:':
		case 'https:':
		// running on a server, should be good.
		break;
		case 'file:':
		alert('Just a heads-up, events will not work when run locally.');
		}

		});
		</script>
	<!-- //calendar -->