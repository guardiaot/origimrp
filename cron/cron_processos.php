<?php 
require_once("../conexao.php");

$query = $pdo->query("SELECT * from processos where status = 'Andamento' and hora_verificacao <= curTime() and (data_verificacao != curDate() or data_verificacao is null) order by id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$processos_verificados = @count($res);
for ($i = 0; $i < $processos_verificados; $i++) {
	
	$id = $res[$i]['id'];	
	$cliente = $res[$i]['cliente'];	
	$num_processo = $res[$i]['num_processo'];
	$tipo_acao = $res[$i]['tipo_acao'];	
	$advogado1 = $res[$i]['advogado1'];
	$advogado2 = $res[$i]['advogado2'];
	$advogado3 = $res[$i]['advogado3'];
	$advogado4 = $res[$i]['advogado4'];	


	
	
	
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


	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$advogado1'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_advogado1 = $res2[0]['nome'];
		$telefone_advogado1 = $res2[0]['telefone'];
	}else{
		$nome_advogado1 = '';
		$telefone_advogado1 = '';
	}


	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$advogado2'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_advogado2 = $res2[0]['nome'];
		$telefone_advogado2 = $res2[0]['telefone'];
	}else{
		$nome_advogado2 = '';
		$telefone_advogado2 = '';
	}


	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$advogado3'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_advogado3 = $res2[0]['nome'];
		$telefone_advogado3 = $res2[0]['telefone'];
	}else{
		$nome_advogado3 = '';
		$telefone_advogado3 = '';
	}

	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$advogado4'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_advogado4 = $res2[0]['nome'];
		$telefone_advogado4 = $res2[0]['telefone'];
	}else{
		$nome_advogado4 = '';
		$telefone_advogado4 = '';
	}


	//buscar na api de processos se há uma nova atualização do mesmo
	$numero = $num_processo;
	require("api_processos/exec_api.php");

	if(@$movimentacao_encontrada == ""){
		$movimentacao_encontrada = "Não";
	}

	echo 'Movimentado '.@$movimentacao_encontrada;
	echo '<br>';

	//enviar whatsapp
		if ($api_whatsapp != 'Não' and @$movimentacao_encontrada == 'Sim') {
			
			$dataAjuizamentoF = $dataAjuizamento->format('d/m/Y');			

			$mensagem = '🕛 *' . $nome_sistema . '*%0A';
			$mensagem .= '*Nova Movimentação no Processo* %0A';	
			$mensagem .= '*Processo:* '.$num_processo.' %0A';		
			$mensagem .= '*Cliente:* '.$nome_cliente.' %0A';		
			$mensagem .= '*Ação:* '.$nome_servico.' %0A%0A';
			$mensagem .= '*Última Movimentação:* '.$dataAjuizamentoF.' %0A';
			$mensagem .= '*Atualização:* '.$texto_movimentacao.' %0A';	
			
			if($telefone_advogado1 != ""){
				$telefone_envio = '55' . preg_replace('/[ ()-]+/', '', $telefone_advogado1);
				require('texto.php');
			}

			if($telefone_advogado2 != ""){
				$telefone_envio = '55' . preg_replace('/[ ()-]+/', '', $telefone_advogado2);
				require('texto.php');
			}

			if($telefone_advogado3 != ""){
				$telefone_envio = '55' . preg_replace('/[ ()-]+/', '', $telefone_advogado3);
				require('texto.php');
			}

			if($telefone_advogado4 != ""){
				$telefone_envio = '55' . preg_replace('/[ ()-]+/', '', $telefone_advogado4);
				require('texto.php');
			}
			

			if(@$status_mensagem == "Mensagem enviada com sucesso." and $api_whatsapp == 'menuia'){
				$pdo->query("UPDATE processos SET data_verificacao = curDate() where id = '$id'");
			}

			if($api_whatsapp != 'menuia'){
				$pdo->query("UPDATE processos SET data_verificacao = curDate() where id = '$id'");
			}
			
		}

}

echo 'Processos Verificados '.$processos_verificados;

 ?>