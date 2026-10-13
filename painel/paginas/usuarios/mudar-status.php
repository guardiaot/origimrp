<?php 
$tabela = 'usuarios';
require_once("../../../conexao.php");

if($modo_teste == 'Sim'){
	echo "No modo de testes você não pode editar esses recursos!";
	exit();
}

$id = $_POST['id'];
$acao = $_POST['acao'];

$pdo->query("UPDATE $tabela SET ativo = '$acao' WHERE id = '$id' ");
echo 'Alterado com Sucesso';
?>