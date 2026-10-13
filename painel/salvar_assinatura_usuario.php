<?php
@session_start();
require_once("../conexao.php");

header('Content-Type: application/json; charset=utf-8');

if (($modo_teste ?? '') === 'Sim') {
  echo json_encode(['ok' => false, 'msg' => 'No modo de testes você não pode editar esses recursos!']);
  exit();
}

$img = $_POST['img'] ?? '';
$id_usuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;
$nome = trim($_POST['nome'] ?? '');

if ($id_usuario <= 0) {
  echo json_encode(['ok' => false, 'msg' => 'Usuário inválido.']);
  exit();
}

if ($img === '' || strpos($img, 'data:image/png;base64,') !== 0) {
  echo json_encode(['ok' => false, 'msg' => 'Imagem inválida.']);
  exit();
}

// slug do nome
function slug($str){
  $str = mb_strtolower($str, 'UTF-8');
  $map = [
    'á'=>'a','à'=>'a','â'=>'a','ã'=>'a','ä'=>'a',
    'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
    'í'=>'i','ì'=>'i','î'=>'i','ï'=>'i',
    'ó'=>'o','ò'=>'o','ô'=>'o','õ'=>'o','ö'=>'o',
    'ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u',
    'ç'=>'c'
  ];
  $str = strtr($str, $map);
  $str = preg_replace('/[^a-z0-9]+/', '-', $str);
  $str = trim($str, '-');
  return $str !== '' ? substr($str, 0, 60) : 'usuario';
}

$nomeSlug = slug($nome);

// pasta destino (ajuste se quiser)
$pasta = __DIR__ . '/images/assinaturas/';
if (!is_dir($pasta)) {
  @mkdir($pasta, 0755, true);
}

// remove assinaturas antigas desse usuário
foreach (glob($pasta . "assinatura-{$id_usuario}-*.png") as $f) {
  @unlink($f);
}

// nome do arquivo
$arquivo = "assinatura-{$id_usuario}-{$nomeSlug}.png";
$caminhoFisico = $pasta . $arquivo;

// decodifica base64
$base64 = str_replace('data:image/png;base64,', '', $img);
$base64 = str_replace(' ', '+', $base64);
$bin = base64_decode($base64);

if (!$bin) {
  echo json_encode(['ok' => false, 'msg' => 'Falha ao decodificar a assinatura.']);
  exit();
}

// salva arquivo
if (file_put_contents($caminhoFisico, $bin) === false) {
  echo json_encode(['ok' => false, 'msg' => 'Não foi possível salvar a assinatura.']);
  exit();
}

// ✅ grava no banco o nome do arquivo
$up = $pdo->prepare("UPDATE usuarios SET assinatura = :assinatura WHERE id = :id LIMIT 1");
$up->bindValue(':assinatura', $arquivo);
$up->bindValue(':id', $id_usuario, PDO::PARAM_INT);
$up->execute();

$url = "images/assinaturas/" . $arquivo;

echo json_encode([
  'ok' => true,
  'arquivo' => $arquivo,
  'url' => $url
]);