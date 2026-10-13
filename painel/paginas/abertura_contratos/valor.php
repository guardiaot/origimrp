<?php 
$tabela = 'receber';
require_once("../../../conexao.php");
@session_start();
$id_usuario = @$_SESSION['id'];

$id = $_POST['id-valor'];
$valor = $_POST['valor'];
$data = $_POST['data'];
$forma_pgto = $_POST['forma_pgto'];
$descricao = $_POST['descricao'];



//verificar caixa aberto
$query1 = $pdo->query("SELECT * from caixas where operador = '$id_usuario' and data_fechamento is null order by id desc limit 1");
$res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
if(@count($res1) > 0){
	$id_caixa = @$res1[0]['id'];
}else{
	$id_caixa = 0;
}

//buscar valores do processo
$query1 = $pdo->query("SELECT * from abertura_contratos where id = '$id'");
$res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
$cliente = @$res1[0]['cliente'];
$id_contrato = @$res1[0]['id'];
$valor_escritorio = $res1[0]['valor_escritorio'];
	$advogado1 = $res1[0]['advogado1'];
	$advogado2 = $res1[0]['advogado2'];
	$advogado3 = $res1[0]['advogado3'];
	$indicacao = $res1[0]['indicacao'];
	$marketing = $res1[0]['marketing'];
	$pessoa1 = $res1[0]['pessoa1'];
	$pessoa2 = $res1[0]['pessoa2'];
	$valor_advogado1 = $res1[0]['valor_advogado1'];
	$valor_advogado2 = $res1[0]['valor_advogado2'];
	$valor_advogado3 = $res1[0]['valor_advogado3'];
	$valor_marketing = $res1[0]['valor_marketing'];
	$valor_indicacao = $res1[0]['valor_indicacao'];
	$valor_pessoa1 = $res1[0]['valor_pessoa1'];
	$valor_pessoa2 = $res1[0]['valor_pessoa2'];

//verificar as comissões
$total_comissoes = $valor_escritorio + $valor_advogado1 + $valor_advogado2 + $valor_advogado3 + $valor_marketing + $valor_indicacao + $valor_pessoa1 + $valor_pessoa2;

if($total_comissoes == 0){
	$query = $pdo->prepare("INSERT INTO $tabela SET descricao = '$descricao', cliente = '$cliente', valor = :valor, vencimento = '$data', data_pgto = '$data', data_lanc = curDate(), forma_pgto = '$forma_pgto', frequencia = '0', arquivo = 'sem-foto.png', subtotal = :valor, usuario_lanc = '$id_usuario', usuario_pgto = '$id_usuario', pago = 'Sim', referencia = 'Abertura Contrato', id_ref = '$id_contrato', caixa = '$id_caixa', hora = curTime(), parcela_entrada = 'Ad', usuario_receb = '0', hora_alerta = '$hora_random' ");
	$query->bindValue(":valor", "$valor");
	$query->execute();
}else{
	$query = $pdo->prepare("INSERT INTO $tabela SET descricao = '$descricao', cliente = '$cliente', valor = :valor, vencimento = '$data', data_pgto = '$data', data_lanc = curDate(), forma_pgto = '$forma_pgto', frequencia = '0', arquivo = 'sem-foto.png', subtotal = :valor, usuario_lanc = '$id_usuario', usuario_pgto = '$id_usuario', pago = 'Sim', referencia = 'Abertura Contrato', id_ref = '$id_contrato', caixa = '$id_caixa', hora = curTime(), parcela_entrada = 'Ad', financeiro = 'Não', hora_alerta = '$hora_random' ");

	$query->bindValue(":valor", "$valor");
	$query->execute();


	$sql_usuario_pgto = ", usuario_pgto = '$id_usuario' ";
			$sql_data_pgto = ", data_pgto = '$data' ";
			$data_do_venc = $data;
			$esta_pago = 'Sim';
			$sql_parcela = ' ';
			$valor_entrada = $valor;
			$ult_id = $id_contrato;			

			$comissao_entrada = 'Não';
			require_once("lancar_comissoes.php");
}




echo 'Inserido com Sucesso';

?>