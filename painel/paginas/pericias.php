<?php 
@session_start();
$id_usuario = @$_SESSION['id'];
require_once("verificar.php");
$pag = 'pericias';

if(@$audiencias == 'ocultar'){
	echo "<script>window.location='index'</script>";
	exit();
}

?>

<link rel="stylesheet" type="text/css" href="css/style-calendario.css">

<div class="justify-content-between">
	<form action="rel/pericias_class.php" target="_blank" method="POST">
 	<div class="left-content mt-2 mb-3">
 <a style="margin-bottom: 10px; margin-top: 5px" class="btn ripple btn-primary text-white  <?php echo $inserir_processos ?>" onclick="inserir()" type="button"><i class="fe fe-plus me-2"></i>Nova Perícia</a>


 <div style="display: inline-block; position:absolute; right:10px; margin-bottom: 10px">
			<button style="width:140px" type="submit" class="btn btn-danger ocultar_mobile_app" title="Gerar Relatório"><i class="fa fa-file-pdf-o"></i> Relatório</button>
		</div>	
        

         <div class="cab_mobile"></div>               

         <div style="display: inline-block; margin-bottom: 10px">
			<input type="date" name="dataInicial" id="dataInicial" style="height:35px; width:49%; font-size: 13px;" value="<?php echo $data_inicio_mes ?>" onchange="buscar()">

			<input type="date" name="dataFinal" id="dataFinal" style="height:35px; width:49%; font-size: 13px" value="<?php echo $data_final_mes ?>" onchange="buscar()">	
		</div>	

		</div>			
		
		</form>
		
	</div>


<div class="row row-sm">
<div class="col-lg-12">
<div class="card custom-card">
<div class="card-body" id="listar">

</div>
</div>
</div>
</div>


<input type="hidden" id="ids">

<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_inserir"></span></h4>
				 <button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form id="form_pericia">
			<div class="modal-body">


				<div class="row">
					<div class="col-md-12">							
							<label>Associar Cliente</label>
								<select name="cliente" id="cliente" class="sel2" style="width:100%; height:35px; ">
								<option value="0">Selecione um Cliente</option>
								<?php 
								if($mostrar_registros == 'Não'){
									$query = $pdo->query("SELECT * from clientes where usuario = '$id_usuario' or visto_por = 'Todos' order by id asc");
								}else{
									$query = $pdo->query("SELECT * from clientes order by id asc");
								}
								$res = $query->fetchAll(PDO::FETCH_ASSOC);
								$linhas = @count($res);
								if($linhas > 0){
									for($i=0; $i<$linhas; $i++){
										echo '<option value="'.$res[$i]['id'].'">'.$res[$i]['nome'].'</option>';
									}
								}
								?>	
							</select>								
						</div>	
				</div>

				<div class="row">


						
				
						
						<div class="col-md-4 mb-2">							
								<label>Data</label>
								<input type="date" class="form-control" id="data_tarefa" name="data" required="" value="<?php echo $data_atual ?>">							
						</div>


						<div class="col-md-4 mb-2">							
								<label>Hora</label>
								<input type="time" class="form-control" id="hora" name="hora" placeholder="">							
						</div>


						
						<div class="col-md-4 mb-2">							
								<label>Hora Alerta (Receber notificação)</label>
								<input type="time" class="form-control" id="hora_alerta" name="hora_alerta" placeholder="" >							
						</div>
						
					</div>


					<div class="row">	

						<div class="col-md-9 mb-2">							
								<label>Área da Perícia</label>	
									<input type="text" class="form-control" id="area" name="area" placeholder="médica, contábil, engenharia, etc" >													
						</div>


						<div class="col-md-3 mb-2">							
								<label>Concedida</label>
								<select class="form-select" id="concedida" name="concedida" >
									<option value="Sim">Sim</option>
									<option value="Não">Não</option>
									
								</select>					
						</div>

						
					</div>

					<div class="row">
						<div class="col-md-6 mb-2 ">							
											<label>Processo</label>
											<select name="processo" id="processo" class="sel2" style="width:100%; height:35px; " >
												<option value="0">Selecionar Processo</option>
												<?php 
												if($mostrar_registros == 'Sim'){									
												$query = $pdo->query("SELECT * from processos where status = 'Andamento'");
											}else{
												$query = $pdo->query("SELECT * from processos where status = 'Andamento' and (usuario_lanc = '$id_usuario' or  advogado1 = '$id_usuario' or advogado2 = '$id_usuario' or advogado3 = '$id_usuario' or advogado4 = '$id_usuario') ");
											}
												$res = $query->fetchAll(PDO::FETCH_ASSOC);
												$linhas = @count($res);
												if($linhas > 0){
													for($i=0; $i<$linhas; $i++){
														$num_processo = $res[$i]['num_processo'];
														$tipo_acao = $res[$i]['tipo_acao'];
														$cliente = $res[$i]['cliente'];

														$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
														$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
														if(@count($res2) > 0){
															$nome_cliente = $res2[0]['nome'];
														}else{
															$nome_cliente = 'Sem Registro';
														}

														$query2 = $pdo->query("SELECT * FROM tipos_servicos where id = '$tipo_acao'");
														$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
														if(@count($res2) > 0){
															$nome_servico = $res2[0]['nome'];
														}else{
															$nome_servico = '';
														}

														$texto_proc = $num_processo.' / '.$nome_servico.' / '.$nome_cliente;

														echo '<option value="'.$res[$i]['num_processo'].'">'.$texto_proc.'</option>';
													}
												}
												?>	
											</select>								
										</div>	


										<div class="col-md-6 mb-2 ">	

										<label>Advogado</label>	
											<select class="form-control sel2 " aria-label="Default select example" name="advogado" id="advogado" style="width:100%;" onchange="buscar()">	
												<option value="0">Selecionar Advogado</option>

				<?php 
				if($nivel_usuario == 'Administrador' || $nivel_usuario == 'Escritório'){
					$query = $pdo->query("SELECT * FROM usuarios where nivel = 'Advogado'");
					
				}else{
					$query = $pdo->query("SELECT * FROM usuarios where id = '$id_usuario' ");
				}
				

				$res = $query->fetchAll(PDO::FETCH_ASSOC);

				for($i=0; $i < @count($res); $i++){

					if($res[$i]['visto_por'] == 'Não' and $res[$i]['usuario_lanc'] != $id_usuario and $res[$i]['id'] != $id_usuario){
						continue;
					}

						?>	

						<option value="<?php echo $res[$i]['id'] ?>" <?php if($res[$i]['id'] == $id_usuario){ ?> selected <?php } ?> ><?php echo $res[$i]['nome'] ?> </option>



				<?php } ?>			

				

			</select>
										</div>	
					</div>



				

					<div class="row">
						<div class="col-md-4 mb-2">							
								<label>Vara / Comarca</label>
								<input maxlength="255" type="text" class="form-control" id="forum" name="forum" placeholder="Local se necessário">							
						</div>

						<div class="col-md-4 mb-2">							
								<label>Perito</label>
								<input maxlength="255" type="text" class="form-control" id="perito" name="perito" placeholder="Nome do Perito">							
						</div>

						<div class="col-md-4 mb-2">							
								<label>Técnico Especialista</label>
								<input maxlength="255" type="text" class="form-control" id="especialista" name="especialista" placeholder="Especialista que irá acompanhar">							
						</div>

						<div class="col-md-12 mb-2">							
								<label>Observação</label>
								<input maxlength="255" type="text" class="form-control" id="obs" name="obs" placeholder="Alguma observação adicional">							
						</div>
						
					</div>

					<input type="hidden" class="form-control" id="usuario_tarefa" name="usuario_tarefa" value="<?php echo $id_usuario ?>">	
					<input type="hidden" class="form-control" id="id" name="id">					

				<br>
				<small><div id="mensagem" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button type="submit" id="btn_salvar" class="btn btn-primary">Salvar</button>
			</div>
			</form>
		</div>
	</div>
</div>





	<!-- Modal Dados -->
	<div class="modal fade" id="modalDados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_dados"></span></h4>
					<button id="btn-fechar-dados" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
				</div>

				<div class="modal-body">


					<div class="row">


						<div class="col-md-12">
							<div class="tile">
								<div class="table-responsive">
									<table id="" class="text-left table table-bordered">
										
										<tr>
  <td class="bg-warning alert-warning">Perito</td>
  <td><span id="perito_dados"></span></td>
</tr>
<tr>
  <td class="bg-warning alert-warning">Especialista</td>
  <td><span id="especialista_dados"></span></td>
</tr>
<tr>
  <td class="bg-warning alert-warning">Data</td>
  <td><span id="data_dados"></span></td>
</tr>
<tr>
  <td class="bg-warning alert-warning">Hora</td>
  <td><span id="hora_dados"></span></td>
</tr>
<tr>
  <td class="bg-warning alert-warning">Advogado</td>
  <td><span id="advogado_dados"></span></td>
</tr>
<tr>
  <td class="bg-warning alert-warning">Fórum</td>
  <td><span id="forum_dados"></span></td>
</tr>
<tr>
  <td class="bg-warning alert-warning">Área</td>
  <td><span id="area_dados"></span></td>
</tr>
<tr>
  <td class="bg-warning alert-warning">Observação</td>
  <td><span id="obs_dados"></span></td>
</tr>
<tr>
  <td class="bg-warning alert-warning">Processo</td>
  <td><span id="processo_dados"></span></td>
</tr>
<tr>
  <td class="bg-warning alert-warning">Concedida</td>
  <td><span id="concedida_dados"></span></td>
</tr>
<tr>
  <td class="bg-warning alert-warning">Cliente</td>
  <td><span id="cliente_dados"></span></td>
</tr>
									 

									</table>
								</div>
							</div>
						</div>



						

					</div>





				</div>

			</div>
		</div>
	</div>




<input type="hidden" id="data_selecionada" value="<?php echo $data_atual ?>">
<input type="hidden" id="array_datas" value="">


<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>



<script type="text/javascript">

	$(document).ready(function() {

		setTimeout(function() {
  buscar()
}, 500)

		$('.sel2').select2({
					dropdownParent: $('#modalForm')
				});	

		$('.sel50').select2({
					//dropdownParent: $('#modalBaixar')
				});
	});

</script>


<script type="text/javascript">
	

$("#form_pericia").submit(function () {

    event.preventDefault();
    var formData = new FormData(this);

    $('#mensagem').text('Salvando...')
    $('#btn_salvar').hide();

    $.ajax({
        url: 'paginas/' + pag + "/salvar.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem').text('');
            $('#mensagem').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {

                $('#btn-fechar').click();
                buscar();

                $('#mensagem').text('')          

            } else {

                $('#mensagem').addClass('text-danger')
                $('#mensagem').text(mensagem)
            }

            $('#btn_salvar').show();

        },

        cache: false,
        contentType: false,
        processData: false,

    });

});

</script>

<script type="text/javascript">
	function buscar(){			
			var dataInicial = $('#dataInicial').val();
			var dataFinal = $('#dataFinal').val();		
			
			listar(dataInicial, dataFinal)

		}


function excluirPericia(id){   
    $('#mensagem-excluir').text('Excluindo...')
    
    $.ajax({
        url: 'paginas/' + pag + "/excluir.php",
        method: 'POST',
        data: {id},
        dataType: "html",

        success:function(mensagem){
            if (mensagem.trim() == "Excluído com Sucesso") {                
                buscar();
                
            } else {
                $('#mensagem-excluir').addClass('text-danger')
                $('#mensagem-excluir').text(mensagem)
            }
        }
    });
}



</script>

