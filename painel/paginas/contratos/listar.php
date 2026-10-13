<?php
@session_start();


$tabela = 'contratos';
require_once("../../../conexao.php");



$query = $pdo->query("SELECT * from $tabela order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas > 0) {
	echo <<<HTML
<small>
	<table class="table table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead> 
	<tr> 
	<th>Texto</th>		
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;
} else {
	echo 'Não possui nenhum cadastro!';
}

for ($i = 0; $i < $linhas; $i++) {
	$id = $res[$i]['id'];
	$texto = $res[$i]['texto'];
	$titulo = $res[$i]['modelo'];


	if ($id == '1') {
		$ocultar_editar = 'ocultar';
	} else {
		$ocultar_editar = '';
	}
	$texto = str_replace('"', "**", $texto);

	//Limitar o tamanho do texto para exibição na tabela
	if (strlen($titulo) > 100) {
		$texto_limitado = mb_substr(strip_tags($titulo), 0, 100) . '...';
	} else {
		$texto_limitado = strip_tags($titulo);
	}


	echo <<<HTML
<input type="text" id="texto_contrato_{$id}" value="{$texto}" style="display:none">


<td>{$texto_limitado}</td>
<td>
<a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}', '{$titulo}')" title="Editar Dados"><i class="fa fa-edit "></i></a>

<a class="btn btn-warning btn-sm" href="#" onclick="mostrar('{$id}')" title="Mostrar Dados"><i class="fa fa-info-circle"></i></a>

<a href="#" class="btn btn-danger btn-sm" onclick="excluirAlert('{$id}')" title="Excluir"><i class="fa fa-trash "></i></a>

</td>
</tr>
HTML;

}


echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
</small>
HTML;
?>


<script src="../dist/trumbowyg.min.js"></script>

<script type="text/javascript" src="../dist/langs/pt_br.min.js"></script>

<script src="../dist/plugins/emoji/trumbowyg.emoji.min.js"></script>

<script src="../dist/plugins/colors/trumbowyg.colors.min.js"></script>
<script src="../dist/plugins/fontfamily/trumbowyg.fontfamily.min.js"></script>
<script src="../dist/plugins/fontsize/trumbowyg.fontsize.min.js"></script>




<script type="text/javascript">
	$(document).ready(function () {

		$('#trumbowyg-editor').trumbowyg({
			lang: 'pt_br',
			btns: [
				['viewHTML'],
				['undo', 'redo'], // Only supported in Blink browsers
				['formatting'],
				['strong', 'em', 'del'],
				['link'],
				['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
				['unorderedList', 'orderedList'],
				['horizontalRule'],
				['removeformat'],
				['fullscreen'],
				['emoji'],
				['foreColor', 'backColor'],
				['fontfamily'],
				['fontsize'],
				['removeformat']
			],
			autogrow: true
		});

		if (!$.fn.DataTable.isDataTable('#tabela')) {
			$('#tabela').DataTable({
				"language": {
					//"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
				},
				"ordering": false,
				"stateSave": true
			});
		}
	});
</script>

<script type="text/javascript">
	function editar(id, titulo) {

		$('#texto-botao').text('Editar Contrato');
		$('#nav-profile-tab i').removeClass('fa-plus').addClass('fa-edit');

		// Obtém o texto do campo de anotação
		var texto = $('#texto_contrato_' + id).val();
		// Substitui todas as ocorrências de '**' por '"'
		texto = texto.replace(/\*\*/g, '"');
		$('#mensagem').text('');
		$('#id').val(id);
		$('#titulo').val(titulo);
		$('#trumbowyg-editor').trumbowyg('html', texto); // Atualiza o conteúdo do editor

		$('#nav-tab button[data-bs-target="#nav-profile"]').tab('show');

		//$('#nav-profile-tab').click();	
	}


	function mostrar(id) {

		var texto = $('#texto_contrato_' + id).val();
		for (let letra of texto) {
			if (letra === '*') {
				texto = texto.replace('**', '"');
			}
		}
		$('#id_dados').text(id);
		$('#mensagem_dados').html(texto);
		$('#modalDados').modal('show');
	}

	function limparCampos() {
		$('#id').val('');
		$('#trumbowyg-editor').trumbowyg('html', ''); // Limpa o conteúdo

		$('#ids').val('');
		$('#btn-deletar').hide();
	}

</script>