<?php 
@session_start();
require_once("../verificar.php");
require_once("../../conexao.php");
$link = @$_POST['link'];
$modelo = @$_POST['modelo'];
$telefone = @$_POST['telefone'];
//api whats
	if ($api_whatsapp != 'Não' and $telefone != '') {
		$telefone_envio = '55' . preg_replace('/[ ()-]+/', '', $telefone);
		$mensagem_whatsapp = '*' . $nome_sistema . '*%0A%0A';	
		$mensagem_whatsapp .= '_Assinatura do Contrato_ %0A';	
		$mensagem_whatsapp .= '*Contrato:* ' . $modelo . ' %0A';
		$mensagem_whatsapp .= '*Link:* ' . $link . ' %0A%0A';		
		$mensagem = $mensagem_whatsapp;
		require('../apis/texto.php');
	}

echo 'Link enviado!';
 ?>