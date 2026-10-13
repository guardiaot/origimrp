<?php 
require_once("../../../conexao.php");
require_once("../../rel/data_formatada.php");

$contrato = @$_POST['contrato'];
$cliente = @$_POST['cliente'];
$profissional = @$_POST['profissional'];
$servico = @$_POST['servico'];
$clientes_sel = @$_POST['clientes_sel'];
$prof_sel = @$_POST['prof_sel'];

$demais_contratantes = '';
$demais_contratados = '';


$query = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
$id_cliente = @$res[0]['id'];
$nome_cliente = @$res[0]['nome'];
$telefone_cliente = @$res[0]['telefone'];
$email_cliente = @$res[0]['email'];	
$endereco_cliente = @$res[0]['endereco'];
$tipo_pessoa_cliente = @$res[0]['tipo_pessoa'];
$cpf_cliente = @$res[0]['cpf'];

$numero_cliente = @$res[0]['numero'];
$bairro_cliente = @$res[0]['bairro'];
$cidade_cliente = @$res[0]['cidade'];
$estado_cliente = @$res[0]['estado'];
$cep_cliente = @$res[0]['cep'];
$profissao_cliente = @$res[0]['profissao'];
$nacionalidade_cliente = @$res[0]['nacionalidade'];
$estado_civil_cliente = @$res[0]['estado_civil'];
$assinatura_cliente_rel = @$res[0]['assinatura'];

$rg_cliente = @$res[0]['rg'];
$complemento_cliente = @$res[0]['complemento'];
$genitor_cliente = @$res[0]['genitor'];
$genitora_cliente = @$res[0]['genitora'];

$data_cad_cliente = @$res[0]['data_cad'];
$data_nasc_cliente = @$res[0]['data_nasc'];


$data_cadF_cliente = implode('/', array_reverse(@explode('-', $data_cad_cliente)));
$data_nascF_cliente = implode('/', array_reverse(@explode('-', $data_nasc_cliente)));
	
}



$query = $pdo->query("SELECT * from contratos where id = '$contrato'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
	$modelo_contrato = @$res[0]['modelo'];
	$texto_contrato = @$res[0]['texto'];
}

$assinaturas_profissionais_html = '';
$query = $pdo->query("SELECT * from usuarios where id = '$profissional'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
	$nome_usuario = @$res[0]['nome'];
	$telefone_usuario = @$res[0]['telefone'];
	$email_usuario = @$res[0]['email'];	
	$endereco_usuario = @$res[0]['endereco_profissional'];
	$nacionalidade_usuario = @$res[0]['nacionalidade'];
	$estado_civil_usuario = @$res[0]['estado_civil'];
	$seccional_oab = @$res[0]['seccional_oab'];
	$numero_oab = @$res[0]['numero_oab'];
	$assinatura_usuario = @$res[0]['assinatura'];
	
	if (!empty($assinatura_usuario)) {
	 $assinaturas_profissionais_html .=
    '<div style="margin-top:20px; text-align:center;">' .
      '<img src="'.$url_sistema.'painel/images/assinaturas/'.htmlspecialchars($assinatura_usuario, ENT_QUOTES, "UTF-8").'" ' .
      'alt="Assinatura Profissional" width="250px"><br>' .
      '<div style="width:250px; border-top:1px solid #000; margin:5px auto 4px auto;"></div>' .
      '<div style="font-size:13px; font-weight:600;">'.htmlspecialchars($nome_usuario ?? '', ENT_QUOTES, "UTF-8").'</div>' .
      '<div style="font-size:12px;">OAB/'.$seccional_oab.' '.$numero_oab.'</div>' .
    '</div>';
	}
}



$query = $pdo->query("SELECT * from abertura_contratos where id = '$servico'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
	$valor_servico = @$res[0]['valor'];
	$forma_pgto = @$res[0]['forma_pgto'];
	$valor_servicoF = 'R$ '.@number_format($valor_servico, 2, ',', '.');
	$poderes_especificos = @$res[0]['area_poderes'];
	$area_valor = @$res[0]['area_valor'];

	$query = $pdo->query("SELECT * from formas_pgto where id = '$forma_pgto'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res) > 0){
		$nome_forma_pgto = @$res[0]['nome'];	
	}
}


$texto_dados = '';
$texto_dados_oculto = '';

if(@$tipo_pessoa_cliente == 'Física'){
		$texto_dados = "{$nome_cliente}, {$nacionalidade_cliente}, {$estado_civil_cliente}, {$profissao_cliente}, nascido(a) em {$data_nascF_cliente}, inscrito(a) no CPF sob o n.º {$cpf_cliente}, sob identidade n.º {$rg_cliente}, filho de {$genitor_cliente} e {$genitora_cliente}, residente e domiciliado(a) à {$endereco_cliente}, {$numero_cliente}, bairro {$bairro_cliente}, município de {$cidade_cliente} –{$estado_cliente} – CEP: {$cep_cliente}, email: {$email_cliente}, telefone: {$telefone_cliente}";
	}
	if(@$tipo_pessoa_cliente == 'Jurídica'){
		$texto_dados = "{$nome_cliente}, pessoa jurídica de direito privado, inscrita no CNPJ sob o n° {$cpf_cliente}, situada à/ao {$endereco_cliente}, {$numero_cliente}, bairro {$bairro_cliente}, município de {$cidade_cliente} –{$estado_cliente} – CEP: {$cep_cliente}, email: {$email_cliente}, telefone: {$telefone_cliente}";
	}

$texto_dados_final = $texto_dados;




if(@$tipo_pessoa_cliente == 'Física'){
		$texto_dados_oculto = "{$nome_cliente}, {$nacionalidade_cliente}, {$estado_civil_cliente}, {$profissao_cliente}, nascido(a) em {$data_nascF_cliente}, inscrito(a) no CPF sob o n.º {$cpf_cliente}, sob identidade n.º {$rg_cliente}, filho de {$genitor_cliente} e {$genitora_cliente}, residente e domiciliado(a) à {$endereco_cliente}, {$numero_cliente}, bairro {$bairro_cliente}, município de {$cidade_cliente} –{$estado_cliente} – CEP: {$cep_cliente}";
	}
	if(@$tipo_pessoa_cliente == 'Jurídica'){
		$texto_dados_oculto = "{$nome_cliente}, pessoa jurídica de direito privado, inscrita no CNPJ sob o n° {$cpf_cliente}, situada à/ao {$endereco_cliente}, {$numero_cliente}, bairro {$bairro_cliente}, município de {$cidade_cliente} –{$estado_cliente} – CEP: {$cep_cliente}";
	}

$texto_dados_oculto_final = $texto_dados_oculto;


$assinaturas_clientes_html = '';
$texto_profissional = '';
if(@$nome_usuario != ""){
$texto_profissional = "{$nome_usuario}, {$nacionalidade_usuario}, {$estado_civil_usuario}, portador(a) da OAB {$seccional_oab}, n.º {$numero_oab} com endereço profissional à {$endereco_usuario}";
}

if($clientes_sel != ""){
	$sep = explode("-", $clientes_sel);
	$total_clientes = @count($sep) - 1;

	for ($i=0; $i < $total_clientes; $i++) { 
		$id_cli = $sep[$i];
		$query = $pdo->query("SELECT * from clientes where id = '$id_cli'");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res) > 0){

		$nome_cliente2 = @$res[0]['nome'];
		$telefone_cliente2 = @$res[0]['telefone'];
		$email_cliente2 = @$res[0]['email'];	
		$endereco_cliente2 = @$res[0]['endereco'];
		$tipo_pessoa_cliente2 = @$res[0]['tipo_pessoa'];
		$cpf_cliente2 = @$res[0]['cpf'];

		$numero_cliente2 = @$res[0]['numero'];
		$bairro_cliente2 = @$res[0]['bairro'];
		$cidade_cliente2 = @$res[0]['cidade'];
		$estado_cliente2 = @$res[0]['estado'];
		$cep_cliente2 = @$res[0]['cep'];
		$profissao_cliente2 = @$res[0]['profissao'];
		$nacionalidade_cliente2 = @$res[0]['nacionalidade'];
		$estado_civil_cliente2 = @$res[0]['estado_civil'];


		$rg_cliente2 = @$res[0]['rg'];
		$complemento_cliente2 = @$res[0]['complemento'];
		$genitor_cliente2 = @$res[0]['genitor'];
		$genitora_cliente2 = @$res[0]['genitora'];

		$data_cad_cliente2 = @$res[0]['data_cad'];
		$data_nasc_cliente2 = @$res[0]['data_nasc'];


		// ---------- Assinatura do cliente adicional ----------
		$src_cli2 = $url_sistema.'painel/images/assinaturas/'.@$id_cli.'.png';

		$docLabel2 = '';
		if (!empty($cpf_cliente2)) {
		  $docLabel2 = ($tipo_pessoa_cliente2 == 'Jurídica') ? "CNPJ: {$cpf_cliente2}" : "CPF: {$cpf_cliente2}";
		}

		$assinaturas_clientes_html .=
		  '<div style="margin-top:10px; text-align:center;">' .
		    '<img src="'.htmlspecialchars($src_cli2, ENT_QUOTES, "UTF-8").'" alt="Assinatura do Cliente" width="250px"><br>' .
		    '<div style="width:250px; border-top:1px solid #000; margin:5px auto 4px auto;"></div>' .
		    '<div style="font-size:13px; font-weight:600;">'.htmlspecialchars($nome_cliente2 ?? '', ENT_QUOTES, "UTF-8").'</div>' .
		    (!empty($docLabel2) ? '<div style="font-size:12px;">'.htmlspecialchars($docLabel2, ENT_QUOTES, "UTF-8").'</div>' : '') .
		  '</div>';


		$data_cadF_cliente2 = implode('/', array_reverse(@explode('-', $data_cad_cliente)));
		$data_nascF_cliente2 = implode('/', array_reverse(@explode('-', $data_nasc_cliente)));


		if(@$tipo_pessoa_cliente == 'Física'){
		$texto_dados2 = ", {$nome_cliente2}, {$nacionalidade_cliente2}, {$estado_civil_cliente2}, {$profissao_cliente2}, nascido(a) em {$data_nascF_cliente2}, inscrito(a) no CPF sob o n.º {$cpf_cliente2}, sob identidade n.º {$rg_cliente2}, filho de {$genitor_cliente2} e {$genitora_cliente2}, residente e domiciliado(a) à {$endereco_cliente2}, {$numero_cliente2}, bairro {$bairro_cliente2}, município de {$cidade_cliente2} –{$estado_cliente2} – CEP: {$cep_cliente2}, email: {$email_cliente2}, telefone: {$telefone_cliente2}";
	}else{
		$texto_dados2 = ", {$nome_cliente2}, pessoa jurídica de direito privado, inscrita no CNPJ sob o n° {$cpf_cliente2}, situada à/ao {$endereco2}, {$numero_cliente2}, bairro {$bairro_cliente2}, município de {$cidade_cliente2} –{$estado_cliente2} – CEP: {$cep_cliente2}, email: {$email_cliente2}, telefone: {$telefone_cliente2}";
	}

			$texto_dados_final .= $texto_dados2;

			$demais_contratantes .= "<br><br>______________________________________________________________________<br>
{$nome_cliente2}<br>
 (Contratante)";
	}



	
}

}




if($prof_sel != ""){
	$sep = explode("-", $prof_sel);
	$total_clientes = @count($sep) - 1;

	for ($i=0; $i < $total_clientes; $i++) { 
		$id_cli = $sep[$i];
		$query = $pdo->query("SELECT * from usuarios where id = '$id_cli'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
	$nome_usuario2 = @$res[0]['nome'];
	$telefone_usuario2 = @$res[0]['telefone'];
	$email_usuario2 = @$res[0]['email'];	
	$endereco_usuario2 = @$res[0]['endereco_profissional'];
	$nacionalidade_usuario2 = @$res[0]['nacionalidade'];
	$estado_civil_usuario2 = @$res[0]['estado_civil'];
	$seccional_oab2 = @$res[0]['seccional_oab'];
	$numero_oab2 = @$res[0]['numero_oab'];
	$assinatura_usuario2 = @$res[0]['assinatura'];
	
if (!empty($assinatura_usuario2)) {
  $assinaturas_profissionais_html .=
    '<div style="margin-top:20px; text-align:center;">' .
      '<img src="'.$url_sistema.'painel/images/assinaturas/'.htmlspecialchars($assinatura_usuario2, ENT_QUOTES, "UTF-8").'" ' .
      'alt="Assinatura Profissional" width="250px"><br>' .
      '<div style="width:250px; border-top:1px solid #000; margin:5px auto 4px auto;"></div>' .
      '<div style="font-size:13px; font-weight:600;">'.htmlspecialchars($nome_usuario2 ?? '', ENT_QUOTES, "UTF-8").'</div>' .
      '<div style="font-size:12px;">OAB/'.$seccional_oab2.' '.$numero_oab2.'</div>' .
    '</div>';
}


		$texto_profissional .= ", {$nome_usuario2}, {$nacionalidade_usuario2}, {$estado_civil_usuario2}, portador(a) da OAB {$seccional_oab2}, n.º {$numero_oab2} com endereço profissional à {$endereco_usuario2}";


			$demais_contratados .= "<br><br>______________________________________________________________________<br>
{$nome_usuario2}<br>
OAB/{$seccional_oab2} {$numero_oab2}<br>
 (Contratado)";
	}



	
}

}


//dados cliente

if(@$nome_cliente != ""){
	$texto_contrato = @str_replace('{{TEXTO DADOS}}', @$texto_dados_final, @$texto_contrato);
}

if(@$nome_cliente != ""){
	$texto_contrato = @str_replace('{{TEXTO DADOS OCULTO}}', @$texto_dados_oculto_final, @$texto_contrato);
}

if(@$nome_cliente != ""){
	$texto_contrato = @str_replace('{{NOME COMPLETO}}', @$nome_cliente, @$texto_contrato);
}

if(@$nome_cliente != ""){
	$texto_contrato = @str_replace('{{NOME}}', @$nome_cliente, @$texto_contrato);
}

if(@$nacionalidade_cliente != ""){
	$texto_contrato = @str_replace('{{NACIONALIDADE}}', @$nacionalidade_cliente, @$texto_contrato);
}

if(@$profissao_cliente != ""){
	$texto_contrato = @str_replace('{{PROFISSÃO}}', @$profissao_cliente, @$texto_contrato);
}

if(@$estado_civil_cliente != ""){
	$texto_contrato = @str_replace('{{ESTADO CIVIL}}', @$estado_civil_cliente, @$texto_contrato);
}

if(@$data_nasc_cliente != ""){
	$texto_contrato = @str_replace('{{DATA DE NASCIMENTO}}', @$data_nascF_cliente, @$texto_contrato);
}

if(@$cpf_cliente != ""){
	$texto_contrato = @str_replace('{{NÚMERO DO CPF}}', @$cpf_cliente, @$texto_contrato);
}

if(@$rg_cliente != ""){
	$texto_contrato = @str_replace('{{NÚMERO RG}}', @$rg_cliente, @$texto_contrato);
}

if(@$genitor_cliente != ""){
	$texto_contrato = @str_replace('{{GENITOR}}', @$genitor_cliente, @$texto_contrato);
}

if(@$genitora_cliente != ""){
	$texto_contrato = @str_replace('{{GENITORA}}', @$genitora_cliente, @$texto_contrato);
}

if(@$endereco_cliente != ""){
	$texto_contrato = @str_replace('{{LOGRADOURO}}', @$endereco_cliente, @$texto_contrato);
}

if(@$numero_cliente != ""){
	$texto_contrato = @str_replace('{{NÚMERO LOGRADOURO}}', @$numero_cliente, @$texto_contrato);
}

if(@$bairro_cliente != ""){
	$texto_contrato = @str_replace('{{BAIRRO}}', @$bairro_cliente, @$texto_contrato);
}


if(@$cidade_cliente != ""){
	$texto_contrato = @str_replace('{{MUNICÍPIO}}', @$cidade_cliente, @$texto_contrato);
}

if(@$estado_cliente != ""){
	$texto_contrato = @str_replace('{{ESTADO}}', @$estado_cliente, @$texto_contrato);
}

if(@$cep_cliente != ""){
	$texto_contrato = @str_replace('{{CEP}}', @$cep_cliente, @$texto_contrato);
}

if(@$complemento_cliente != ""){
	$texto_contrato = @str_replace('{{COMPLEMENTO}}', @$complemento_cliente, @$texto_contrato);
}

if(@$email_cliente != ""){
	$texto_contrato = @str_replace('{{EMAIL}}', @$email_cliente, @$texto_contrato);
}

if(@$telefone_cliente != ""){
	$texto_contrato = @str_replace('{{TELEFONE}}', @$telefone_cliente, @$texto_contrato);
}




	

//dados do profissional / advogado

if(@$nome_usuario != ""){
	$texto_contrato = @str_replace('{{PROFISSIONAL}}', @$texto_profissional, @$texto_contrato);
}

if(@$nome_usuario != ""){
	$texto_contrato = @str_replace('{{NOME2}}', @$nome_usuario, @$texto_contrato);
}

if(@$nacionalidade_usuario != ""){
	$texto_contrato = @str_replace('{{NACIONALIDADE2}}', @$nacionalidade_usuario, @$texto_contrato);
}

if(@$estado_civil_usuario != ""){
	$texto_contrato = @str_replace('{{ESTADO CIVIL2}}', @$estado_civil_usuario, @$texto_contrato);
}

if(@$seccional_oab != ""){
	$texto_contrato = @str_replace('{{SECCIONAL OAB}}', @$seccional_oab, @$texto_contrato);
}

if(@$numero_oab != ""){
	$texto_contrato = @str_replace('{{NÚMERO OAB}}', @$numero_oab, @$texto_contrato);
}

if(@$endereco_usuario != ""){
	$texto_contrato = @str_replace('{{ENDERECO2}}', @$endereco_usuario, @$texto_contrato);
}





if(@$cidade_sistema != ""){
	$texto_contrato = @str_replace('{{LOCAL}}', @$cidade_sistema, @$texto_contrato);
}


if(@$data_hoje != ""){
	$texto_contrato = @str_replace('{{DATA}}', @$data_hoje, @$texto_contrato);
}


if(@$nome_sistema != ""){
	$texto_contrato = @str_replace('{{NOME DO ESCRITÓRIO}}', @$nome_sistema, @$texto_contrato);
}


if(@$nome_sistema != ""){
	$texto_contrato = @str_replace('{{NOME ESCRITÓRIO}}', @$nome_sistema, @$texto_contrato);
}


if(@$cnpj_sistema != ""){
	$texto_contrato = @str_replace('{{CNPJ}}', @$cnpj_sistema, @$texto_contrato);
}


if(@$seccional_oab_escritorio != ""){
	$texto_contrato = @str_replace('{{SECCIONAL OAB ESCRITORIO}}', @$seccional_oab_escritorio, @$texto_contrato);
}


if(@$numero_oab_escritorio != ""){
	$texto_contrato = @str_replace('{{NÚMERO OAB ESCRITORIO}}', @$numero_oab_escritorio, @$texto_contrato);
}


if(@$endereco_sistema != ""){
	$texto_contrato = @str_replace('{{ENDERECO ESCRITORIO}}', @$endereco_sistema, @$texto_contrato);
}


if(@$dados_pagamento != ""){
	$texto_contrato = @str_replace('{{FORMA PGTO ESCRITORIO}}', @$dados_pagamento, @$texto_contrato);
}


if(@$cidade_sistema != ""){
	$texto_contrato = @str_replace('{{MUNICÍPIO ESCRITORIO}}', @$cidade_sistema, @$texto_contrato);
}


if(@$seccional_oab_escritorio != ""){
	$texto_contrato = @str_replace('{{ESTADO DO FORO}}', @$seccional_oab_escritorio, @$texto_contrato);
}



if(@$cidade_sistema != ""){
	$texto_contrato = @str_replace('{{LOCAL}}', @$cidade_sistema, @$texto_contrato);
}


if(@$data_hoje != ""){
	$texto_contrato = @str_replace('{{DATA}}', @$data_hoje, @$texto_contrato);
}


if(@$nome_sistema != ""){
	$texto_contrato = @str_replace('{{NOME SISTEMA}}', @$nome_sistema, @$texto_contrato);
}

if(@$nome_sistema != ""){
	$texto_contrato = @str_replace('{{NOME EMPRESA}}', @$nome_sistema, @$texto_contrato);
}

if(@$nome_sistema != ""){
	$texto_contrato = @str_replace('{{NOME DO SISTEMA}}', @$nome_sistema, @$texto_contrato);
}

if(@$nome_sistema != ""){
	$texto_contrato = @str_replace('{{NOME DA EMPRESA}}', @$nome_sistema, @$texto_contrato);
}

if(@$cnpj_sistema != ""){
	$texto_contrato = @str_replace('{{CNPJ SISTEMA}}', @$cnpj_sistema, @$texto_contrato);
}

if(@$cnpj_sistema != ""){
	$texto_contrato = @str_replace('{{CNPJ EMPRESA}}', @$cnpj_sistema, @$texto_contrato);
}



if(@$endereco_sistema != ""){
	$texto_contrato = @str_replace('{{ENDEREÇO SISTEMA}}', @$endereco_sistema, @$texto_contrato);
}

if(@$endereco_sistema != ""){
	$texto_contrato = @str_replace('{{ENDEREÇO EMPRESA}}', @$endereco_sistema, @$texto_contrato);
}




if(@$valor_servico != ""){
	$texto_contrato = @str_replace('{{VALOR}}', @$valor_servicoF, @$texto_contrato);
}


if(@$nome_forma_pgto != ""){
	$texto_contrato = @str_replace('{{FORMA DE PAGAMENTO}}', @$nome_forma_pgto, @$texto_contrato);
}

if(@$poderes_especificos != ""){
	$texto_contrato = @str_replace('{{PODERES ESPECÍFICOS}}', @$poderes_especificos, @$texto_contrato);
}

if(@$poderes_especificos != ""){
	$texto_contrato = @str_replace('{{VALOR_PAGAMENTO}}', @$area_valor, @$texto_contrato);
}


$img_assinatura = '';




// ===============================
// ASSINATURAS DOS CLIENTES (PRINCIPAL + ADICIONAIS)
// ===============================

// ---------- Cliente principal ----------
$src_cli = $url_sistema.'painel/images/assinaturas/'.@$id_cliente.'.png';

$docLabel = '';
if (!empty($cpf_cliente)) {
  $docLabel = ($tipo_pessoa_cliente == 'Jurídica') ? "CNPJ: {$cpf_cliente}" : "CPF: {$cpf_cliente}";
}

if (!empty($id_cliente)) {
  $assinaturas_clientes_html .=
    '<div style="margin-top:10px; text-align:center;">' .
      '<img src="'.htmlspecialchars($src_cli, ENT_QUOTES, "UTF-8").'" alt="Assinatura do Cliente" width="250px"><br>' .
      '<div style="width:250px; border-top:1px solid #000; margin:5px auto 4px auto;"></div>' .
      '<div style="font-size:13px; font-weight:600;">'.htmlspecialchars($nome_cliente ?? '', ENT_QUOTES, "UTF-8").'</div>' .
      (!empty($docLabel) ? '<div style="font-size:12px;">'.htmlspecialchars($docLabel, ENT_QUOTES, "UTF-8").'</div>' : '') .
    '</div>';
}






$texto_contrato = @str_replace('{{ENDERECO_COMPLETO}}', "$endereco_cliente, $numero_cliente , $bairro_cliente, $cidade_cliente-$estado_cliente", $texto_contrato);



$assinatura_sistema_html = '';

$assinatura_sistema_html .=
  '<div style="margin-top:10px; text-align:center;">' .
    '<img src="'.$url_sistema.'img/assinatura.png" alt="Assinatura Sistema" width="250px"><br>' .
    '<div style="width:250px; border-top:1px solid #000; margin:5px auto 4px auto;"></div>' .
    '<div style="font-size:13px; font-weight:600;">'.htmlspecialchars($nome_sistema ?? '', ENT_QUOTES, "UTF-8").'</div>';

if (!empty($cnpj_sistema)) {
  $assinatura_sistema_html .=
    '<div style="font-size:12px;">CNPJ: '.htmlspecialchars($cnpj_sistema, ENT_QUOTES, "UTF-8").'</div>';
}

$assinatura_sistema_html .= '</div>';

$texto_contrato = str_replace('{{ASSINATURA_SISTEMA}}', $assinatura_sistema_html, $texto_contrato);


// joga todas as assinaturas de clientes na chave
$texto_contrato = str_replace('{{ASSINATURA}}', $assinaturas_clientes_html, $texto_contrato);

// se não tiver nenhuma assinatura, deixa vazio (ou coloque um texto)
$texto_contrato = @str_replace('{{ASSINATURA_PROFISSIONAL}}', $assinaturas_profissionais_html, $texto_contrato);



$texto_contrato = @str_replace('{{DEMAIS CONTRATANTES}}', @$demais_contratantes, @$texto_contrato);
$texto_contrato = @str_replace('{{DEMAIS CONTRATADOS}}', @$demais_contratados, @$texto_contrato);



echo @$texto_contrato;

?>