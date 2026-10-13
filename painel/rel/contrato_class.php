<?php 
@session_start();
//require_once("../verificar.php");
require_once("../../conexao.php");
$mostrar_registros = @$_SESSION['registros'];
$id_usuario = @$_SESSION['id'];
$chave_secreta = "sua_chave_secreta";
$id_contrato = "";

if(@$_GET['id_contrato'] != "" and @$_GET['id_contrato'] != "contrato_class.php"){
 $token_recebido = base64_decode($_GET['id_contrato']); // Decodifica o token
    list($id_contrato, $hash) = explode("|", $token_recebido); // Separa ID e Hash
    // Verifica se o hash é válido
    if ($hash === md5($id_contrato . $chave_secreta)) {
        $id_contrato = htmlspecialchars($id_contrato);
        // Aqui você pode carregar os dados do aluno a partir do banco de dados
    }
}
$get_contrato = $id_contrato;

if($get_contrato == "contrato_class.php"){
	$get_contrato = "";
}
$id_post_contrato = @$_POST['id_post_contrato'];

if($id_contrato == "" || $id_contrato == "contrato_class.php"){
	$id_contrato = $id_post_contrato;
}
$cliente_gerando_post = @$_POST['cliente_gerando'];

if($get_contrato != ""){
	$cliente_gerando = 'Sim';
}else{
	$cliente_gerando = 'Não';
}

if($cliente_gerando_post != ""){
	$cliente_gerando = $cliente_gerando_post;
}
$assinatura = @$_POST['assinatura'];
$gerar = @$_POST['gerar'];


if($get_contrato == ""){
	$texto = $_POST['texto'];
	$cabecalho_form = $_POST['cabecalho_form'];	
	$cliente = @$_POST['cliente'];
	$modelo = @$_POST['modelo'];
}else{
	$query = $pdo->query("SELECT * FROM temp_texto WHERE id = '$id_contrato'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$texto = @$res[0]['texto'];
	$cliente = @$res[0]['cliente'];
	$cabecalho_form = @$res[0]['cabecalho'];
	$modelo = @$res[0]['modelo'];
}


if($texto == ""){
	echo 'Selecione um texto para o contrato!';
	exit();
}
//buscar telefone cliente
$query = $pdo->query("SELECT * FROM clientes WHERE id = '$cliente'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$telefone = @$res[0]['telefone'];
$query = $pdo->query("SELECT * FROM contratos WHERE id = '$modelo'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_modelo = @$res[0]['modelo'];
$nome_img = '';


if($assinatura != "" and $assinatura != "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAV4AAABGCAYAAACE2fxYAAACSklEQVR4Xu3UwQkAAAgDMbv/0m5xr7hAIcjtHAECBAikAkvXjBEgQIDACa8nIECAQCwgvDG4OQIECAivHyBAgEAsILwxuDkCBAgIrx8gQIBALCC8Mbg5AgQICK8fIECAQCwgvDG4OQIECAivHyBAgEAsILwxuDkCBAgIrx8gQIBALCC8Mbg5AgQICK8fIECAQCwgvDG4OQIECAivHyBAgEAsILwxuDkCBAgIrx8gQIBALCC8Mbg5AgQICK8fIECAQCwgvDG4OQIECAivHyBAgEAsILwxuDkCBAgIrx8gQIBALCC8Mbg5AgQICK8fIECAQCwgvDG4OQIECAivHyBAgEAsILwxuDkCBAgIrx8gQIBALCC8Mbg5AgQICK8fIECAQCwgvDG4OQIECAivHyBAgEAsILwxuDkCBAgIrx8gQIBALCC8Mbg5AgQICK8fIECAQCwgvDG4OQIECAivHyBAgEAsILwxuDkCBAgIrx8gQIBALCC8Mbg5AgQICK8fIECAQCwgvDG4OQIECAivHyBAgEAsILwxuDkCBAgIrx8gQIBALCC8Mbg5AgQICK8fIECAQCwgvDG4OQIECAivHyBAgEAsILwxuDkCBAgIrx8gQIBALCC8Mbg5AgQICK8fIECAQCwgvDG4OQIECAivHyBAgEAsILwxuDkCBAgIrx8gQIBALCC8Mbg5AgQICK8fIECAQCwgvDG4OQIECAivHyBAgEAsILwxuDkCBAgIrx8gQIBALCC8Mbg5AgQICK8fIECAQCwgvDG4OQIECDyhzQBHXB7ZNwAAAABJRU5ErkJggg=="){
	//salvar a imagem
	// Decodificar a imagem base64 e salvar
	list($type, $assinaturaData) = explode(';', $assinatura);
	list(, $assinaturaData) = explode(',', $assinaturaData);
	$assinaturaData = base64_decode($assinaturaData);
	$nome_img = $cliente.'.png';
	$caminhoImagem = '../images/assinaturas/'.$cliente.'.png';
	file_put_contents($caminhoImagem, $assinaturaData);
	$pdo->query("UPDATE clientes set assinatura = '$nome_img' WHERE id = '$cliente'");
}

if($get_contrato == ""){
	//buscar um contrato que já tenha o mesmo texto
	$query = $pdo->query("SELECT * FROM temp_texto WHERE texto = '$texto'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res) > 0){
		$id = $res[0]['id'];
	}else{
		//inserir o texto na tabela temporaria
		$query = $pdo->prepare("INSERT INTO temp_texto SET texto = :texto, cabecalho = '$cabecalho_form', cliente = '$cliente', modelo = '$modelo', assinatura = '$nome_img'");
		$query->bindValue(":texto", "$texto");
		$query->execute();
		$id = $pdo->lastInsertId();
	}
}else{
	$id = $id_contrato;
}
$chave_secreta = "sua_chave_secreta";
$hash_sec = md5($id . $chave_secreta);
$token_sec = base64_encode($id . "|" . $hash_sec); 
$link_assinatura = $url_sistema.'contrato/'.$token_sec;
$token_rel = "M543661";
ob_start();
include("contrato.php");
$html = ob_get_clean();
	//CARREGAR DOMPDF
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;


if(($get_contrato == '' and $gerar == 'Sim') or $assinatura_cliente == 'Não'){

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
	'contrato.pdf',
	array("Attachment" => false)
);

exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $nome_sistema ?></title>
    <link rel="icon" href="<?php echo $url_sistema ?>img/icone.png" type="image/x-icon" />
    <style>
    /* Estilo para os botões */
    /* Estilizando os botões */
    .btn-container {
        display: flex;
        gap: 10px; /* Espaçamento entre os botões */
        margin-top: 10px;
    }
    .btn {
        padding: 8px 15px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-limpar {
        background-color: #ff4d4d;
        color: white;
    }
    .btn-limpar:hover {
        background-color: #cc0000;
    }
    .btn-gerar {
        background-color: #007bff;
        color: white;
    }
    .btn-gerar:hover {
        background-color: #0056b3;
    }
    .btn-assinatura {
        background-color: #437052;
        color: white;
    }
    .btn-assinatura:hover {
        background-color: #8cbd9c;
    }
</style>
</head>
<body>
	<div align="center">
	<?php if($get_contrato != ""){ ?>
    <h3>Assine Aqui </h3>
    <small><span style="font-weight: normal">(Caso já tenha assinatura carregada, não precisa assinar novamente)</span></small><br>
    <canvas id="signature-pad" width="350" height="70" style="border:1px solid #000;"></canvas><br><br>
	<?php } ?>
    <form method="POST" action="contrato_class.php" onsubmit="return prepararAssinatura()">
        <input type="hidden" name="assinatura" id="assinatura">       
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="gerar" id="gerar" value="Sim">       
        <input type="hidden" name="cabecalho_form" value="<?= $cabecalho_form ?>">
        <input type="hidden" name="texto" value="<?= htmlspecialchars($texto) ?>">
        <input type="hidden" name="id_post_contrato" value="<?= $id_contrato ?>">
        <input type="hidden" name="cliente" value="<?= $cliente ?>">
        <input type="hidden" name="cliente_gerando" value="<?= $cliente_gerando ?>">
        <?php if($get_contrato != ""){ ?>
        <button type="button" class="btn btn-limpar" onclick="clearCanvas()">🗑 Limpar Assinatura</button>
    	<?php } ?>
        <button type="submit" class="btn btn-gerar">📄 Gerar PDF</button>
        <?php if($get_contrato == ""){ ?>
        	<button onclick="solicitarAssinatura('<?php echo $link_assinatura ?>', '<?php echo $nome_modelo ?>', '<?php echo $telefone ?>')" type="button" class="btn btn-assinatura">💬 Solicitar Assinatura</button>
        <?php } ?>
        <?php if($get_contrato == ""){ ?>
        <br><br>
        <div><?php echo $link_assinatura ?></div>
    	<?php } ?>
    </form>
    </div>
    <br><br>
     <div id="contrato">
        <?= $html ?>
    </div>
    <script src="../../assets/plugins/jquery/jquery.min.js"></script>
    <script>
        let canvas = document.getElementById('signature-pad');
        let ctx = canvas.getContext('2d');
        let isDrawing = false;
        canvas.addEventListener('mousedown', () => { isDrawing = true; ctx.beginPath(); });
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', () => isDrawing = false);
        canvas.addEventListener('mouseout', () => isDrawing = false);
        canvas.addEventListener('touchstart', e => { 
            isDrawing = true; 
            ctx.beginPath(); 
            e.preventDefault(); 
        });
        canvas.addEventListener('touchmove', e => { 
            let touch = e.touches[0];
            let rect = canvas.getBoundingClientRect();
            ctx.lineTo(touch.clientX - rect.left, touch.clientY - rect.top);
            ctx.stroke();
            e.preventDefault();
        });
        canvas.addEventListener('touchend', () => isDrawing = false);
        function draw(e) {
            if (!isDrawing) return;
            ctx.lineWidth = 1;
            ctx.lineCap = 'round';
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
        }
        function clearCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
        function prepararAssinatura() {
            let assinaturaInput = document.getElementById('assinatura');
            assinaturaInput.value = canvas.toDataURL();
            return true;
        }
        function solicitarAssinatura(link, modelo, telefone){
        	$.ajax({
				url: 'disparo_assinatura.php',
				method: 'POST',
				data: {
					link, modelo, telefone
				},
				dataType: "html",
				success: function(result) {
					alert(result)
				}
			});
        }
    </script>
</body>
</html>
