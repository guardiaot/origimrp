<?php 
require_once("data_formatada.php");

if($token_rel != 'A5030'){
echo '<script>window.location="../../"</script>';
exit();
}
$dataInicialF = implode('/', array_reverse(@explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(@explode('-', $dataFinal)));
$texto_status = "";
if($status == 'Pendente'){
	$texto_status = ' PENDENTES';
}

if($status == 'Em Análise'){
	$texto_status = ' EM ANÁLISES';
}	

if($status == 'Concluído'){
	$texto_status = ' CONCLUÍDOS';
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
				<td style="width: 30%; text-align: left; font-size: 13px;">
				</td>
				<td style="width: 1%; text-align: center; font-size: 13px;">
				</td>
				<td style="width: 47%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>RELATÓRIO DE CASOS <?php echo $texto_status ?></big></b>
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
					<td style="width:25%">CLIENTE</td>
					<td style="width:10%">TELEFONE</td>
					<td style="width:20%">TIPO AÇÃO</td>
					<td style="width:10%">DATA</td>
					<td style="width:10%">STATUS</td>
					<td style="width:25%">ADVOGADO</td>
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



if($mostrar_registros == 'Não'){	
		$query = $pdo->query("SELECT * from casos where status like '%$status%' and data >= '$dataInicial' and data <= '$dataFinal'and (id_advogado = '$id_usuario') order by id desc");
}else{
	$query = $pdo->query("SELECT * from casos where status like '%$status%' and data >= '$dataInicial' and data <= '$dataFinal' order by id desc");
}
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
$tipo_acao = $res[$i]['tipo_acao'];
$descricao = $res[$i]['descricao'];
$partes = $res[$i]['partes'];
$processo_existente = $res[$i]['processo_existente'];
$objetivo = $res[$i]['objetivo'];
$id_cliente = $res[$i]['id_cliente'];
$advogado = $res[$i]['id_advogado'];
$status = $res[$i]['status'];
$data = $res[$i]['data'];	
	$dataF = implode('/', array_reverse(@explode('-', $data)));	
	$query2 = $pdo->query("SELECT * FROM clientes where id = '$id_cliente'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_cliente = $res2[0]['nome'];
		$telefone_cliente = $res2[0]['telefone'];
	}else{
		$nome_cliente = 'Sem Registro';
		$telefone_cliente = 'Sem Registro';
	}
	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$advogado'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_advogado = $res2[0]['nome'];		
	}else{
		$nome_advogado = 'Não Selecionado';
	}
	$query2 = $pdo->query("SELECT * FROM tipos_servicos where id = '$tipo_acao'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_servico = $res2[0]['nome'];
	}else{
		$nome_servico = '';
	}
	if($status == 'Pendente'){
		$ocultar_botoes = '';
		$classe_status = '#9c2834';			
	}else if ($status == 'Em Análise'){
		$ocultar_botoes = '';
		$classe_status = '#1e237d';	
	}else if ($status == 'Concluído'){
		$ocultar_botoes = '';
		$classe_status = '#22854d';		
	}
  	 ?>
      <tr>
<td style="width:25%"><?php echo $nome_cliente ?> </td>
					<td style="width:10%"><?php echo $telefone_cliente ?></td>
					<td style="width:20%"><?php echo $nome_servico ?></td>
					<td style="width:10%"><?php echo $dataF ?></td>
					<td style="width:10%; color:<?php echo $classe_status ?>"><?php echo $status ?></td>
					<td style="width:25%; "><?php echo $nome_advogado ?></td>
    </tr>
<?php } } ?>
				</tbody>
			</thead>
		</table>
</div>
<hr>
</body>
</html>
 