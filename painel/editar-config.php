<?php 
$tabela = 'config';
require_once("../conexao.php");

if($modo_teste == 'Sim'){
	echo "No modo de testes você não pode editar esses recursos!";
	exit();
}

$nome = $_POST['nome_sistema'];
$email = $_POST['email_sistema'];
$telefone = $_POST['telefone_sistema'];
$endereco = $_POST['endereco_sistema'];
$instagram = $_POST['instagram_sistema'];
$multa_atraso = $_POST['multa_atraso'];
$juros_atraso = $_POST['juros_atraso'];
$marca_dagua = $_POST['marca_dagua'];
$assinatura_recibo = $_POST['assinatura_recibo'];
$impressao_automatica = $_POST['impressao_automatica'];
$cnpj_sistema = $_POST['cnpj_sistema'];
$entrar_automatico = $_POST['entrar_automatico'];
$mostrar_preloader = $_POST['mostrar_preloader'];
$ocultar_mobile = $_POST['ocultar_mobile'];
$api_whatsapp = $_POST['api_whatsapp'];
$token_whatsapp = $_POST['token_whatsapp'];
$instancia_whatsapp = $_POST['instancia_whatsapp'];
$alterar_acessos = $_POST['alterar_acessos'];
$dados_pagamento = $_POST['dados_pagamento'];
$cidade_sistema = $_POST['cidade_sistema'];
$seccional_oab_escritorio = $_POST['seccional_oab_escritorio'];
$numero_oab_escritorio = $_POST['numero_oab_escritorio'];
$cor_login = $_POST['cor_login'];
$filtrar_colunas = $_POST['filtrar_colunas'];
$assinatura_cliente = $_POST['assinatura_cliente'];
$tipo_tel = $_POST['tipo_tel'];
$entrada_sistema = $_POST['entrada_sistema'];
$token_chatgpt = $_POST['token_chatgpt'];
$chave_asaas = $_POST['chave_asaas'];
$api_ia = $_POST['api_ia'];
$token_gemini = $_POST['token_gemini'];
$url_evolution = $_POST['url_evolution'];

$multa_atraso = str_replace(',', '.', $multa_atraso);
$multa_atraso = str_replace('%', '', $multa_atraso);

$juros_atraso = str_replace(',', '.', $juros_atraso);
$juros_atraso = str_replace('%', '', $juros_atraso);

//foto logo
$caminho = '../img/logo.png';
$imagem_temp = @$_FILES['foto-logo']['tmp_name']; 

if(@$_FILES['foto-logo']['name'] != ""){
	$ext = pathinfo($_FILES['foto-logo']['name'], PATHINFO_EXTENSION);   
	if($ext == 'png' || $ext == 'PNG'){ 	
				
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


//foto logo rel
$caminho = '../img/logo.jpg';
$imagem_temp = @$_FILES['foto-logo-rel']['tmp_name']; 

if(@$_FILES['foto-logo-rel']['name'] != ""){
	$ext = pathinfo(@$_FILES['foto-logo-rel']['name'], PATHINFO_EXTENSION);   
	if($ext == 'jpg' || $ext == 'JPG'){ 	
			
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


//foto icone
$caminho = '../img/icone.png';
$imagem_temp = @$_FILES['foto-icone']['tmp_name']; 

if(@$_FILES['foto-icone']['name'] != ""){
	$ext = pathinfo(@$_FILES['foto-icone']['name'], PATHINFO_EXTENSION);   
	if($ext == 'png' || $ext == 'png'){ 	
			
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


//foto ass
$caminho = '../img/assinatura.jpg';
$imagem_temp = @$_FILES['assinatura_rel']['tmp_name']; 

if(@$_FILES['assinatura_rel']['name'] != ""){
	$ext = pathinfo(@$_FILES['assinatura_rel']['name'], PATHINFO_EXTENSION);   
	if($ext == 'jpg' || $ext == 'JPG'){ 	
			
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


//foto painel
$caminho = '../img/foto-painel.png';
$imagem_temp = @$_FILES['foto-painel']['tmp_name']; 

if(@$_FILES['foto-painel']['name'] != ""){
	$ext = pathinfo(@$_FILES['foto-painel']['name'], PATHINFO_EXTENSION);   
	if($ext == 'png' || $ext == 'PNG'){ 	
			
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


//foto fundo login
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['fundo_login']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);
$caminho = '../img/'.$nome_img;
$imagem_temp = @$_FILES['fundo_login']['tmp_name']; 

if(@$_FILES['fundo_login']['name'] != ""){
	$ext = pathinfo(@$_FILES['fundo_login']['name'], PATHINFO_EXTENSION);   
	if($ext == 'jpg' || $ext == 'JPG' || $ext == 'png' || $ext == 'PNG'|| $ext == 'gif' || $ext == 'GIF' || $ext == 'webp' || $ext == 'WEBP'){			
		move_uploaded_file($imagem_temp, $caminho);
		$fundo_login = $nome_img;

		$query = $pdo->query("SELECT * FROM config");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$fundo_login_antigo = @$res[0]['fundo_login'];

		if($fundo_login_antigo != "sem-foto.png"){
			@unlink('../img/'.$fundo_login_antigo);
		} 

	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco, instagram = :instagram, multa_atraso = :multa_atraso, juros_atraso = :juros_atraso, marca_dagua = :marca_dagua, marca_dagua = :marca_dagua, assinatura_recibo = :assinatura_recibo, impressao_automatica = :impressao_automatica, cnpj = :cnpj_sistema, entrar_automatico = :entrar_automatico, mostrar_preloader = :mostrar_preloader, ocultar_mobile = :ocultar_mobile, api_whatsapp = '$api_whatsapp', token_whatsapp = :token_whatsapp, instancia_whatsapp = :instancia_whatsapp, alterar_acessos = :alterar_acessos, dados_pagamento = :dados_pagamento, cidade_sistema = :cidade_sistema, seccional_oab_escritorio = :seccional_oab_escritorio, numero_oab_escritorio = :numero_oab_escritorio, cor_login = :cor_login, filtrar_colunas = :filtrar_colunas, fundo_login = '$fundo_login', assinatura_cliente = '$assinatura_cliente', tipo_tel = '$tipo_tel', entrada_sistema = '$entrada_sistema', token_chatgpt = :token_chatgpt, chave_asaas = :chave_asaas, api_ia = :api_ia, token_gemini = :token_gemini, url_evolution = :url_evolution where id = 1");

$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":instagram", "$instagram");
$query->bindValue(":multa_atraso", "$multa_atraso");
$query->bindValue(":juros_atraso", "$juros_atraso");
$query->bindValue(":marca_dagua", "$marca_dagua");
$query->bindValue(":assinatura_recibo", "$assinatura_recibo");
$query->bindValue(":impressao_automatica", "$impressao_automatica");
$query->bindValue(":cnpj_sistema", "$cnpj_sistema");
$query->bindValue(":entrar_automatico", "$entrar_automatico");
$query->bindValue(":mostrar_preloader", "$mostrar_preloader");
$query->bindValue(":ocultar_mobile", "$ocultar_mobile");
$query->bindValue(":token_whatsapp", "$token_whatsapp");
$query->bindValue(":instancia_whatsapp", "$instancia_whatsapp");
$query->bindValue(":alterar_acessos", "$alterar_acessos");
$query->bindValue(":dados_pagamento", "$dados_pagamento");
$query->bindValue(":cidade_sistema", "$cidade_sistema");
$query->bindValue(":seccional_oab_escritorio", "$seccional_oab_escritorio");
$query->bindValue(":numero_oab_escritorio", "$numero_oab_escritorio");
$query->bindValue(":cor_login", "$cor_login");
$query->bindValue(":filtrar_colunas", "$filtrar_colunas");
$query->bindValue(":token_chatgpt", "$token_chatgpt");
$query->bindValue(":chave_asaas", "$chave_asaas");
$query->bindValue(":api_ia", "$api_ia");
$query->bindValue(":token_gemini", "$token_gemini");
$query->bindValue(":url_evolution", "$url_evolution");
$query->execute();

echo 'Editado com Sucesso';
 ?>