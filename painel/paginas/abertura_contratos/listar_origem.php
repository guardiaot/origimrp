<?php 
require_once("../../../conexao.php");

$id = $_POST['cliente'];

$query = $pdo->query("SELECT * from clientes where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$indicacao = @$res[0]['indicacao'];
echo $indicacao;
?>