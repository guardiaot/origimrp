<?php
@session_start();
require_once("../conexao.php");

header('Content-Type: application/json; charset=utf-8');

// se você tiver modo teste
if (($modo_teste ?? '') === 'Sim') {
    echo json_encode([
        'ok' => false,
        'msg' => 'No modo de testes você não pode editar esses recursos!'
    ]);
    exit();
}

// caminho da assinatura (ajuste se necessário)
$arquivo = '../img/assinatura.png';

// verifica se existe antes de excluir
if (file_exists($arquivo)) {
    if (!unlink($arquivo)) {
        echo json_encode([
            'ok' => false,
            'msg' => 'Não foi possível remover o arquivo.'
        ]);
        exit();
    }
}

// opcional: se você estiver salvando no banco
// $pdo->query("UPDATE config SET assinatura_arquivo = '' WHERE id = 1");

echo json_encode([
    'ok' => true
]);