<?php 
@session_start();
require_once("../../../conexao.php");
$tabela = 'jornada';


$usuario_logado = @$_SESSION['id_usuario'];

$hora_entrada = $_POST['hora_entrada'];
$hora_saida = $_POST['hora_saida'];
$entrada_almoco = $_POST['entrada_almoco'];
$saida_almoco = $_POST['saida_almoco'];
$data_agenda = $_POST['data_agenda'];
$funcionario = $_POST['usuario'];
$folga = @$_POST['folga'];
$feriado = @$_POST['feriado'];
$falta = @$_POST['falta'];
$id = $_POST['id'];

$query = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$jornada_horas = $res[0]['jornada_horas'];
$nome_func = $res[0]['nome'];

$intervalo = gmdate('H:i', strtotime( $saida_almoco ) - strtotime( $entrada_almoco ) );
$horas_trabalhadas = gmdate('H:i', strtotime( $hora_saida ) - strtotime( $hora_entrada ) );
$horas_trabalhadas = gmdate('H:i', strtotime( $horas_trabalhadas ) - strtotime( $intervalo ) );

$hora_extra = "";
if(strtotime($horas_trabalhadas) > strtotime($jornada_horas) ){
	$hora_extra = gmdate('H:i', strtotime( $horas_trabalhadas ) - strtotime( $jornada_horas ) );	
}


if($id == ""){
	$query = $pdo->prepare("INSERT INTO $tabela SET funcionario = '$funcionario', data = '$data_agenda', entrada = '$hora_entrada', entrada_almoco = '$entrada_almoco', saida_almoco = '$saida_almoco', saida = '$hora_saida', total_horas = '$horas_trabalhadas', intervalo = '$intervalo', hora_extra = '$hora_extra', folga = '$folga', feriado = '$feriado', falta = '$falta', data_lanc = curDate(), usuario_lanc = '$usuario_logado'");
	$acao = 'inserção';
	

}else{
	$query = $pdo->prepare("UPDATE $tabela SET funcionario = '$funcionario', data = '$data_agenda', entrada = '$hora_entrada', entrada_almoco = '$entrada_almoco', saida_almoco = '$saida_almoco', saida = '$hora_saida', total_horas = '$horas_trabalhadas', intervalo = '$intervalo', hora_extra = '$hora_extra', folga = '$folga', feriado = '$feriado', falta = '$falta', data_lanc = curDate(), usuario_lanc = '$usuario_logado' where id = '$id'");
	$acao = 'edição';

	
}

	$query->execute();
	$ult_id = $pdo->lastInsertId();


if(@$ult_id == "" || @$ult_id == 0){
	$ult_id = $id;
}



echo 'Salvo com Sucesso'; 

?>