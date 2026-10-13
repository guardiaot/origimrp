<?php 
require_once("../conexao.php");

$tipo_acao = filter_var(@$_POST['tipo_acao'], @FILTER_SANITIZE_STRING);
$descricao = filter_var(@$_POST['descricao'], @FILTER_SANITIZE_STRING);
$partes = filter_var(@$_POST['partes'], @FILTER_SANITIZE_STRING);
$processo_existente = filter_var(@$_POST['processo_existente'], @FILTER_SANITIZE_STRING);
$objetivo = filter_var(@$_POST['objetivo'], @FILTER_SANITIZE_STRING);
$cpf = filter_var(@$_POST['cpf'], @FILTER_SANITIZE_STRING);
$telefone = filter_var(@$_POST['telefone'], @FILTER_SANITIZE_STRING);
$nome = filter_var(@$_POST['nome'], @FILTER_SANITIZE_STRING);
$advogado = filter_var(@$_POST['advogado'], @FILTER_SANITIZE_STRING);

//ATUALIZAÇÃO DE DADOS DO CLIENTE
$query = $pdo->prepare("SELECT * from clientes where cpf = :cpf");
	$query->bindValue(":cpf", "$cpf");
	$query->execute();
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res) > 0){
		$id_cliente = $res[0]['id'];
		$query2 = $pdo->prepare("UPDATE clientes SET nome = :nome, telefone = :telefone where cpf = :cpf");
	}else{
		$query2 = $pdo->prepare("INSERT INTO clientes SET nome = :nome, telefone = :telefone, cpf = :cpf, data_cad = curDate()");
	}

	$query2->bindValue(":cpf", "$cpf");
	$query2->bindValue(":nome", "$nome");
	$query2->bindValue(":telefone", "$telefone");
	$query2->execute();

	if(@count($res) == 0){
		$id_cliente = $pdo->lastInsertId();
	}



//SALVAR AS INFORMAÇÕES NA TABELA DOS CASOS
$query = $pdo->prepare("INSERT INTO casos SET tipo_acao = :tipo_acao, descricao = :descricao, partes = :partes, processo_existente = :processo_existente, objetivo = :objetivo, id_cliente = :id_cliente, id_advogado = :id_advogado, status = :status, data = curDate()");

$query->bindValue(":tipo_acao", "$tipo_acao");
$query->bindValue(":descricao", "$descricao");
$query->bindValue(":partes", "$partes");
$query->bindValue(":processo_existente", "$processo_existente");
$query->bindValue(":objetivo", "$objetivo");
$query->bindValue(":id_cliente", "$id_cliente");
$query->bindValue(":id_advogado", "$advogado");
$query->bindValue(":status", "Pendente");

$query->execute();

echo 'Salvo com Sucesso';


$query = $pdo->prepare("SELECT * from usuarios where id = :advogado");
	$query->bindValue(":advogado", "$advogado");
	$query->execute();
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res) > 0){
		$telefone_advogado = $res[0]['telefone'];	
		$nome_advogado = $res[0]['nome'];
	}else{
		$telefone_advogado = "";
	}

//enviar uma notificação de mensagem

//enviar cliente
$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
$mensagem_whatsapp = '*'.$nome_sistema.'*%0A';
$mensagem_whatsapp .= '⚖️ _Olá *'.$nome.'*, recebemos sua solicitação!_ %0A%0A';
$mensagem_whatsapp .= '📝 Seu caso foi registrado com sucesso em nosso sistema e será analisado pela equipe jurídica em breve.%0A%0A';
$mensagem_whatsapp .= '📌 Assim que houver retorno ou orientação, entraremos em contato com você!.%0A%0A';
$mensagem_whatsapp .= '🙏 Agradecemos a confiança em nossos serviços.%0A';
$mensagem_whatsapp .= '*Equipe Jurídica '.$nome_sistema.'*';
require('../painel/apis/texto.php');


//enviar advogado
if($telefone_advogado != ""){
$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_advogado);
$mensagem_whatsapp = '*'.$nome_sistema.'*%0A';
$mensagem_whatsapp .= '⚖️ _Olá '.$nome_advogado.', um de seus clientes enviou uma solicitação de novo caso!_ %0A%0A';
$mensagem_whatsapp .= 'Cliente: *'.$nome.'*%0A';
$mensagem_whatsapp .= 'Telefone: *'.$telefone.'*%0A%0A';
$mensagem_whatsapp .= 'Descrição do Caso %0A';
$mensagem_whatsapp .= $descricao;
require('../painel/apis/texto.php');
}


$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_sistema);
$mensagem_whatsapp = '*'.$nome_sistema.'*%0A%0A';
$mensagem_whatsapp .= '*Solicitação de novo caso* %0A';
$mensagem_whatsapp .= 'Cliente: *'.$nome.'*%0A';
$mensagem_whatsapp .= 'Telefone: *'.$telefone.'*%0A%0A';
$mensagem_whatsapp .= 'Descrição do Caso %0A';
$mensagem_whatsapp .= $descricao;
require('../painel/apis/texto.php');

?>