<?php
@session_start();
$id_usuario = @$_SESSION['id']; 
$tabela = 'audiencias';
require_once("../../../conexao.php");

$data_atual = 'Y-m-d';
$hora_atual = date("H:i:s");

$forum = $_POST['forum'];
$data = $_POST['data'];
$hora = $_POST['hora'];
$hora_alerta = $_POST['hora_alerta'];
$usuario_tarefa = $_POST['usuario_tarefa'];
$online = $_POST['online'];
$obs = $_POST['obs'];
$processo = $_POST['processo'];
$id = $_POST['id'];
$cliente = $_POST['cliente'];

$dataF = implode('/', array_reverse(@explode('-', $data)));
$horaF = date("H:i", @strtotime($hora));


if($processo > 0){
	$query2 = $pdo->query("SELECT * FROM processos where num_processo = '$processo'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$cliente = $res2[0]['cliente'];
	$tipo_acao = $res2[0]['tipo_acao'];

}else{
	//$cliente = 0;
	$tipo_acao = 0;
}

$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_cliente = $res2[0]['nome'];
		$tel_cliente = $res2[0]['telefone'];
	}else{
		$nome_cliente = 'Sem Registro';
		$tel_cliente = "";
	}

	$query2 = $pdo->query("SELECT * FROM tipos_servicos where id = '$tipo_acao'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_servico = $res2[0]['nome'];
	}else{
		$nome_servico = '';
	}


//validacao hora
$query = $pdo->query("SELECT * from $tabela where data = '$data' and hora = '$hora' and advogado = '$usuario_tarefa'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id != $id_reg){
	echo 'Horário já Agendado!';
	exit();
}


if(strtotime($hora_alerta) >= strtotime($hora)){
	echo 'A hora do alerta tem que ser menor que a hora do agendamento da tarefa!';
	exit();
}


$query = $pdo->query("SELECT * from usuarios where id = '$usuario_tarefa'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$telefone_usuario = @$res[0]['telefone'];




if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET advogado = '$usuario_tarefa', usuario_lanc = '$id_usuario', data = '$data', hora = '$hora', hora_mensagem = '$hora_alerta', forum = :forum,  online = '$online', obs = :obs, processo = :processo, cliente = :cliente ");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET data = '$data', hora = '$hora', hora_mensagem = '$hora_alerta', forum = :forum, online = '$online', obs = :obs, processo = :processo, cliente = :cliente where id = '$id'");
}
$query->bindValue(":forum", "$forum");
$query->bindValue(":obs", "$obs");
$query->bindValue(":processo", "$processo");
$query->bindValue(":cliente", "$cliente");
$query->execute();
$ultimo_id = $pdo->lastInsertId();



//recuperar hash
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$hash = @$res[0]['hash'];

if($hash != ""){
	require("../../apis/cancelar_agendamento.php");
}

$hash = @$res[0]['hash2'];

if($hash != ""){
	require("../../apis/cancelar_agendamento.php");
}

	//enviar whatsapp
if($api_whatsapp != 'Não' and $telefone_usuario != '' and $hora_alerta != ''){

	$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_usuario);
	$mensagem_whatsapp = '*'.$nome_sistema.'*%0A';
	$mensagem_whatsapp .= '🤩 _Lembrete de Audiência Agendada_ %0A';
	$mensagem_whatsapp .= '🕛 *Hora:* '.$horaF.' %0A';
	$mensagem_whatsapp .= '📅 *Data:* '.$dataF.' %0A';
	$mensagem_whatsapp .= '✅ *Fórum:* '.$forum.' %0A';
	if($processo > 0){
		$mensagem_whatsapp .= '✅ *Processo:* '.$processo.' %0A';
		$mensagem_whatsapp .= '✅ *Cliente:* '.$nome_cliente.' %0A';
		$mensagem_whatsapp .= '✅ *Tipo Ação:* '.$nome_servico.' %0A';
	}
	
	$mensagem_whatsapp .= '✅ *Obs:* '.$obs.' %0A';	
	$data_agd = $data.' '.$hora_alerta;
	require('../../apis/agendar.php');

	$pdo->query("UPDATE $tabela SET hash = '$hash' where id = '$ultimo_id'");
	
}



	//enviar whatsapp cliente
if($api_whatsapp != 'Não' and $tel_cliente != '' and $hora_alerta != ''){

	$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $tel_cliente);
	$mensagem_whatsapp = '*'.$nome_sistema.'*%0A';
	$mensagem_whatsapp .= '🤩 _Lembrete de Audiência Agendada_ %0A';
	$mensagem_whatsapp .= '🕛 *Hora:* '.$horaF.' %0A';
	$mensagem_whatsapp .= '📅 *Data:* '.$dataF.' %0A';
	$mensagem_whatsapp .= '✅ *Fórum:* '.$forum.' %0A';
	if($processo > 0){
		$mensagem_whatsapp .= '✅ *Processo:* '.$processo.' %0A';
		$mensagem_whatsapp .= '✅ *Cliente:* '.$nome_cliente.' %0A';
		$mensagem_whatsapp .= '✅ *Tipo Ação:* '.$nome_servico.' %0A';
	}
	
	$mensagem_whatsapp .= '✅ *Obs:* '.$obs.' %0A';	
	$data_agd = $data.' '.$hora_alerta;
	require('../../apis/agendar.php');

	$pdo->query("UPDATE $tabela SET hash2 = '$hash' where id = '$ultimo_id'");
	
}

echo 'Salvo com Sucesso';
 ?>