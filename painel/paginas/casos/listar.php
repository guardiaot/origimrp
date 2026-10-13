<?php 
@session_start();
$mostrar_registros = @$_SESSION['registros'];
$id_usuario = @$_SESSION['id'];
$tabela = 'casos';
require_once("../../../conexao.php");
require_once("../../verificar.php");


$dataInicial = @$_POST['p2'];
$dataFinal = @$_POST['p3'];
$status = '%'.@$_POST['p1'].'%';
$status_filtro = @$_POST['p4'];

$data_hoje = date('Y-m-d');
$data_atual = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$data_inicio_mes = $ano_atual."-".$mes_atual."-01";
$data_inicio_ano = $ano_atual."-01-01";
$data_final_ano = $ano_atual."-12-31";

$data_ontem = date('Y-m-d', @strtotime("-1 days",@strtotime($data_atual)));
$data_amanha = date('Y-m-d', @strtotime("+1 days",@strtotime($data_atual)));


if($mes_atual == '04' || $mes_atual == '06' || $mes_atual == '09' || $mes_atual == '11'){
	$data_final_mes = $ano_atual.'-'.$mes_atual.'-30';
}else if($mes_atual == '02'){
	$bissexto = date('L', @mktime(0, 0, 0, 1, 1, $ano_atual));
	if($bissexto == 1){
		$data_final_mes = $ano_atual.'-'.$mes_atual.'-29';
	}else{
		$data_final_mes = $ano_atual.'-'.$mes_atual.'-28';
	}

}else{
	$data_final_mes = $ano_atual.'-'.$mes_atual.'-31';
}

if($dataInicial == ""){
	$dataInicial = $data_inicio_ano;
}

if($dataFinal == ""){
	$dataFinal = $data_final_ano;
}


if($mostrar_registros == 'Não'){	
	$sql_usuario_lanc = " and (id_advogado = '$id_usuario')";
}else{	
	$sql_usuario_lanc = " ";
}

$query = $pdo->query("SELECT * from $tabela where status = 'Pendente' $sql_usuario_lanc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_pendente = @count($res);

$query = $pdo->query("SELECT * from $tabela where status = 'Em Análise' $sql_usuario_lanc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_analise = @count($res);

$query = $pdo->query("SELECT * from $tabela where status = 'Concluído' $sql_usuario_lanc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_concluido = @count($res);


if($mostrar_registros == 'Não'){
	if($status_filtro != ""){
		$query = $pdo->query("SELECT * from $tabela where status like '$status_filtro' and (id_advogado = '$id_usuario') order by id desc");
	}else{
		$query = $pdo->query("SELECT * from $tabela where status like '$status' and data >= '$dataInicial' and data <= '$dataFinal' and (id_advogado = '$id_usuario') order by id desc");
	}
	
}else{
	if($status_filtro != ""){
		$query = $pdo->query("SELECT * from $tabela where status like '$status_filtro' order by id desc");
	}else{
		$query = $pdo->query("SELECT * from $tabela where status like '$status' and data >= '$dataInicial' and data <= '$dataFinal' order by id desc");
	}
	
}
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML

	<table class="table text-nowrap border-bottom dt-responsive" id="tabela">
	<thead> 
	<tr class="bg-primary"> 
	
	<th>Cliente</th>	
	<th class="esc">Telefone</th>	
	<th class="esc">Tipo Ação</th>	
	<th class="esc">Data</th>
	<th class="esc">Status</th>	
	<th class="esc">Advogado</th>	
	<th>Ações</th>
			
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
$tipo_acao = $res[$i]['tipo_acao'];
$descricao = $res[$i]['descricao'];
$partes = $res[$i]['partes'];
$processo_existente = $res[$i]['processo_existente'];
$objetivo = $res[$i]['objetivo'];
$id_cliente = $res[$i]['id_cliente'];
$advogado = $res[$i]['id_advogado'];
$status = $res[$i]['status'];
	
	

	$dataF = implode('/', array_reverse(@explode('-', $data)));	
	
	$query2 = $pdo->query("SELECT * FROM clientes where id = '$id_cliente'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_cliente = $res2[0]['nome'];
		$telefone_cliente = $res2[0]['telefone'];
	}else{
		$nome_cliente = 'Sem Registro';
		$telefone_cliente = 'Sem Registro';
	}


	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$advogado'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_advogado = $res2[0]['nome'];		
	}else{
		$nome_advogado = 'Não Selecionado';
		
	}

	$query2 = $pdo->query("SELECT * FROM tipos_servicos where id = '$tipo_acao'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_servico = $res2[0]['nome'];
	}else{
		$nome_servico = '';
	}

	if($status == 'Pendente'){
		$ocultar_botoes = '';
		$classe_status = '#9c2834';			
	}else if ($status == 'Em Análise'){
		$ocultar_botoes = '';
		$classe_status = '#1e237d';	
	}else if ($status == 'Concluído'){
		$ocultar_botoes = '';
		$classe_status = '#22854d';		
	}


	$descricaoF = rawurlencode($descricao);
	$partesF = rawurlencode($partes);
	$objetivoF = rawurlencode($objetivo);


	$query2 = $pdo->query("SELECT * from casos_ia where id_caso = '$id' order by id desc limit 1");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$linhas2 = @count($res2);
	if($linhas2 > 0){		
		$parecer = $res2[0]['parecer'];		
	}else{
		$parecer = "";		
	}

	$parecerF = rawurlencode($parecer);

echo <<<HTML
<tr >
<td>{$nome_cliente}</td>
<td>{$telefone_cliente}</td>
<td>{$nome_servico}</td>
<td>{$dataF}</td>
<td>
<div class="dropdown " style="display: inline-block;">                      
                        <a class="" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-square  mr-1" style="color:{$classe_status}"></i> {$status} <i class="fa fa-chevron-down"></i> </a>
                        <div  class="dropdown-menu tx-13">
                        <div class="dropdown-item-text" style="background:#f1f1f1">
                        <p style="margin-top:5px; font-size: 15px;">
                        
                        <div style="margin-bottom:5px;"><a href="#" onclick="alterarStatus('{$id}', 'Pendente')"><span ><i class="fa fa-square  mr-1" style="color:#9c2834"></i> Pendente</span><br></a></div>
                      <div style="margin-bottom:5px;"> <a href="#" onclick="alterarStatus('{$id}', 'Em Análise')"> <span style="margin-bottom:8px"><i class="fa fa-square  mr-1" style="color:#1e237d"></i> Em Análise</span><br></a></div>
                      <div style="margin-bottom:5px;">  <a href="#" onclick="alterarStatus('{$id}', 'Concluído')"><span style="margin-bottom:8px"><i class="fa fa-square  mr-1" style="color:#22854d"></i> Concluído</span><br></a></div>
                       
                        </p>
                        </div>
                        </div>
                        </div>


</td>
<td>{$nome_advogado}</td>

<td>
	<big><a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}','{$tipo_acao}','{$descricaoF}','{$partesF}','{$processo_existente}','{$objetivoF}','{$advogado}')" title="Editar Dados"><i class="fa fa-edit "></i></a></big>

	<div class="dropdown " style="display: inline-block;">                      
                        <a class="btn btn-danger btn-sm $ocultar_botoes" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash "></i> </a>
                        <div  class="dropdown-menu tx-13">
                        <div class="dropdown-item-text botao_excluir">
                        <p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
                        </div>
                        </div>
                        </div>



                        <big><a class="btn btn-danger btn-sm" href="#" onclick="modalPDF('{$id}','{$parecerF}')" title="PDF Orientação Jurídica"><i class="fa fa-file-pdf-o "></i></a></big>

	
</td>

</tr>
HTML;

}

}else{
	echo 'Não possui nenhum cadastro!';
}


echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
HTML;
?>


<script type="text/javascript">
	$(document).ready( function () {	
	var ordenar = "<?=$filtrar_colunas?>";
	
	if(ordenar.trim() === 'true'){
		 $('#tabela').DataTable({
    		"language" : {
            //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
	        },
	        "ordering": true,
			"stateSave": true
	    });	

		 
	}else{
		
		$('#tabela').DataTable({
    		"language" : {
            //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
	        },
	        "ordering": false,
			"stateSave": true
	    });	
	}
   


} );
</script>

<script type="text/javascript">
	$(document).ready( function () {

		$('#total_pendente').text('<?=$total_pendente?>');
	    $('#total_analise').text('<?=$total_analise?>');
	    $('#total_concluido').text('<?=$total_concluido?>');
	   
	  

  
} );
</script>


<script type="text/javascript">
	function editar(id, tipo_acao, descricao, partes, processo, objetivo, advogado){

		$('#descricao').val(decodeURIComponent(descricao));
		$('#partes').val(decodeURIComponent(partes));
		$('#objetivo').val(decodeURIComponent(objetivo));
		

		$('#mensagem').text('');
    	$('#titulo_inserir').text('Editar Registro');

    	$('#id').val(id);
    	$('#tipo_acao').val(tipo_acao).change();
    	$('#processo_existente').val(processo).change();   
    	$('#advogado').val(advogado).change();   	
		
    	
    	$('#modalForm').modal('show');
	}


	

	function modalPDF(id, texto){
    $('#id_pdf_modal').val(id);     
    
    let textoLimpo = decodeURIComponent(texto).replace(/\n/g, '<br>');
	$('#texto').trumbowyg('html', textoLimpo);


    $('#modalPDF').modal('show');
}


</script>