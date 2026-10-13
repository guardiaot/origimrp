<?php 
@session_start();
require_once("../../conexao.php");
$dataInicial = $_POST['data_inicio_mes'];
$dataFinal = $_POST['data_final_mes'];
$funcionario = $_POST['usuario_filtro'];
$token_rel = "M543661";
ob_start();
include("ponto.php");
$html = ob_get_clean();
//CARREGAR DOMPDF
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

header("Content-Transfer-Encoding: binary");
header("Content-Type: image/png");
//INICIALIZAR A CLASSE DO DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$pdf = new DOMPDF($options);
//Definir o tamanho do papel e orientação da página
$pdf->set_paper('A4', 'portrait');
//CARREGAR O CONTEÚDO HTML
$pdf->load_html($html);
//RENDERIZAR O PDF
$pdf->render();
//NOMEAR O PDF GERADO
$pdf->stream(
'ponto.pdf',
array("Attachment" => false)
);
?>