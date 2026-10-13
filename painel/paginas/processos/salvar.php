<?php 
@session_start();
$id_usuario = @$_SESSION['id'];
$tabela = 'processos';
require_once("../../../conexao.php");

$cliente = $_POST['cliente'];
$valor = $_POST['valor'];
$valor = str_replace(',', '.', $valor);
$tipo_acao = $_POST['tipo_acao'];
$numero_processo = $_POST['numero_processo'];
$status = $_POST['status'];
$segredo_justica = $_POST['segredo_justica'];
$justica_gratuita = $_POST['justica_gratuita'];
$obs = $_POST['obs'];

$orgao_julgador = $_POST['orgao_julgador'];
$data_abertura = $_POST['data_abertura'];

$advogado1 = @$_POST['advogado1'];
$advogado2 = @$_POST['advogado2'];
$advogado3 = @$_POST['advogado3'];
$advogado4 = @$_POST['advogado4'];


$jurisdicao = $_POST['jurisdicao'];
$vara = $_POST['vara'];
$comarca = $_POST['comarca'];


$nome_contraria = $_POST['nome_contraria'];
$cpf_contraria = $_POST['cpf_contraria'];
$rg_contraria = $_POST['rg_contraria'];
$telefone_contraria = $_POST['telefone_contraria'];
$advogado_contraria = $_POST['advogado_contraria'];
$endereco_contraria = $_POST['endereco_contraria'];
$estado_civil_contraria = $_POST['estado_civil_contraria'];

$id = $_POST['id'];


//validacao num processo
if($numero_processo != ""){
	$query = $pdo->query("SELECT * from $tabela where num_processo = '$numero_processo'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$id_reg = @$res[0]['id'];
	if(@count($res) > 0 and $id != $id_reg){
		echo 'Processo já Cadastrado!';
		exit();
	}
}


if($status == 'Finalizado'){
	$sql_data = ' , data_fechamento = curDate()';
}else{
	$sql_data = '';
}

if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET num_processo = :num_processo, tipo_acao = :tipo_acao, jurisdicao = :jurisdicao, vara = :vara, comarca = :comarca, valor = :valor, segredo = :segredo, justica_gratuita = :justica_gratuita, orgao_julgador = :orgao_julgador, status = :status, advogado1 = :advogado1, advogado2 = :advogado2, advogado3 = :advogado3, advogado4 = :advogado4, data_abertura = :data_abertura, cliente = :cliente, usuario_lanc = :usuario_lanc, nome_contraria = :nome_contraria, telefone_contraria = :telefone_contraria, cpf_contraria = :cpf_contraria, rg_contraria = :rg_contraria, endereco_contraria = :endereco_contraria, estado_civil_contraria = :estado_civil_contraria, advogado_contraria = :advogado_contraria, data_cad = curDate(), obs = :obs, hora_verificacao = '$hora_random_verificar'");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET num_processo = :num_processo, tipo_acao = :tipo_acao, jurisdicao = :jurisdicao, vara = :vara, comarca = :comarca, valor = :valor, segredo = :segredo, justica_gratuita = :justica_gratuita, orgao_julgador = :orgao_julgador, status = :status, advogado1 = :advogado1, advogado2 = :advogado2, advogado3 = :advogado3, advogado4 = :advogado4, data_abertura = :data_abertura, cliente = :cliente, usuario_lanc = :usuario_lanc, nome_contraria = :nome_contraria, telefone_contraria = :telefone_contraria, cpf_contraria = :cpf_contraria, rg_contraria = :rg_contraria, endereco_contraria = :endereco_contraria, estado_civil_contraria = :estado_civil_contraria, advogado_contraria = :advogado_contraria, data_cad = curDate(), obs = :obs $sql_data, hora_verificacao = '$hora_random_verificar' where id = '$id'");
}

$query->bindValue(":num_processo", "$numero_processo");
$query->bindValue(":tipo_acao", "$tipo_acao");
$query->bindValue(":jurisdicao", "$jurisdicao");
$query->bindValue(":vara", "$vara");
$query->bindValue(":comarca", "$comarca");
$query->bindValue(":valor", "$valor");
$query->bindValue(":segredo", "$segredo_justica");
$query->bindValue(":justica_gratuita", "$justica_gratuita");
$query->bindValue(":orgao_julgador", "$orgao_julgador");
$query->bindValue(":status", "$status");
$query->bindValue(":advogado1", "$advogado1");
$query->bindValue(":advogado2", "$advogado2");
$query->bindValue(":advogado3", "$advogado3");
$query->bindValue(":advogado4", "$advogado4");
$query->bindValue(":data_abertura", "$data_abertura");
$query->bindValue(":cliente", "$cliente");
$query->bindValue(":usuario_lanc", "$id_usuario");
$query->bindValue(":nome_contraria", "$nome_contraria");
$query->bindValue(":telefone_contraria", "$telefone_contraria");
$query->bindValue(":cpf_contraria", "$cpf_contraria");
$query->bindValue(":rg_contraria", "$rg_contraria");
$query->bindValue(":endereco_contraria", "$endereco_contraria");
$query->bindValue(":estado_civil_contraria", "$estado_civil_contraria");
$query->bindValue(":advogado_contraria", "$advogado_contraria");
$query->bindValue(":obs", "$obs");

$query->execute();
$ult_id = $pdo->lastInsertId();



$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome = @$res2[0]['nome'];	
$telefone = @$res2[0]['telefone'];
$email = @$res2[0]['email'];	


$query2 = $pdo->query("SELECT * FROM usuarios where id = '$advogado1'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_advogado1 = @$res2[0]['nome'];
$telefone_advogado1 = @$res2[0]['telefone'];

$query2 = $pdo->query("SELECT * FROM usuarios where id = '$advogado2'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_advogado2 = @$res2[0]['nome'];
$telefone_advogado2 = @$res2[0]['telefone'];

$query2 = $pdo->query("SELECT * FROM usuarios where id = '$advogado3'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_advogado3 = @$res2[0]['nome'];
$telefone_advogado3 = @$res2[0]['telefone'];

$query2 = $pdo->query("SELECT * FROM usuarios where id = '$advogado4'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_advogado4 = @$res2[0]['nome'];
$telefone_advogado4 = @$res2[0]['telefone'];


$query2 = $pdo->query("SELECT * FROM tipos_servicos where id = '$tipo_acao'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_servico = $res2[0]['nome'];
	}else{
		$nome_servico = '';
	}


if($api_whatsapp != 'Não' and $telefone_advogado1 != "" and $id == ""){
	//enviar whatsapp
		$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_advogado1);
		$mensagem_whatsapp = '*'.$nome_sistema.'*%0A';		
		$mensagem_whatsapp .= '🤩_Você foi Associado a um novo Processo_ %0A%0A';
		$mensagem_whatsapp .= '*Advogado:* '.$nome_advogado1.' %0A';
		$mensagem_whatsapp .= '*Cliente:* '.$nome.' %0A';
		$mensagem_whatsapp .= '*Tipo de Ação:* '.$nome_servico.' %0A';	
		if($numero_processo != ""){
			$mensagem_whatsapp .= '*Nº Processo:* '.$numero_processo.' %0A';	
		}
			

		require('../../apis/texto.php');
	
}


if($api_whatsapp != 'Não' and $telefone_advogado2 != "" and $id == ""){
	//enviar whatsapp
		$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_advogado2);
		$mensagem_whatsapp = '*'.$nome_sistema.'*%0A';		
		$mensagem_whatsapp .= '🤩_Você foi Associado a um novo Processo_ %0A%0A';
		$mensagem_whatsapp .= '*Advogado:* '.$nome_advogado2.' %0A';
		$mensagem_whatsapp .= '*Cliente:* '.$nome.' %0A';
		$mensagem_whatsapp .= '*Tipo de Ação:* '.$nome_servico.' %0A';	
		if($numero_processo != ""){
			$mensagem_whatsapp .= '*Nº Processo:* '.$numero_processo.' %0A';	
		}		

		require('../../apis/texto.php');
	
}


if($api_whatsapp != 'Não' and $telefone_advogado3 != "" and $id == ""){
	//enviar whatsapp
		$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_advogado3);
		$mensagem_whatsapp = '*'.$nome_sistema.'*%0A';
		$mensagem_whatsapp .= '🤩_Você foi Associado a um novo Processo_ %0A%0A';
		$mensagem_whatsapp .= '*Advogado:* '.$nome_advogado3.' %0A';
		$mensagem_whatsapp .= '*Cliente:* '.$nome.' %0A';
		$mensagem_whatsapp .= '*Tipo de Ação:* '.$nome_servico.' %0A';	
		if($numero_processo != ""){
			$mensagem_whatsapp .= '*Nº Processo:* '.$numero_processo.' %0A';	
		}			

		require('../../apis/texto.php');
	
}


if($api_whatsapp != 'Não' and $telefone_advogado4 != "" and $id == ""){
	//enviar whatsapp
		$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_advogado4);
		$mensagem_whatsapp = '*'.$nome_sistema.'*%0A';
		$mensagem_whatsapp .= '🤩_Você foi Associado a um novo Processo_ %0A%0A';
		$mensagem_whatsapp .= '*Advogado:* '.$nome_advogado4.' %0A';
		$mensagem_whatsapp .= '*Cliente:* '.$nome.' %0A';
		$mensagem_whatsapp .= '*Tipo de Ação:* '.$nome_servico.' %0A';	
		if($numero_processo != ""){
			$mensagem_whatsapp .= '*Nº Processo:* '.$numero_processo.' %0A';	
		}				

		require('../../apis/texto.php');
	
}

echo 'Salvo com Sucesso';


 ?>
