<?php 
$tabela = 'receber';
require_once("../../../conexao.php");

$id = $_POST['id'];

$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$descricao = @$res[0]['descricao'];
$valor = @$res[0]['valor'];
$cliente = @$res[0]['cliente'];
$vencimento = @$res[0]['vencimento'];

$data_atual = date('Y-m-d');

$vencimentoF = implode('/', array_reverse(@explode('-', $vencimento)));



//CODIGO PARA CALCULAR OS JUROS
    $valor_multa = 0;
    $valor_juros = 0;
    $data_hoje = date('Y-m-d');

    // Aplicar multa e juros se tiver atrasada
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
    $valor_multaF = @number_format($valor_multa, 2, ',', '.');

    $valorF = @number_format($valor, 2, ',', '.');

$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$nome_cliente = $res2[0]['nome'];
	$telefone_cliente = $res2[0]['telefone'];
}else{
	$nome_cliente = 'Sem Registro';
	$telefone_cliente = "";
}


if($api_whatsapp != 'Não' and $telefone_cliente != ''){			

			$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_cliente);
			$mensagem_whatsapp  = '🏛️ *'.$nome_sistema.'*%0A';
$mensagem_whatsapp .= '━━━━━━━━━━━━━━%0A';

if (strtotime($vencimento) < strtotime($data_atual)) {
  $mensagem_whatsapp .= '⛔ _Conta Vencida_%0A';
} else {
  $mensagem_whatsapp .= '🔔 _Lembrete de Pagamento_%0A';
}

$mensagem_whatsapp .= '━━━━━━━━━━━━━━━%0A';
$mensagem_whatsapp .= '🧾 *Descrição:* '.$descricao.'%0A';

if($valor_multa > 0){
	$mensagem_whatsapp .= '💰 *Multa:* '.$valor_multaF.'%0A';
}
if($valor_juros > 0){
	$mensagem_whatsapp .= '💰 *Júros:* '.$valor_jurosF.'%0A';
}

$mensagem_whatsapp .= '💰 *Valor:* '.$valorF.'%0A';
$mensagem_whatsapp .= '📅 *Vencimento:* '.$vencimentoF.'%0A';
$mensagem_whatsapp .= '%0A';

if ($dados_pagamento != "") {
  $mensagem_whatsapp .= '💳 *Como pagar*%0A';
  $mensagem_whatsapp .= $dados_pagamento.'%0A';
} else {
  if ($chave_asaas != "") {
    $url_pagar = $url_sistema.'pagar/'.$id;
    $mensagem_whatsapp .= '💳 *Pagar agora*%0A';
    $mensagem_whatsapp .= $url_pagar.'%0A%0A';
    $mensagem_whatsapp .= '🔒 Link seguro (Asaas)%0A';
  }
}
			
			require('../../apis/texto.php');			
			
		}	

echo 'Cobrança Efetuada';
?>