<?php 
@session_start();
require_once("../../../conexao.php");


$dataMes = Date('m');
$dataDia = Date('d');
$dataAno = Date('Y');
$data_atual = date('Y-m-d');

$data_semana = date('Y/m/d', strtotime("-7 days",strtotime($data_atual)));

@session_start();
$id_usuario = $_SESSION['id'];

$clientes = $_POST['cli'];

// Buscar os Contatos que serão enviados
if($clientes == "Teste"){
	$query = $pdo->query("SELECT * FROM config where telefone != '' ");	

}else if ($clientes == "Clientes Mês"){
	$query = $pdo->query("SELECT * FROM clientes where month(data_cad) = '$dataMes' and year(data_cad) = '$dataAno' and telefone != '' and (marketing = '' or marketing is null) ");

}else if ($clientes == "Clientes Semana"){
	$query = $pdo->query("SELECT * FROM clientes where data_cad >= '$data_semana' and telefone != '' and (marketing = '' or marketing is null) ");

}else if ($clientes == "Todos"){
	$query = $pdo->query("SELECT * FROM clientes where telefone != '' and (marketing = '' or marketing is null) ");

}else if ($clientes == "Aniversáriantes Mês"){
	$query = $pdo->query("SELECT * FROM clientes where month(data_nasc) = '$dataMes'  and telefone != '' and (marketing = '' or marketing is null) ");	

}else if ($clientes == "Aniversáriantes Dia"){
	$query = $pdo->query("SELECT * FROM clientes where month(data_nasc) = '$dataMes' and day(data_nasc) = '$dataDia'  and telefone != '' and (marketing = '' or marketing is null) ");	

}else if ($clientes == "Inadimplêntes"){
	$query = $pdo->query("SELECT DISTINCT c.nome, c.telefone, c.id FROM clientes c JOIN receber r ON c.id = r.cliente WHERE r.vencimento < CURDATE() AND r.pago = 'Não' ");

}else{
	
}

$res = @$query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

echo $total_reg;

 ?>