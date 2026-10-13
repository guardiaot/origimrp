<?php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token_chatgpt
]);

$data = [
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'system', 'content' => 'Você é um advogado especialista.'],
        ['role' => 'user', 'content' => $mensagem]
    ],
    'temperature' => 0.7
];

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$resposta = json_decode($response, true);

if ($http_code !== 200) {
    $mensagemErro = isset($resposta['error']['message']) ? $resposta['error']['message'] : $response;
    echo json_encode([
        'status' => 'erro',
        'mensagem' => "❌ Erro na requisição à API\nCódigo HTTP: $http_code\n$mensagemErro"
    ]);
    exit;
}

if (isset($resposta['choices'][0]['message']['content'])) {
    $parecer_ia = $resposta['choices'][0]['message']['content'];
    echo json_encode([
        'status' => 'sucesso',
        'mensagem' => 'Gerando PDF...'
    ]);
    
} else {
    echo json_encode([
        'status' => 'erro',
        'mensagem' => '⚠️ A resposta da IA não contém conteúdo válido.'
    ]);
    exit;
}
