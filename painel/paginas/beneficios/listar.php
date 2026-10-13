<?php 
@session_start();
$mostrar_registros = @$_SESSION['registros'];
$id_usuario        = @$_SESSION['id'];
$tabela            = 'beneficios';

require_once("../../../conexao.php");
require_once("../../verificar.php");

if(@$_SESSION['nivel'] != 'Administrador' and @$_SESSION['nivel'] != 'Escritório'){
    require_once("../../verificar_permissoes.php");
}else{
    $mostrar_registros = 'Sim';
}

// Parâmetros do filtro (p1=status, p2=dataInicial, p3=dataFinal)
$status      = trim(@$_POST['p1'] ?? '');
$dataInicial = trim(@$_POST['p2'] ?? '');
$dataFinal   = trim(@$_POST['p3'] ?? '');

// Datas padrão = mês atual
$data_atual      = date('Y-m-d');
$mes_atual       = date('m');
$ano_atual       = date('Y');
$data_inicio_mes = $ano_atual."-".$mes_atual."-01";

// último dia do mês
if(in_array($mes_atual, ['04','06','09','11'])){
    $data_final_mes = $ano_atual.'-'.$mes_atual.'-30';
}elseif($mes_atual == '02'){
    $bissexto = date('L', mktime(0,0,0,1,1,$ano_atual));
    $data_final_mes = $ano_atual.'-'.$mes_atual.'-'.($bissexto ? '29' : '28');
}else{
    $data_final_mes = $ano_atual.'-'.$mes_atual.'-31';
}

if($dataInicial == "") $dataInicial = $data_inicio_mes;
if($dataFinal   == "") $dataFinal   = $data_final_mes;

// Status: vazio/Todos => wildcard
$status_like = ($status == '' || strtolower($status) == 'todos') ? '%' : $status;

// Escopo por usuário
$sql_usuario = ($mostrar_registros == 'Não') ? " AND usuario_cadastro = :usuario " : "";

// Consulta (filtra por DIB no intervalo)
$sql = "SELECT * 
        FROM $tabela 
        WHERE status LIKE :status
          AND dib IS NOT NULL
          AND dib >= :dataInicial 
          AND dib <= :dataFinal
          $sql_usuario
        ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':status', $status_like);
$stmt->bindValue(':dataInicial', $dataInicial);
$stmt->bindValue(':dataFinal', $dataFinal);
if($mostrar_registros == 'Não'){
    $stmt->bindValue(':usuario', $id_usuario, PDO::PARAM_INT);
}
$stmt->execute();

$res    = $stmt->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);

if($linhas > 0){
echo <<<HTML
<table class="table text-nowrap border-bottom dt-responsive" id="tabela">
  <thead>
    <tr class="bg-primary">
      <th>NB</th>
      <th>Cliente</th>
      <th class="esc">Tipo Benefício</th>
      <th class="esc">Status</th>
      <th class="esc">DER</th>
      <th class="esc">DIB</th>
      <th class="esc">DCB Prev.</th>
      <th class="esc">DCB</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
HTML;

for($i=0; $i<$linhas; $i++){
    $id               = $res[$i]['id'];
    $cliente          = $res[$i]['cliente'];
    $nb               = $res[$i]['nb'];
    $tipo_beneficio   = $res[$i]['tipo_beneficio'];
    $der              = $res[$i]['der'];
    $dib              = $res[$i]['dib'];
    $dii              = $res[$i]['dii'];
    $alta_programada  = $res[$i]['alta_programada'];
    $dcb_prevista     = $res[$i]['dcb_prevista'];
    $dcb              = $res[$i]['dcb'];
    $status_row       = $res[$i]['status'];
    $motivo_cessacao  = $res[$i]['motivo_cessacao'];
    $origem_decisao   = $res[$i]['origem_decisao'];
    $doc_cessacao     = $res[$i]['doc_cessacao'];
    $obs              = $res[$i]['obs'];
    $usuario_cadastro = $res[$i]['usuario_cadastro'];

    // Datas formatadas
    $derF          = $der          ? implode('/', array_reverse(explode('-', $der))) : '';
    $dibF          = $dib          ? implode('/', array_reverse(explode('-', $dib))) : '';
    $dcb_previstaF = $dcb_prevista ? implode('/', array_reverse(explode('-', $dcb_prevista))) : '';
    $dcbF          = $dcb          ? implode('/', array_reverse(explode('-', $dcb))) : '';
    $diiF          = $dii          ? implode('/', array_reverse(explode('-', $dii))) : '';

    // Cliente
    $qCli = $pdo->query("SELECT nome FROM clientes WHERE id = '$cliente'");
    $rCli = $qCli->fetch(PDO::FETCH_ASSOC);
    $nome_cliente = $rCli['nome'] ?? 'Sem Registro';

    // Cores por status
    switch (strtolower($status_row)) {
        case 'cancelado':  $classe_status = '#91180d'; break;
        case 'suspenso':   $classe_status = '#bf4b1d'; break;
        case 'convertido': $classe_status = '#2b7a00'; break;
        case 'cessado':    $classe_status = '#8f8f8f'; break;
        case 'ativo':
        default:           $classe_status = '#1c6cad'; break;
    }

    // Sanitizações para inline JS
    $obs_js             = str_replace('"', '**', (string)$obs);
    $doc_cessacao_js    = str_replace('"', '**', (string)$doc_cessacao);
    $motivo_js          = str_replace('"', '**', (string)$motivo_cessacao);
    $origem_js          = str_replace('"', '**', (string)$origem_decisao);
    $tipo_beneficio_js  = str_replace('"', '**', (string)$tipo_beneficio);
    $alta_prog_js       = str_replace('"', '**', (string)$alta_programada);
    $status_js          = str_replace('"', '**', (string)$status_row);

    // Botões/ações (ajuste classes de permissão se tiver: $editar_beneficios, $excluir_beneficios)
    $editar_beneficios  = @$editar_beneficios ?: '';
    $excluir_beneficios = @$excluir_beneficios ?: '';

echo <<<HTML
<tr>
  <td>{$nb}</td>
  <td>{$nome_cliente}</td>
  <td class="esc">{$tipo_beneficio}</td>
  <td class="esc">
    <i class="fa fa-square mr-1" style="color:{$classe_status}"></i> {$status_row}
  </td>
  <td class="esc">{$derF}</td>
  <td class="esc"><b>{$dibF}</b></td>
  <td class="esc">{$dcb_previstaF}</td>
  <td class="esc">{$dcbF}</td>
  <td>
    <big>
      <a class="btn btn-info btn-sm {$editar_beneficios}" href="#"
         onclick="editar('{$id}','{$cliente}','{$nb}','{$tipo_beneficio_js}','{$der}','{$dib}','{$dii}','{$alta_prog_js}','{$dcb_prevista}','{$dcb}','{$status_js}','{$motivo_js}','{$origem_js}','{$doc_cessacao_js}','{$obs_js}')"
         title="Editar Dados">
        <i class="fa fa-edit"></i>
      </a>

     <a class="btn btn-primary btn-sm " href="#"
   onclick="mostrar('{$id}','{$nb}','{$nome_cliente}','{$tipo_beneficio}','{$status_row}','{$derF}','{$dibF}','{$diiF}','{$dcb_previstaF}','{$dcbF}','{$alta_programada}','{$motivo_cessacao}','{$origem_decisao}','{$doc_cessacao}','{$obs}')"
   title="Mostrar Dados">
  <i class="fa fa-info-circle"></i>
</a>


<a class="btn btn-info btn-sm " href="#" onclick="arquivo('{$id}', '{$nome_cliente}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-o "></i></a>
    </big>

    <div class="dropdown" style="display:inline-block;">
      <a class="btn btn-danger btn-sm {$excluir_beneficios}" href="#" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-trash"></i>
      </a>
      <div class="dropdown-menu tx-13">
        <div class="dropdown-item-text botao_excluir">
          <p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
        </div>
      </div>
    </div>
  </td>
</tr>
HTML;
}

echo <<<HTML
  </tbody>
  <small><div align="center" id="mensagem-excluir"></div></small>
</table>
HTML;

}else{
    echo 'Não possui nenhum cadastro!';
}
?>


<script type="text/javascript">
	$(document).ready( function () {	
			
		$('#tabela').DataTable({
    		"language" : {
            //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
	        },
	        "ordering": false,
			"stateSave": true
	    });	
	}
   

 );
</script>



<script type="text/javascript">
	function editar(id, cliente, nb, tipo_beneficio, der, dib, dii, alta_programada, dcb_prevista, dcb, status, motivo_cessacao, origem_decisao, doc_cessacao, obs){
  // tenta pegar obs do hidden (ex.: #obs_ID); se não houver, usa o parâmetro
  let texto = $('#obs_'+id).val();
  if (typeof texto === 'undefined' || texto === '') { texto = obs || ''; }

  // dessanitiza aspas caso tenham vindo como ** (igual ao seu padrão)
  if (texto.indexOf('**') !== -1) {
    texto = texto.replaceAll('**','"');
  }

  $('#mensagem').text('');
  $('#titulo_inserir').text('Editar Registro');

  // IDs do form de benefícios
  $('#id').val(id);
  $('#cliente').val(cliente).change();
  $('#nb').val(nb);
  $('#tipo_beneficio').val(tipo_beneficio).change();
  $('#status').val(status).change();
  $('#alta_programada').val(alta_programada).change();

  $('#der').val(der || '');
  $('#dib').val(dib || '');
  $('#dii').val(dii || '');
  $('#dcb_prevista').val(dcb_prevista || '');
  $('#dcb').val(dcb || '');

  $('#motivo_cessacao').val(motivo_cessacao || '');
  $('#origem_decisao').val(origem_decisao || '').change();
  $('#doc_cessacao').val(doc_cessacao || '');

  // OBS: se usar nicEditor, mantém compatibilidade
  $('#obs').val(texto);
  

  // Se você usa abas como no exemplo:
  if (document.getElementById('nav-home-tab')) { $('#nav-home-tab').click(); }

  $('#modalForm').modal('show');
}

function limparCampos(){
  $('#mensagem').text('');
  $('#titulo_inserir').text('Novo Registro');

  $('#id').val('');
  $('#cliente').val('').change();
  $('#nb').val('');
  $('#tipo_beneficio').val('').change();
  $('#status').val('ativo').change();
  $('#alta_programada').val('Não').change();

  $('#der').val('');
  $('#dib').val('');
  $('#dii').val('');
  $('#dcb_prevista').val('');
  $('#dcb').val('');

  $('#motivo_cessacao').val('');
  $('#origem_decisao').val('').change();
  $('#doc_cessacao').val('');

  if (window.nicEditors && nicEditors.findEditor) {
    try { nicEditors.findEditor("obs").setContent(''); } catch(e){ $('#obs').val(''); }
  } else {
    $('#obs').val('');
  }

  if (document.getElementById('nav-home-tab')) { $('#nav-home-tab').click(); }

  if ($('#btn-deletar').length) $('#btn-deletar').hide();
}

function mostrar(id, nb, nome_cliente, tipo_beneficio, status, derF, dibF, diiF, dcb_previstaF, dcbF, alta_programada, motivo_cessacao, origem_decisao, doc_cessacao, obs){
  // dessanitiza obs/doc se vieram com ** no onclick
  let obsTxt = (obs || '').replaceAll('**','"');
  let docTxt = (doc_cessacao || '').replaceAll('**','"');

  // preenche spans/labels da modal de detalhes (ajuste os IDs conforme seu HTML)
  $('#m_nb').text(nb || '');
  $('#m_cliente').text(nome_cliente || '');
  $('#m_tipo').text(tipo_beneficio || '');
  $('#m_status').text(status || '');
  $('#m_der').text(derF || '');
  $('#m_dib').text(dibF || '');
  $('#m_dii').text(diiF || '');
  $('#m_dcb_prevista').text(dcb_previstaF || '');
  $('#m_dcb').text(dcbF || '');
  $('#m_alta_programada').text(alta_programada || '');
  $('#m_motivo').text(motivo_cessacao || '');
  $('#m_origem').text(origem_decisao || '');
  $('#m_obs').text(obsTxt || '');

  // doc: se for link, ativa anchor; senão apenas texto
  if (docTxt && /^https?:\/\//i.test(docTxt)) {
    $('#m_doc_texto').hide();
    $('#m_doc_link').attr('href', docTxt).text(docTxt).show();
  } else {
    $('#m_doc_link').hide();
    $('#m_doc_texto').text(docTxt).show();
  }

  $('#modalMostrar').modal('show');
}


	

	function arquivo(id, nome){
    $('#id-arquivo').val(id);    
    $('#nome-arquivo').text(nome);
    $('#modalArquivos').modal('show');
    $('#mensagem-arquivo').text(''); 
    $('#arquivo_conta').val('');
    listarArquivos();   
}



function movimentacoes(id, nome){
    $('#id-movimentacoes').val(id);    
    $('#nome-movimentacoes').text(nome);
    $('#modalMovimentacoes').modal('show');
    $('#mensagem-movimentacoes').text('');
    
    listarMovimentacoes();   
}




function movimentacoesApi(id, nome, numero){
    $('#id-movimentacoes-api').val(id);    
    $('#nome-movimentacoes-api').text(nome);
    $('#modalMovimentacoesApi').modal('show');
    $('#mensagem-movimentacoes-api').text('');
    $('#numero-api').val(numero);
    
    if(numero == ""){
    	alert('Esse processo não tem número para consulta!');
    }else{
    	 processoApi(); 
    }
     
}


	function valor(id){
    $('#id-valor').val(id);     
    $('#modalValor').modal('show');
    $('#mensagem-valor').text('');    
    listarValores();   
}



</script>