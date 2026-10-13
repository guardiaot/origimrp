<?php
// Arquivo: gemini.php - Apenas Processamento Interno (Sem 'echo' ou 'exit')

// Variáveis necessárias: $mensagem, $gemini_api_key
// As variáveis de retorno ($parecer_ia e $erro_api) são preenchidas neste script.

$parecer_ia = null; 
$erro_api = null; 
$response = ''; // Variável para capturar a resposta bruta para debug

// ----------------------------------------------------
// 1. Configuração da API
// ----------------------------------------------------
$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $gemini_api_key;

$data = [
    'contents' => [
        [
            'role' => 'user',
            'parts' => [
                ['text' => $mensagem]
            ]
        ]
    ],
    // ✅ CORRETO: O nome da chave deve ser "generationConfig"
    'generationConfig' => [
        'temperature' => 0.7, 
        // ✅ LIMITE AUMENTADO: 4096 tokens é mais seguro para textos longos
        'maxOutputTokens' => 4096,
    ]
];

$json_data = json_encode($data);

// ----------------------------------------------------
// 2. Chamada via cURL
// ----------------------------------------------------

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($json_data)
]);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);

$response = curl_exec($ch); // Captura a resposta bruta
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// ----------------------------------------------------
// 3. Processamento da Resposta
// ----------------------------------------------------

if ($error) {
    // ❌ ERRO cURL (Problema de rede ou configuração do servidor)
    $erro_api = "ERRO cURL (Gemini): " . $error;
} elseif ($http_code != 200) {
    // ❌ ERRO DA API (Problema de chave, limite excedido, etc.)
    $response_data = json_decode($response, true);
    // Tenta extrair a mensagem de erro do JSON da API
    $error_message = $response_data['error']['message'] ?? "Erro HTTP: {$http_code}. Resposta: " . substr($response, 0, 200) . "...";
    $erro_api = "ERRO NA API (Gemini): " . $error_message;
} else {
    // ✅ SUCESSO
    $response_data = json_decode($response, true);
    
    if (isset($response_data['candidates'][0]['content']['parts'][0]['text'])) {
        $parecer_ia = $response_data['candidates'][0]['content']['parts'][0]['text'];
    } else {
        // ⚠️ Resposta vazia, mas HTTP 200
        $erro_api = "⚠️ A resposta da IA (Gemini) não contém conteúdo válido. Resposta: " . substr($response, 0, 200) . "...";
    }
}

// O script principal (gerar_analise.php) agora verificará $erro_api.