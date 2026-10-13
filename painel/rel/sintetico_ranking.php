<?php
include('data_formatada.php');

if ($token_rel != 'A5030') {
	echo '<script>window.location="../../"</script>';
	exit();
}

if ($mostrar_registros == "Não") {
	$sql_registros = " and usuario_lanc = '$id_usuario' ";
} else {
	$sql_registros = " ";
}
$dataInicialF = implode('/', array_reverse(@explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(@explode('-', $dataFinal)));
$datas = "";
if ($dataInicial == $dataFinal) {
	$datas = $dataInicialF;
} else {
	$datas = $dataInicialF . ' à ' . $dataFinalF;
}
$texto_filtro = $datas;

if ($dataInicial == '1900-01-01') {
	$texto_filtro = 'Todo o Período';
}
?>
<!DOCTYPE html>
<html>
<head>
	<style>
		@import url('https://fonts.cdnfonts.com/css/tw-cen-mt-condensed');
		@page {
			margin: 145px 20px 25px 20px;
		}
		#header {
			position: fixed;
			left: 0px;
			top: -110px;
			bottom: 100px;
			right: 0px;
			height: 35px;
			text-align: center;
			padding-bottom: 100px;
		}
		#content {
			margin-top: 0px;
		}
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
		.marca {
			position: fixed;
			left: 50;
			top: 100;
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
					<td style="border: 1px; solid #000; width: 7%; text-align: left;">
						<img style="margin-top: 7px; margin-left: 7px;" id="imag"
							src="<?php echo $url_sistema ?>img/logo.jpg" width="130px">
					</td>
					<td style="width: 30%; text-align: left; font-size: 13px;">
					</td>
					<td style="width: 1%; text-align: center; font-size: 13px;">
					</td>
					<td style="width: 47%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>RELATÓRIO DE RANKING</big></b><br> <?php echo mb_strtoupper($texto_filtro) ?> <br>
						<?php echo mb_strtoupper($data_hoje) ?>
					</td>
				</tr>
			</table>
		</div>
		<br>
		<table id="cabecalhotabela"
			style="border-bottom-style: solid; font-size: 12px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
					<td style="width:40%">ADVOGADO</td>
					<td style="width:15%">CLIENTES</td>
					<td style="width:15%">R$ PENDENTES</td>
					<td style="width:15%">R$ RECEBIDAS</td>
					<td style="width:15%">CONTRATOS</td>
				</tr>
			</thead>
		</table>
	</div>
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
	<div id="content" style="margin-top: 0;">
		<table style="width: 100%; table-layout: fixed; font-size:11px; text-transform: uppercase;">
			<thead>
			<tbody>
				<?php
				$query9 = $pdo->query("SELECT * from usuarios where (nivel = 'Advogado' or nivel = 'Administrador') $sql_registros order by nome asc");
				$res9 = $query9->fetchAll(PDO::FETCH_ASSOC);
				$linhas9 = @count($res9);
				if ($linhas9 > 0) {
					for ($i9 = 0; $i9 < $linhas9; $i9++) {
						$id_pessoa = $res9[$i9]['id'];
						$nome_pessoa = $res9[$i9]['nome'];
						$tel_pessoa = $res9[$i9]['telefone'];
						$total_clientes = 0;
						$total_contratos = 0;
						$total_pendentes = 0;
						$total_pendentesF = 0;
						$total_pagas = 0;
						$total_pagasF = 0;
						$query = $pdo->query("SELECT * from receber where data_pgto >= '$dataInicial' and data_pgto <= '$dataFinal' and pago = 'Sim' and usuario_receb = '$id_pessoa' and (financeiro is null or mostrar = 'Sim')");
						$res = $query->fetchAll(PDO::FETCH_ASSOC);
						$linhas = @count($res);
						if ($linhas > 0) {
							for ($i = 0; $i < $linhas; $i++) {
								$valor = $res[$i]['valor'];
								$total_pagas += $valor;
								$total_pagasF = @number_format($total_pagas, 2, ',', '.');
							}
						}
						$query = $pdo->query("SELECT * from receber where vencimento >= '$dataInicial' and vencimento <= '$dataFinal' and pago = 'Não' and usuario_receb = '$id_pessoa' and (financeiro is null or mostrar = 'Sim')");
						$res = $query->fetchAll(PDO::FETCH_ASSOC);
						$linhas = @count($res);
						if ($linhas > 0) {
							for ($i = 0; $i < $linhas; $i++) {
								$valor = $res[$i]['valor'];
								$total_pendentes += $valor;
								$total_pendentesF = @number_format($total_pendentes, 2, ',', '.');
							}
						}
						$query = $pdo->query("SELECT * from clientes where data_cad >= '$dataInicial' and data_cad <= '$dataFinal' and usuario = '$id_pessoa'");
						$res = $query->fetchAll(PDO::FETCH_ASSOC);
						$total_clientes = @count($res);
						$query = $pdo->query("SELECT * from abertura_contratos where data >= '$dataInicial' and data <= '$dataFinal' and (advogado1 = '$id_pessoa' or advogado2 = '$id_pessoa' or advogado3 = '$id_pessoa' or marketing = '$id_pessoa' or indicacao = '$id_pessoa' or pessoa1 = '$id_pessoa' or pessoa2 = '$id_pessoa')");
						$res = $query->fetchAll(PDO::FETCH_ASSOC);
						$total_contratos = @count($res);
						?>
						<tr>
							<td style="width:40%">
								<?php echo $nome_pessoa ?>
							</td>
							<td style="width:15%"><?php echo $total_clientes ?></td>
							<td style="width:15%; color:red">R$ <?php echo $total_pendentesF ?></td>
							<td style="width:15%; color:green">R$ <?php echo $total_pagasF ?></td>
							<td style="width:15%"><?php echo $total_contratos ?></td>
						</tr>
					<?php }
				} ?>
			</tbody>
			</thead>
		</table>
	</div>
	<hr>
</body>
</html>