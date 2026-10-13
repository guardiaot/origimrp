<?php

//definir fuso horário
date_default_timezone_set('America/Sao_Paulo');

$modo_teste = 'Não';

$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$url_sistema = $protocolo . "://" . $_SERVER['HTTP_HOST'] . "/";


//dados conexão bd local
$servidor = 'localhost';
$banco = 'advocacia';
$usuario = 'root';
$senha = '';

try {
	$pdo = new PDO("mysql:dbname=$banco;host=$servidor;charset=utf8mb4", "$usuario", "$senha");
} catch (Exception $e) {
	echo 'Erro ao conectar ao banco de dados!<br>';
	echo $e;
}


//variaveis para os disparos de notificações
$hora_rand = rand(8, 10);
$minutos_rand = rand(0, 59);
if ($hora_rand < 10) {
	$hora_rand = '0' . $hora_rand;
}
if ($minutos_rand < 10) {
	$minutos_rand = '0' . $minutos_rand;
}

$hora_random = $hora_rand . ':' . $minutos_rand . ':00';



//variaveis para verificacao processos tarefa cron
$hora_rand = rand(7, 21);
$minutos_rand = rand(0, 59);
if ($hora_rand < 10) {
	$hora_rand = '0' . $hora_rand;
}
if ($minutos_rand < 10) {
	$minutos_rand = '0' . $minutos_rand;
}

$hora_random_verificar = $hora_rand . ':' . $minutos_rand . ':00';

//variaveis globais
$nome_sistema = 'Nome Sistema';
$email_sistema = 'guardiaot@gmail.com';
$telefone_sistema = '(16)98100-9199';

$query = $pdo->query("SELECT * from config");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas == 0) {
	$pdo->query("INSERT INTO config SET nome = '$nome_sistema', email = '$email_sistema', telefone = '$telefone_sistema', logo = 'logo.png', logo_rel = 'logo.jpg', icone = 'icone.png', ativo = 'Sim', multa_atraso = '0', juros_atraso = '0', marca_dagua = 'Sim', assinatura_recibo = 'Não', impressao_automatica = 'Não', api_whatsapp = 'Não', alterar_acessos = 'Não', cor_login = '#000', filtrar_colunas = 'Não'");
} else {
	$nome_sistema = $res[0]['nome'];
	$email_sistema = $res[0]['email'];
	$telefone_sistema = $res[0]['telefone'];
	$endereco_sistema = $res[0]['endereco'];
	$instagram_sistema = $res[0]['instagram'];
	$logo_sistema = $res[0]['logo'];
	$logo_rel = $res[0]['logo_rel'];
	$icone_sistema = $res[0]['icone'];
	$ativo_sistema = $res[0]['ativo'];
	$multa_atraso = $res[0]['multa_atraso'];
	$juros_atraso = $res[0]['juros_atraso'];
	$marca_dagua = $res[0]['marca_dagua'];
	$assinatura_recibo = $res[0]['assinatura_recibo'];
	$impressao_automatica = $res[0]['impressao_automatica'];
	$cnpj_sistema = $res[0]['cnpj'];
	$entrar_automatico = $res[0]['entrar_automatico'];
	$mostrar_preloader = $res[0]['mostrar_preloader'];
	$ocultar_mobile = $res[0]['ocultar_mobile'];
	$api_whatsapp = $res[0]['api_whatsapp'];
	$token_whatsapp = $res[0]['token_whatsapp'];
	$instancia_whatsapp = $res[0]['instancia_whatsapp'];
	$alterar_acessos = $res[0]['alterar_acessos'];
	$dados_pagamento = $res[0]['dados_pagamento'];
	$cidade_sistema = $res[0]['cidade_sistema'];
	$seccional_oab_escritorio = $res[0]['seccional_oab_escritorio'];
	$numero_oab_escritorio = $res[0]['numero_oab_escritorio'];
	$cor_login = $res[0]['cor_login'];
	$filtrar_colunas = $res[0]['filtrar_colunas'];
	$fundo_login = $res[0]['fundo_login'];
	$assinatura_cliente = $res[0]['assinatura_cliente'];
	$tipo_tel = $res[0]['tipo_tel'];
	$entrada_sistema = $res[0]['entrada_sistema'];
	$token_chatgpt = $res[0]['token_chatgpt'];
	$chave_asaas = $res[0]['chave_asaas'];
	$api_ia = $res[0]['api_ia'];
	$token_gemini = $res[0]['token_gemini'];
	$url_evolution = $res[0]['url_evolution'];

	if ($fundo_login == "") {
		$fundo_login = 'sem-foto.png';
	}

	$tel_whats = '55' . preg_replace('/[ ()-]+/', '', $telefone_sistema);

	if ($ativo_sistema != 'Sim' and $ativo_sistema != '') { ?>
		<style type="text/css">
			@media only screen and (max-width: 700px) {
				.imgsistema_mobile {
					width: 300px;
				}

			}
		</style>
		<div style="text-align: center; margin-top: 100px">
			<img src="<?php echo $url_sistema ?>img/bloqueio.png" class="imgsistema_mobile">
		</div>
		<?php
		exit();
	}

}
?>