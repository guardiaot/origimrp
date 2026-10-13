<?php
include('data_formatada.php');

if ($token_rel != 'M543661') {
	echo '<script>window.location="../../"</script>';
	exit();
}
$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));
$mes = $mes_extenso["$mes"];
$texto_apuracao = 'FOLHA DE PONTO ' . $mes;
$query = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_func = $res[0]['nome'];
$salario = $res[0]['salario'];
$valor_hora = $res[0]['valor_hora'];

if ($valor_hora != "" and $valor_hora != "0.00") {
	$salario = number_format($valor_hora, 2, ',', '.') . ' HORA';
} else {
	$salario = number_format($salario, 2, ',', '.') . ' MÊS';
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
		<img class="marca" src="<?php echo $url_sistema ?>img/<?php echo $logo_rel ?>">
	<?php } ?>
	<div id="header">
		<div style="border-style: solid; font-size: 10px; height: 70px;">
			<table style="width: 100%; border: 0px solid #ccc;">
				<tr>
					<td style="border: 1px; solid #000; width: 7%; text-align: left;">
						<img style="margin-top: 7px; margin-left: 7px;" id="imag"
							src="<?php echo $url_sistema ?>img/<?php echo $logo_rel ?>" width="130px">
					</td>
					<td style="width: 30%; text-align: left; font-size: 13px;">
					</td>
					<td style="width: 1%; text-align: center; font-size: 13px;">
					</td>
					<td style="width: 47%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>FOLHA DE PONTO </big></b>
						<br>FUNCIONÁRIO: <?php echo mb_strtoupper($nome_func) ?> / SALÁRIO R$
						<?php echo mb_strtoupper($salario) ?>
						<br> <?php echo mb_strtoupper($data_hoje) ?>
					</td>
				</tr>
			</table>
		</div>
		<br>
		<table id="cabecalhotabela"
			style="border-bottom-style: solid; font-size: 10px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
					<td style="width:15%">DATA</td>
					<td style="width:10%">ENTRADA</td>
					<td style="width:10%">ALMOCO</td>
					<td style="width:10%">ALMOCO</td>
					<td style="width:10%">SAÍDA</td>
					<td style="width:15%">TOTAL</td>
					<td style="width:10%">INTERVALO</td>
					<td style="width:20%">HORA EXTRA</td>
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
		<?php
		$query = $pdo->query("SELECT * FROM jornada where funcionario = '$funcionario' and data >= '$dataInicial' and data <= '$dataFinal' order by data asc");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$total_reg = count($res);
		if ($total_reg > 0) {
			?>
			<table style="width: 100%; table-layout: fixed; font-size:10px; text-transform: uppercase;">
				<thead>
				<tbody>
					<?php
					for ($i = 0; $i < $total_reg; $i++) {
						foreach ($res[$i] as $key => $value) {
						}
						$id = $res[$i]['id'];
						$data = $res[$i]['data'];
						$entrada = $res[$i]['entrada'];
						$entrada_almoco = $res[$i]['entrada_almoco'];
						$saida_almoco = $res[$i]['saida_almoco'];
						$saida = $res[$i]['saida'];
						$total_horas = $res[$i]['total_horas'];
						$hora_extra = $res[$i]['hora_extra'];
						$folga = $res[$i]['folga'];
						$falta = $res[$i]['falta'];
						$feriado = $res[$i]['feriado'];
						$intervalo = $res[$i]['intervalo'];
						$dataF = implode('/', array_reverse(explode('-', $data)));
						$entrada = date("H:i", strtotime($entrada));
						$entrada_almoco = date("H:i", strtotime($entrada_almoco));
						$saida_almoco = date("H:i", strtotime($saida_almoco));
						$saida = date("H:i", strtotime($saida));
						$total_horas = date("H:i", strtotime($total_horas));
						$hora_extra = date("H:i", strtotime($hora_extra));
						$intervalo = date("H:i", strtotime($intervalo));
						$classe_linha = '';
						if ($hora_extra != '00:00') {
							$classe_extra = 'red';
						} else {
							$classe_extra = '';
						}
						if ($folga != "") {
							$entrada = 'Folga';
							$entrada_almoco = 'Folga';
							$saida_almoco = '';
							$saida = 'Folga';
							$total_horas = 'Folga';
							$hora_extra = 'Folga';
							$classe_linha = 'blue';
						}
						if ($falta != "") {
							$entrada = 'Falta';
							$entrada_almoco = 'Falta';
							$saida_almoco = '';
							$saida = 'Falta';
							$total_horas = 'Falta';
							$hora_extra = 'Falta';
							$classe_linha = 'red';
						}
						if ($feriado != "") {
							$entrada = 'Feriado';
							$entrada_almoco = 'Feriado';
							$saida_almoco = '';
							$saida = 'Feriado';
							$total_horas = 'Feriado';
							$hora_extra = 'Feriado';
							$classe_linha = 'green';
						}
						?>
						<tr>
							<td style="width:15%; color:<?php echo $classe_linha ?>"><?php echo $dataF ?></td>
							<td style="width:10%; color:<?php echo $classe_linha ?>"><?php echo $entrada ?></td>
							<td style="width:10%; color:<?php echo $classe_linha ?>"><?php echo $entrada_almoco ?></td>
							<td style="width:10%; color:<?php echo $classe_linha ?>"><?php echo $saida_almoco ?></td>
							<td style="width:10%; color:<?php echo $classe_linha ?>"><?php echo $saida ?></td>
							<td style="width:15%; color:<?php echo $classe_linha ?>"><?php echo $total_horas ?></td>
							<td style="width:10%; color:<?php echo $classe_linha ?>"><?php echo $intervalo ?></td>
							<td style="width:20%; color:<?php echo $classe_linha ?>; color:<?php echo $classe_extra ?>">
								<?php echo $hora_extra ?></td>
						</tr>
					<?php } ?>
				</tbody>
				</thead>
			</table>
		<?php } ?>
	</div>
	<hr>
	<br><br>
	<div class="col-md-12 p-2">
		<div class="" align="center">
			________________________________________________________<br>
			<span class=""> <small><small><small><?php echo mb_strtoupper($texto_apuracao) ?></small></small></small>
			</span>
		</div>
	</div>
</body>
</html>