<?php
require_once("data_formatada.php");

if ($token_rel != 'A5030') {
	echo '<script>window.location="../../"</script>';
	exit();
}
$dataInicialF = implode('/', array_reverse(@explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(@explode('-', $dataFinal)));
$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_selecionado'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if (@count($res2) > 0) {
	$texto_status = ' Profissional: ' . $res2[0]['nome'];
} else {
	$texto_status = '';
}
$datas = "";
if ($dataInicial == $dataFinal) {
	$datas = $dataInicialF;
} else {
	$datas = $dataInicialF . ' à ' . $dataFinalF;
}
$texto_filtro = $datas;
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
					<td style="width: 10%; text-align: left; font-size: 13px;">
					</td>
					<td style="width: 1%; text-align: center; font-size: 13px;">
					</td>
					<td style="width: 67%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>RELATÓRIO DE AUDIÊNCIAS <?php echo mb_strtoupper($texto_status) ?></big></b>
						<br>FILTRO: <?php echo mb_strtoupper($texto_filtro) ?>
						<br> <?php echo mb_strtoupper($data_hoje) ?>
					</td>
				</tr>
			</table>
		</div>
		<br>
		<table id="cabecalhotabela"
			style="border-bottom-style: solid; font-size: 8px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
					<td style="width:10%">DATA</td>
					<td style="width:10%">HORA</td>
					<td style="width:30%">ADVOGADO</td>
					<td style="width:20%">PROCESSO</td>
					<td style="width:20%">FÓRUM / COMARCA</td>
					<td style="width:10%">ONLINE</td>
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
		<table style="width: 100%; table-layout: fixed; font-size:8px; text-transform: uppercase;">
			<thead>
			<tbody>
				<?php
				if ($usuario_selecionado > 0) {
					$query = $pdo->query("SELECT * from audiencias where data >= '$dataInicial' and data <= '$dataFinal' and   advogado = '$usuario_selecionado' order by data asc, hora asc");
				} else {
					$query = $pdo->query("SELECT * from audiencias where data >= '$dataInicial' and data <= '$dataFinal'  order by data asc, hora asc");
				}
				$res = $query->fetchAll(PDO::FETCH_ASSOC);
				$linhas = @count($res);
				if ($linhas > 0) {
					for ($i = 0; $i < $linhas; $i++) {
						$id = $res[$i]['id'];
						$usuario = $res[$i]['advogado'];
						$usuario_lanc = $res[$i]['usuario_lanc'];
						$data = $res[$i]['data'];
						$hora = $res[$i]['hora'];
						$descricao = $res[$i]['forum'];
						$online = $res[$i]['online'];
						$hora_mensagem = $res[$i]['hora_mensagem'];
						$obs = $res[$i]['obs'];
						$processo = $res[$i]['processo'];
						$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario'");
						$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						if (@count($res2) > 0) {
							$nome_advogado = $res2[0]['nome'];
						} else {
							$nome_advogado = '';
						}
						$dataF = implode('/', array_reverse(@explode('-', $data)));
						$horaF = date("H:i", @strtotime($hora));
						?>
						<tr>
							<td style="width:10%"><?php echo $dataF ?> </td>
							<td style="width:10%"><?php echo $horaF ?></td>
							<td style="width:30%"><?php echo $nome_advogado ?></td>
							<td style="width:20%"><?php echo $processo ?></td>
							<td style="width:20%; "><?php echo $descricao ?></td>
							<td style="width:10%; "><?php echo $online ?></td>
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