<?php
include('data_formatada.php');

if ($token_rel != 'M543661') {
	echo '<script>window.location="../../"</script>';
	exit();
}
$query = $pdo->query("SELECT * FROM temp_texto WHERE id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$texto = $res[0]['texto'];
$cliente = $res[0]['cliente'];
$ip_usuario = $_SERVER['REMOTE_ADDR'];

if ($cliente_gerando == 'Sim') {
	$pdo->query("UPDATE temp_texto SET ip = '$ip_usuario', data = curDate(), hora = curTime() WHERE id = '$id'");
}
$query = $pdo->query("SELECT * FROM clientes WHERE id = '$cliente'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$assinatura = @$res[0]['assinatura'];


if ($cliente_gerando == 'Não') {
	$assinatura = '';
}
//$pdo->query("DELETE FROM temp_texto WHERE id = '$id'");
?>
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		<?php if ($cabecalho_form != 'Não') { ?>
			@page {
				margin: 25px 20px 25px 20px;
			}
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
			#content {
				margin-left: 113px;
				margin-right: 75px;
				margin-bottom: 50px
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
				font-family: 'Times New Roman', Times, serif;
				/* Ou Arial */
				font-size: 12pt;
				line-height: 1.5;
				color: #000;
				text-align: justify;
			}
		<?php } else { ?>
			@page {
				margin: 113px 75px 75px 113px;
			}
		<?php } ?>
	</style>
</head>
<body>
	<?php if ($cabecalho_form != 'Não') { ?>
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
							<b><big><?php echo mb_strtoupper($nome_sistema) ?></big></b>
							<br>CNPJ: <?php echo mb_strtoupper($cnpj_sistema) ?> Telefone: <?php echo $telefone_sistema ?>
							<br> <?php echo mb_strtoupper($data_hoje) ?>
						</td>
					</tr>
				</table>
			</div>
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
		<div id="content" style="">
			<?php echo $texto ?>
		</div>
	<?php } else {
		echo $texto;
	} ?>
</body>
</html>