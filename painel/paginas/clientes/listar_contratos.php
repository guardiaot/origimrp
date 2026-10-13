<?php
@session_start();
$tabela = 'temp_texto';
require_once("../../../conexao.php");

$data_atual = date('Y-m-d');
$data_hoje = date('Y-m-d');

$id = @$_POST['id'];

$total_pago = 0;
$total_pendentes = 0;
$total_vencidas = 0;
$total_pendentesF = 0;
$total_vencidasF = 0;
$query = $pdo->query("SELECT * from $tabela where cliente = '$id' order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas > 0) {
	echo <<<HTML
<small><small>
	<table class="table table-striped table-hover table-bordered text-nowrap border-bottom dt-responsive" id="tabela4">
	<thead> 
	<tr> 
	<th class="">Modelo</th>
	<th class="">Data</th>	
	<th class="esc">Hora</th>	
	<th class="esc">IP</th>		
	<th>Contrato</th>				
	</tr> 
	</thead> 
	<tbody>	
HTML;

	for ($i = 0; $i < $linhas; $i++) {
		$id = $res[$i]['id'];
		
		$ip = $res[$i]['ip'];
		$data = $res[$i]['data'];
		$texto = $res[$i]['texto'];
		$cabecalho = $res[$i]['cabecalho'];
		$hora = $res[$i]['hora'];
		$modelo = $res[$i]['modelo'];
		$cliente = $res[$i]['cliente'];

        $query2 = $pdo->query("SELECT * FROM clientes WHERE id = '$cliente'");
        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        $telefone = @$res2[0]['telefone'];
		

		$dataF = implode('/', array_reverse(@explode('-', $data)));
		
		

		$query2 = $pdo->query("SELECT * FROM contratos where id = '$modelo'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($res2) > 0) {
			$nome_modelo = $res2[0]['modelo'];
		} else {
			$nome_modelo = '';
		}


		$textoF = rawurlencode($texto);

        $chave_secreta = "sua_chave_secreta";
        $hash_sec = md5($id . $chave_secreta);
        $token_sec = base64_encode($id . "|" . $hash_sec); 

        $link_assinatura = $url_sistema.'contrato/'.$token_sec;

		echo <<<HTML
<tr class="">
<td class="esc" > {$nome_modelo}</td>
<td class=""> {$dataF}</td>
<td class="esc" >{$hora}</td>
<td class="esc" >{$ip}</td>
<td class="esc" ><a class="btn btn-info btn-sm" href="#" onclick="gerarContrato('{$textoF}', '{$cliente}', '{$cabecalho}', '{$modelo}',)"><i class="fa fa-file-pdf-o "></i></a>


<a class="btn btn-danger btn-sm" href="#" onclick="excluirContrato('{$id}')" title="Excluir Contrato"><i class="fa fa-trash"></i></a>


<a class="btn btn-success btn-sm" href="#" onclick="solicitarAssinatura('{$link_assinatura}', '{$nome_modelo}', '{$telefone}')" title="Solicitar Assinatura"><i class="bi bi-whatsapp "></i></a>

</td>
</tr>
HTML;
	}
} else {
	echo '<small>Não possui nenhuma conta!</small>';
}


echo <<<HTML
</tbody>
</table>
</small></small>

HTML;
?>


<script type="text/javascript">
	$(document).ready(function() {
		$('#tabela4').DataTable({
			"language": {
				//"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
			},
			"ordering": false,
			"stateSave": true
		});
	});


function gerarContrato(texto, cliente, cabecalho, modelo){
	$('#cabecalho_form').val(cabecalho);
	$('#cliente_form').val(cliente);
	$('#modelo_form').val(modelo);
	
	$('#texto_contrato').val(decodeURIComponent(texto));

	$('#botao_form').click();
}



	
// ALERT EXCLUIR #######################################
function excluirContrato(id) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success", // Adiciona margem à direita do botão "Sim, Excluir!"
            cancelButton: "btn btn-danger me-1",
            container: 'swal-whatsapp-container'
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: "Deseja Excluir?",
        text: "Você não conseguirá recuperá-lo novamente!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, Excluir!",
        cancelButtonText: "Não, Cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Realiza a requisição AJAX para excluir o item
            $.ajax({
                url: 'paginas/' + pag + "/excluir_contrato.php",
                method: 'POST',
                data: { id },
                dataType: "html",
                success: function (mensagem) {
                    if (mensagem.trim() == "Excluído com Sucesso") {
                        // Exibe mensagem de sucesso após a exclusão
                        swalWithBootstrapButtons.fire({
                            title: mensagem,
                            text: 'Fecharei em 1 segundo.',
                            icon: "success",
                            timer: 1000,
                            timerProgressBar: true,
                            confirmButtonText: 'OK',
                            customClass: {
                             container: 'swal-whatsapp-container'
                             }
                        });
                        listarContratos(id);
                        limparCampos()
                    } else {
                        // Exibe mensagem de erro se a requisição falhar
                        swalWithBootstrapButtons.fire({
                            title: "Opss!",
                            text: mensagem,
                            icon: "error",
                            confirmButtonText: 'OK',
                            customClass: {
                             container: 'swal-whatsapp-container'
                             }
                        });
                    }
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Fecharei em 1 segundo.",
                icon: "error",
                timer: 1000,
                timerProgressBar: true,
            });
        }
    });
}


function solicitarAssinatura(link, modelo, telefone){
            $.ajax({
                url: 'rel/disparo_assinatura.php',
                method: 'POST',
                data: {
                    link, modelo, telefone
                },
                dataType: "html",

                success: function(result) {
                    alertsucesso(result)
                }
            });
        }

</script>