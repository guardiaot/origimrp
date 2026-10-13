<?php 
require_once("../conexao.php");
$cpf = filter_var(@$_POST['cpf'], @FILTER_SANITIZE_STRING);

$query = $pdo->prepare("SELECT * from clientes where cpf = :cpf");
	$query->bindValue(":cpf", "$cpf");
	$query->execute();
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res) > 0){
		echo $res[0]['nome'].'*'.$res[0]['telefone'];
	}
?>