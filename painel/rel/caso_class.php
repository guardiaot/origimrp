<?php 
require_once("../verificar.php");
require_once("../../conexao.php");
$id = $_POST['id'];
$texto = @$_POST['texto'];
$query = $pdo->query("SELECT * from casos where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$cliente = $res[0]['id_cliente'];
$query = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
	$nome_cliente = $res[0]['nome'];
}else{
	$nome_cliente = "";
}
$token_rel = "A5030";
ob_start();
include("caso.php");
$html = ob_get_clean();
//CARREGAR DOMPDF

require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;

use Dompdf\Options;



header("Content-Transfer-Encoding: binary");

header("Content-Type: image/png");
//INICIALIZAR A CLASSE DO DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$pdf = new DOMPDF($options);
//Definir o tamanho do papel e orientação da página
$pdf->set_paper('A4', 'portrait');
//CARREGAR O CONTEÚDO HTML
$pdf->load_html($html);
//RENDERIZAR O PDF
$pdf->render();
//NOMEAR O PDF GERADO
$pdf->stream(
	'orientacao_juridica_'.$nome_cliente.'pdf',
	array("Attachment" => false)
);
 ?>