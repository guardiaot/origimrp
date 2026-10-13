<?php 
@session_start();
$usuario_cadastro = @$_SESSION['id'];
$tabela = 'beneficios';
require_once("../../../conexao.php");

$cliente          = isset($_POST['cliente']) ? (int)$_POST['cliente'] : null;
$nb               = isset($_POST['nb']) ? trim($_POST['nb']) : null;
$tipo_beneficio   = isset($_POST['tipo_beneficio']) ? trim($_POST['tipo_beneficio']) : null;

$der              = !empty($_POST['der']) ? $_POST['der'] : null;
$dib              = !empty($_POST['dib']) ? $_POST['dib'] : null;
$dii              = !empty($_POST['dii']) ? $_POST['dii'] : null;

$alta_programada  = !empty($_POST['alta_programada']) ? $_POST['alta_programada'] : 'Não';
$dcb_prevista     = !empty($_POST['dcb_prevista']) ? $_POST['dcb_prevista'] : null;
$dcb              = !empty($_POST['dcb']) ? $_POST['dcb'] : null;

$status           = !empty($_POST['status']) ? $_POST['status'] : 'ativo';
$motivo_cessacao  = isset($_POST['motivo_cessacao']) ? trim($_POST['motivo_cessacao']) : null;
$origem_decisao   = isset($_POST['origem_decisao']) ? trim($_POST['origem_decisao']) : null;
$doc_cessacao     = isset($_POST['doc_cessacao']) ? trim($_POST['doc_cessacao']) : null;
$obs              = isset($_POST['obs']) ? trim($_POST['obs']) : null;

$id = $_POST['id'];


// normaliza datas vazias para NULL
$der          = !empty($der) ? $der : null;
$dib          = !empty($dib) ? $dib : null;
$dii          = !empty($dii) ? $dii : null;
$dcb_prevista = !empty($dcb_prevista) ? $dcb_prevista : null;
$dcb          = !empty($dcb) ? $dcb : null;

if($id == ""){
  $query = $pdo->prepare("INSERT INTO $tabela SET cliente = :cliente, nb = :nb, tipo_beneficio = :tipo_beneficio, der = :der, dib = :dib, dii = :dii, alta_programada = :alta_programada, dcb_prevista = :dcb_prevista, dcb = :dcb, status = :status, motivo_cessacao = :motivo_cessacao, origem_decisao = :origem_decisao, doc_cessacao = :doc_cessacao, obs = :obs, usuario_cadastro = :usuario_cadastro");
}else{
  $query = $pdo->prepare("UPDATE $tabela SET cliente = :cliente, nb = :nb, tipo_beneficio = :tipo_beneficio, der = :der, dib = :dib, dii = :dii, alta_programada = :alta_programada, dcb_prevista = :dcb_prevista, dcb = :dcb, status = :status, motivo_cessacao = :motivo_cessacao, origem_decisao = :origem_decisao, doc_cessacao = :doc_cessacao, obs = :obs, usuario_cadastro = :usuario_cadastro WHERE id = :id");
}

$query->bindValue(":cliente", $cliente, PDO::PARAM_INT);
$query->bindValue(":nb", $nb);
$query->bindValue(":tipo_beneficio", $tipo_beneficio);
$query->bindValue(":der", $der);
$query->bindValue(":dib", $dib);
$query->bindValue(":dii", $dii);
$query->bindValue(":alta_programada", $alta_programada); // 'Sim'/'Não'
$query->bindValue(":dcb_prevista", $dcb_prevista);
$query->bindValue(":dcb", $dcb);
$query->bindValue(":status", $status); // 'ativo','suspenso','cessado','cancelado','convertido' (ou livre, conforme seu schema)
$query->bindValue(":motivo_cessacao", $motivo_cessacao);
$query->bindValue(":origem_decisao", $origem_decisao);
$query->bindValue(":doc_cessacao", $doc_cessacao);
$query->bindValue(":obs", $obs);
$query->bindValue(":usuario_cadastro", $usuario_cadastro, PDO::PARAM_INT);

if($id != ""){
  $query->bindValue(":id", $id, PDO::PARAM_INT);
}

$query->execute();


echo 'Salvo com Sucesso';


 ?>
