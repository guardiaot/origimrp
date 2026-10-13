<?php 

require_once("verificar.php");
$pag = 'casos';

//verificar se ele tem a permissão de estar nessa página
if(@$casos == 'ocultar'){
	echo "<script>window.location='index'</script>";
	exit();
}
?>

<!-- CSS do Trumbowyg -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trumbowyg@2.25.1/dist/ui/trumbowyg.min.css">

<!-- JS do Trumbowyg -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.25.1/dist/trumbowyg.min.js"></script>


<div class="justify-content-between">
	<form action="rel/casos_class.php" target="_blank" method="POST">
 	<div class="left-content mt-2 mb-3">

 <div style="display: inline-block; position:absolute; right:10px; margin-bottom: 10px">
			<button style="width:140px" type="submit" class="btn btn-danger ocultar_mobile_app" title="Gerar Relatório"><i class="fa fa-file-pdf-o"></i> Relatório </button>
		</div>	
        

         <div class="cab_mobile"></div>               

         <div style="display: inline-block; margin-bottom: 10px">
			<input type="date" name="dataInicial" id="dataInicial" style="height:35px; width:49%; font-size: 13px;" value="<?php echo $data_inicio_ano ?>" onchange="buscar()">

			<input type="date" name="dataFinal" id="dataFinal" style="height:35px; width:49%; font-size: 13px" value="<?php echo $data_final_ano ?>" onchange="buscar()">	
		</div>	


		<select class="form-select" name="status" id="status_busca" style="display:inline-block; width:200px" onchange="buscar()">
			<option value="">Selecine um Status</option>
			<option value="Pendente">Pendente</option>
			<option value="Análise">Em Análise</option>
			<option value="Concluído">Concluído</option>			
		</select>
		


		</div>			
		
		</form>
		
	</div>



	<div class="card-group" style="margin-bottom: -30px">
	
	<div class="card text-center mb-5" style="width: 100%; margin-right: 10px; border-radius: 10px; height:110px">
		<a class="text-white" href="#" onclick="$('#tipo_data_filtro').val('Pendente'); buscar(); ">
			<div class="card-header bg-red border-light">
	             Pendentes
	            <i class="fa fa-external-link pull-right"></i>
	        </div>
	        <div class="card-body">
	        	<p class="card-text" style="margin-top:-15px;">
	        		<h4><span class="text-danger" id="total_pendente">0</span></h4>
	        	</p>
	        </div>
        </a>
    </div>
    


    <div class="card text-center mb-5" style="width: 100%; margin-right: 10px; border-radius: 10px; height:110px">
    	<a href="#" onclick="$('#tipo_data_filtro').val('Em Análise'); buscar(); ">
			<div class="card-header border-light text-white" style="background: #1e237d">
	            Em Análise
	            <i class="fa fa-external-link pull-right"></i>
	        </div>
	        <div class="card-body">
	        	<p class="card-text" style="margin-top:-15px;">
	        		<h4><span style="color: #1e237d" id="total_analise">0</span></h4>
	        	</p>
	        </div>
        </a>
    </div>


    <div class="card text-center mb-5" style="width: 100%; margin-right: 10px; border-radius: 10px; height:110px">
    	<a href="#" onclick="$('#tipo_data_filtro').val('Concluído');  buscar(); ">
			<div class="card-header border-light text-white" style="background: #22854d">
	            Concluídos
	            <i class="fa fa-external-link pull-right"></i>
	        </div>
	        <div class="card-body">
	        	<p class="card-text" style="margin-top:-15px;">
	        		<h4><span style="color: #22854d" id="total_concluido">0</span></h4>
	        	</p>
	        </div>
        </a>
    </div>

    


<input type="hidden" id="tipo_data_filtro">
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
			<form id="form_casos">
				<div class="modal-body">
	

  

        <div class="mb-3">
          <label class="form-label">Tipo de problema jurídico</label>
          <select name="tipo_acao" id="tipo_acao" class="form-select" required>
            <option value="">Selecione</option>
            <?php 
              $query = $pdo->query("SELECT * from tipos_servicos order by nome asc");
              $res = $query->fetchAll(PDO::FETCH_ASSOC);
              foreach($res as $item){
                echo '<option value="'.$item['id'].'">'.$item['nome'].'</option>';
              }
            ?>
            <option value="0">Outros</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Descreva o caso com o máximo de detalhes</label>
          <textarea name="descricao" id="descricao" rows="6" class="form-control" placeholder="Explique o que ocorreu, datas, partes envolvidas..." required></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Quem está envolvido no caso?</label>
          <input type="text" name="partes" id="partes" class="form-control" placeholder="Ex: empresa tal, vizinho, ex-cônjuge, etc." required>
        </div>

        <div class="row">
        <div class="mb-3 col-md-6">
          <label class="form-label">Já existe processo em andamento?</label>
          <select name="processo_existente" id="processo_existente" class="form-select">            
            <option>Sim</option>
            <option>Não</option>
          </select>
        </div>


         <div class="mb-3 col-md-6">
          <label class="form-label">Advogado</label>
          <select name="advogado" id="advogado" class="form-select" required>
            <option value="0">Selecionar Advogado</option>
            <?php 
              $query = $pdo->query("SELECT * from usuarios where nivel = 'Advogado' || nivel = 'Advogada' order by nome asc");
              $res = $query->fetchAll(PDO::FETCH_ASSOC);
              foreach($res as $item){
                echo '<option value="'.$item['id'].'">'.$item['nome'].'</option>';
              }
            ?>
          </select>
        </div>
  
        </div>


        <div class="mb-3">
          <label class="form-label">O que você espera com esse atendimento?</label>
          <textarea name="objetivo" id="objetivo" rows="4" class="form-control" placeholder="Ex: Receber uma indenização, conseguir guarda dos filhos, etc." required></textarea>
        </div>

       

   <input type="hidden" name="id" id="id">

    <br>
    <small><div id="mensagem" class="text-center"></div></small>


						</div>
						<div class="modal-footer">     
							<?php if($token_chatgpt != "" || $token_gemini != ""){ ?>  

								<select name="escolha" id="escolha" class="form-select" required style="width:200px">
						            <?php 
              $query = $pdo->query("SELECT * from prompts order by nome asc");
              $res = $query->fetchAll(PDO::FETCH_ASSOC);
              foreach($res as $item){
                echo '<option value="'.$item['id'].'">'.$item['nome'].'</option>';
              }
            ?>
						          </select>


								<button type="button" onclick="gerarAnalise()" id="btn_analise" class="btn btn-success">Análise com IA</button>
							<?php } ?>
							<button type="submit" id="btn_salvar" class="btn btn-primary">Editar</button>
						</div>
					</form>
				</div>
			</div>
		</div>




<!-- Modal Texto Orientação Juridica -->
<div class="modal fade" id="modalPDF" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel">Gerar Orientação Jurídica</h4>
				<button id="btn-fechar-contratos" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
						class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form method="POST" action="rel/caso_class.php" target="_blank">
			<div class="modal-body">
				 <div class="row">
                    <div class="col-md-12 mb-2" style="font-weight: normal !important">
                        <label>Texto</label>
                        <textarea class="form-control" id="texto" name="texto" style="font-weight: normal !important">
                        </textarea>
                    </div>
                </div>
				<input type="hidden" id="id_pdf_modal" name="id">
			</div>

			<div class="modal-footer">  
							<button type="submit" id="btn_gerar_pdf" class="btn btn-primary">Gerar PDF</button>
						</div>

			</form>
		</div>
	</div>
</div>







<div id="loadingOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
    background: rgba(0, 0, 0, 0.95); /* Deixar a tela bem escura para ver o que aparece */
    color: white; 
    display: none; 
    justify-content: center; 
    align-items: center; 
    z-index: 200000; /* <-- Aumente o valor para garantir! */
    text-align: center;">
    
    <div>
        <h2>🤖 Consumindo IA e Gerando Análise...</h2>
        <p>Aguarde, este processo pode levar alguns segundos (máximo de 30s).</p>
    </div>
</div>



<form method="post" action="rel/caso_class.php" style="display:none" target="_blank">
	<input type="hidden" name="id" id="id_pdf">
	<button type="submit" id="btn_pdf"></button>
</form>



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
			var status_filtro = $('#tipo_data_filtro').val();
			
			listar(status, dataInicial, dataFinal, status_filtro)

		}
		</script>



		<script type="text/javascript">
			function fecharModal(){
				$('#modalForm').modal('show');
			}

</script>

	
<script type="text/javascript">
	


    $("#form_casos").submit(function () {

      event.preventDefault();
      alertCarregando("Inserindo...")
      var formData = new FormData(this);    
      $('#btn_salvar').hide();
      $.ajax({
        url: 'paginas/' + pag + "/salvar.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {            
          if (mensagem.trim() == "Salvo com Sucesso") {  
            alertsucesso(mensagem);
            listar();
          } else {
            alertInformativo(mensagem)
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
			function alterarStatus(id, status){
				
				$.ajax({
					url: 'paginas/' + pag + "/alterar_status.php",
					method: 'POST',
					data: {id, status},
					dataType: "text",

					success:function(result){
						buscar()
					}
				});
			}
</script>



<script type="text/javascript">
    function gerarAnalise(){
        // 1. CHAMA o novo carregamento
        iniciarCarregamentoIA('🤖 Consumindo IA e Gerando Análise...');
        $('#btn_analise').hide();
        
        var id = $('#id').val();
        var escolha = $('#escolha').val();

        $('#id_pdf').val(id);
        
        $.ajax({
            url: 'paginas/' + pag + "/gerar_analise.php",
            method: 'POST',
            data: {id, escolha},
            dataType: "json",
            
            success: function(result){
                // 2. FECHA o carregamento
                fecharCarregamentoIA();

                
                if(result.status === 'sucesso'){
                    alertsucesso(result.mensagem); 
                    
                    // 3. Inicia o PDF
                    $('#btn_pdf').click();
                } else {
                    alertWarning(result.mensagem);
                }
            },
            
            error: function(xhr, status, error){
                // 2. FECHA o carregamento no caso de erro
                fecharCarregamentoIA();
                alertWarning("❌ Erro inesperado na comunicação com o servidor.\n" + error);
            },
            
            complete: function(){
                 $('#btn_analise').show();
            }
        });
    }
</script>





<script>
$(document).ready(function () {
    $('#texto').trumbowyg({
        btns: [
            ['viewHTML'],
            ['undo', 'redo'],
            ['formatting'],
            ['strong', 'em', 'underline'],
            ['justifyLeft', 'justifyCenter', 'justifyRight'],
            ['unorderedList', 'orderedList'],
            ['removeformat']
        ],
        autogrow: true
    });
});
</script>



<script type="text/javascript">
	function iniciarCarregamentoIA(mensagem) {
    // 1. Esconde o backdrop do modal original, forçando o novo overlay a aparecer
    $('.modal-backdrop').hide(); // Se estiver usando Bootstrap
    $('#btn-fechar').click();
    
    // 2. Mostra o novo overlay
    $('#loadingOverlay').show();
    $('#loadingOverlay h2').text(mensagem);
}

function fecharCarregamentoIA() {
    $('#loadingOverlay').hide();
    // 3. Mostra o backdrop original de volta (se for necessário manter o modal aberto)
    $('.modal-backdrop').show(); 
}
</script>