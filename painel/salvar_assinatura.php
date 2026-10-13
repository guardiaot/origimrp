<?php
@session_start();
require_once("../conexao.php");
header('Content-Type: application/json; charset=utf-8');

// (opcional) bloquear modo teste, como você faz
if (($modo_teste ?? '') === 'Sim') {
  echo json_encode(['ok'=>false,'msg'=>'No modo de testes você não pode editar esses recursos!']);
  exit();
}

$img = $_POST['img'] ?? '';
if ($img == '' || strpos($img, 'data:image/png;base64,') !== 0) {
  echo json_encode(['ok'=>false,'msg'=>'Imagem inválida.']);
  exit();
}

$base64 = str_replace('data:image/png;base64,', '', $img);
$base64 = str_replace(' ', '+', $base64);
$bin = base64_decode($base64);

if (!$bin) {
  echo json_encode(['ok'=>false,'msg'=>'Falha ao decodificar imagem.']);
  exit();
}

// caminho final do arquivo
// opção 1: arquivo fixo
$arquivo = 'assinatura.png';
$caminho = '../img/' . $arquivo;

// garante pasta (se precisar)
// if(!is_dir('../img')) mkdir('../img', 0755, true);

if (file_put_contents($caminho, $bin) === false) {
  echo json_encode(['ok'=>false,'msg'=>'Não foi possível salvar o arquivo.']);
  exit();
}

// se você quiser também salvar isso no banco (config), dá pra fazer aqui.
// Exemplo (se você criar a coluna assinatura_arquivo na config):
// $pdo->query("UPDATE config SET assinatura_arquivo = '$arquivo' WHERE id=1");

echo json_encode([
  'ok' => true,
  'arquivo' => $arquivo,
  'url' => $caminho
]);