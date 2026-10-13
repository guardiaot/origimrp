<?php 

require_once("verificar.php");
$pag = 'beneficios';

//verificar se ele tem a permissão de estar nessa página
if(@$beneficios == 'ocultar'){
	echo "<script>window.location='index'</script>";
	exit();
}
?>

<div class="justify-content-between">
	<form action="rel/processos_class.php" target="_blank" method="POST">
 	<div class="left-content mt-2 mb-3">
 <a style="margin-bottom: 10px; margin-top: 5px" class="btn ripple btn-primary text-white  <?php echo $inserir_processos ?>" onclick="inserir()" type="button"><i class="fe fe-plus me-2"></i>Novo Benefício</a>



         <div class="cab_mobile"></div>               

         <div title="Filtro por Data de Início do Benefício" style="display: inline-block; margin-bottom: 10px">
			<input type="date" name="dataInicial" id="dataInicial" style="height:35px; width:49%; font-size: 13px;" value="<?php echo $data_inicio_mes ?>" onchange="buscar()">

			<input type="date" name="dataFinal" id="dataFinal" style="height:35px; width:49%; font-size: 13px" value="<?php echo $data_final_mes ?>" onchange="buscar()">	
		</div>	


		<select class="form-select" name="status" id="status_busca" style="display:inline-block; width:200px" onchange="buscar()">
			<option value="">Todos</option>
			<option value="ativo">Ativo</option>
      <option value="suspenso">Suspenso</option>
      <option value="cessado">Cessado</option>
      <option value="cancelado">Cancelado</option>
      <option value="convertido">Convertido</option>
		</select>
		


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

<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
	<div class="modal-dialog modal-lg" >
		<div class="modal-content" >
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form id="form_beneficios">
				<div class="modal-body">
					
					
					<div class="row g-3">

  <!-- CLIENTE (FK) -->

    <div class="col-md-5 mb-2">							
									<label>Cliente</label>
									<div id="listar_clientes">
										
									</div>

								</div>

								<div class="col-md-1 mb-2" style="padding: 0px;">							
									<button style="margin-top: 26px;" type="button" class="btn btn-primary" href="" data-bs-toggle="modal" data-bs-target="#modalCliente"><i class="fa fa-plus "></i></button>				
								</div>


  <!-- NB -->
  <div class="col-md-6">
    <div class="form-floating">
      <input type="text" class="form-control" id="nb" name="nb" placeholder="NB" required value="<?= @$nb ?>">
      <label for="nb">NB (Número do Benefício)</label>
    </div>
  </div>

  <!-- TIPO DE BENEFÍCIO -->
  <div class="col-md-6">
    <label class="form-label mb-1">Tipo de Benefício</label>
    <select class="form-select" id="tipo_beneficio" name="tipo_beneficio" required>
      <option value="">Selecione...</option>
      <option value="Auxílio por Incapacidade Temporária"  <?= (@$tipo_beneficio=='aux_temp'?'selected':'') ?>>Auxílio por Incapacidade Temporária</option>
      <option value="Auxílio Acidente"  <?= (@$tipo_beneficio=='aux_acid'?'selected':'') ?>>Auxílio-acidente</option>
      <option value="Aposentadoria por Incapacidade"  <?= (@$tipo_beneficio=='ap_incap'?'selected':'') ?>>Aposentadoria por Incapacidade Permanente</option>
      <option value="Pensão por Morte"    <?= (@$tipo_beneficio=='pensao'?'selected':'') ?>>Pensão por Morte</option>
      <option value="BPC/LOAS"       <?= (@$tipo_beneficio=='bpc'?'selected':'') ?>>BPC/LOAS</option>
      <option value="Outros"    <?= (@$tipo_beneficio=='outros'?'selected':'') ?>>Outros</option>
    </select>
  </div>

  <!-- STATUS -->
  <div class="col-md-6">
    <label class="form-label mb-1">Status</label>
    <select class="form-select" id="status" name="status" required>
      <option value="ativo">Ativo</option>
      <option value="suspenso">Suspenso</option>
      <option value="cessado">Cessado</option>
      <option value="cancelado">Cancelado</option>
      <option value="convertido">Convertido</option>
    </select>
  </div>

  <!-- DER / DIB / DII -->
  <div class="col-md-4">
    <div class="form-floating">
      <input type="date" class="form-control" id="der" name="der" placeholder="DER" value="<?= @$der ?>">
      <label for="der">DER (Entrada Requerimento)</label>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-floating">
      <input type="date" class="form-control" id="dib" name="dib" placeholder="DIB" value="<?= @$dib ?>" required>
      <label for="dib">DIB (Início Benefício)</label>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-floating">
      <input type="date" class="form-control" id="dii" name="dii" placeholder="DII" value="<?= @$dii ?>">
      <label for="dii">DII (Início Incapacidade)</label>
    </div>
  </div>

  <!-- ALTA PROGRAMADA / DCB PREVISTA / DCB -->
  <div class="col-md-4">
    <label class="form-label mb-1">Alta Programada</label>
    <select class="form-select" id="alta_programada" name="alta_programada" required>
      <option value="Não" <?= (@$alta_programada=='Não'?'selected':'') ?>>Não</option>
      <option value="Sim" <?= (@$alta_programada=='Sim'?'selected':'') ?>>Sim</option>
    </select>
  </div>

  <div class="col-md-4">
    <div class="form-floating">
      <input type="date" class="form-control" id="dcb_prevista" name="dcb_prevista" placeholder="DCB Prevista" value="<?= @$dcb_prevista ?>">
      <label for="dcb_prevista">DCB (Cessão Prevista)</label>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-floating">
      <input type="date" class="form-control" id="dcb" name="dcb" placeholder="DCB" value="<?= @$dcb ?>">
      <label for="dcb">DCB (Cessão Finalizada)</label>
    </div>
  </div>

  <!-- MOTIVO / ORIGEM -->
  <div class="col-md-6">
    <div class="form-floating">
      <input type="text" class="form-control" id="motivo_cessacao" name="motivo_cessacao" placeholder="Motivo da cessação" value="<?= @$motivo_cessacao ?>">
      <label for="motivo_cessacao">Motivo da Cessação</label>
    </div>
  </div>

  <div class="col-md-6">
    <label class="form-label mb-1">Origem da Decisão</label>
    <select class="form-select" id="origem_decisao" name="origem_decisao">
      <option value="">Selecione...</option>
      <option value="pericia"        <?= (@$origem_decisao=='pericia'?'selected':'') ?>>Perícia</option>
      <option value="administrativo" <?= (@$origem_decisao=='administrativo'?'selected':'') ?>>Administrativo</option>
      <option value="judicial"       <?= (@$origem_decisao=='judicial'?'selected':'') ?>>Judicial</option>
    </select>
  </div>

  <!-- DOC CESSAÇÃO -->
  <div class="col-md-6">
    <div class="form-floating">
      <input type="text" class="form-control" id="doc_cessacao" name="doc_cessacao" placeholder="URL / caminho do documento" value="<?= @$doc_cessacao ?>">
      <label for="doc_cessacao">Doc. Cessação (URL/Caminho)</label>
    </div>
    <!-- Se preferir upload, troque por: <input type="file" class="form-control" id="doc_cessao_arquivo" name="doc_cessao_arquivo"> -->
  </div>

  <!-- OBS -->
  <div class="col-md-6">
    <div class="form-floating">
      <input type="text" class="form-control" id="obs" name="obs" placeholder="Observações"><?= @$obs ?>
      <label for="obs">Observações</label>
    </div>
  </div>

  <!-- HIDDEN -->
  <input type="hidden" id="usuario_cadastro" name="usuario_cadastro" value="<?= @$usuario_cadastro ?>">
</div>


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





		<!-- Modal Arquivos -->
		<div class="modal fade" id="modalArquivos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-primary text-white">
						<h4 class="modal-title" id="tituloModal">Gestão de Arquivos - <span id="nome-arquivo"> </span></h4>
						<button id="btn-fechar-arquivos" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
					</div>
					<form id="form-arquivos" method="post">
						<div class="modal-body">

							<div class="row">
								<div class="col-md-8">						
									<div class="form-group"> 
										<label>Arquivo</label> 
										<input class="form-control" type="file" name="arquivo_conta" onChange="carregarImgArquivos();" id="arquivo_conta">
									</div>	
								</div>
								<div class="col-md-4">	
									<div id="divImgArquivos">
										<img src="images/arquivos/sem-foto.png"  width="60px" id="target-arquivos">									
									</div>					
								</div>




							</div>

							<div class="row" >
								<div class="col-md-8">
									<input type="text" class="form-control" name="nome-arq"  id="nome-arq" placeholder="Nome do Arquivo * " required>
								</div>

								<div class="col-md-4">										 
									<button type="submit" class="btn btn-primary">Inserir</button>
								</div>
							</div>

							<hr>

							<small><div id="listar-arquivos"></div></small>

							<br>
							<small><div align="center" id="mensagem-arquivo"></div></small>

							<input type="hidden" class="form-control" name="id-arquivo"  id="id-arquivo">


						</div>
					</form>
				</div>
			</div>
		</div>







		<!-- Modal Cliente -->
		<div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header bg-primary text-white">
						<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_inserir"></span></h4>
						<button onclick="fecharModal()" id="btn-fechar_cliente" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
					</div>
					<form id="form_cliente">
						<div class="modal-body">


							<div class="row">
								<div class="col-md-6 mb-2 col-6">							
									<label>Nome</label>
									<input type="text" class="form-control" id="nome" name="nome" placeholder="Seu Nome" required>							
								</div>

								<div class="col-md-3 col-6">							
									<label>Telefone</label>
									<input type="text" class="form-control" id="telefone" name="telefone" placeholder="Seu Telefone">							
								</div>		

								<div class="col-md-3 mb-2">							
									<label>Nascimento</label>
									<input type="date" class="form-control" id="data_nasc" name="data_nasc" placeholder="" >							
								</div>


							</div>


							<div class="row">

								<div class="col-md-2 mb-2 col-6">							
									<label>Pessoa</label>
									<select name="tipo_pessoa" id="tipo_pessoa" class="form-select" onchange="mudarPessoa()">
										<option value="Física">Física</option>
										<option value="Jurídica">Jurídica</option>
									</select>							
								</div>		

								<div class="col-md-3 mb-2 col-6">							
									<label>CPF / CNPJ</label>
									<input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF/CNPJ" >							
								</div>


								<div class="col-md-3">							
									<label>RG</label>
									<input type="text" class="form-control" id="rg" name="rg" placeholder="RG" >							
								</div>


								<div class="col-md-4">							
									<label>Email</label>
									<input type="email" class="form-control" id="email" name="email" placeholder="Email" >							
								</div>


							</div>

							<div class="row">

								<div class="col-md-2 mb-2">							
									<label>CEP</label>
									<input type="text" class="form-control" id="cep" name="cep" placeholder="CEP" onblur="pesquisacep(this.value);">							
								</div>

								<div class="col-md-5 mb-2">							
									<label>Rua</label>
									<input type="text" class="form-control" id="endereco" name="endereco" placeholder="Rua" >							
								</div>

								<div class="col-md-2 mb-2">							
									<label>Número</label>
									<input type="text" class="form-control" id="numero" name="numero" placeholder="Número" >							
								</div>

								<div class="col-md-3 mb-2">							
									<label>Complemento</label>
									<input type="text" class="form-control" id="complemento" name="complemento" placeholder="Se houver" >							
								</div>



							</div>


							<div class="row">

								<div class="col-md-4 mb-2">							
									<label>Bairro</label>
									<input type="text" class="form-control" id="bairro" name="bairro" placeholder="Bairro" >							
								</div>

								<div class="col-md-5 mb-2">							
									<label>Cidade</label>
									<input type="text" class="form-control" id="cidade" name="cidade" placeholder="Cidade" >							
								</div>

								<div class="col-md-3 mb-2">							
									<label>Estado</label>
									<select class="form-select" id="estado" name="estado">
										<option value="">Selecionar</option>
										<option value="AC">Acre</option>
										<option value="AL">Alagoas</option>
										<option value="AP">Amapá</option>
										<option value="AM">Amazonas</option>
										<option value="BA">Bahia</option>
										<option value="CE">Ceará</option>
										<option value="DF">Distrito Federal</option>
										<option value="ES">Espírito Santo</option>
										<option value="GO">Goiás</option>
										<option value="MA">Maranhão</option>
										<option value="MT">Mato Grosso</option>
										<option value="MS">Mato Grosso do Sul</option>
										<option value="MG">Minas Gerais</option>
										<option value="PA">Pará</option>
										<option value="PB">Paraíba</option>
										<option value="PR">Paraná</option>
										<option value="PE">Pernambuco</option>
										<option value="PI">Piauí</option>
										<option value="RJ">Rio de Janeiro</option>
										<option value="RN">Rio Grande do Norte</option>
										<option value="RS">Rio Grande do Sul</option>
										<option value="RO">Rondônia</option>
										<option value="RR">Roraima</option>
										<option value="SC">Santa Catarina</option>
										<option value="SP">São Paulo</option>
										<option value="SE">Sergipe</option>
										<option value="TO">Tocantins</option>
										<option value="EX">Estrangeiro</option>
									</select>												
								</div>


							</div>


								<div class="row">

						<div class="col-md-5 mb-2">							
							<label>Profissão</label>
							<input type="text" class="form-control" id="profissao" name="profissao" placeholder="Profissão" >							
						</div>

						<div class="col-md-4 mb-2">							
							<label>Nacionalidade</label>
							<input type="text" class="form-control" id="nacionalidade" name="nacionalidade" placeholder="Nacionalidade" >							
						</div>

							<div class="col-md-3 mb-2">							
							<label>Estado Civil</label>
							<select class="form-select" id="estado_civil" name="estado_civil">
							<option value="Solteiro(a)">Solteiro(a)</option>
							<option value="Casado(a)">Casado(a)</option>
							<option value="Divorciado(a)">Divorciado(a)</option>
							<option value="Viúvo(a)">Viúvo(a)</option>
							</select>							
						</div>

					
				</div>



							<div class="row">
								<div class="col-md-6 mb-2">							
									<label>Genitor</label>
									<input type="text" class="form-control" id="genitor" name="genitor" placeholder="Nome do Pai" >							
								</div>

								<div class="col-md-6 mb-2">							
									<label>Genitora</label>
									<input type="text" class="form-control" id="genitora" name="genitora" placeholder="Nome da mãe" >							
								</div>
							</div>




							<input type="hidden" class="form-control" id="id" name="id">					

							<br>
							<small><div id="mensagem_cliente" align="center"></div></small>
						</div>
						<div class="modal-footer">       
							<button type="submit" id="btn_salvar_cliente" class="btn btn-primary">Salvar</button>
						</div>
					</form>
				</div>
			</div>
		</div>





<!-- MODAL MOSTRAR BENEFÍCIO -->
<div class="modal fade" id="modalMostrar" tabindex="-1" aria-labelledby="modalMostrarLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h4 class="modal-title" id="modalMostrarLabel">Detalhes do Benefício</h4>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">

          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <h6 class="text-muted mb-3">Identificação</h6>
              <p class="mb-2"><strong>NB:</strong> <span id="m_nb"></span></p>
              <p class="mb-2"><strong>Cliente:</strong> <span id="m_cliente"></span></p>
              <p class="mb-2"><strong>Tipo de Benefício:</strong> <span id="m_tipo"></span></p>
              <p class="mb-0"><strong>Status:</strong> <span id="m_status"></span></p>
            </div>
          </div>

          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <h6 class="text-muted mb-3">Datas</h6>
              <div class="row">
                <div class="col-6"><p class="mb-2"><strong>DER:</strong> <span id="m_der"></span></p></div>
                <div class="col-6"><p class="mb-2"><strong>DIB:</strong> <span id="m_dib"></span></p></div>
                <div class="col-6"><p class="mb-2"><strong>DII:</strong> <span id="m_dii"></span></p></div>
                <div class="col-6"><p class="mb-2"><strong>DCB Prev.:</strong> <span id="m_dcb_prevista"></span></p></div>
                <div class="col-6"><p class="mb-0"><strong>DCB:</strong> <span id="m_dcb"></span></p></div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <h6 class="text-muted mb-3">Cessação</h6>
              <p class="mb-2"><strong>Alta Programada:</strong> <span id="m_alta_programada"></span></p>
              <p class="mb-2"><strong>Motivo da Cessação:</strong> <span id="m_motivo"></span></p>
              <p class="mb-0"><strong>Origem da Decisão:</strong> <span id="m_origem"></span></p>
            </div>
          </div>

          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <h6 class="text-muted mb-3">Documento de Cessação</h6>
              <!-- Se for link, o JS mostra este <a>; caso contrário, mostra o span de texto -->
              <a id="m_doc_link" href="#" target="_blank" class="d-none"></a>
              <span id="m_doc_texto"></span>
            </div>
          </div>

          <div class="col-12">
            <div class="border rounded p-3">
              <h6 class="text-muted mb-2">Observações</h6>
              <div id="m_obs" class="small"></div>
            </div>
          </div>

        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>








		<script type="text/javascript">var pag = "<?=$pag?>"</script>
		<script src="js/ajax.js"></script>

		<script type="text/javascript">
			$(document).ready(function() {
				listarClientes();
				$('.sel2').select2({
					dropdownParent: $('#modalForm')
				});

			});


			function buscar(){
			var status = $('#status_busca').val();
			var dataInicial = $('#dataInicial').val();
			var dataFinal = $('#dataFinal').val();
			
			listar(status, dataInicial, dataFinal)

		}
		</script>


		<script type="text/javascript">
			$("#form-arquivos").submit(function () {
				event.preventDefault();
				var formData = new FormData(this);

				$.ajax({
					url: 'paginas/' + pag + "/arquivos.php",
					type: 'POST',
					data: formData,

					success: function (mensagem) {
						$('#mensagem-arquivo').text('');
						$('#mensagem-arquivo').removeClass()
						if (mensagem.trim() == "Inserido com Sucesso") {                    
						//$('#btn-fechar-arquivos').click();
						$('#nome-arq').val('');
						$('#arquivo_conta').val('');
						$('#target-arquivos').attr('src','images/arquivos/sem-foto.png');
						listarArquivos();
					} else {
						$('#mensagem-arquivo').addClass('text-danger')
						$('#mensagem-arquivo').text(mensagem)
					}

				},

				cache: false,
				contentType: false,
				processData: false,

			});

			});
		</script>

		<script type="text/javascript">
			function listarArquivos(){
				var id = $('#id-arquivo').val();	
				$.ajax({
					url: 'paginas/' + pag + "/listar-arquivos.php",
					method: 'POST',
					data: {id},
					dataType: "text",

					success:function(result){
						$("#listar-arquivos").html(result);
					}
				});
			}

		</script>




		<script type="text/javascript">
			function carregarImgArquivos() {
				var target = document.getElementById('target-arquivos');
				var file = document.querySelector("#arquivo_conta").files[0];

				var arquivo = file['name'];
				resultado = arquivo.split(".", 2);

				if(resultado[1] === 'pdf'){
					$('#target-arquivos').attr('src', "images/pdf.png");
					return;
				}

				if(resultado[1] === 'rar' || resultado[1] === 'zip'){
					$('#target-arquivos').attr('src', "images/rar.png");
					return;
				}

				if(resultado[1] === 'doc' || resultado[1] === 'docx' || resultado[1] === 'txt'){
					$('#target-arquivos').attr('src', "images/word.png");
					return;
				}


				if(resultado[1] === 'xlsx' || resultado[1] === 'xlsm' || resultado[1] === 'xls'){
					$('#target-arquivos').attr('src', "images/excel.png");
					return;
				}


				if(resultado[1] === 'xml'){
					$('#target-arquivos').attr('src', "images/xml.png");
					return;
				}



				var reader = new FileReader();

				reader.onloadend = function () {
					target.src = reader.result;
				};

				if (file) {
					reader.readAsDataURL(file);

				} else {
					target.src = "";
				}
			}
		</script>


		<script type="text/javascript">
			function fecharModal(){
				$('#modalForm').modal('show');
			}





			$("#form_cliente").submit(function () {

				event.preventDefault();
				var formData = new FormData(this);

				$('#mensagem_cliente').text('Salvando...')
				$('#btn_salvar_cliente').hide();

				$.ajax({
					url: 'paginas/clientes/salvar.php',
					type: 'POST',
					data: formData,

					success: function (mensagem) {
						$('#mensagem_cliente').text('');
						$('#mensagem_cliente').removeClass()
						if (mensagem.trim() == "Salvo com Sucesso") {

							$('#btn-fechar_cliente').click();
							listarClientes('ult');
							limparCamposClientes();

							$('#mensagem_cliente').text('')          

						} else {

							$('#mensagem_cliente').addClass('text-danger')
							$('#mensagem_cliente').text(mensagem)
						}

						$('#btn_salvar_cliente').show();

					},

					cache: false,
					contentType: false,
					processData: false,

				});

			});



			function listarClientes(ult){

				$.ajax({
					url: 'paginas/' + pag + "/listar_clientes.php",
					method: 'POST',
					data: {ult},
					dataType: "text",

					success:function(result){
						$("#listar_clientes").html(result);
					}
				});
			}


				

		</script>


	
<script type="text/javascript">
	

$("#form_beneficios").submit(function () {

    event.preventDefault();    
    var formData = new FormData(this);

    
    $('#btn_salvar').hide();

    $.ajax({
        url: 'paginas/' + pag + "/salvar.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {           
            if (mensagem.trim() == "Salvo com Sucesso") {            	

                $('#btn-fechar').click();
                buscar();

                $('#mensagem').text('')          

            } else {

                alertWarning(mensagem)
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

	$(document).ready( function () {
		verMasc();
	});

	function verMasc(){
		var cpf = $('#cpf_contraria').val();
		
		if(cpf.length >= 15){
			$('#cpf_contraria').mask('00.000.000/0000-00');
		}else{
			$('#cpf_contraria').mask('000.000.000-000');
		}
	}
	
</script>







		<script type="text/javascript">
	

	function limparCamposClientes(){
		
    	$('#nome').val('');
    	$('#email').val('');
    	$('#telefone').val('');
    	$('#endereco').val('');
    	$('#cpf').val('');
    	$('#tipo_pessoa').val('Física').change();
    	$('#data_nasc').val('');
    	$('#visto_por').val('Todos').change();


    	$('#rg').val('');
    	$('#complemento').val('');
    	$('#genitor').val('');
    	$('#genitora').val('');

    	$('#numero').val('');
    	$('#bairro').val('');
    	$('#cidade').val('');
    	$('#estado').val('').change();
    	$('#cep').val('');

    	$('#indicacao').val('');

    	$('#profissao').val('');
    	$('#nacionalidade').val('');
    	$('#estado_civil').val('Solteiro(a)');

    		
	}
</script>