<?php 
$tabela = 'casos';
require_once("../../../conexao.php");

$id = $_POST['id'];
$escolha = $_POST['escolha'];

// 1) Buscar o caso
$stmt = $pdo->prepare("SELECT tipo_acao, descricao, partes, processo_existente, objetivo FROM casos WHERE id = :id");
$stmt->execute([':id' => $id]);
$caso = $stmt->fetch(PDO::FETCH_ASSOC);

$tipo_acao          = $caso['tipo_acao'] ?? '';
$descricao          = $caso['descricao'] ?? '';
$partes             = $caso['partes'] ?? '';
$processo_existente = $caso['processo_existente'] ?? '';
$objetivo           = $caso['objetivo'] ?? '';

$gemini_api_key = $token_gemini;
$erro_api = null;
$parecer_ia = null;

// 2) Buscar o texto-base do prompt escolhido
$stmt = $pdo->prepare("SELECT texto FROM prompts WHERE id = :id");
$stmt->execute([':id' => $escolha]);
$prompt_base = $stmt->fetchColumn();

if(!$prompt_base){
    $erro_api = 'Prompt não encontrado.';
    // trate o erro como preferir (return/echo/json)
}

// 3) Substituir as chaves {variavel} pelos valores
// --- Opção A (fixa, direta: rápida e clara)
$mensagem = strtr($prompt_base, [
    '{tipo_acao}'          => (string)$tipo_acao,
    '{descricao}'          => (string)$descricao,
    '{partes}'             => (string)$partes,
    '{processo_existente}' => (string)$processo_existente,
    '{objetivo}'           => (string)$objetivo,
]);


// (Opcional) normalizar quebras de linha
$mensagem = preg_replace("/\r\n?/", "\n", $mensagem);



	if($api_ia == 'chatgpt'){
		//chamar api do chatgpt
		require('../../apis/chatgpt.php');
	}else{
		//chamar api do gemini
		require('../../apis/gemini.php');
	}

	


if($api_ia == 'gemini'){
	if ($erro_api !== null || $parecer_ia === null) {
    // Se houver qualquer erro na chamada da API (Gemini ou ChatGPT)
    $mensagem_retorno = $erro_api ?? 'Erro desconhecido ao obter o parecer da IA.';
    $retorno = [
        'status' => 'erro',
        'mensagem' => "❌ Falha na Geração da Análise:\n$mensagem_retorno"
    ];
	} else {
	    // Se o parecer foi gerado com sucesso, salvamos no banco
	    
	    $pdo->query("DELETE FROM casos_ia WHERE id_caso = '$id'");
	    
	    $query = $pdo->prepare("INSERT INTO casos_ia SET id_caso = :id_caso, parecer = :parecer, data = curDate()");
	    $query->bindValue(":id_caso", $id);
	    $query->bindValue(":parecer", $parecer_ia);
	    $query->execute();

	    $retorno = [
	        'status' => 'sucesso',
	        'mensagem' => 'Parecer Jurídico gerado e salvo com sucesso! Gerando PDF...'
	    ];
	}

	// ⚠️ FINAL DA SAÍDA: SEMPRE RETORNA JSON (resolve o erro SyntaxError)
	header('Content-Type: application/json');
	echo json_encode($retorno);
	exit; 
}else{
	$pdo->query("DELETE FROM casos_ia WHERE id_caso = '$id'");

$query = $pdo->prepare("INSERT INTO casos_ia SET id_caso = :id_caso, parecer = :parecer, data = curDate()");
$query->bindValue(":id_caso", $id);
$query->bindValue(":parecer", $parecer_ia);
$query->execute();
}






?>