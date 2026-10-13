<?php 
require_once("data_formatada.php");

if($token_rel != 'A5030'){
echo '<script>window.location="../../"</script>';
exit();
}

if($mostrar_registros == "Não"){
	$sql_registros = " and usuario_cadastro = '$id_usuario' ";
}else{
	$sql_registros = " ";
}
$dataInicialF = implode('/', array_reverse(@explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(@explode('-', $dataFinal)));
$texto_status = '';
if($status != ""){
	$texto_status = $status.' ';
}

if($tipo != ""){
	$texto_status .= ' Tipo: '.$tipo;
}
$datas = "";
if($dataInicial == $dataFinal){
	$datas = $dataInicialF;
}else{
	$datas = $dataInicialF.' à '.$dataFinalF;
}
$texto_filtro = $datas;
?>
<!DOCTYPE html>
<html>
<head>
<style>
@import url('https://fonts.cdnfonts.com/css/tw-cen-mt-condensed');
@page { margin: 145px 20px 25px 20px; }
#header { position: fixed; left: 0px; top: -110px; bottom: 100px; right: 0px; height: 35px; text-align: center; padding-bottom: 100px; }
#content {margin-top: 0px;}
#footer { position: fixed; left: 0px; bottom: -60px; right: 0px; height: 80px; }
#footer .page:after {content: counter(page, my-sec-counter);}
body {font-family: 'Tw Cen MT', sans-serif;}
.marca{
	position:fixed;
	left:50;
	top:100;
	width:80%;
	opacity:8%;
}
</style>
</head>
<body>
<?php 
if($marca_dagua == 'Sim'){ ?>
<img class="marca" src="<?php echo $url_sistema ?>img/logo.jpg">	
<?php } ?>
<div id="header" >
	<div style="border-style: solid; font-size: 10px; height: 70px;">
		<table style="width: 100%; border: 0px solid #ccc;">
			<tr>
				<td style="border: 1px; solid #000; width: 7%; text-align: left;">
					<img style="margin-top: 7px; margin-left: 7px;" id="imag" src="<?php echo $url_sistema ?>img/logo.jpg" width="130px">
				</td>
				<td style="width: 10%; text-align: left; font-size: 13px;">
				</td>
				<td style="width: 1%; text-align: center; font-size: 13px;">
				</td>
				<td style="width: 67%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>RELATÓRIO DE BENEFÍCIOS <?php echo mb_strtoupper($texto_status) ?></big></b>
							<br>FILTRO: <?php echo mb_strtoupper($texto_filtro) ?> 
							<br> <?php echo mb_strtoupper($data_hoje) ?>
				</td>
			</tr>		
		</table>
	</div>
<br>
		<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 8px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
					<td style="width:10%">NB</td>
					<td style="width:20%">CLIENTE</td>
					<td style="width:20%">TIPO BENEFÍCIO</td>
					<td style="width:10%">STATUS</td>
					<td style="width:10%">DER</td>
					<td style="width:10%">DIB</td>	
					<td style="width:10%">DCB PREV</td>					
					<td style="width:10%">DCB</td>	
				</tr>
			</thead>
		</table>
</div>
<div id="footer" class="row">
<hr style="margin-bottom: 0;">
	<table style="width:100%;">
		<tr style="width:100%;">
			<td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> Telefone: <?php echo $telefone_sistema ?></td>
			<td style="width:40%; font-size: 10px; text-align: right;"><p class="page">Página  </p></td>
		</tr>
	</table>
</div>
<div id="content" style="margin-top: 0;">
		<table style="width: 100%; table-layout: fixed; font-size:8px; text-transform: uppercase;">
			<thead>
				<tbody>
					<?php
$query = $pdo->query("SELECT * from beneficios where $data_filtro >= '$dataInicial' and $data_filtro <= '$dataFinal' and status like '%$status%' and tipo_beneficio like '%$tipo%' $sql_registros order by $data_filtro asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
for($i=0; $i<$linhas; $i++){
	 $id               = $res[$i]['id'];
    $cliente          = $res[$i]['cliente'];
    $nb               = $res[$i]['nb'];
    $tipo_beneficio   = $res[$i]['tipo_beneficio'];
    $der              = $res[$i]['der'];
    $dib              = $res[$i]['dib'];
    $dii              = $res[$i]['dii'];
    $alta_programada  = $res[$i]['alta_programada'];
    $dcb_prevista     = $res[$i]['dcb_prevista'];
    $dcb              = $res[$i]['dcb'];
    $status_row       = $res[$i]['status'];
    $motivo_cessacao  = $res[$i]['motivo_cessacao'];
    $origem_decisao   = $res[$i]['origem_decisao'];
    $doc_cessacao     = $res[$i]['doc_cessacao'];
    $obs              = $res[$i]['obs'];
    $usuario_cadastro = $res[$i]['usuario_cadastro'];
    // Datas formatadas
    $derF          = $der          ? implode('/', array_reverse(explode('-', $der))) : '';
    $dibF          = $dib          ? implode('/', array_reverse(explode('-', $dib))) : '';
    $dcb_previstaF = $dcb_prevista ? implode('/', array_reverse(explode('-', $dcb_prevista))) : '';
    $dcbF          = $dcb          ? implode('/', array_reverse(explode('-', $dcb))) : '';
    $diiF          = $dii          ? implode('/', array_reverse(explode('-', $dii))) : '';
    // Cliente
    $qCli = $pdo->query("SELECT nome FROM clientes WHERE id = '$cliente'");
    $rCli = $qCli->fetch(PDO::FETCH_ASSOC);
    $nome_cliente = $rCli['nome'] ?? 'Sem Registro';
    // Cores por status
    switch (strtolower($status_row)) {
        case 'cancelado':  $classe_status = '#91180d'; break;
        case 'suspenso':   $classe_status = '#bf4b1d'; break;
        case 'convertido': $classe_status = '#2b7a00'; break;
        case 'cessado':    $classe_status = '#8f8f8f'; break;
        case 'ativo':
        default:           $classe_status = '#1c6cad'; break;
    }
    // Sanitizações para inline JS
    $obs_js             = str_replace('"', '**', (string)$obs);
    $doc_cessacao_js    = str_replace('"', '**', (string)$doc_cessacao);
    $motivo_js          = str_replace('"', '**', (string)$motivo_cessacao);
    $origem_js          = str_replace('"', '**', (string)$origem_decisao);
    $tipo_beneficio_js  = str_replace('"', '**', (string)$tipo_beneficio);
    $alta_prog_js       = str_replace('"', '**', (string)$alta_programada);
    $status_js          = str_replace('"', '**', (string)$status_row);
    // Botões/ações (ajuste classes de permissão se tiver: $editar_beneficios, $excluir_beneficios)
    $editar_beneficios  = @$editar_beneficios ?: '';
    $excluir_beneficios = @$excluir_beneficios ?: '';
  	 ?>
      <tr>
<td style="width:10%"><?php echo $nb ?> </td>
<td style="width:20%"><?php echo $nome_cliente ?></td>
<td style="width:20%"><?php echo $tipo_beneficio ?></td>
<td style="width:10%" style="color:<?php echo $classe_status ?>"><?php echo $status_row ?></td>
<td style="width:10%; "><?php echo $derF ?></td>
<td style="width:10%; "><?php echo $dibF ?></td>
<td style="width:10%; "><?php echo $dcb_previstaF ?></td>
<td style="width:10%; "><?php echo $dcbF ?></td>
    </tr>
<?php } } ?>
				</tbody>
			</thead>
		</table>
</div>
<hr>
</body>
</html>
 