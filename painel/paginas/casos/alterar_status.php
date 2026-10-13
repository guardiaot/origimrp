<?php 
$tabela = 'casos';
require_once("../../../conexao.php");

$id = $_POST['id'];
$status = $_POST['status'];

$pdo->query("UPDATE $tabela SET status = '$status' WHERE id = '$id' ");
?>