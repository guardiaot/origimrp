<?php
@session_start();
$id_usuario = @$_SESSION['id'];
$tabela = 'abertura_contratos';
require_once("../../../conexao.php");

$data_atual = date('Y-m-d');

$cliente = @$_POST['cliente'];
$valor = @$_POST['valor'];
$valor = str_replace(',', '.', $valor);
$valor_entrada = @$_POST['valor_entrada'];
$valor_entrada = str_replace(',', '.', $valor_entrada);
$parcelas = @$_POST['parcelas'];
$frequencia = @$_POST['frequencia'];
$data_venc = @$_POST['data_venc'];
$frequencia_entrada = @$_POST['frequencia_entrada'];
$parcelas_entrada = @$_POST['parcelas_entrada'];

if ($valor == "") {
	$valor = 0;
}

$valorF = @@number_format($valor, 2, ',', '.');

$valor_entrada_post = @$_POST['valor_entrada'];

$obs = @$_POST['obs'];
$data_abertura = @$_POST['data_abertura'];
$numero_contrato = @$_POST['numero_contrato'];
$nova_numeracao = @$numero_contrato;

$supervisor = @$_POST['supervisor'];
$prestador1 = @$_POST['prestador1'];
$prestador2 = @$_POST['prestador2'];
$origem = @$_POST['origem'];
$forma_recebimento = @$_POST['forma_recebimento'];
$data_valor_entrada = @$_POST['data_valor_entrada'];

$sep = explode("/", $nova_numeracao);
$ano_contrato = @$sep[0];
$ultima_numeracao = @$sep[1];

$valor_escritorio = @$_POST['valor_escritorio'];


$advogado1 = @$_POST['advogado1'];
$advogado2 = @$_POST['advogado2'];
$advogado3 = @$_POST['advogado3'];
$indicacao = @$_POST['indicacao'];
$marketing = @$_POST['marketing'];
$pessoa1 = @$_POST['pessoa1'];
$pessoa2 = @$_POST['pessoa2'];

$valor_advogado1 = $_POST['valor_advogado1'];
$valor_advogado1 = str_replace(',', '.', $valor_advogado1);
$valor_advogado2 = $_POST['valor_advogado2'];
$valor_advogado2 = str_replace(',', '.', $valor_advogado2);
$valor_advogado3 = $_POST['valor_advogado3'];
$valor_advogado3 = str_replace(',', '.', $valor_advogado3);
$valor_indicacao = $_POST['valor_indicacao'];
$valor_indicacao = str_replace(',', '.', $valor_indicacao);
$valor_marketing = $_POST['valor_marketing'];
$valor_marketing = str_replace(',', '.', $valor_marketing);
$valor_pessoa1 = $_POST['valor_pessoa1'];
$valor_pessoa1 = str_replace(',', '.', $valor_pessoa1);
$valor_pessoa2 = $_POST['valor_pessoa2'];
$valor_pessoa2 = str_replace(',', '.', $valor_pessoa2);

$motivo1 = @$_POST['motivo1'];
$motivo2 = @$_POST['motivo2'];
$forma_pgto = @$_POST['forma_pgto'];


$area_poderes = @$_POST['area_poderes'];
$area_valor = @$_POST['area_valor'];





//dados procesoss
$numero_processo = @$_POST['numero_processo'];
$tipo_servico = @$_POST['tipo_servico'];
$valor_causa = @$_POST['valor_causa'];
$valor_causa = str_replace(',', '.', $valor_causa);
$numero_processo = @$_POST['numero_processo'];
$status = @$_POST['status'];
$segredo_justica = @$_POST['segredo_justica'];
$justica_gratuita = @$_POST['justica_gratuita'];
$obs_processo = @$_POST['obs_processo'];
$orgao_julgador = @$_POST['orgao_julgador'];
$data_abertura_processo = @$_POST['data_abertura_processo'];
$advogado1_processo = @$_POST['advogado1_processo'];
$advogado2_processo = @$_POST['advogado2_processo'];
$advogado3_processo = @$_POST['advogado3_processo'];
$advogado4_processo = @$_POST['advogado4_processo'];
$jurisdicao = @$_POST['jurisdicao'];
$vara = @$_POST['vara'];
$comarca = @$_POST['comarca'];
$nome_contraria = @$_POST['nome_contraria'];
$cpf_contraria = @$_POST['cpf_contraria'];
$rg_contraria = @$_POST['rg_contraria'];
$telefone_contraria = @$_POST['telefone_contraria'];
$advogado_contraria = @$_POST['advogado_contraria'];
$endereco_contraria = @$_POST['endereco_contraria'];
$estado_civil_contraria = @$_POST['estado_civil_contraria'];


if ($status == 'Finalizado') {
	$sql_data = ' , data_fechamento = curDate()';
} else {
	$sql_data = '';
}


if ($valor_causa == '') {
	echo 'informe o valor da causa!';
	exit();
}

if($tipo_servico == ''){
	echo 'informe o tipo de serviço!';
	exit();
}

//verificar se já tem o processo cadastrado
$query = $pdo->query("SELECT * FROM processos where num_processo = '$numero_processo'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($res) == 0) {
	//inserir o processo
	$query = $pdo->prepare("INSERT INTO processos SET num_processo = :num_processo, tipo_acao = :tipo_acao, jurisdicao = :jurisdicao, vara = :vara, comarca = :comarca, valor = :valor, segredo = :segredo, justica_gratuita = :justica_gratuita, orgao_julgador = :orgao_julgador, status = :status, advogado1 = :advogado1, advogado2 = :advogado2, advogado3 = :advogado3, advogado4 = :advogado4, data_abertura = :data_abertura, cliente = :cliente, usuario_lanc = :usuario_lanc, nome_contraria = :nome_contraria, telefone_contraria = :telefone_contraria, cpf_contraria = :cpf_contraria, rg_contraria = :rg_contraria, endereco_contraria = :endereco_contraria, estado_civil_contraria = :estado_civil_contraria, advogado_contraria = :advogado_contraria, data_cad = curDate(), obs = :obs, hora_verificacao = '$hora_random_verificar'");
} else {
	//editar o processo
	$query = $pdo->prepare("UPDATE processos SET num_processo = :num_processo, tipo_acao = :tipo_acao, jurisdicao = :jurisdicao, vara = :vara, comarca = :comarca, valor = :valor, segredo = :segredo, justica_gratuita = :justica_gratuita, orgao_julgador = :orgao_julgador, status = :status, advogado1 = :advogado1, advogado2 = :advogado2, advogado3 = :advogado3, advogado4 = :advogado4, data_abertura = :data_abertura, cliente = :cliente, usuario_lanc = :usuario_lanc, nome_contraria = :nome_contraria, telefone_contraria = :telefone_contraria, cpf_contraria = :cpf_contraria, rg_contraria = :rg_contraria, endereco_contraria = :endereco_contraria, estado_civil_contraria = :estado_civil_contraria, advogado_contraria = :advogado_contraria, data_cad = curDate(), obs = :obs $sql_data where num_processo = '$numero_processo'");
}


$query->bindValue(":num_processo", "$numero_processo");
$query->bindValue(":tipo_acao", "$tipo_servico");
$query->bindValue(":jurisdicao", "$jurisdicao");
$query->bindValue(":vara", "$vara");
$query->bindValue(":comarca", "$comarca");
$query->bindValue(":valor", "$valor_causa");
$query->bindValue(":segredo", "$segredo_justica");
$query->bindValue(":justica_gratuita", "$justica_gratuita");
$query->bindValue(":orgao_julgador", "$orgao_julgador");
$query->bindValue(":status", "$status");
$query->bindValue(":advogado1", "$advogado1_processo");
$query->bindValue(":advogado2", "$advogado1_processo");
$query->bindValue(":advogado3", "$advogado1_processo");
$query->bindValue(":advogado4", "$advogado1_processo");
$query->bindValue(":data_abertura", "$data_abertura_processo");
$query->bindValue(":cliente", "$cliente");
$query->bindValue(":usuario_lanc", "$id_usuario");
$query->bindValue(":nome_contraria", "$nome_contraria");
$query->bindValue(":telefone_contraria", "$telefone_contraria");
$query->bindValue(":cpf_contraria", "$cpf_contraria");
$query->bindValue(":rg_contraria", "$rg_contraria");
$query->bindValue(":endereco_contraria", "$endereco_contraria");
$query->bindValue(":estado_civil_contraria", "$estado_civil_contraria");
$query->bindValue(":advogado_contraria", "$advogado_contraria");
$query->bindValue(":obs", "$obs_processo");

$query->execute();


$id = $_POST['id'];

$query = $pdo->query("SELECT * FROM tipos_servicos where id = '$tipo_servico'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_servico = @$res[0]['nome'];

//buscar o valor_entrada antigo
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

$valor_entrada_antigo = @$res[0]['valor_entrada'];
$valor_antigo = @$res[0]['valor'];
$frequencia_antiga = @$res[0]['frequencia'];
$parcelas_antiga = @$res[0]['parcelas'];
$data_venc_antiga = @$res[0]['data_venc'];


//validacao num contrato
$query = $pdo->query("SELECT * from $tabela where numeracao = '$numero_contrato'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if (@count($res) > 0 and $id != $id_reg) {
	echo 'Número do Contrato já Cadastrado!';
	exit();
}

//buscar o total de reg
$query = $pdo->query("SELECT * FROM $tabela");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registros = @count($res);

$valor_advogado1_antigo = @$res[0]['valor_advogado1'];
$valor_advogado2_antigo = @$res[0]['valor_advogado2'];
$valor_advogado3_antigo = @$res[0]['valor_advogado3'];
$valor_indicacao_antigo = @$res[0]['valor_indicacao'];
$valor_marketing_antigo = @$res[0]['valor_marketing'];
$valor_pessoa1_antigo = @$res[0]['valor_pessoa1'];
$valor_pessoa2_antigo = @$res[0]['valor_pessoa2'];
$valor_escritorio_antigo = @$res[0]['valor_escritorio'];

require_once("validacoes.php");

//verificar as comissões
$total_comissoes = $valor_escritorio + $valor_advogado1 + $valor_advogado2 + $valor_advogado3 + $valor_marketing + $valor_indicacao + $valor_pessoa1 + $valor_pessoa2;

if ($total_comissoes > 0) {
	if ($total_comissoes != 100) {
		echo 'O total de recebimentos / comissões não está dando 100%, ou retire todas ou coloque os 100% redondo!';
		exit();
	}
}


if ($id == "") {

	$query = $pdo->prepare("INSERT INTO $tabela SET cliente = :cliente, valor = :valor, valor_entrada = :valor_entrada, parcelas = :parcelas, frequencia = :frequencia, data_venc = :data_venc, status = :status, tipo_servico = :tipo_servico, obs = :obs, valor_escritorio = :valor_escritorio, advogado1 = :advogado1, advogado2 = :advogado2, advogado3 = :advogado3, indicacao = :indicacao, marketing = :marketing, pessoa1 = :pessoa1, valor_advogado1 = :valor_advogado1, valor_advogado2 = :valor_advogado2, valor_advogado3 = :valor_advogado3, valor_marketing = :valor_marketing, valor_pessoa1 = :valor_pessoa1, valor_pessoa2 = :valor_pessoa1, pessoa2 = :pessoa2, valor_indicacao = :valor_indicacao, numero_processo = :numero_processo, motivo1 = :motivo1, motivo2 = :motivo2, usuario_lanc = :usuario_lanc, forma_pgto = :forma_pgto, data = '$data_abertura', numeracao = '$nova_numeracao', ano = '$ano_contrato', ultimo_numero = '$ultima_numeracao', supervisor = '$supervisor', prestador1 = '$prestador1', prestador2 = '$prestador2', origem = '$origem', forma_recebimento = '$forma_recebimento', data_valor_entrada = '$data_valor_entrada', area_poderes = '$area_poderes', area_valor = '$area_valor', parcelas_entrada = '$parcelas_entrada', frequencia_entrada = '$frequencia_entrada' ");

} else {
	$query = $pdo->prepare("UPDATE $tabela SET cliente = :cliente, valor = :valor, valor_entrada = :valor_entrada, parcelas = :parcelas, frequencia = :frequencia, data_venc = :data_venc, status = :status, tipo_servico = :tipo_servico, obs = :obs, valor_escritorio = :valor_escritorio, advogado1 = :advogado1, advogado2 = :advogado2, advogado3 = :advogado3, indicacao = :indicacao, marketing = :marketing, pessoa1 = :pessoa1, valor_advogado1 = :valor_advogado1, valor_advogado2 = :valor_advogado2, valor_advogado3 = :valor_advogado3, valor_marketing = :valor_marketing, valor_pessoa1 = :valor_pessoa1, valor_pessoa2 = :valor_pessoa2, pessoa2 = :pessoa2, valor_indicacao = :valor_indicacao, numero_processo = :numero_processo, motivo1 = :motivo1, motivo2 = :motivo2, usuario_lanc = :usuario_lanc, forma_pgto = :forma_pgto, data = '$data_abertura', numeracao = '$nova_numeracao', ano = '$ano_contrato', ultimo_numero = '$ultima_numeracao', supervisor = '$supervisor', prestador1 = '$prestador1', prestador2 = '$prestador2', origem = '$origem', forma_recebimento = '$forma_recebimento', data_valor_entrada = '$data_valor_entrada', area_poderes = '$area_poderes', area_valor = '$area_valor', parcelas_entrada = '$parcelas_entrada', frequencia_entrada = '$frequencia_entrada' where id = '$id'");
}

$query->bindValue(":cliente", "$cliente");
$query->bindValue(":valor", "$valor");
$query->bindValue(":valor_entrada", "$valor_entrada");
$query->bindValue(":parcelas", "$parcelas");
$query->bindValue(":frequencia", "$frequencia");
$query->bindValue(":data_venc", "$data_venc");
$query->bindValue(":status", "Aberto");
$query->bindValue(":tipo_servico", "$tipo_servico");
$query->bindValue(":obs", "$obs");
$query->bindValue(":valor_escritorio", "$valor_escritorio");
$query->bindValue(":advogado1", "$advogado1");
$query->bindValue(":advogado2", "$advogado2");
$query->bindValue(":advogado3", "$advogado3");
$query->bindValue(":indicacao", "$indicacao");
$query->bindValue(":marketing", "$marketing");
$query->bindValue(":pessoa1", "$pessoa1");
$query->bindValue(":valor_advogado1", "$valor_advogado1");
$query->bindValue(":valor_advogado2", "$valor_advogado2");
$query->bindValue(":valor_advogado3", "$valor_advogado3");
$query->bindValue(":valor_marketing", "$valor_marketing");
$query->bindValue(":valor_pessoa1", "$valor_pessoa1");
$query->bindValue(":valor_pessoa2", "$valor_pessoa2");
$query->bindValue(":pessoa2", "$pessoa2");
$query->bindValue(":valor_indicacao", "$valor_indicacao");
$query->bindValue(":numero_processo", "$numero_processo");
$query->bindValue(":motivo1", "$motivo1");
$query->bindValue(":motivo2", "$motivo2");
$query->bindValue(":usuario_lanc", "$id_usuario");
$query->bindValue(":forma_pgto", "$forma_pgto");

$query->execute();
$ult_id = $pdo->lastInsertId();



//verificar caixa aberto
$query1 = $pdo->query("SELECT * from caixas where operador = '$id_usuario' and data_fechamento is null order by id desc limit 1");
$res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
if (@count($res1) > 0) {
	$id_caixa = @$res1[0]['id'];
} else {
	$id_caixa = 0;
}

$desc_contrato = $nome_servico;

$desc_entrada = 'Entrada ' . @$nova_numeracao;

//verificar se tem entrada
if ($valor_entrada > 0) {

	if ($data_valor_entrada != "" and $parcelas_entrada == 1) {
		$sql_pgto_entrada = " ,pago = 'Sim', data_pgto = '$data_valor_entrada' ";
	} else {
		$sql_pgto_entrada = " ,pago = 'Não'";
	}

	if ($id == "" or $valor_entrada_antigo != $valor_entrada) {
		//inserir uma conta a receber com financeiro = nao, contendo valor da entreda
		$pdo->query("INSERT INTO receber set descricao = '$desc_entrada', cliente = '$cliente', valor = '$valor_entrada', usuario_lanc = '$id_usuario', data_lanc = '$data_valor_entrada', vencimento = '$data_valor_entrada',  frequencia = '0', forma_pgto = '$forma_pgto', arquivo = 'sem-foto.png', usuario_pgto = '$id_usuario', referencia = 'Abertura Contrato', id_ref = '$ult_id', subtotal = '$valor_entrada', hora = curTime(), caixa = '$id_caixa', financeiro = 'Não', hora_alerta = '$hora_random' $sql_pgto_entrada");
	}

	if ($id == "" or $valor_entrada_antigo != $valor_entrada or $valor_advogado1_antigo != $valor_advogado1 or $valor_advogado2_antigo != $valor_advogado2 or $valor_advogado3_antigo != $valor_advogado3 or $valor_marketing_antigo != $valor_marketing or $valor_indicacao_antigo != $valor_indicacao or $valor_pessoa1_antigo != $valor_pessoa1 or $valor_pessoa2_antigo != $valor_pessoa2 or $valor_escritorio_antigo != $valor_escritorio) {

		if ($id != "" and $id > 0) {
			$pdo->query("DELETE FROM receber WHERE referencia = 'Abertura Contrato' and id_ref = '$id' and pago = 'Sim' and parcela is null");
		}


		if ($total_comissoes == 0 and $parcelas_entrada == 1) {
			$pdo->query("INSERT INTO receber set descricao = '$desc_contrato', cliente = '$cliente', valor = '$valor_entrada', usuario_lanc = '$id_usuario', data_lanc = '$data_valor_entrada', vencimento = '$data_valor_entrada', data_pgto = '$data_valor_entrada', frequencia = '0', forma_pgto = '$forma_pgto', arquivo = 'sem-foto.png', usuario_pgto = '$id_usuario', pago = 'Sim', referencia = 'Abertura Contrato', id_ref = '$ult_id', subtotal = '$valor_entrada', hora = curTime(), caixa = '$id_caixa', hora_alerta = '$hora_random'");
		}

		//separar os lançamentos feitos
		if ($total_comissoes > 0 and $parcelas_entrada == 1) {
			$sql_usuario_pgto = ", usuario_pgto = '$id_usuario' ";
			$sql_data_pgto = ", data_pgto = '$data_valor_entrada' ";
			$data_do_venc = $data_valor_entrada;
			$esta_pago = 'Sim';
			$sql_parcela = ' ';



			$comissao_entrada = 'Sim';
			require_once("lancar_comissoes.php");
		}



	}

	if ($id != "") {
		$pdo->query("DELETE FROM receber WHERE referencia = 'Abertura Contrato' and id_ref = '$id' and pago != 'Sim' and parcela is not null and financeiro is null and parcela_entrada is null and comissao_entrada = 'Sim'");
	}
}



//lançar as demais contas pendentes
$query = $pdo->query("SELECT * FROM receber WHERE referencia = 'Abertura Contrato' and id_ref = '0' and usuario_lanc = '$id_usuario' and parcela is not null");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas > 0) {
	for ($i = 0; $i < $linhas; $i++) {
		$data_venc = $res[$i]['vencimento'];
		$numero_parcela = $res[$i]['parcela'];
		$valor = $res[$i]['valor'];
		$parc_entrada = $res[$i]['parcela_entrada'];

		$sql_usuario_pgto = ' ';
		$sql_data_pgto = ' ';
		$data_do_venc = $data_venc;
		$esta_pago = 'Não';
		$sql_parcela = ", parcela = '$numero_parcela' ";



		if ($id != "") {
			if ($valor_entrada_antigo != $valor_entrada_post) {
				$pdo->query("DELETE FROM receber WHERE referencia = 'Abertura Contrato' and id_ref = '$id' and pago != 'Sim' and parcela is not null and financeiro is null and parcela_entrada is null and comissao_entrada != 'Sim'");
			}
		}

		$valor_entrada = $valor;




		$comissao_entrada = $parc_entrada;
		require("lancar_comissoes.php");
	}
}


if ($total_comissoes > 0) {
	//associar as parcelas
	$query = $pdo->query("UPDATE receber SET id_ref = '$ult_id' WHERE referencia = 'Abertura Contrato' and id_ref = '0' and usuario_lanc = '$id_usuario' and parcela is not null");

} else {
	//associar as parcelas
	$query = $pdo->query("UPDATE receber SET id_ref = '$ult_id', mostrar = 'Sim' WHERE referencia = 'Abertura Contrato' and id_ref = '0' and usuario_lanc = '$id_usuario' and parcela is not null");

}


if ($id != "") {
	if ($valor_antigo != $valor or $valor_entrada_antigo != $valor_antigo or $parcelas_antiga != $parcelas or $frequencia_antiga != $frequencia or $data_venc_antiga != $data_venc) {


		$pdo->query("UPDATE receber SET id_ref = '$id' WHERE referencia = 'Abertura Contrato' and id_ref = '0' and usuario_lanc = '$id_usuario' and parcela is not null");

	}
}


$query = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = @$res[0]['nome'];

if ($id == "") {
	//recuperar os dados dos advogados relacionados e enviar uma mensagem para eles
	$query = $pdo->query("SELECT * FROM usuarios where id = '$advogado1'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$nome_advogado1 = @$res[0]['nome'];
	$telefone_advogado1 = @$res[0]['telefone'];

	$query = $pdo->query("SELECT * FROM usuarios where id = '$advogado2'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$nome_advogado2 = @$res[0]['nome'];
	$telefone_advogado2 = @$res[0]['telefone'];

	$query = $pdo->query("SELECT * FROM usuarios where id = '$advogado3'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$nome_advogado3 = @$res[0]['nome'];
	$telefone_advogado3 = @$res[0]['telefone'];


	$telefone_envio = '55' . @preg_replace('/[ ()-]+/', '', $telefone_advogado1);
	$mensagem_whatsapp = '🏛️ *' . $nome_sistema . '*%0A%0A';
	$mensagem_whatsapp .= '👩‍⚖️ _Olá ' . $nome_advogado1 . '! Você foi associado(a) a um novo contrato._%0A%0A';
	$mensagem_whatsapp .= '👤 *Cliente:* ' . $nome_cliente . '%0A';
	$mensagem_whatsapp .= '💵 *Valor:* ' . $valorF . '%0A';
	$mensagem_whatsapp .= '⚖️ *Tipo de Serviço:* ' . $nome_servico;
	require('../../apis/texto.php');

	$telefone_envio = '55' . @preg_replace('/[ ()-]+/', '', $telefone_advogado2);
	$mensagem_whatsapp = '🏛️ *' . $nome_sistema . '*%0A%0A';
	$mensagem_whatsapp .= '👩‍⚖️ _Olá ' . $nome_advogado2 . '! Você foi associado(a) a um novo contrato._%0A%0A';
	$mensagem_whatsapp .= '👤 *Cliente:* ' . $nome_cliente . '%0A';
	$mensagem_whatsapp .= '💵 *Valor:* ' . $valorF . '%0A';
	$mensagem_whatsapp .= '⚖️ *Tipo de Serviço:* ' . $nome_servico;
	require('../../apis/texto.php');

	$telefone_envio = '55' . @@preg_replace('/[ ()-]+/', '', $telefone_advogado3);
	$mensagem_whatsapp = '🏛️ *' . $nome_sistema . '*%0A%0A';
	$mensagem_whatsapp .= '👩‍⚖️ _Olá ' . $nome_advogado3 . '! Você foi associado(a) a um novo contrato._%0A%0A';
	$mensagem_whatsapp .= '👤 *Cliente:* ' . $nome_cliente . '%0A';
	$mensagem_whatsapp .= '💵 *Valor:* ' . $valorF . '%0A';
	$mensagem_whatsapp .= '⚖️ *Tipo de Serviço:* ' . $nome_servico;
	require('../../apis/texto.php');

}
echo 'Salvo com Sucesso';


?>