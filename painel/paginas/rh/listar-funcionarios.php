<?php 
require_once("../../../conexao.php");

$id = $_POST['id'];
$data = $_POST['data'];


$query = $pdo->query("SELECT * FROM usuarios where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_func = $res[0]['nome'];
$hora_entrada = $res[0]['hora_entrada'];
$hora_saida = $res[0]['hora_saida'];

$hora_entrada = date("H:i", strtotime($hora_entrada));
$hora_saida = date("H:i", strtotime($hora_saida));


$query = $pdo->query("SELECT * FROM jornada where funcionario = '$id' and data = '$data'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res)> 0){
$hora_entrada = $res[0]['entrada'];
$hora_saida = $res[0]['saida'];
$entrada_almoco = $res[0]['entrada_almoco'];
$saida_almoco = $res[0]['saida_almoco'];
$folga = $res[0]['folga'];
$feriado = $res[0]['feriado'];
$falta = $res[0]['falta'];
$id_reg = $res[0]['id'];

$hora_entrada = date("H:i", strtotime($hora_entrada));
$hora_saida = date("H:i", strtotime($hora_saida));
$entrada_almoco = date("H:i", strtotime($entrada_almoco));
$saida_almoco = date("H:i", strtotime($saida_almoco));
}

echo $nome_func;
echo '-/';
echo $hora_entrada;
echo '-/';
echo $hora_saida;
echo '-/';
echo @$entrada_almoco;
echo '-/';
echo @$saida_almoco;
echo '-/';
echo @$folga;
echo '-/';
echo @$feriado;
echo '-/';
echo @$falta;
echo '-/';
echo @$id_reg;
echo '-/';
?>

