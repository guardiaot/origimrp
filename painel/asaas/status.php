<?php
@session_start();
require("../../conexao.php");
require("config/configApi.php");

$id_conta = @filter_var($_GET['id'], @FILTER_SANITIZE_STRING);

//ref pix testes aprovado pay_wdx0czye9gp2u4tj
$query2 = $pdo->prepare("SELECT * FROM receber where id = :id_conta");
$query2->bindValue(":id_conta", "$id_conta");
$query2->execute();
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$id = $res2[0]['id'];
    $id_par = $res2[0]['id'];
    $descricao = $res2[0]['descricao'];
    $valor = $res2[0]['valor'];
    $vencimento = $res2[0]['vencimento'];
    $data_venc = $res2[0]['vencimento'];
    $data_pgto = $res2[0]['data_pgto'];
    $data_lanc = $res2[0]['data_lanc'];
    $forma_pgto = $res2[0]['forma_pgto'];
    $frequencia = $res2[0]['frequencia'];
    $obs = $res2[0]['obs'];
    $arquivo = $res2[0]['arquivo'];
    $referencia = $res2[0]['referencia'];
    $id_ref = $res2[0]['id_ref'];
    $multa = $res2[0]['multa'];
    $juros = $res2[0]['juros'];
    $desconto = $res2[0]['desconto'];
    $taxa = $res2[0]['taxa'];
    $subtotal = $res2[0]['subtotal'];
    $usuario_lanc = $res2[0]['usuario_lanc'];
    $usuario_pgto = $res2[0]['usuario_pgto'];
    $pago = $res2[0]['pago'];
    $ref_pix = $res2[0]['ref_api'];
    $cliente = $res2[0]['cliente'];     
    $data_vencF = $data_venc;

//CALCULAR OS JUROS PARA A CONTA CASO EXISTA
$data_atual = date('Y-m-d');

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

    
    $valor_multa_juros = $valor_multa + $valor_juros;

    $valor_jurosF = @number_format($valor_juros, 2, ',', '.');

    $valorF = @number_format($valor, 2, ',', '.');
    $valor_multa_jurosF = @number_format($valor_multa_juros, 2, ',', '.');

$valor = $valor_multa + $valor_juros + $valor;



//dados do cliente
$query22 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res22 = $query22->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $res22[0]['nome'];
$tel_cliente = $res22[0]['telefone'];
$tel_cliente = '55'.preg_replace('/[ ()-]+/' , '' , $tel_cliente);
$telefone_envio = $tel_cliente;
$hash = @$res2[0]['hash2'];




// Verifica o pagamento se ainda não estiver marcado como pago
if ($pago != 'Sim') {
    $payment_id = $ref_pix;

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.asaas.com/v3/payments/' . $payment_id, // Consulta completa da cobrança
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'accept: application/json',
            'content-type: application/json',
            'User-Agent: MeuSistema/1.0',
            'access_token: ' . $access_token
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $resultado = json_decode($response);

    // Apenas para visualizar o retorno completo em teste:
    // echo "<pre>"; print_r($resultado); echo "</pre>"; exit();

    // Variáveis corretas agora:
    $status_api = $resultado->status ?? 'PENDING'; // Status
    $valor_liquido = $resultado->netValue ?? 0; // Valor líquido recebido
    $total_pago = $resultado->value ?? 0; // Valor da cobrança
    $tipo_pagamento = $resultado->billingType ?? 'PIX'; // Forma de pagamento

    //echo "Status: $status_api <br>";
    //echo "Valor Pago (líquido): R$ " . number_format($valor_liquido, 2, ',', '.') . "<br>";
    //echo "Valor Original: R$ " . number_format($total_pago, 2, ',', '.') . "<br>";
    //echo "Forma de Pagamento: $tipo_pagamento <br>";


    if($tipo_pagamento == 'CREDIT_CARD'){
        $tipo_pagamento = 'Cartão de Crédito';
    }
   
    if($tipo_pagamento == 'BOLETO'){
        $tipo_pagamento = 'Boleto';
    }

    if($tipo_pagamento == 'PIX '){
        $tipo_pagamento = 'Pix';
    }

    //echo $status_api;

    // Se confirmado ou recebido, atualiza como pago no banco
    if (in_array($status_api, ['RECEIVED', 'CONFIRMED'])) {
        




//verificar se a empresa possui débitos
    if ($cliente != 0 and $cliente != '') {
        //procurar o dia de venc da parcela padrão
        $query = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);       

       
    } else {
        $data_venc = $data_venc;
    }
    $valorF = number_format($valor, 2, ',', '.');




    // Mensagem de saldação
    $hora = date('H');
    $data_atual = date('Y-m-d');


       
        //CRIAR A PRÓXIMA CONTA A PAGAR
        $dias_frequencia = $frequencia;

        if ($dias_frequencia == 30 || $dias_frequencia == 31) {
            $nova_data_vencimento = date('Y/m/d', strtotime("+1 month", strtotime($data_venc)));
        } else if ($dias_frequencia == 90) {
            $nova_data_vencimento = date('Y/m/d', strtotime("+3 month", strtotime($data_venc)));
        } else if ($dias_frequencia == 180) {
            $nova_data_vencimento = date('Y/m/d', strtotime("+6 month", strtotime($data_venc)));
        } else if ($dias_frequencia == 360 || $dias_frequencia == 365) {
            $nova_data_vencimento = date('Y/m/d', strtotime("+1 year", strtotime($data_venc)));
        } else {
            $nova_data_vencimento = date('Y/m/d', strtotime("+$dias_frequencia days", strtotime($data_vencF)));
        }

       


        if (@$dias_frequencia > 0) {
            $pdo->query("INSERT INTO receber set descricao = '$descricao', cliente = '$cliente', valor = '$valor', data_lanc = curDate(), vencimento = '$nova_data_vencimento', frequencia = '$frequencia', arquivo = '$arquivo', pago = 'Não', referencia = '$referencia', caixa = 0, hora = curTime(), subtotal = '$subtotal', hora_alerta = '$hora_random'");
            $id_ult_registro = $pdo->lastInsertId();


        }


        $valorF = @number_format($valor, 2, ',', '.');

        // ENVIAR AS MENSAGENS PARA O CLIENTE E ADM
        if ($api_whatsapp != 'Não') {

            //telefone do cliente
            $query = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
            $res = $query->fetchAll(PDO::FETCH_ASSOC);
            @$telefone_cliente = $res[0]['telefone'];
            @$nome_cliente = $res[0]['nome'];


            $valorF = number_format($valor, 2, ',', '.');

            $telefone_envio = '55' . preg_replace('/[ ()-]+/', '', $telefone_cliente);

            //ENVIAR A CONFIRMAÇÃO DO PAGAMENTO PARA O CLIENTE
           $mensagem = '🌟🌟🌟 *CONFIRMAÇÃO DE PAGAMENTO* 🌟🌟🌟%0A';           
            $mensagem .= '✅ *Pagamento efetuado com sucesso!*%0A%0A';
            $mensagem .= '🎉 *Muito obrigado por sua confiança!*%0A';
            $mensagem .= '📞 Em caso de dúvidas, estamos à disposição. 🤝';

            require("texto.php");


            // ENVIAR MSG PARA O ADM DA CONFIRMAÇÃO DE PAGAMENTO
            $query = $pdo->query("SELECT * FROM usuarios where nivel = 'Administrador'");
            $res = $query->fetchAll(PDO::FETCH_ASSOC);
            @$telefone_admin = $res[0]['telefone'];

            $telefone_envio = '55' . preg_replace('/[ ()-]+/', '', $telefone_sistema);

            $mensagem = '_Confirmação de Pagamento_ %0A%0A%0A';

            $mensagem .= 'O Cliente *' . $nome_cliente . '* %0A';
            $mensagem .= 'efetuou o pagamento da conta *' . $descricao . '* %0A';
            $mensagem .= 'Valor: *' . $valorF . '*';

            require("texto.php");


        }
  

$pdo->query("UPDATE receber SET pago = 'Sim', valor = '$total_pago', subtotal = '$total_pago', data_pgto = curDate(), hora = curTime(), forma_pgto = '$tipo_pagamento' where ref_api = '$ref_pix'");

    }


}

// Se já pago, redireciona direto
if ($pago == 'Sim') {
    echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=" . $url_sistema . "painel/asaas/obrigado.php?id=" . $id_conta . "'>";
    exit;
}
?>
