<?php
@session_start();
require_once("../../../conexao.php");


$id = $_POST['idFunc'];

if($id == ""){
	$query = $pdo->query("SELECT * FROM usuarios where cargo != 'Cliente' and id = '$id' order by nome asc limit 1");									
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$id = $res[0]['id'];

}

$data_inicio_mes = date('Y').'-'.date('m').'-01';
$data_final_mes = date('Y-m-d');




$partes_final_mes = explode("-", $data_final_mes);
$dia_final_mes = $partes_final_mes[2];
$mes_final = $partes_final_mes[1];
$ano_final = $partes_final_mes[0];



for($i_dia=1; $i_dia < $dia_final_mes; $i_dia++){
	$data_busca = $ano_final.'-'.($mes_final - 1).'-'.$i_dia;

	$query = $pdo->query("SELECT * FROM jornada where funcionario = '$id' and data = '$data_busca' ORDER BY data asc");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);	

		if($total_reg > 0){
			$data = $res[0]['data'];
			$partes = explode("-", $data);
			$dia = $partes[2];
			echo $dia.'-';
		}else{
			echo 'N-';
		}
		

		

}





?>