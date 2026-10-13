<?php 
@session_start();
$mostrar_registros = @$_SESSION['registros'];
$tabela = 'pericias';
require_once("../../verificar.php");
require_once("../../../conexao.php");

if(@$_SESSION['nivel'] != 'Administrador' and @$_SESSION['nivel'] != 'Escritório'){
	require_once("../../verificar_permissoes.php");
}else{
	$mostrar_registros = 'Sim';
}


if($mostrar_registros == "Não"){
	$sql_registros = " or usuario_lanc = '$id_usuario' ";
}else{
	$sql_registros = " ";
}

$dataInicial = @$_POST['p1'];
$dataFinal = @$_POST['p2'];


$data_atual = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$data_inicio_mes = $ano_atual."-".$mes_atual."-01";
$data_inicio_ano = $ano_atual."-01-01";
$data_final_ano = $ano_atual."-12-31";

$data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($data_atual)));
$data_amanha = date('Y-m-d', strtotime("+1 days",strtotime($data_atual)));


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
	$dataInicial = $data_inicio_mes;
}

if($dataFinal == ""){
	$dataFinal = $data_final_mes;
}

if($mostrar_registros == 'Sim'){	
$query = $pdo->query("SELECT * from $tabela where data >= '$dataInicial' and data <= '$dataFinal' order by id desc");
}else{
	$query = $pdo->query("SELECT * from $tabela where data >= '$dataInicial' and data <= '$dataFinal' and advogado = '$id_usuario' $sql_registros order by id desc");
}
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML

	<table class="table text-nowrap border-bottom dt-responsive" id="tabela">
	<thead> 
	<tr class="bg-primary"> 
	<th>Processo</th>		
	<th>Cliente</th>
	<th>Advogado</th>
	<th>Data</th>
	<th>Área</th>
	<th>Perito</th>
	<th>Especialista</th>
	<th>Concedida</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;


for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
	$perito = $res[$i]['perito'];
	$especialista = $res[$i]['especialista'];
	$data = $res[$i]['data'];
	$hora = $res[$i]['hora'];
	$advogado = $res[$i]['advogado'];
	$usuario_lanc = $res[$i]['usuario_lanc'];
	$forum = $res[$i]['forum'];
	$area = $res[$i]['area'];
	$obs = $res[$i]['obs'];
	$hora_mensagem = $res[$i]['hora_mensagem'];
	$hash = $res[$i]['hash'];
	$processo = $res[$i]['processo'];
	$concedida = $res[$i]['concedida'];
	$cliente = $res[$i]['cliente'];

	$dataF = implode('/', array_reverse(@explode('-', $data)));
	$horaF = date("H:i", @strtotime($hora));

	//recuperar informações do processo
	

	$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$nome_cliente = @$res2[0]['nome'];	

	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$advogado'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$nome_advogado = @$res2[0]['nome'];	

		
echo <<<HTML
<tr>

<td>{$processo}</td>
  <td>{$nome_cliente}</td>
  <td>{$nome_advogado}</td>
  <td>{$dataF} <small><span style="color:blue">{$horaF}</span></small></td>
  <td>{$area}</td>
  <td>{$perito}</td>
  <td>{$especialista}</td>
  <td>{$concedida}</td>

<td>
	<big><a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}','{$perito}','{$especialista}','{$data}','{$hora}','{$advogado}','{$usuario_lanc}','{$forum}','{$area}','{$obs}','{$hora_mensagem}','{$hash}','{$processo}','{$concedida}')" title="Editar Dados"><i class="fa fa-edit "></i></a></big>

	<div class="dropdown" style="display: inline-block;">                      
                        <a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash"></i> </a>
                        <div  class="dropdown-menu tx-13">
                       <div class="dropdown-item-text botao_excluir">
                        <p>Confirmar Exclusão? <a href="#" onclick="excluirPericia('{$id}')"><span class="text-danger">Sim</span></a></p>
                        </div>
                        </div>
                        </div>

                    <a class="btn btn-primary btn-sm" href="#" onclick="mostrar('{$perito}','{$especialista}','{$dataF}','{$horaF}','{$nome_advogado}','{$forum}','{$area}','{$obs}','{$processo}','{$concedida}','{$nome_cliente}')" title="Mostrar Dados"><i class="fa fa-info-circle "></i></a>
</td>
</tr>
HTML;

}


echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
HTML;

}else{
	echo '<small>Nenhum Registro Encontrado!</small>';
}

?>



<script type="text/javascript">
	$(document).ready( function () {	
			
		if ( $.fn.DataTable.isDataTable('#tabela') ) {
		    $('#tabela').DataTable().destroy();
		}

		$('#tabela').DataTable({
		    "language" : {
		        //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
		    },
		    "ordering": false,
		    "stateSave": true
		});


} );
</script>

<script type="text/javascript">
	function editar(id, perito, especialista, data, hora, advogado, usuario_lanc, forum, area, obs, hora_mensagem, hash, processo, concedida) {
		$('#mensagem').text('');
    	$('#titulo_inserir').text('Editar Registro');

    	
    	$('#id').val(id);
  $('#perito').val(perito);
  $('#especialista').val(especialista);
  $('#data_tarefa').val(data);
  $('#hora').val(hora);
  $('#advogado').val(advogado).change();
  $('#usuario_lanc').val(usuario_lanc);
  $('#forum').val(forum);
  $('#area').val(area);
  $('#obs').val(obs);
  $('#hora_alerta').val(hora_mensagem);

  $('#processo').val(processo).change();
  $('#concedida').val(concedida).change();
    
    	$('#modalForm').modal('show');
	}




function mostrar(perito, especialista, dataF, horaF, nome_advogado, forum, area, obs, processo, concedida, nome_cliente){

				    	
    	$('#titulo_dados').text(nome_cliente);
    	$('#perito_dados').text(perito);
		$('#especialista_dados').text(especialista);
		$('#data_dados').text(dataF);
		$('#hora_dados').text(horaF);
		$('#advogado_dados').text(nome_advogado);
		$('#forum_dados').text(forum);
		$('#area_dados').text(area);
		$('#obs_dados').text(obs);
		$('#processo_dados').text(processo);
		$('#concedida_dados').text(concedida);
		$('#cliente_dados').text(nome_cliente);
    	
    	
    	$('#modalDados').modal('show');
	}

	function limparCampos(){
		$('#id').val('');
    	
    	$('#perito').val('');
  $('#especialista').val('');
  $('#data_tarefa').val('');
  $('#hora').val('');
  $('#advogado').val('0').change();
  $('#usuario_lanc').val('');
  $('#forum').val('');
  $('#area').val('');
  $('#obs').val('');
  $('#hora_alerta').val('');

  $('#processo').val('0').change();
  $('#concedida').val('Sim').change();
    

    	$('#ids').val('');
    	$('#btn-deletar').hide();	
	}

	function selecionar(id){

		var ids = $('#ids').val();

		if($('#seletor-'+id).is(":checked") == true){
			var novo_id = ids + id + '-';
			$('#ids').val(novo_id);
		}else{
			var retirar = ids.replace(id + '-', '');
			$('#ids').val(retirar);
		}

		var ids_final = $('#ids').val();
		if(ids_final == ""){
			$('#btn-deletar').hide();
		}else{
			$('#btn-deletar').show();
		}
	}

	function deletarSel(){
		var ids = $('#ids').val();
		var id = ids.split("-");
		
		for(i=0; i<id.length-1; i++){
			excluirMultiplos(id[i]);			
		}

		setTimeout(() => {
		  	buscar();	
		}, 1000);

		limparCampos();
	}
</script>