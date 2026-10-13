<?php 
$tabela = 'movimentacao_processo';
require_once("../../../conexao.php");
@session_start();
$id_usuario = @$_SESSION['id'];

$id = $_POST['id-movimentacoes'];
$titulo = $_POST['titulo'];
$descricao = $_POST['descricao'];
$notificar = $_POST['notificar'];
$visivel_cliente = @$_POST['visivel_cliente'];

$query = $pdo->prepare("INSERT INTO $tabela SET processo = :processo,  data = curDate(), usuario = '$id_usuario', titulo = :titulo, descricao = :descricao, visivel_cliente = :visivel_cliente");

$query->bindValue(":processo", "$id");
$query->bindValue(":titulo", "$titulo");
$query->bindValue(":descricao", "$descricao");
$query->bindValue(":visivel_cliente", "$visivel_cliente");
$query->execute();



$query2 = $pdo->query("SELECT * FROM processos where id = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$cliente = $res2[0]['cliente'];	
$advogado1 = $res2[0]['advogado1'];	
$advogado2 = $res2[0]['advogado2'];	
$advogado3 = $res2[0]['advogado3'];	
$advogado4 = $res2[0]['advogado4'];	


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




$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome = @$res2[0]['nome'];	
$telefone = @$res2[0]['telefone'];
$email = @$res2[0]['email'];	

if($notificar == 'Sim' and $visivel_cliente == 'Sim'){

	//enviar whatsapp
	if($api_whatsapp != 'Não' and $telefone != ''){

		$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
		$mensagem_whatsapp = '*'.$nome_sistema.'*%0A';
		$mensagem_whatsapp .= '🤩_Olá '.$nome.' Seu Processo foi atualizado_ %0A';
		$mensagem_whatsapp .= '*Título:* '.$titulo.' %0A';
		$mensagem_whatsapp .= '*Descrição:* '.$descricao.' %0A';		

		require('../../apis/texto.php');
	}

	//enviar email
	if($email != ''){
		$url_logo = $url_sistema.'img/logo.png';
		$destinatario = $email;
		$assunto = utf8_decode('Seu Processo foi atualizado '. $nome_sistema);
		$mensagem_email = 'Olá '.$nome.' seu processo foi atualizado! <br>';
		$mensagem_email .= '<b>Título</b>: '.$titulo.'<br>';
		$mensagem_email .= '<b>Descrição: </b>'.$descricao.'<br><br>';
		
		$mensagem_email .= "<img src='".$url_logo."' width='200px'> ";
		require('../../apis/disparar_email.php');
	}

}


if($api_whatsapp != 'Não' and $telefone_advogado1 != ""){
	//enviar whatsapp
		$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_advogado1);
		$mensagem_whatsapp = '*'.$nome_sistema.'*%0A';		
		$mensagem_whatsapp .= '🤩_O processo do cliente '.$nome.' foi atualizado_ %0A%0A';
		$mensagem_whatsapp .= '*Advogado:* '.$nome_advogado1.' %0A';
		$mensagem_whatsapp .= '*Título:* '.$titulo.' %0A';
		$mensagem_whatsapp .= '*Descrição:* '.$descricao.' %0A';		

		require('../../apis/texto.php');
	
}


if($api_whatsapp != 'Não' and $telefone_advogado2 != ""){
	//enviar whatsapp
		$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_advogado2);
		$mensagem_whatsapp = '*'.$nome_sistema.'*%0A';
		$mensagem_whatsapp .= '🤩_O processo do cliente '.$nome.' foi atualizado_ %0A%0A';
		$mensagem_whatsapp .= '*Advogado:* '.$nome_advogado2.' %0A';
		$mensagem_whatsapp .= '*Título:* '.$titulo.' %0A';
		$mensagem_whatsapp .= '*Descrição:* '.$descricao.' %0A';		

		require('../../apis/texto.php');
	
}


if($api_whatsapp != 'Não' and $telefone_advogado3 != ""){
	//enviar whatsapp
		$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_advogado3);
		$mensagem_whatsapp = '*'.$nome_sistema.'*%0A';
		$mensagem_whatsapp .= '🤩_O processo do cliente '.$nome.' foi atualizado_ %0A%0A';
		$mensagem_whatsapp .= '*Advogado:* '.$nome_advogado3.' %0A';
		$mensagem_whatsapp .= '*Título:* '.$titulo.' %0A';
		$mensagem_whatsapp .= '*Descrição:* '.$descricao.' %0A';		

		require('../../apis/texto.php');
	
}


if($api_whatsapp != 'Não' and $telefone_advogado4 != ""){
	//enviar whatsapp
		$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_advogado4);
		$mensagem_whatsapp = '*'.$nome_sistema.'*%0A';
		$mensagem_whatsapp .= '🤩_O processo do cliente '.$nome.' foi atualizado_ %0A%0A';
		$mensagem_whatsapp .= '*Advogado:* '.$nome_advogado4.' %0A';
		$mensagem_whatsapp .= '*Título:* '.$titulo.' %0A';
		$mensagem_whatsapp .= '*Descrição:* '.$descricao.' %0A';		

		require('../../apis/texto.php');
	
}

echo 'Inserido com Sucesso';

?>