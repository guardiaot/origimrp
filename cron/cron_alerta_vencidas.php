<?php 
require_once("../conexao.php");

$query = $pdo->query("SELECT * from receber where vencimento < curDate() and pago != 'Sim' and cliente > 0 and cliente is not null and hora_alerta <= curTime() and (data_alerta != curDate() or data_alerta is null) ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagar_vencidas = @count($res);
for ($i = 0; $i < $contas_pagar_vencidas; $i++) {
	$valor = $res[$i]['valor'];
	$descricao = $res[$i]['descricao'];
	$id = $res[$i]['id'];
	$cliente = $res[$i]['cliente'];
	$vencimento = $res[$i]['vencimento'];

	$vencimentoF = implode('/', array_reverse(@explode('-', $vencimento)));

	$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if (@count($res2) > 0) {
		$nome_cliente = $res2[0]['nome'];
		$telefone_cliente = $res2[0]['telefone'];
	} else {
		$nome_cliente = 'Sem Registro';
		$telefone_cliente = "";
	}



$link_pgto = $url_sistema.'pagar/'.$id;


//CODIGO PARA CALCULAR OS JUROS
$valor_multa = 0;
$valor_juros = 0;
$data_hoje = date('Y-m-d');

if (@strtotime($vencimento) < @strtotime($data_hoje)) {
	
	$valor_multa = $multa_atraso;

			//pegar a quantidade de dias que o pagamento está atrasado
	$dif = @strtotime($data_hoje) - @strtotime($vencimento);
	$dias_vencidos = floor($dif / (60 * 60 * 24));

	$valor_juros = ($valor * $juros_atraso / 100) * $dias_vencidos;
}

$valor = $valor_multa + $valor_juros + $valor;
$valor_multa_juros = $valor_multa + $valor_juros;

$valor_jurosF = @number_format($valor_juros, 2, ',', '.');

	$valorF = @number_format($valor, 2, ',', '.');
	$valor_multa_jurosF = @number_format($valor_multa_juros, 2, ',', '.');

	//enviar whatsapp
		if ($api_whatsapp != 'Não' and $telefone_cliente != '') {
			$telefone_envio = '55' . preg_replace('/[ ()-]+/', '', $telefone_cliente);
			$mensagem = '💰 *' . $nome_sistema . '*%0A';
			$mensagem .= '_Sua conta Venceu_ %0A';
			
			$mensagem .= '*Descrição:* '.$descricao.' %0A';
	$mensagem .= '*Cliente:* '.$nome_cliente.' %0A';
	if($valor_multa_juros > 0){
	$mensagem .= '*Multa / Júros:* R$ '.$valor_multa_jurosF.' %0A';		
	}
	$mensagem .= '*Valor:* R$ '.$valorF.' %0A';	
	$mensagem .= '*Vencimento:* '.$vencimentoF.' %0A%0A';	

	if($dados_pagamento != ""){
				$mensagem .= '*Dados para o Pagamento:* %0A';
				$mensagem .= $dados_pagamento;
			}else{
				if ($chave_asaas != "") {
				    $url_pagar = $url_sistema.'pagar/'.$id;
				    $mensagem .= '💳 *Pagar agora*%0A';
				    $mensagem .= $url_pagar.'%0A%0A';
				    $mensagem .= '🔒 Link seguro (Asaas)%0A';
				  }
			}		
			
			require('texto.php');

			if(@$status_mensagem == "Mensagem enviada com sucesso." and $api_whatsapp == 'menuia'){
				$pdo->query("UPDATE receber SET data_alerta = curDate(), hora_alerta = '$hora_random' where id = '$id'");
			}

			if($api_whatsapp != 'menuia'){
				$pdo->query("UPDATE receber SET data_alerta = curDate(), hora_alerta = '$hora_random' where id = '$id'");
			}
			
		}

}

echo $contas_pagar_vencidas;

 ?>