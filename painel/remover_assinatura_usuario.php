<?php
@session_start();
require_once("../conexao.php");

header('Content-Type: application/json; charset=utf-8');

if (($modo_teste ?? '') === 'Sim') {
  echo json_encode(['ok' => false, 'msg' => 'No modo de testes você não pode editar esses recursos!']);
  exit();
}

$id_usuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;
$arquivo    = trim($_POST['arquivo'] ?? '');

if ($id_usuario <= 0) {
  echo json_encode(['ok' => false, 'msg' => 'Usuário inválido.']);
  exit();
}

// pasta das assinaturas (mesma usada no salvar)
$pasta = __DIR__ . '/images/assinaturas/';
if (!is_dir($pasta)) {
  echo json_encode(['ok' => true]); // nada para remover
  exit();
}

// segurança: só permite remover arquivos dentro do padrão esperado
function arquivoSeguro($nome){
  // assinatura-12-joao-silva.png
  return (bool)preg_match('/^assinatura-\d+-[a-z0-9\-]{1,80}\.png$/i', $nome);
}

$removidos = 0;

// 1) se veio nome do arquivo, remove ele
if ($arquivo !== '' && arquivoSeguro($arquivo)) {
  $path = $pasta . $arquivo;
  if (file_exists($path)) {
    if (@unlink($path)) $removidos++;
  }
}

// 2) remove qualquer assinatura antiga do usuário (garante limpeza)
$pattern = $pasta . "assinatura-" . $id_usuario . "-*.png";
foreach (glob($pattern) as $f) {
  if (is_file($f)) {
    if (@unlink($f)) $removidos++;
  }
}

// opcional: quando você criar o campo no banco, você zera aqui
// $pdo->prepare("UPDATE usuarios SET assinatura = NULL WHERE id = ?")->execute([$id_usuario]);

echo json_encode([
  'ok' => true,
  'removidos' => $removidos
]);