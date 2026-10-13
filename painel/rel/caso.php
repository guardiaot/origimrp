<?php
require_once("data_formatada.php");

if ($token_rel != 'A5030') {
	echo '<script>window.location="../../"</script>';
	exit();
}
$query = $pdo->query("SELECT * from casos_ia where id_caso = '$id' order by id desc limit 1");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas == 0) {
	if ($texto == "") {
		echo 'Você precisa antes gerar a análise com a IA ou preencher algum texto manualmente!';
		exit();
	}
	$parecer_formatado = $texto;
} else {
	$parecer = $res[0]['parecer'];
	if ($texto != "") {
		$parecer = $texto;
	}
	$parecer_formatado = preg_replace('/\*\*(.*?)\*\*/s', '<strong>$1</strong>', $parecer);
}
$data_atual = date('Y-m-d');
$query = $pdo->query("SELECT * from casos where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas == 0) {
	echo 'Caso não encontrado!';
	exit();
} else {
	$id = $res[0]['id'];
	$tipo_acao = $res[0]['tipo_acao'];
	$descricao = $res[0]['descricao'];
	$partes = $res[0]['partes'];
	$processo_existente = $res[0]['processo_existente'];
	$objetivo = $res[0]['objetivo'];
	$id_cliente = $res[0]['id_cliente'];
	$advogado = $res[0]['id_advogado'];
	$status = $res[0]['status'];
	$dataF = implode('/', array_reverse(@explode('-', $data)));
	$query2 = $pdo->query("SELECT * FROM clientes where id = '$id_cliente'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if (@count($res2) > 0) {
		$nome_cliente = $res2[0]['nome'];
		$telefone_cliente = $res2[0]['telefone'];
	} else {
		$nome_cliente = 'Sem Registro';
		$telefone_cliente = 'Sem Registro';
	}
	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$advogado'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if (@count($res2) > 0) {
		$nome_advogado = $res2[0]['nome'];
	} else {
		$nome_advogado = 'Não Selecionado';
	}
	$query2 = $pdo->query("SELECT * FROM tipos_servicos where id = '$tipo_acao'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if (@count($res2) > 0) {
		$nome_servico = $res2[0]['nome'];
	} else {
		$nome_servico = '';
	}
	if ($status == 'Pendente') {
		$ocultar_botoes = '';
		$classe_status = '#9c2834';
	} else if ($status == 'Em Análise') {
		$ocultar_botoes = '';
		$classe_status = '#1e237d';
	} else if ($status == 'Concluído') {
		$ocultar_botoes = '';
		$classe_status = '#22854d';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<style>
		@import url('https://fonts.cdnfonts.com/css/tw-cen-mt-condensed');
		/* Agora sem espaço reservado de header repetido */
		@page {
			margin: 25px 20px 25px 20px;
		}
		/* Cabeçalho só na primeira página: elemento normal (não fixed) */
		#header {
			position: relative;
			/* antes era fixed */
			left: 0;
			right: 0;
			top: 0;
			height: auto;
			text-align: center;
			padding: 0;
			/* ajuste do espaçamento */
			margin-bottom: 12px;
			/* espaço abaixo do cabeçalho para o conteúdo */
		}
		/* Rodapé continua fixo para repetir em todas as páginas */
		#footer {
			position: fixed;
			left: 0px;
			bottom: -60px;
			right: 0px;
			height: 80px;
		}
		#footer .page:after {
			content: counter(page, my-sec-counter);
		}
		body {
			font-family: 'Tw Cen MT', sans-serif;
		}
		/* Marca d'água continua fixa (se quiser repetir em todas as páginas) */
		.marca {
			position: fixed;
			left: 50px;
			top: 100px;
			width: 80%;
			opacity: 8%;
		}
	</style>
</head>
<body>
	<?php
	if ($marca_dagua == 'Sim') { ?>
		<img class="marca" src="<?php echo $url_sistema ?>img/logo.jpg">
	<?php } ?>
	<div id="header">
		<div style="border-style: solid; font-size: 10px; height: 70px;">
			<table style="width: 100%; border: 0px solid #ccc;">
				<tr>
					<td style="border: 1px; solid #000; width: 40%; text-align: left;">
						<img style="margin-top: 7px; margin-left: 7px;" id="imag"
							src="<?php echo $url_sistema ?>img/logo.jpg" width="130px">
					</td>
					<td style="width: 5%; text-align: center; font-size: 13px;">
					</td>
					<td style="width: 40%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>ORIENTAÇÃO JURÍDICA</big></b><br> <b>CLIENTE:
						</b><?php echo mb_strtoupper($nome_cliente) ?> <br> <?php echo mb_strtoupper($data_hoje) ?>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<br>
	<div id="footer" class="row">
		<hr style="margin-bottom: 0;">
		<table style="width:100%;">
			<tr style="width:100%;">
				<td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> Telefone:
					<?php echo $telefone_sistema ?></td>
				<td style="width:40%; font-size: 10px; text-align: right;">
					<p class="page">Página </p>
				</td>
			</tr>
		</table>
	</div>
	<table id="cabecalhotabela"
		style="border-bottom-style: solid; font-size: 10px; margin-bottom:10px; width: 100%; table-layout: fixed;">
		<thead>
			<tr id="cabeca" style="margin-left: 0px; background-color:#CCC; font-weight: bold">
				<td style="width:25%">CLIENTE</td>
				<td style="width:25%">TIPO AÇÃO</td>
				<td style="width:15%">TEM PROCESSO?</td>
				<td style="width:25%">ADVOGADO</td>
				<td style="width:10%">STATUS</td>
			</tr>
			<tr id="cabeca" style="margin-left: 0px;">
				<td style="width:25%"><?php echo $nome_cliente ?></td>
				<td style="width:25%"><?php echo $nome_servico ?></td>
				<td style="width:15%"><?php echo $processo_existente ?></td>
				<td style="width:25%"><?php echo $nome_advogado ?></td>
				<td style="width:10%; color:<?php echo $classe_status ?>"><?php echo $status ?></td>
			</tr>
		</thead>
	</table>
	<div
		style="font-size:14px; font-weight:bold; border-bottom:2px solid #333; padding-bottom:5px; margin-bottom:10px;">
		DETALHAMENTO DA ORIENTAÇÃO JURÍDICA
	</div>
	<div
		style="border:1px solid #ccc; padding:15px; border-radius:5px; font-size:14px; line-height:1.6; text-align:justify;">
		<?php echo nl2br($parecer_formatado); ?>
	</div>
</body>
</html>