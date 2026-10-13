<?php 
@session_start();
$mostrar_registros = @$_SESSION['registros'];
$id_usuario = @$_SESSION['id'];
require_once("../verificar.php");
require_once("../../conexao.php");
$dataInicial = $_POST['dataInicial'];
$dataFinal = $_POST['dataFinal'];
$usuario_selecionado = $_POST['usuario_selecionado'];
$token_rel = "A5030";
ob_start();
include("audiencias.php");
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
	'audiencias.pdf',
	array("Attachment" => false)
);
 ?>