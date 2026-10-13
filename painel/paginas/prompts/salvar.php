<?php 
@session_start();


$tabela = 'prompts';
require_once("../../../conexao.php");


$id = @$_POST['id'];
$texto = @$_POST['texto'];
$titulo = @$_POST['titulo'];


if($titulo == ''){
	echo 'Preecha o titulo do contrato!';
	exit();
}

if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, texto = :texto");
}else{
$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, texto = :texto where id = '$id'");
}
$query->bindValue(":nome", "$titulo");
$query->bindValue(":texto", "$texto");
$query->execute();

echo 'Salvo com Sucesso';
 ?>