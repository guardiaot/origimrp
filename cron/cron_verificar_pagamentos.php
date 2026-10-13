<?php 
@session_start();
require_once("../conexao.php");

$query_venc = $pdo->query("SELECT * from receber where pago != 'Sim' and ref_api is not null ");
$res_venc = $query_venc->fetchAll(PDO::FETCH_ASSOC);
$total_contas_venc = @count($res_venc);
for ($i_venc = 0; $i_venc < $total_contas_venc; $i_venc++) {
	$valor = $res_venc[$i_venc]['valor'];
	$descricao = $res_venc[$i_venc]['descricao'];
	$id = $res_venc[$i_venc]['id'];
	$cliente = $res_venc[$i_venc]['cliente'];
	$vencimento = $res_venc[$i_venc]['vencimento'];
	$ref_pix = $res_venc[$i_venc]['ref_api'];	
	$data_venc = $res_venc[$i_venc]['vencimento'];
	 $data_vencF = $data_venc;
	 $frequencia = $res_venc[$i_venc]['frequencia'];
	 $arquivo = $res_venc[$i_venc]['arquivo'];
	 $referencia = $res_venc[$i_venc]['referencia'];
	 $subtotal = $res_venc[$i_venc]['subtotal'];


	$valorF = @number_format($valor, 2, ',', '.');

	$query432 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
	$res432 = $query432->fetchAll(PDO::FETCH_ASSOC);
	

	//aprovacao do asaas	
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
            'access_token: ' . $chave_asaas
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

    echo "Status: $status_api <br>";
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




echo '<br>';
echo $total_contas_venc;

 ?>