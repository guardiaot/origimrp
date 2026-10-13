<?php
require_once("../../../conexao.php");

$cliente = isset($_POST['cliente']) ? (int)$_POST['cliente'] : 0;

if ($cliente == 0) {
    echo json_encode(['erro' => true, 'msg' => 'Cliente não informado']);
    exit();
}

$query = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
$query->bindValue(":id", $cliente);
$query->execute();

$res = $query->fetch(PDO::FETCH_ASSOC);

if (!$res) {
    echo json_encode(['erro' => true, 'msg' => 'Cliente não encontrado']);
    exit();
}

/* retorno padrão */
$retorno = [
    'erro' => false,
    'id' => $res['id'],
    'nome' => $res['nome'],
    'cpf' => $res['cpf'],
    'rg' => $res['rg'],
    'telefone' => $res['telefone'],
    'email' => $res['email'],
    'tipo_pessoa' => $res['tipo_pessoa'],
    'data_nasc' => $res['data_nasc'],
    'endereco' => $res['endereco'],
    'numero' => $res['numero'],
    'bairro' => $res['bairro'],
    'cidade' => $res['cidade'],
    'estado' => $res['estado'],
    'cep' => $res['cep'],
    'complemento' => $res['complemento'],
    'profissao' => $res['profissao'],
    'estado_civil' => $res['estado_civil'],
    'nacionalidade' => $res['nacionalidade'],
    'genitor' => $res['genitor'],
    'genitora' => $res['genitora'],
    'indicacao' => $res['indicacao'],
    'resumo_fatos' => $res['resumo_fatos'],
    'data_cad' => $res['data_cad'],
    'ativo' => $res['ativo']
];

echo json_encode($retorno);
