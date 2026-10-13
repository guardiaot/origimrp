<?php 
@session_start();
$id_usuario = @$_SESSION['id'];
$tabela = 'casos';
require_once("../../../conexao.php");

$tipo_acao = filter_var(@$_POST['tipo_acao'], @FILTER_SANITIZE_STRING);
$descricao = filter_var(@$_POST['descricao'], @FILTER_SANITIZE_STRING);
$partes = filter_var(@$_POST['partes'], @FILTER_SANITIZE_STRING);
$processo_existente = filter_var(@$_POST['processo_existente'], @FILTER_SANITIZE_STRING);
$objetivo = filter_var(@$_POST['objetivo'], @FILTER_SANITIZE_STRING);
$advogado = filter_var(@$_POST['advogado'], @FILTER_SANITIZE_STRING);
$id = $_POST['id'];


$query = $pdo->prepare("UPDATE $tabela SET tipo_acao = :tipo_acao, descricao = :descricao, partes = :partes, processo_existente = :processo_existente, objetivo = :objetivo, id_advogado = :id_advogado where id = '$id'");


$query->bindValue(":tipo_acao", "$tipo_acao");
$query->bindValue(":descricao", "$descricao");
$query->bindValue(":partes", "$partes");
$query->bindValue(":processo_existente", "$processo_existente");
$query->bindValue(":objetivo", "$objetivo");
$query->bindValue(":id_advogado", "$advogado");
$query->execute();


echo 'Salvo com Sucesso';


 ?>
