<?php 
@session_start();
$id_usuario = @$_SESSION['id'];
$nivel_usuario = @$_SESSION['nivel'];

$tabela = 'pagar';
require_once("../../../conexao.php");

$descricao = $_POST['descricao'];
$valor = $_POST['valor'];
$fornecedor = $_POST['fornecedor'];
$funcionario = $_POST['funcionario'];
$vencimento = $_POST['vencimento'];
$data_pgto = $_POST['data_pgto'];
$forma_pgto = $_POST['forma_pgto'];
$frequencia = $_POST['frequencia'];
$obs = $_POST['obs'];
$usuario_receb = @$_POST['usuario_receb'];
$quant_recorrencia = @$_POST['quant_recorrencia'];
$recorrencia_inf = @$_POST['recorrencia_inf'];

if($quant_recorrencia > 0 and $frequencia == '0'){
	echo 'Você precisa selecionar uma Frequência!';
	exit();
}

$id = $_POST['id'];

if($nivel_usuario == 'Administrador' || $nivel_usuario == 'Escritório'){
	$usuario_receb = 0;
}

$valor = str_replace(',', '.', $valor);

$valorF = @number_format($valor, 2, ',', '.');

if($fornecedor == ""){
	$fornecedor = 0;
}

if($funcionario == ""){
	$funcionario = 0;
}

if($forma_pgto == ""){
	$forma_pgto = 0;
}

if($frequencia == ""){
	$frequencia = 0;
}

if($quant_recorrencia == ""){
	$quant_recorrencia = 0;
}

if($data_pgto == ""){
	$pgto = '';
	$usu_pgto = '';
	$pago = 'Não';
}else{
	$pgto = " ,data_pgto = '$data_pgto'";
	$usu_pgto = " ,usuario_pgto = '$id_usuario'";
	$pago = 'Sim';
}

//validacao
if($descricao == "" and $fornecedor == "0" and $funcionario == "0"){
	echo 'Selecione um Fornecedor ou um Funcionário ou uma Descrição!';
	exit();
}

if($fornecedor != "0" and $funcionario != "0"){
	echo 'Selecione um Fornecedor ou um Funcionário!';
	exit();
}



//validar troca da foto
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
	$foto = $res[0]['arquivo'];
	$quant_recorrencia_rec = $res[0]['quant_recorrencia'];
	$recorrencia_inf_rec = $res[0]['recorrencia_inf'];
}else{
	$foto = 'sem-foto.png';
	$quant_recorrencia_rec = '';
	$recorrencia_inf_rec = '';
}



//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../images/contas/' .$nome_img;

$imagem_temp = @$_FILES['foto']['tmp_name']; 

if(@$_FILES['foto']['name'] != ""){
	$ext = pathinfo($nome_img, PATHINFO_EXTENSION);   
	if($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'pdf' or $ext == 'rar' or $ext == 'zip' or $ext == 'doc' or $ext == 'docx' or $ext == 'webp' or $ext == 'PNG' or $ext == 'JPG' or $ext == 'JPEG' or $ext == 'GIF' or $ext == 'PDF' or $ext == 'RAR' or $ext == 'ZIP' or $ext == 'DOC' or $ext == 'DOCX' or $ext == 'WEBP' or $ext == 'xlsx' or $ext == 'xlsm' or $ext == 'xls' or $ext == 'xml'){ 
	
			//EXCLUO A FOTO ANTERIOR
			if($foto != "sem-foto.png"){
				@unlink('../../images/contas/'.$foto);
			}

			$foto = $nome_img;
		
		//pegar o tamanho da imagem
			list($largura, $altura) = getimagesize($imagem_temp);
		 	if($largura > 1400){
		 		if($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'webp' or $ext == 'PNG' or $ext == 'JPG' or $ext == 'JPEG' or $ext == 'GIF' or $ext == 'PDF' or $ext == 'RAR' or $ext == 'WEBP'){
		 			$image = imagecreatefromjpeg($imagem_temp);
			        // Reduza a qualidade para 20% ajuste conforme necessário
			        imagejpeg($image, $caminho, 20);
			        imagedestroy($image);
		 		}else{
		 			move_uploaded_file($imagem_temp, $caminho);
		 		}
			 		
		 	}else{
		 		move_uploaded_file($imagem_temp, $caminho);
		 	}
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}



if($fornecedor != 0 || $funcionario != 0){
	if($fornecedor != 0){
		$tab = 'fornecedores';
		$id_pessoa = $fornecedor;
	}

	if($funcionario != 0){
		$tab = 'usuarios';
		$id_pessoa = $funcionario;
	}

	//nome pessoa
	$query = $pdo->query("SELECT * FROM $tab where id = '$id_pessoa'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);
	if($total_reg > 0){
		$nome_pessoa = $res[0]['nome'];
	}else{
		$nome_pessoa = '';
	}

	if($descricao == ""){
		$descricao = $nome_pessoa;
	}
	
}


//verificar caixa aberto
$query1 = $pdo->query("SELECT * from caixas where operador = '$id_usuario' and data_fechamento is null order by id desc limit 1");
$res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
if(@count($res1) > 0){
	$id_caixa = @$res1[0]['id'];
}else{
	$id_caixa = 0;
}
//  

if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET descricao = :descricao, fornecedor = :fornecedor, funcionario = :funcionario, valor = :valor, vencimento = '$vencimento' $pgto, data_lanc = curDate(), forma_pgto = '$forma_pgto', frequencia = '$frequencia', obs = :obs, arquivo = '$foto', subtotal = :valor, usuario_lanc = '$id_usuario' $usu_pgto, pago = '$pago', referencia = 'Conta', caixa = '$id_caixa', hora = curTime(), usuario_receb = :usuario_receb, quant_recorrencia = :quant_recorrencia, recorrencia_inf = :recorrencia_inf, hora_alerta = '$hora_random' ");

	
}else{
$query = $pdo->prepare("UPDATE $tabela SET descricao = :descricao, fornecedor = :fornecedor, funcionario = :funcionario, valor = :valor, vencimento = '$vencimento' $pgto, forma_pgto = '$forma_pgto', frequencia = '$frequencia', obs = :obs, arquivo = '$foto', subtotal = :valor, usuario_receb = :usuario_receb, quant_recorrencia = :quant_recorrencia, recorrencia_inf = :recorrencia_inf where id = '$id'");
}
$query->bindValue(":descricao", "$descricao");
$query->bindValue(":fornecedor", "$fornecedor");
$query->bindValue(":funcionario", "$funcionario");
$query->bindValue(":valor", "$valor");
$query->bindValue(":obs", "$obs");
$query->bindValue(":usuario_receb", "$usuario_receb");
$query->bindValue(":quant_recorrencia", "$quant_recorrencia");
$query->bindValue(":recorrencia_inf", "$recorrencia_inf");
$query->execute();
$ultimo_id = $pdo->lastInsertId();



//criar as recorrências

if($quant_recorrencia > 0 or $recorrencia_inf == 'Sim'){


	//excluir as recorrencia se já existir
	if($id > 0){
		$ultimo_id = $id;
		$query = $pdo->query("SELECT * from $tabela where id_recorrencia = '$id' and pago != 'Sim'");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$total_reg = @count($res);
		if($total_reg > 0){
			for($i=0; $i < $total_reg; $i++){
				$hash = @$res[$i]['hash'];
				$id_conta = @$res[$i]['id'];

				$pdo->query("DELETE FROM $tabela WHERE id = '$id_conta'");

			}
		}
		
	}

	if($recorrencia_inf == 'Sim'){
		$qtd_parcelas = '120';
	}else{
		$qtd_parcelas = $quant_recorrencia;
	}


	$dias_frequencia = $frequencia;
	$data_venc = $_POST['vencimento'];

	for($i=2; $i <= $qtd_parcelas; $i++){

		$nova_descricao = $descricao;
		$novo_valor = $valor;
		$dias_parcela = $i - 1;
		$dias_parcela_2 = ($i - 1) * $dias_frequencia;

		
			if($dias_frequencia == 30 || $dias_frequencia == 31){
				
				$novo_vencimento = date('Y/m/d', strtotime("+$dias_parcela month",strtotime($data_venc)));

			}else if($dias_frequencia == 90){ 
				$dias_parcela = $dias_parcela * 3;
				$novo_vencimento = date('Y/m/d', strtotime("+$dias_parcela month",strtotime($data_venc)));

			}else if($dias_frequencia == 180){ 

				$dias_parcela = $dias_parcela * 6;
				$novo_vencimento = date('Y/m/d', strtotime("+$dias_parcela month",strtotime($data_venc)));

			}else if($dias_frequencia == 360 || $dias_frequencia == 365){ 

				$dias_parcela = $dias_parcela * 12;
				$novo_vencimento = date('Y/m/d', strtotime("+$dias_parcela month",strtotime($data_venc)));

			}else{
				
				$novo_vencimento = date('Y/m/d', strtotime("+$dias_parcela_2 days",strtotime($data_venc)));
			}
			
					


		$query = $pdo->prepare("INSERT INTO $tabela SET descricao = :descricao, fornecedor = :fornecedor, funcionario = :funcionario, valor = :valor, vencimento = '$novo_vencimento', data_lanc = curDate(), forma_pgto = '$forma_pgto', frequencia = '0', obs = :obs, arquivo = '$foto', subtotal = :valor, usuario_lanc = '$id_usuario', pago = 'Não', referencia = 'Conta', caixa = '$id_caixa', hora = curTime(), usuario_receb = :usuario_receb, id_recorrencia = '$ultimo_id', hora_alerta = '$hora_random' ");

		$query->bindValue(":descricao", "$descricao");
		$query->bindValue(":fornecedor", "$fornecedor");
		$query->bindValue(":funcionario", "$funcionario");
		$query->bindValue(":valor", "$valor");
		$query->bindValue(":obs", "$obs");
		$query->bindValue(":usuario_receb", "$usuario_receb");
		$query->execute();
		$ultimo_id_rec = $pdo->lastInsertId();




	}

}
echo 'Salvo com Sucesso';
 ?>