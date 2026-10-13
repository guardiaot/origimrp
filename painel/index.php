<?php 
@session_start();
require_once("../conexao.php");
require_once("verificar.php");


$data_atual = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$data_inicio_mes = $ano_atual."-".$mes_atual."-01";
$data_inicio_ano = $ano_atual."-01-01";
$data_final_ano = $ano_atual."-12-31";

$data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($data_atual)));
$data_amanha = date('Y-m-d', strtotime("+1 days",strtotime($data_atual)));


if($mes_atual == '04' || $mes_atual == '06' || $mes_atual == '09' || $mes_atual == '11'){
$data_final_mes = $ano_atual.'-'.$mes_atual.'-30';
}else if($mes_atual == '02'){
$bissexto = date('L', @mktime(0, 0, 0, 1, 1, $ano_atual));
if($bissexto == 1){
$data_final_mes = $ano_atual.'-'.$mes_atual.'-29';
}else{
$data_final_mes = $ano_atual.'-'.$mes_atual.'-28';
}

}else{
$data_final_mes = $ano_atual.'-'.$mes_atual.'-31';
}



$pag_inicial = 'home';
if(@$_SESSION['nivel'] != 'Administrador' and @$_SESSION['nivel'] != 'Escritório'){
require_once("verificar_permissoes.php");
}

if(@$_GET['pagina'] != ""){
$pagina = @$_GET['pagina'];
}else{
$pagina = $pag_inicial;
}

$id_usuario = @$_SESSION['id'];
$query = $pdo->query("SELECT * from usuarios where id = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
$nome_usuario = $res[0]['nome'];
$email_usuario = $res[0]['email'];
$telefone_usuario = $res[0]['telefone'];
$especialidade_usuario = $res[0]['especialidade'];
$nacionalidade = $res[0]['nacionalidade'];
$estado_civil = $res[0]['estado_civil'];
$endereco_profissional = $res[0]['endereco_profissional'];
$seccional_oab = $res[0]['seccional_oab'];
$numero_oab = $res[0]['numero_oab'];

$nivel_usuario = $res[0]['nivel'];
$foto_usuario = $res[0]['foto'];
$endereco_usuario = $res[0]['endereco'];
$mostrar_registros = $res[0]['mostrar_registros'];
$assinatura_usuario = $res[0]['assinatura'];
}else{
echo '<script>window.location="../login.php"</script>';
exit();
}


if(@$_SESSION['nivel'] == 'Administrador' or @$_SESSION['nivel'] == 'Escritório'){
$mostrar_registros = 'Sim';
}

//verificar caixa aberto
$query1 = $pdo->query("SELECT * from caixas where operador = '$id_usuario' and data_fechamento is null order by id desc limit 1");
$res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
if(@count($res1) > 0){
$texto_caixa = '<small><span style="color:green"> (Aberto)</span></small>';
}else{
$texto_caixa = '<small><span style="color:red"> (Fechado)</span></small>';
}


//criar um cadastro de prompt para casos se não exitir
$query = $pdo->query("SELECT * from prompts");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if(@count($res) == 0){  
$texto_caso_index = "Você é um advogado especialista em orientar processos jurídicos. Analise o caso abaixo e sugira os melhores caminhos que o advogado pode tomar, considerando os dados fornecidos:

📌 Tipo de Ação: {tipo_acao}
📝 Descrição do Caso: {descricao}
👥 Partes Envolvidas: {partes}
📂 Processo Existente: {processo_existente}
🎯 Objetivo do Cliente: {objetivo}

Retorne um parecer jurídico com sugestões de próximos passos, não precisa colocar título Parecer Jurídico.";
$query = $pdo->query("INSERT INTO prompts SET nome = 'Caso Resumido', texto = '$texto_caso_index'");
}

?>
<!DOCTYPE HTML>
<html lang="pt-BR" dir="ltr">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>


<meta charset="UTF-8">
<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="Description" content="Desenvolvimento de Sistemas Comerciais">
<meta name="Author" content="Origem">
<meta name="Keywords" content="cursos Origem, sistemas Origem, sistema web, desenvolvimento web"/>

<title><?php echo $nome_sistema ?></title>

<link rel="icon" href="../img/icone.png" type="image/x-icon"/>
<link href="../assets/css/icons.css" rel="stylesheet"/>
<link id="style" href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
<link href="../assets/css/style.css" rel="stylesheet">
<link href="../assets/css/style-dark.css" rel="stylesheet">
<link href="../assets/css/style-transparent.css" rel="stylesheet">
<link href="../assets/css/skin-modes.css" rel="stylesheet" />
<link href="../assets/css/animate.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="../assets/css/custom.css" rel="stylesheet" />
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/modernizr.custom.js"></script>
<link rel="stylesheet" href="css/estilos_home.css"/>


<link rel="stylesheet" href="css/monthly.css"/>


<!-- Trumbowyg  -->
<link rel="stylesheet" href="../dist/ui/trumbowyg.min.css"/>
<link rel="stylesheet" href="../dist/plugins/emoji/ui/trumbowyg.emoji.min.css"/>
<link rel="stylesheet" href="../dist/plugins/colors/ui/trumbowyg.colors.min.css"/>



</head>

<body class="ltr main-body app sidebar-mini">

<?php if($mostrar_preloader == 'Sim'){ ?>
<!-- GLOBAL-LOADER -->
<div id="global-loader">
<img src="../img/loader.gif" class="loader-img loader loader_mobile" alt="">
</div>
<!-- /GLOBAL-LOADER -->
<?php } ?>

<!-- Page -->
<div class="page">

<div>
<!-- APP-HEADER1 -->
<div class="main-header side-header sticky nav nav-item">
	<div class=" main-container container-fluid">
		<div class="main-header-left ">
			<div class="responsive-logo">
				<a href="index.php" class="header-logo">
					<img src="../img/foto-painel.png" class="mobile-logo logo-1" alt="logo" style="width:40% !important; margin-left: -120px !important">
					<img src="../img/foto-painel.png" class="mobile-logo dark-logo-1" alt="logo" style="width:40% !important; margin-left: 50px !important">
				</a>
			</div>
			<div class="app-sidebar__toggle" data-bs-toggle="sidebar">
				<a class="open-toggle" href="javascript:void(0);"><i class="header-icon fe fe-align-left" ></i></a>
				<a class="close-toggle" href="javascript:void(0);"><i class="header-icon fe fe-x"></i></a>
			</div>
			<div class="logo-horizontal">
				<a href="index.php" class="header-logo">
					<img src="../img/foto-painel.png" class="mobile-logo logo-1" alt="logo">
					<img src="../img/foto-painel.png" class="mobile-logo dark-logo-1" alt="logo">
				</a>
			</div>
			<div class="main-header-center ms-4 d-sm-none d-md-none d-lg-block form-group">

			</div>
		</div>
		<div class="main-header-right">

			<div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
				<div class="" id="navbarSupportedContent-4">
					<ul class="nav nav-item header-icons navbar-nav-right ms-auto">

						<li class="dropdown nav-item esc" >
							<form method="post" action="paginas/processos/api/exec_api.php" target="_blank">
								<input required="" id="numero_processo_index" name="numero" type="text" placeholder="Buscar Processo da Api DataJus" 
								style="width: 250px; padding: 8px 35px 8px 10px; border: none; border-bottom: 2px solid #FFF; 
								background: transparent; outline: none; font-size: 12px; color:#FFF">

								<input type="hidden" name="acao" value="consulta">
								<button type="submit" style="background: transparent; border: none;">
									<i class="fa fa-search" 
									style="font-size:15px; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #FFF; cursor: pointer;"></i>
								</button>
							</form>
						</li>



						<li class="dropdown nav-item ocultar_mobile">
							<a class="new nav-link theme-layout nav-link-bg layout-setting" id="btn_tema">
								<span class="dark-layout"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24"><path d="M20.742 13.045a8.088 8.088 0 0 1-2.077.271c-2.135 0-4.14-.83-5.646-2.336a8.025 8.025 0 0 1-2.064-7.723A1 1 0 0 0 9.73 2.034a10.014 10.014 0 0 0-4.489 2.582c-3.898 3.898-3.898 10.243 0 14.143a9.937 9.937 0 0 0 7.072 2.93 9.93 9.93 0 0 0 7.07-2.929 10.007 10.007 0 0 0 2.583-4.491 1.001 1.001 0 0 0-1.224-1.224zm-2.772 4.301a7.947 7.947 0 0 1-5.656 2.343 7.953 7.953 0 0 1-5.658-2.344c-3.118-3.119-3.118-8.195 0-11.314a7.923 7.923 0 0 1 2.06-1.483 10.027 10.027 0 0 0 2.89 7.848 9.972 9.972 0 0 0 7.848 2.891 8.036 8.036 0 0 1-1.484 2.059z"/></svg></span>
								<span class="light-layout"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24"><path d="M6.993 12c0 2.761 2.246 5.007 5.007 5.007s5.007-2.246 5.007-5.007S14.761 6.993 12 6.993 6.993 9.239 6.993 12zM12 8.993c1.658 0 3.007 1.349 3.007 3.007S13.658 15.007 12 15.007 8.993 13.658 8.993 12 10.342 8.993 12 8.993zM10.998 19h2v3h-2zm0-17h2v3h-2zm-9 9h3v2h-3zm17 0h3v2h-3zM4.219 18.363l2.12-2.122 1.415 1.414-2.12 2.122zM16.24 6.344l2.122-2.122 1.414 1.414-2.122 2.122zM6.342 7.759 4.22 5.637l1.415-1.414 2.12 2.122zm13.434 10.605-1.414 1.414-2.122-2.122 1.414-1.414z"/></svg></span>
							</a>
						</li>


						<?php 
						if($mostrar_registros == 'Não'){
							$query = $pdo->query("SELECT * from receber where vencimento < curDate() and pago != 'Sim' and usuario_receb = '$id_usuario' and (financeiro is null or mostrar = 'Sim') order by id asc");
						}else{
							$query = $pdo->query("SELECT * from receber where vencimento < curDate() and pago != 'Sim' and (financeiro is null or mostrar = 'Sim') order by id asc");
						}
						$res = $query->fetchAll(PDO::FETCH_ASSOC);
						$linhas = @count($res);
						?>

						<li class="dropdown nav-item  main-header-message <?php echo $receber ?>">
							<a class="new nav-link"  data-bs-toggle="dropdown" href="javascript:void(0);">
								<small><i class="fa fa-dollar" style="color:#d4fae1;"></i></small>
								<span class="badge  header-badge" style="background:green"><?php echo $linhas ?></span>
							</a>



							<div class="dropdown-menu">
								<div class="menu-header-content text-start border-bottom">
									<div class="d-flex">
										<h6 class="dropdown-title mb-1 tx-15 font-weight-semibold">Contas à Receber</h6>

									</div>
									<p class="dropdown-title-text subtext mb-0 op-6 pb-0 tx-12 "><?php echo $linhas ?> Contas Vencidas!</p>
								</div>
								<div class="main-message-list chat-scroll">

									<?php 
									if($mostrar_registros == 'Não'){
										$query = $pdo->query("SELECT * from receber where vencimento < curDate() and pago != 'Sim' and usuario_receb = '$id_usuario' and (financeiro is null or mostrar = 'Sim') order by id asc");
									}else{
										$query = $pdo->query("SELECT * from receber where vencimento < curDate() and pago != 'Sim' and (financeiro is null or mostrar = 'Sim') order by id asc");
									}
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$linhas = @count($res);
									for($i=0; $i<$linhas; $i++){
										$valor = $res[$i]['valor'];
										$vencimento = $res[$i]['vencimento'];
										$valorF = @number_format($valor, 2, ',', '.');  
										$vencimentoF = implode('/', array_reverse(@explode('-', $vencimento)));                                 
										?>

										<a href="receber" class="dropdown-item d-flex border-bottom">

											<div class="wd-90p">
												<div class="d-flex">
													<h5 class="mb-0 name" style="color:green">R$ <?php echo $valorF ?></h5>
												</div>
												<p class="mb-0 desc"><?php echo $res[$i]['descricao'] ?> </p>
												<p class="time mb-0 text-start float-start ms-2"><?php echo $vencimentoF ?> </p>
											</div>
										</a>

									<?php } ?>


								</div>
								<div class="text-center dropdown-footer">
									<a class="btn btn-primary btn-sm btn-block text-center"  href="receber">Ver Todas</a>
								</div>
							</div>
						</li>




						<?php 
						if($mostrar_registros == 'Não'){
							$query = $pdo->query("SELECT * from pagar where vencimento < curDate() and pago != 'Sim' and usuario_receb = '$id_usuario' and financeiro is null order by id asc");
						}else{
							$query = $pdo->query("SELECT * from pagar where vencimento < curDate() and pago != 'Sim' order by id asc");
						}
						$res = $query->fetchAll(PDO::FETCH_ASSOC);
						$linhas = @count($res);
						?>

						<li class="dropdown nav-item  main-header-message <?php echo $pagar ?>">
							<a class="new nav-link"  data-bs-toggle="dropdown" href="javascript:void(0);">
								<small><i class="fa fa-dollar" style="color:#fff3f2;"></i></small>
								<span class="badge  header-badge" style="background:red"><?php echo $linhas ?></span>
							</a>



							<div class="dropdown-menu">
								<div class="menu-header-content text-start border-bottom">
									<div class="d-flex">
										<h6 class="dropdown-title mb-1 tx-15 font-weight-semibold">Contas à Pagar</h6>

									</div>
									<p class="dropdown-title-text subtext mb-0 op-6 pb-0 tx-12 "><?php echo $linhas ?> Contas Vencidas!</p>
								</div>

								<div class="main-message-list Notification-scroll">

									<?php 
									if($mostrar_registros == 'Não'){
										$query = $pdo->query("SELECT * from pagar where vencimento < curDate() and pago != 'Sim' and usuario_receb = '$id_usuario' and financeiro is null order by id asc");
									}else{
										$query = $pdo->query("SELECT * from pagar where vencimento < curDate() and pago != 'Sim' order by id asc");
									}
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$linhas = @count($res);
									for($i=0; $i<$linhas; $i++){
										$valor = $res[$i]['valor'];
										$valorF = @number_format($valor, 2, ',', '.');
										$vencimentoF = implode('/', array_reverse(@explode('-', $vencimento)));                                             
										?>

										<a href="pagar" class="dropdown-item d-flex border-bottom">

											<div class="wd-90p">
												<div class="d-flex">
													<h5 class="mb-0 name" style="color:red">R$ <?php echo $valorF ?></h5>
												</div>
												<p class="mb-0 desc"><?php echo $res[$i]['descricao'] ?> </p>
												<p class="time mb-0 text-start float-start ms-2"><?php echo $vencimentoF ?> </p>
											</div>
										</a>

									<?php } ?>


								</div>
								<div class="text-center dropdown-footer">
									<a class="btn btn-primary btn-sm btn-block text-center"  href="pagar">Ver Todas</a>
								</div>
							</div>
						</li>



						<li class="nav-item full-screen fullscreen-button">
							<a class="new nav-link full-screen-link" href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24"><path d="M5 5h5V3H3v7h2zm5 14H5v-5H3v7h7zm11-5h-2v5h-5v2h7zm-2-4h2V3h-7v2h5z"/></svg></a>
						</li>

						<li class="dropdown main-profile-menu nav nav-item nav-link ps-lg-2">
							<a class="new nav-link profile-user d-flex" href="#" data-bs-toggle="dropdown"><img src="images/perfil/<?php echo $foto_usuario ?>"></a>
							<div class="dropdown-menu">
								<div class="menu-header-content p-3 border-bottom">
									<div class="d-flex wd-100p">
										<div class="main-img-user"><img src="images/perfil/<?php echo $foto_usuario ?>"></div>
										<div class="ms-3 my-auto">
											<h6 class="tx-15 font-weight-semibold mb-0"><?php echo $nome_usuario ?></h6><span class="dropdown-title-text subtext op-6  tx-12"><?php echo $nivel_usuario ?></span>
										</div>
									</div>
								</div>
								<a class="dropdown-item" href="" data-bs-target="#modalPerfil" data-bs-toggle="modal"><i class="fa fa-user"></i>Perfil</a>  
								<span class="<?php echo $configuracoes ?>"><a class="dropdown-item " href="" data-bs-target="#modalConfig" data-bs-toggle="modal"><i class="fa fa-cogs "></i>  Configurações</a>
								</span>

								<a class="dropdown-item" href="logout.php"><i class="fa fa-arrow-left"></i> Sair</a>
							</div>
						</li>
					</ul>
				</div>
			</div>

		</div>
	</div>
</div>              

<!--APP-SIDEBAR-->
<div class="sticky">
<aside class="app-sidebar">
	<div class="main-sidebar-header active">
		<a class="header-logo active" href="index.php">
			<img src="../img/foto-painel.png" class="main-logo  desktop-logo" alt="logo">
			<img src="../img/foto-painel.png" class="main-logo  desktop-dark" alt="logo">
			<img src="../img/icone.png" class="main-logo  mobile-logo" alt="logo">
			<img src="../img/icone.png" class="main-logo  mobile-dark" alt="logo">
		</a>
	</div>


<div class="main-sidemenu">
<div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/></svg></div>
<ul class="side-menu">

<li class="slide <?php echo @$home ?>">
<a class="side-menu__item" href="index.php">
<i class="fa fa-home text-white"></i>
<span class="side-menu__label" style="margin-left: 15px">Dashboard</span></a>
</li>


<li class="slide <?php echo @$menu_pessoas ?>">
<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
<i class="fa fa-users text-white mt-1"></i>
<span class="side-menu__label" style="margin-left: 15px">Pessoas</span><i class="angle fe fe-chevron-right"></i></a>
<ul class="slide-menu">

<li class="<?php echo @$clientes ?>"><a class="slide-item" href="clientes"> Clientes</a></li>
<li class="<?php echo @$usuarios ?>"><a class="slide-item" href="usuarios"> Usuários</a></li>
<li class="<?php echo @$funcionarios ?>"><a class="slide-item" href="funcionarios"> Profissionais</a></li>
<li class="<?php echo @$fornecedores ?>"><a class="slide-item" href="fornecedores"> Fornecedores</a></li>

</ul>
</li>



<li class="slide <?php echo @$menu_cadastros ?>">
<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
<i class="fa fa-plus text-white mt-1"></i>
<span class="side-menu__label" style="margin-left: 15px">Cadastros</span><i class="angle fe fe-chevron-right"></i></a>
<ul class="slide-menu">

<li class="<?php echo @$prompts ?>"><a class="slide-item" href="prompts"> Prompts</a></li>

<li class="<?php echo @$formas_pgto ?>"><a class="slide-item" href="formas_pgto"> Formas Pgto</a></li>

<li class="<?php echo @$frequencias ?> "><a class="slide-item" href="frequencias"> Frequências</a></li>

<li class="<?php echo @$cargos ?> "><a class="slide-item" href="cargos"> Cargos</a></li>

<li class="<?php echo @$tipos_servicos ?> "><a class="slide-item" href="tipos_servicos"> Serviços Prestados</a></li>

<li class="<?php echo @$contratos ?> "><a class="slide-item" href="contratos"> Modelos de Contratos</a></li>


<?php if($alterar_acessos == 'Sim'){ ?>
<li class="<?php echo @$grupo_acessos ?> "><a class="slide-item" href="grupo_acessos"> Grupos</a></li>

<li class="<?php echo @$acessos ?> "><a class="slide-item" href="acessos"> Acessos</a></li>
<?php } ?>

</ul>
</li>




<li class="slide <?php echo @$menu_financeiro ?>">
<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
<i class="fa fa-dollar text-white mt-1"></i>
<span class="side-menu__label" style="margin-left: 15px">Financeiro</span><i class="angle fe fe-chevron-right"></i></a>
<ul class="slide-menu">

<li class="<?php echo @$receber ?> "><a class="slide-item" href="receber"> Receber</a></li>

<li class="<?php echo @$pagar ?> "><a class="slide-item" href="pagar"> Despesas</a></li>

<li class="<?php echo @$rel_financeiro ?> ocultar_mobile_app"><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelFin"> Relatório Financeiro</a></li>


<li class="<?php echo @$rel_sintetico_despesas ?> ocultar_mobile_app"><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelSinDesp"> Rel Sintético Despesas</a></li>

<li class="<?php echo @$rel_sintetico_receber ?> ocultar_mobile_app"><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelSinRec"> Rel Sintético Receber</a></li>


<li class="<?php echo @$rel_balanco ?> ocultar_mobile_app"><a class="slide-item" href="rel/balanco_anual_class.php" target="_blank"> Rel Balanço Anual</a></li>


<li class="<?php echo @$rel_inadimplementes ?> ocultar_mobile_app"><a class="slide-item" href="rel/sintetico_inadimplentes_class.php" target="_blank"> Rel Inadimplementes</a></li>


<li class="<?php echo @$rel_ranking ?> ocultar_mobile_app"><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelSinRanking"> Rel Sintético Ranking</a></li>

</ul>
</li>


<li class="slide <?php echo @$anotacoes ?>">
<a class="side-menu__item" href="anotacoes">
<i class="fa fa-file text-white"></i>
<span class="side-menu__label" style="margin-left: 15px">Anotações</span></a>
</li>





<li class="slide <?php echo @$caixas ?>">
<a class="side-menu__item" href="caixas">
<i class="fa fa-briefcase text-white"></i>
<span class="side-menu__label" style="margin-left: 15px">Caixas  <?php echo $texto_caixa ?></span></a>
</li>


<li class="slide <?php echo @$tarefas ?>">
<a class="side-menu__item" href="tarefas">
<i class="fa fa-calendar text-white"></i>
<span class="side-menu__label" style="margin-left: 15px">Tarefas / Agenda</span></a>
</li>


<li class="slide <?php echo @$abertura_contratos ?>">
<a class="side-menu__item" href="abertura_contratos">
<i class="fa fa-file-text text-white"></i>
<span class="side-menu__label" style="margin-left: 15px">Abertura de Contratos</span></a>
</li>


<li class="slide <?php echo @$rel_contratos ?>">
<a class="side-menu__item" href="rel_contratos">
<i class="fa fa-file-text text-white"></i>
<span class="side-menu__label" style="margin-left: 15px">Gerar Contrato</span></a>
</li>


<li class="slide <?php echo @$processos ?>">
<a class="side-menu__item" href="processos">
<i class="fa fa-anchor text-white"></i>
<span class="side-menu__label" style="margin-left: 15px">Processos</span></a>
</li>


<li class="slide <?php echo @$processos_andamento ?>">
<a class="side-menu__item" href="processos_andamento">
<i class="fa fa-folder-open-o text-white"></i>
<span class="side-menu__label" style="margin-left: 15px">Processos Andamento</span></a>
</li>



<li class="slide <?php echo @$casos ?>">
<a class="side-menu__item" href="casos">
<i class="fa fa-file-pdf-o text-white"></i>
<span class="side-menu__label" style="margin-left: 15px">Casos Solicitados</span></a>
</li>




<li class="slide <?php echo @$menu_audiencias ?>">
<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
<i class="fa fa-calendar-o text-white mt-1"></i>
<span class="side-menu__label" style="margin-left: 15px">Audiências</span><i class="angle fe fe-chevron-right"></i></a>
<ul class="slide-menu">

<li class="<?php echo @$audiencias ?>"><a class="slide-item" href="audiencias"> Audiências</a></li>
<li class="<?php echo @$rel_audiencias ?> ocultar_mobile_app"><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelAudiencias"> Relatório Audiências</a></li>


</ul>
</li>


<li class="slide <?php echo @$pericias ?>">
<a class="side-menu__item" href="pericias">
<i class="fa fa-file text-white"></i>
<span class="side-menu__label" style="margin-left: 15px">Perícias</span></a>
</li>




<li class="slide <?php echo @$menu_beneficios ?>">
<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
<i class="fa fa-file-text-o text-white mt-1"></i>
<span class="side-menu__label" style="margin-left: 15px">Benefícios / DCB</span><i class="angle fe fe-chevron-right"></i></a>
<ul class="slide-menu">

<li class="<?php echo @$beneficios ?>"><a class="slide-item" href="beneficios">Lançar Benefícios</a></li>

<li class="<?php echo @$beneficios_previstos ?>"><a class="slide-item" href="beneficios_previstos">DCB Previstas Mês</a></li>

<li class="<?php echo @$rel_beneficios ?> ocultar_mobile_app"><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelBeneficios"> Relatório Benefícios</a></li>


</ul>
</li>




<li class="slide <?php echo @$site ?>">
<a class="side-menu__item" href="site">
<i class="fa fa-globe text-white"></i>
<span class="side-menu__label" style="margin-left: 15px">Site</span></a>
</li>


<?php if($api_whatsapp == 'menuia'){ ?>
<li class="slide <?php echo @$dispositivos ?>">
<a class="side-menu__item" href="dispositivos">
<i class="fa fa-mobile text-white"></i>
<span class="side-menu__label" style="margin-left: 15px">Conectar Dispositivo</span></a>
</li>
<?php } ?>


<li class="slide <?php echo @$rh ?>">
<a class="side-menu__item" href="rh">
<i class="fa fa-calendar text-white"></i>
<span class="side-menu__label" style="margin-left: 15px">RH</span></a>
</li>



<li class="slide <?php echo @$marketing ?>">
<a class="side-menu__item" href="marketing">
<i class="fa fa-comment-o text-white"></i>
<span class="side-menu__label" style="margin-left: 15px">Marketing</span></a>
</li>


</ul>
<div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/></svg></div>
</div>



</aside>
</div>              <!--/APP-SIDEBAR-->
</div>

<!-- MAIN-CONTENT -->
<div class="main-content app-content">

<!-- container -->
<div class="main-container container-fluid ">

<?php 
//Classe para ocultar mobile
if($ocultar_mobile == 'Sim'){ ?>
<style type="text/css">
@media only screen and (max-width: 700px) {
.ocultar_mobile_app{
display:none; 
}
}
</style>
<?php } ?>


<?php 
echo "<script>localStorage.setItem('pagina', '$pagina')</script>";
require_once('paginas/'.$pagina.'.php');
?>              


</div>
<!-- Container closed -->
</div>
<!-- MAIN-CONTENT CLOSED -->









<!-- FOOTER -->
<div class="main-footer">
	<div class="container-fluid pt-0 ht-100p">
		Copyright © <?php echo date('Y'); ?> <a href="javascript:void(0);" class="text-primary">Origem</a>. Todos os direitos reservados
	</div>
</div>          <!-- FOOTER END -->

</div>
<!-- End Page -->

<!-- BUYNOW-MODAL -->



<a href="#top" id="back-to-top"><i class="las la-arrow-up"></i></a>


<!-- GRAFICOS -->
<script src="../assets/plugins/chart.js/Chart.bundle.min.js"></script>      
<script src="../assets/js/apexcharts.js"></script>

<!--INTERNAL  INDEX JS -->
<script src="../assets/js/index.js"></script>



<script src="../assets/plugins/jquery/jquery.min.js"></script>
<script src="../assets/plugins/bootstrap/js/popper.min.js"></script>
<script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="../assets/plugins/moment/moment.js"></script>
<script src="../assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="../assets/plugins/perfect-scrollbar/p-scroll.js"></script>
<script src="../assets/js/eva-icons.min.js"></script>
<script src="../assets/plugins/side-menu/sidemenu.js"></script>
<script src="../assets/js/sticky.js"></script>
<script src="../assets/plugins/sidebar/sidebar.js"></script>
<script src="../assets/plugins/sidebar/sidebar-custom.js"></script>


<!-- INTERNAL DATA TABLES -->
<script src="../assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="../assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
<script src="../assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
<script src="../assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
<script src="../assets/plugins/datatable/js/jszip.min.js"></script>
<script src="../assets/plugins/datatable/pdfmake/pdfmake.min.js"></script>
<script src="../assets/plugins/datatable/pdfmake/vfs_fonts.js"></script>
<script src="../assets/plugins/datatable/js/buttons.html5.min.js"></script>
<script src="../assets/plugins/datatable/js/buttons.print.min.js"></script>
<script src="../assets/plugins/datatable/js/buttons.colVis.min.js"></script>
<script src="../assets/plugins/datatable/dataTables.responsive.min.js"></script>
<script src="../assets/plugins/datatable/responsive.bootstrap5.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<!-- POPOVER JS -->
<script src="../assets/js/popover.js"></script>

<script src="../assets/js/themecolor.js"></script>
<script src="../assets/js/custom.js"></script>      

<!--INTERNAL  INDEX JS -->
<script src="../assets/js/index.js"></script>




<!-- SweetAlert JS -->
<script src="js/sweetalert2.all.min.js"></script>
<script src="js/sweetalert1.min.css"></script>
<script src="js/alertas.js"></script>

<!-- Alertas -->
<script src="../assets/plugins/sweet-alert/sweetalert.min.js"></script>
<script src="../assets/plugins/sweet-alert/jquery.sweet-alert.js"></script>


</body>

</html>


<!-- Modal Perfil -->
<div class="modal fade" id="modalPerfil" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title">Alterar Dados</h4>
				<button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>

			<form id="form-perfil">
				<div class="modal-body">


					<div class="row">
						<div class="col-md-6 mb-3">                         
							<label>Nome</label>
							<input type="text" class="form-control" id="nome_perfil" name="nome" placeholder="Seu Nome" value="<?php echo @$nome_usuario ?>" required>                          
						</div>

						<div class="col-md-6 mb-3">                         
							<label>Email</label>
							<input type="email" class="form-control" id="email_perfil" name="email" placeholder="Seu Nome" value="<?php echo @$email_usuario ?>" required>                          
						</div>
					</div>


					<div class="row">
						<div class="col-md-4 mb-3">                         
							<label>Telefone</label>
							<input type="text" class="form-control" id="telefone_perfil" name="telefone" placeholder="Seu Telefone" value="<?php echo @$telefone_usuario ?>" required>                          
						</div>

						<div class="col-md-4 mb-3">                         
							<label>Senha</label>
							<input type="password" class="form-control" id="senha_perfil" name="senha" placeholder="Senha" value="" required>                           
						</div>

						<div class="col-md-4 mb-3">                         
							<label>Confirmar Senha</label>
							<input type="password" class="form-control" id="conf_senha_perfil" name="conf_senha" placeholder="Confirmar Senha" value="" required>                           
						</div>


					</div>


					<div class="row">       

						<div class="col-md-4 mb-2 col-6">                           
							<label>Nacionalidade</label>
							<input type="text" class="form-control" id="" name="nacionalidade" placeholder="Nacionalidade" value="<?php echo @$nacionalidade ?>">                           
						</div>

						<div class="col-md-3 mb-2">                         
							<label>Estado Civil</label>
							<select class="form-select" id="" name="estado_civil">
								<option value="Solteiro(a)" <?php if($estado_civil == 'Solteiro(a)'){ ?> selected <?php } ?>>Solteiro(a)</option>
								<option value="Casado(a)" <?php if($estado_civil == 'Casado(a)'){ ?> selected <?php } ?>>Casado(a)</option>
								<option value="Divorciado(a)" <?php if($estado_civil == 'Divorciado(a)'){ ?> selected <?php } ?>>Divorciado(a)</option>
								<option value="Viúvo(a)" <?php if($estado_civil == 'Viúvo(a)'){ ?> selected <?php } ?>>Viúvo(a)</option>
							</select>                           
						</div>


						<div class="col-md-3 mb-2">                         
							<label>Seccional OAB</label>
							<select class="form-select" id="" name="seccional_oab">
								<option value="">Selecionar</option>
								<option value="AC" <?php if($seccional_oab == 'AC'){ ?> selected <?php } ?>>AC</option>
								<option value="AL" <?php if($seccional_oab == 'AL'){ ?> selected <?php } ?>>AL</option>
								<option value="AP" <?php if($seccional_oab == 'AP'){ ?> selected <?php } ?>>AP</option>
								<option value="AM" <?php if($seccional_oab == 'AM'){ ?> selected <?php } ?>>AM</option>
								<option value="BA" <?php if($seccional_oab == 'BA'){ ?> selected <?php } ?>>BA</option>
								<option value="CE" <?php if($seccional_oab == 'CE'){ ?> selected <?php } ?>>CE</option>
								<option value="DF" <?php if($seccional_oab == 'DF'){ ?> selected <?php } ?>>DF</option>
								<option value="ES" <?php if($seccional_oab == 'ES'){ ?> selected <?php } ?>>ES</option>
								<option value="GO" <?php if($seccional_oab == 'GO'){ ?> selected <?php } ?>>GO</option>
								<option value="MA" <?php if($seccional_oab == 'MA'){ ?> selected <?php } ?>>MA</option>
								<option value="MT" <?php if($seccional_oab == 'MT'){ ?> selected <?php } ?>>MT</option>
								<option value="MS" <?php if($seccional_oab == 'MS'){ ?> selected <?php } ?>>MS</option>
								<option value="MG" <?php if($seccional_oab == 'MG'){ ?> selected <?php } ?>>MG</option>
								<option value="PA" <?php if($seccional_oab == 'PA'){ ?> selected <?php } ?>>PA</option>
								<option value="PB" <?php if($seccional_oab == 'PB'){ ?> selected <?php } ?>>PB</option> 
								<option value="PE" <?php if($seccional_oab == 'PE'){ ?> selected <?php } ?>>PE</option>
								<option value="PI" <?php if($seccional_oab == 'PI'){ ?> selected <?php } ?>>PI</option>
								<option value="PR" <?php if($seccional_oab == 'PR'){ ?> selected <?php } ?>>PR</option>
								<option value="RJ" <?php if($seccional_oab == 'RJ'){ ?> selected <?php } ?>>RJ</option>
								<option value="RN" <?php if($seccional_oab == 'RN'){ ?> selected <?php } ?>>RN</option>
								<option value="RS" <?php if($seccional_oab == 'RS'){ ?> selected <?php } ?>>RS</option>
								<option value="RO" <?php if($seccional_oab == 'RO'){ ?> selected <?php } ?>>RO</option>
								<option value="RR" <?php if($seccional_oab == 'RR'){ ?> selected <?php } ?>>RR</option>
								<option value="SC" <?php if($seccional_oab == 'SC'){ ?> selected <?php } ?>>SC</option>
								<option value="SP" <?php if($seccional_oab == 'SP'){ ?> selected <?php } ?>>SP</option>
								<option value="SE" <?php if($seccional_oab == 'SE'){ ?> selected <?php } ?>>SE</option>
								<option value="TO" <?php if($seccional_oab == 'TO'){ ?> selected <?php } ?>>TO</option>

							</select>                                               
						</div>  


						<div class="col-md-2 mb-2 col-6">                           
							<label>Número OAB</label>
							<input type="text" class="form-control" id="" name="numero_oab" placeholder="Numero OAB Ex 200.000" value="<?php echo @$numero_oab ?>">                         
						</div>



					</div>



					<div class="row">

						<div class="col-md-5 mb-2 col-6">                           
							<label>Especialidade</label>
							<input type="text" class="form-control" id="especialidade_usuario" name="especialidade" placeholder="Especialidade Advogado" value="<?php echo $especialidade_usuario ?>">                          
						</div>

						<div class="col-md-7 mb-3"> 
							<label>Endereço</label>
							<input type="text" class="form-control" id="endereco_perfil" name="endereco" placeholder="Seu Endereço" value="<?php echo @$endereco_usuario ?>" >  
						</div>
					</div>




					<div class="row">

						<div class="col-md-12">                         
							<label>Endereço Profissional</label>
							<input type="text" class="form-control" id="" name="endereco_profissional" placeholder="Seu Endereço" value="<?php echo @$endereco_profissional ?>">                            
						</div>


					</div>



					<div class="row">
						<div class="col-md-8 mb-3">                         
							<label>Foto</label>
							<input type="file" class="form-control" id="foto_perfil" name="foto" value="<?php echo @$foto_usuario ?>" onchange="carregarImgPerfil()">                           
						</div>

						<div class="col-md-4 mb-3">                             
							<img src="images/perfil/<?php echo $foto_usuario ?>"  width="80px" id="target-usu">                             

						</div>


					</div>


					<div class="row">
  <div class="col-md-8 mb-3">
    <label>Assinatura (desenhar)</label>

    <div class="d-flex align-items-center gap-3 flex-wrap">
      <!-- preview -->
     <img id="preview_assinatura_usuario"
     src="<?php echo !empty($assinatura_usuario) 
        ? 'images/assinaturas/' . $assinatura_usuario 
        : 'images/assinaturas/sem-assinatura.png'; ?>"
     style="width:220px; height:80px; object-fit:contain; border:1px dashed #ccc; border-radius:8px; background:#fff;">

      <div class="d-flex flex-column gap-2">
       <button type="button" class="btn btn-primary btn-sm" onclick="abrirModalAssinaturaUsuario()">
  Assinar
</button>

        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removerAssinaturaUsuario()">
          Remover
        </button>
      </div>
    </div>

    <!-- por enquanto: só estrutura (você vai criar o campo depois) -->
    <input type="hidden" 
       name="assinatura_usuario" 
       id="assinatura_usuario" 
       value="<?php echo htmlspecialchars($assinatura_usuario ?? ''); ?>">
  </div>
</div>


					<input type="hidden" name="id_usuario" value="<?php echo @$id_usuario ?>">


					<br>
					<small><div id="msg-perfil" align="center"></div></small>
				</div>
				<div class="modal-footer">       
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>





<!-- Modal Config -->
<div class="modal fade" id="modalConfig" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title">Alterar Configurações</h4>
				<button id="btn-fechar-config" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form id="form-config">
				<div class="modal-body">


					<div class="row mb-3">
						<div class="col-md-3">                          
							<label>Nome do Projeto</label>
							<input type="text" class="form-control" id="nome_sistema" name="nome_sistema" placeholder="Delivery Interativo" value="<?php echo @$nome_sistema ?>" required>                          
						</div>

						<div class="col-md-4">                          
							<label>Email Sistema</label>
							<input type="email" class="form-control" id="email_sistema" name="email_sistema" placeholder="Email do Sistema" value="<?php echo @$email_sistema ?>" >                         
						</div>


						<div class="col-md-3">                          
							<label>Telefone Sistema</label>
							<input type="text" class="form-control" id="telefone_sistema" name="telefone_sistema" placeholder="Telefone do Sistema" value="<?php echo @$telefone_sistema ?>" required>                          
						</div>


						<div class="col-md-2">                          
							<label>Tipo</label>
							<select name="tipo_tel" id="tipo_tel" class="form-select" onchange="mudarMascaraTel()">
								<option value="Fixo" <?php if(@$tipo_tel == 'Fixo'){ ?> selected <?php } ?>>Fixo</option>
								<option value="Celular" <?php if(@$tipo_tel == 'Celular'){ ?> selected <?php } ?>>Celular</option>

							</select>                               
						</div>

					</div>


					<div class="row mb-3">
						<div class="col-md-6">                          
							<label>Endereço <small>(Rua Número Bairro e Cidade)</small></label>
							<input type="text" class="form-control" id="endereco_sistema" name="endereco_sistema" placeholder="Rua X..." value="<?php echo @$endereco_sistema ?>" >                         
						</div>

						<div class="col-md-6">                          
							<label>Instagram</label>
							<input type="text" class="form-control" id="instagram_sistema" name="instagram_sistema" placeholder="Link do Instagram" value="<?php echo @$instagram_sistema ?>">                          
						</div>
					</div>


					<div class="row mb-3">  


						<div class="col-md-3">                          
							<label>CNPJ Sistema</label>
							<input type="text" class="form-control" id="cnpj_sistema" name="cnpj_sistema" placeholder="CNPJ" value="<?php echo @$cnpj_sistema ?>">                          
						</div>

						<div class="col-md-3">                          
							<label>Multa Atraso Conta</label>
							<input type="text" class="form-control" id="multa_atraso" name="multa_atraso" placeholder="Valor em R$" value="<?php echo @$multa_atraso ?>">                           
						</div>  

						<div class="col-md-3">                          
							<label>Júros Atraso Dia Conta</label>
							<input type="text" class="form-control" id="juros_atraso" name="juros_atraso" placeholder="Valor em %" value="<?php echo @$juros_atraso ?>">                            
						</div>  

						<div class="col-md-3">                          
							<label>Marca D'agua Rel</label>
							<select name="marca_dagua" class="form-select">
								<option value="Sim" <?php if($marca_dagua == 'Sim'){ ?> selected <?php } ?>>Sim</option>
								<option value="Não" <?php if($marca_dagua == 'Não'){ ?> selected <?php } ?>>Não</option>
							</select>                       
						</div>  


					</div>


					<div class="row mb-3">

						<div class="col-md-3">
							<label>Assinatura Recibo</label>
							<select name="assinatura_recibo" class="form-select" id="assinatura_recibo">
								<option value="Sim" <?= ($assinatura_recibo=='Sim'?'selected':'') ?>>Sim</option>
								<option value="Não" <?= ($assinatura_recibo=='Não'?'selected':'') ?>>Não</option>
							</select>
						</div>



						<div class="col-md-3">                          
							<label>Impressão Automática</label>
							<select name="impressao_automatica" class="form-select">
								<option value="Sim" <?php if($impressao_automatica == 'Sim'){ ?> selected <?php } ?>>Sim</option>
								<option value="Não" <?php if($impressao_automatica == 'Não'){ ?> selected <?php } ?>>Não</option>
							</select>                       
						</div>


						<div class="col-md-3">                          
							<label>Entrar Automáticamente</label>
							<select name="entrar_automatico" class="form-select">
								<option value="Sim" <?php if($entrar_automatico == 'Sim'){ ?> selected <?php } ?>>Sim</option>
								<option value="Não" <?php if($entrar_automatico == 'Não'){ ?> selected <?php } ?>>Não</option>
							</select>                       
						</div>

						<div class="col-md-3">                          
							<label>Mostrar PreLoader</label>
							<select name="mostrar_preloader" class="form-select">
								<option value="Sim" <?php if($mostrar_preloader == 'Sim'){ ?> selected <?php } ?>>Sim</option>
								<option value="Não" <?php if($mostrar_preloader == 'Não'){ ?> selected <?php } ?>>Não</option>
							</select>                       
						</div>
					</div>


					<div class="row mb-3">
						<div class="col-md-2" style="margin-top: -8px">
							<label>
								<div class="icones_mobile" class="dropdown" style="display: inline-block; ">
									<a title="Clique para ver as opções das apis" href="#" aria-expanded="false" aria-haspopup="true"
									data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-info-circle text-primary"></i> </a>
									<div class="dropdown-menu tx-13" style="width:500px">
										<div class="dropdown-item-text " style="width:500px; background: #c3fff0">
											<p class="mt-1">Integrei ao projeto 3 serviços de apis, estes serviços sáo terceirizados e tem
												custo adicional
												para utilização, segue abaixo site e contato dos 3. <br><br>
												<b>1 - (Menuia)</b> Diego (81)98976-9960 <br>
												<a href="https://chatbot.menuia.com/" target="_blank">https://chatbot.menuia.com/ </a>
												<br><br>
												<b>2 - (WordMensagens)</b> Bruno (44) 99986-3238 <br>
												<a href="http://wordmensagens.com.br/sistema/index.php"
												target="_blank">http://wordmensagens.com.br/sistema/index.php </a>
												<br><br>
												<b>3 - (NewTek)</b> Nando Silva (21) 99998-8666 <br>
												<a href="https://webapp.newteksoft.com.br/" target="_blank">https://webapp.newteksoft.com.br/
												</a>
												<br><br>
												<b>4 - (Evolution)</b> <br>
												<a href="" target="_blank">Aplicação da Evolution
												</a>
												<br><br>
											</p>
										</div>
									</div>
								</div>
								Api Whatsapp <a href="#" onclick="testarApi()" title="Testar disparo Api">
									<i class="fa fa-whatsapp text-verde"></i></a>
								</label>
								<select onchange="habilitarUrl()" name="api_whatsapp" id="api_whatsapp" class="form-select">                            

									<option value="Não" <?php if ($api_whatsapp == 'Não') { ?> selected <?php } ?>>Não</option>

									<option value="evolution" <?php if ($api_whatsapp == 'evolution') { ?> selected <?php } ?>>Evolution</option>

									<option value="menuia" <?php if ($api_whatsapp == 'menuia') { ?> selected <?php } ?>>Menuia</option>
									<option value="wm" <?php if ($api_whatsapp == 'wm') { ?> selected <?php } ?>>WordMensagens</option>
									<option value="newtek" <?php if ($api_whatsapp == 'newtek') { ?> selected <?php } ?>>NewTek</option>
								</select>
							</div>
							<div class="col-md-3">
								<label>Token Whatsapp (appkey)</label>
								<input type="text" class="form-control" id="token_whatsapp" name="token_whatsapp"
								placeholder="Token ou App Key" value="<?php echo @$token_whatsapp ?>">
							</div>
							<div class="col-md-4">
								<label>Instância Whatsapp (authKey)</label>
								<input type="text" class="form-control" id="instancia_whatsapp" name="instancia_whatsapp"
								placeholder="Instância ou authKey" value="<?php echo @$instancia_whatsapp ?>">
							</div>

							<div id="url_da_evo" class="col-md-3">
								<label>Url da Evolution</label>
								<input type="text" class="form-control" id="url_evolution" name="url_evolution"
								placeholder="Url da Evolution" value="<?php echo @$url_evolution ?>">
							</div>
						</div>





						<div class="row mb-3">

							<div class="col-md-3">                          
								<label>Ocultar Itens Mobile</label>
								<select name="ocultar_mobile" class="form-select">
									<option value="Sim" <?php if($ocultar_mobile == 'Sim'){ ?> selected <?php } ?>>Sim</option>
									<option value="Não" <?php if($ocultar_mobile == 'Não'){ ?> selected <?php } ?>>Não</option>
								</select>                       
							</div>



							<div class="col-md-3">                          
								<label>Alterar Acessos</label>
								<select name="alterar_acessos" class="form-select">
									<option value="Sim" <?php if($alterar_acessos == 'Sim'){ ?> selected <?php } ?>>Sim</option>
									<option value="Não" <?php if($alterar_acessos == 'Não'){ ?> selected <?php } ?>>Não</option>
								</select>                       
							</div>


							<div class="col-md-6">                          
								<label>Dados para Pagamentos (Deixar Vazio se for usar APi do Asaas)</label>
								<input type="text" class="form-control" id="dados_pagamento" name="dados_pagamento" placeholder="Vai aparecer nas cobranças se preenchido" value="<?php echo @$dados_pagamento ?>">         
							</div>





						</div>


						<div class="row">
							<div class="col-md-4">                          
								<label>Cidade (Mostrar nos Contratos)</label>
								<input type="text" class="form-control" id="cidade_sistema" name="cidade_sistema" placeholder="Vai aparecer nos contratos Gerados" value="<?php echo @$cidade_sistema ?>">          
							</div>

							<div class="col-md-3 mb-2">                         
								<label>Seccional OAB Escritório</label>
								<select class="form-select" id="" name="seccional_oab_escritorio">
									<option value="">Selecionar</option>
									<option value="AC" <?php if($seccional_oab_escritorio == 'AC'){ ?> selected <?php } ?>>AC</option>
									<option value="AL" <?php if($seccional_oab_escritorio == 'AL'){ ?> selected <?php } ?>>AL</option>
									<option value="AP" <?php if($seccional_oab_escritorio == 'AP'){ ?> selected <?php } ?>>AP</option>
									<option value="AM" <?php if($seccional_oab_escritorio == 'AM'){ ?> selected <?php } ?>>AM</option>
									<option value="BA" <?php if($seccional_oab_escritorio == 'BA'){ ?> selected <?php } ?>>BA</option>
									<option value="CE" <?php if($seccional_oab_escritorio == 'CE'){ ?> selected <?php } ?>>CE</option>
									<option value="DF" <?php if($seccional_oab_escritorio == 'DF'){ ?> selected <?php } ?>>DF</option>
									<option value="ES" <?php if($seccional_oab_escritorio == 'ES'){ ?> selected <?php } ?>>ES</option>
									<option value="GO" <?php if($seccional_oab_escritorio == 'GO'){ ?> selected <?php } ?>>GO</option>
									<option value="MA" <?php if($seccional_oab_escritorio == 'MA'){ ?> selected <?php } ?>>MA</option>
									<option value="MT" <?php if($seccional_oab_escritorio == 'MT'){ ?> selected <?php } ?>>MT</option>
									<option value="MS" <?php if($seccional_oab_escritorio == 'MS'){ ?> selected <?php } ?>>MS</option>
									<option value="MG" <?php if($seccional_oab_escritorio == 'MG'){ ?> selected <?php } ?>>MG</option>
									<option value="PA" <?php if($seccional_oab_escritorio == 'PA'){ ?> selected <?php } ?>>PA</option>
									<option value="PB" <?php if($seccional_oab_escritorio == 'PB'){ ?> selected <?php } ?>>PB</option>  
									<option value="PE" <?php if($seccional_oab_escritorio == 'PE'){ ?> selected <?php } ?>>PE</option>
									<option value="PI" <?php if($seccional_oab_escritorio == 'PI'){ ?> selected <?php } ?>>PI</option>
									<option value="PR" <?php if($seccional_oab_escritorio == 'PR'){ ?> selected <?php } ?>>PR</option>
									<option value="RJ" <?php if($seccional_oab_escritorio == 'RJ'){ ?> selected <?php } ?>>RJ</option>
									<option value="RN" <?php if($seccional_oab_escritorio == 'RN'){ ?> selected <?php } ?>>RN</option>
									<option value="RS" <?php if($seccional_oab_escritorio == 'RS'){ ?> selected <?php } ?>>RS</option>
									<option value="RO" <?php if($seccional_oab_escritorio == 'RO'){ ?> selected <?php } ?>>RO</option>
									<option value="RR" <?php if($seccional_oab_escritorio == 'RR'){ ?> selected <?php } ?>>RR</option>
									<option value="SC" <?php if($seccional_oab_escritorio == 'SC'){ ?> selected <?php } ?>>SC</option>
									<option value="SP" <?php if($seccional_oab_escritorio == 'SP'){ ?> selected <?php } ?>>SP</option>
									<option value="SE" <?php if($seccional_oab_escritorio == 'SE'){ ?> selected <?php } ?>>SE</option>
									<option value="TO" <?php if($seccional_oab_escritorio == 'TO'){ ?> selected <?php } ?>>TO</option>

								</select>                                               
							</div>  


							<div class="col-md-3 mb-2 col-6">                           
								<label>Número OAB Escritório</label>
								<input type="text" class="form-control" id="" name="numero_oab_escritorio" placeholder="Numero OAB Ex 200.000" value="<?php echo @$numero_oab_escritorio ?>">                           
							</div>
							<div class="col-md-2 mb-2 col-6">                           
								<label>Cor Login</label>
								<input type="text" class="form-control" id="" name="cor_login" placeholder="#000000" value="<?php echo @$cor_login ?>">                         
							</div>

						</div>



						<div class="row mb-2">
							<div class="col-md-2">                          
								<label>Filtrar Colunas</label>
								<select name="filtrar_colunas" class="form-select">
									<option value="true" <?php if($filtrar_colunas == 'true'){ ?> selected <?php } ?>>Sim</option>
									<option value="false" <?php if($filtrar_colunas == 'false'){ ?> selected <?php } ?>>Não</option>
								</select>                       
							</div>

							<div class="col-md-2">                          
								<label>Assinatura Cliente</label>
								<select name="assinatura_cliente" class="form-select">
									<option value="Sim" <?php if($assinatura_cliente == 'Sim'){ ?> selected <?php } ?>>Sim</option>
									<option value="Não" <?php if($assinatura_cliente == 'Não'){ ?> selected <?php } ?>>Não</option>
								</select>                       
							</div>

							<div class="col-md-2">                          
								<label>Entrada Inicial</label>
								<select name="entrada_sistema" class="form-select">
									<option value="Site" <?php if($entrada_sistema == 'Site'){ ?> selected <?php } ?>>Site</option>
									<option value="Login" <?php if($entrada_sistema == 'Login'){ ?> selected <?php } ?>>Login</option>
								</select>               
							</div>



							<div class="col-md-6 mb-2 col-6">                           
								<label>Token Api Asaas</label>
								<input type="text" class="form-control" id="" name="chave_asaas" placeholder="Token se for usar api de pagamento Asaas" value="<?php echo @$chave_asaas ?>">                            
							</div>





						</div>



						<div class="row mb-3">

							<div class="col-md-2">                          
								<label>Usar IA</label>
								<select name="api_ia" class="form-select">
									<option value="chatgpt" <?php if($api_ia == 'chatgpt'){ ?> selected <?php } ?>>ChatGpt</option>
									<option value="gemini" <?php if($api_ia == 'gemini'){ ?> selected <?php } ?>>Gemini</option>
								</select>               
							</div>

							<div class="col-md-5 mb-2 col-6">                           
								<label>Token ChatGPT</label>
								<input type="text" class="form-control" id="" name="token_chatgpt" placeholder="Token se for usar a IA Chatgpt" value="<?php echo @$token_chatgpt ?>">                          
							</div>


							<div class="col-md-5 mb-2 col-6">                           
								<label>Token Gemini</label>
								<input type="text" class="form-control" id="" name="token_gemini" placeholder="Token se for usar a IA Gemini" value="<?php echo @$token_gemini ?>">                         
							</div>

						</div>


						<div class="row mb-3">
							<div class="col-md-4">                      
								<div class="form-group"> 
									<label>Logo (*PNG)</label> 
									<input class="form-control" type="file" name="foto-logo" onChange="carregarImgLogo();" id="foto-logo">
								</div>                      
							</div>
							<div class="col-md-2">
								<div id="divImg">
									<img src="../img/<?php echo $logo_sistema ?>"  width="80px" id="target-logo">                                   
								</div>
							</div>


							<div class="col-md-4">                      
								<div class="form-group"> 
									<label>Ícone (*Png)</label> 
									<input class="form-control" type="file" name="foto-icone" onChange="carregarImgIcone();" id="foto-icone">
								</div>                      
							</div>
							<div class="col-md-2">
								<div id="divImg">
									<img src="../img/<?php echo $icone_sistema ?>"  width="50px" id="target-icone">                                 
								</div>
							</div>


						</div>




						<div class="row mb-3">
							<div class="col-md-4">                      
								<div class="form-group"> 
									<label>Logo Relatório (*Jpg)</label> 
									<input class="form-control" type="file" name="foto-logo-rel" onChange="carregarImgLogoRel();" id="foto-logo-rel">
								</div>                      
							</div>
							<div class="col-md-2">
								<div id="divImg">
									<img src="../img/<?php echo @$logo_rel ?>"  width="80px" id="target-logo-rel">                                  
								</div>
							</div>




							<div class="col-md-4">                      
								<div class="form-group"> 
									<label>Logo Painél (Clara)(*PNG)</label> 
									<input class="form-control" type="file" name="foto-painel" onChange="carregarImgPainel();" id="foto-painel">
								</div>                      
							</div>
							<div class="col-md-2">
								<div id="divImg">
									<img src="../img/foto-painel.png"  width="80px" id="target-painel">                                 
								</div>
							</div>


							<div class="col-md-6">
								<label>Assinatura (desenhar)</label><br>

								<div class="d-flex align-items-center gap-3">

									<!-- Preview -->
									<img id="preview_assinatura"
									src="../img/assinatura.png?v=<?= time() ?>"
									style="width:220px; height:80px; object-fit:contain; 
									border:1px dashed #ccc; border-radius:8px; background:#fff;"
									onerror="this.src='../img/assinatura_vazia.png';">

									<!-- Botões -->
									<div class="d-flex flex-column gap-2">
										<button type="button" class="btn btn-primary btn-sm"
										onclick="abrirModalAssinatura()">
										Assinar
									</button>

									<button type="button" class="btn btn-outline-danger btn-sm"
									onclick="removerAssinatura()">
									Remover
								</button>
							</div>

						</div>

						<!-- guarda o nome do arquivo salvo -->
						<input type="hidden" name="assinatura_arquivo" id="assinatura_arquivo" value="<?= htmlspecialchars($assinatura_arquivo ?? 'assinatura.png') ?>">
					</div>



					<div class="col-md-4">
						<div class="form-group">
							<label>Fundo Login <small>(Imagem Grande) 1920 x 1080</small></label>
							<input class="form-control" type="file" name="fundo_login" onChange="carregarImgFundo();"
							id="fundo_login">
						</div>
					</div>
					<div class="col-md-2">
						<div id="divImg">
							<img src="../img/<?php echo $fundo_login ?>" width="80px" id="target-fundo">
							<a title="Excluir Imagem" href="#" onclick="excluirImg('Fundo')"><i class="fa fa-close text-danger"></i></a>
						</div>
					</div>



				</div>                  


				<br>
				<small><div id="msg-config" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button type="submit" class="btn btn-primary">Salvar</button>
			</div>
		</form>
	</div>
</div>
</div>








<!-- Modal Rel Financeiro -->
<div class="modal fade" id="modalRelFin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">  
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel">Relatório Financeiro</h4>
				<button id="btn-fechar-config" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form method="POST" action="rel/financeiro_class.php" target="_blank">
				<div class="modal-body">    
					<div class="row">
						<div class="col-md-4">
							<label>Data Inicial</label>
							<input type="date" name="dataInicial" class="form-control" value="<?php echo $data_atual ?>">
						</div>

						<div class="col-md-4">
							<label>Data Final</label>
							<input type="date" name="dataFinal" class="form-control" value="<?php echo $data_atual ?>">
						</div>

						<div class="col-md-4">
							<label>Filtro Data</label>
							<select name="filtro_data" class="form-select">
								<option value="data_lanc">Data de Lançamento</option>
								<option value="vencimento">Data de Vencimento</option>
								<option value="data_pgto">Data de Pagamento</option>
							</select>
						</div>
					</div>      


					<div class="row">               
						<div class="col-md-4">
							<label>Entradas / Saídas</label>
							<select name="filtro_tipo" class="form-select">
								<option value="receber">Entradas / Ganhos</option>
								<option value="pagar">Saídas / Despesas</option>
							</select>
						</div>

						<div class="col-md-4">
							<label>Tipo Lançamento</label>
							<select name="filtro_lancamento" class="form-select">
								<option value="">Tudo</option>
								<option value="Conta">Ganhos / Despesas</option>
								<option value="Abertura Contrato">Abertura Contrato</option>

							</select>
						</div>
						<div class="col-md-4">
							<label>Pendentes / Pago</label>
							<select name="filtro_pendentes" class="form-select">
								<option value="">Tudo</option>
								<option value="Não">Pendentes</option>
								<option value="Sim">Pago</option>
							</select>
						</div>          
					</div>      



				</div>
				<div class="modal-footer">       
					<button type="submit" class="btn btn-primary">Gerar</button>
				</div>
			</form>
		</div>
	</div>
</div>






<!-- Modal Rel Sintético Despesas -->
<div class="modal fade" id="modalRelSinDesp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel">Relatório Sintético Contas à Pagar</h4>
				<button id="btn-fechar-config" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form method="POST" action="rel/sintetico_class.php" target="_blank">
				<div class="modal-body">    
					<div class="row">
						<div class="col-md-4">
							<label>Data Inicial</label>
							<input type="date" name="dataInicial" class="form-control" value="<?php echo $data_atual ?>">
						</div>

						<div class="col-md-4">
							<label>Data Final</label>
							<input type="date" name="dataFinal" class="form-control" value="<?php echo $data_atual ?>">
						</div>

						<div class="col-md-4">
							<label>Filtro Data</label>
							<select name="filtro_data" class="form-select">
								<option value="data_lanc">Data de Lançamento</option>
								<option value="vencimento">Data de Vencimento</option>
								<option value="data_pgto">Data de Pagamento</option>
							</select>
						</div>
					</div>      


					<div class="row">           


						<div class="col-md-4">
							<label>Tipo Filtro Contas</label>
							<select name="filtro_lancamento" class="form-select">                   
								<option value="fornecedor">Fornecedores</option>
								<option value="funcionario">Funcionário</option>

							</select>
						</div>
						<div class="col-md-4">
							<label>Pendentes / Pago</label>
							<select name="filtro_pendentes" class="form-select">
								<option value="">Tudo</option>
								<option value="Não">Pendentes</option>
								<option value="Sim">Pago</option>
							</select>
						</div>          
					</div>      



				</div>
				<div class="modal-footer">       
					<button type="submit" class="btn btn-primary">Gerar</button>
				</div>
			</form>
		</div>
	</div>
</div>







<!-- Modal Rel Sintético Recebimentos -->
<div class="modal fade" id="modalRelSinRec" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel">Relatório Sintético Recebimentos</h4>
				<button id="btn-fechar-config" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form method="POST" action="rel/sintetico_recebimentos_class.php" target="_blank">
				<div class="modal-body">    
					<div class="row">
						<div class="col-md-4">
							<label>Data Inicial</label>
							<input type="date" name="dataInicial" class="form-control" value="<?php echo $data_atual ?>">
						</div>

						<div class="col-md-4">
							<label>Data Final</label>
							<input type="date" name="dataFinal" class="form-control" value="<?php echo $data_atual ?>">
						</div>

						<div class="col-md-4">
							<label>Filtro Data</label>
							<select name="filtro_data" class="form-select">
								<option value="data_lanc">Data de Lançamento</option>
								<option value="vencimento">Data de Vencimento</option>
								<option value="data_pgto">Data de Pagamento</option>
							</select>
						</div>
					</div>      


					<div class="row">           


						<div class="col-md-4">
							<label>Tipo Filtro Contas</label>
							<select name="filtro_lancamento" class="form-select">                   
								<option value="cliente">Clientes</option>                       

							</select>
						</div>
						<div class="col-md-4">
							<label>Pendentes / Pago</label>
							<select name="filtro_pendentes" class="form-select">
								<option value="">Tudo</option>
								<option value="Não">Pendentes</option>
								<option value="Sim">Pago</option>
							</select>
						</div>          
					</div>      



				</div>
				<div class="modal-footer">       
					<button type="submit" class="btn btn-primary">Gerar</button>
				</div>
			</form>
		</div>
	</div>
</div>









<!-- Modal Rel Sintético Recebimentos -->
<div class="modal fade" id="modalRelSinRanking" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel">Relatório Sintético Ranking</h4>
				<button id="btn-fechar-config" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form method="POST" action="rel/sintetico_ranking_class.php" target="_blank">
				<div class="modal-body">    
					<div class="row">
						<a href="#" onclick="$('#dataIniRanking').val('1900-01-01'); $('#dataFinRanking').val('2200-01-01');">Tudo</a>/

						<a href="#" onclick="$('#dataIniRanking').val('<?=$data_atual?>'); $('#dataFinRanking').val('<?=$data_atual?>');">Hoje</a>/

						<a href="#" onclick="$('#dataIniRanking').val('<?=$data_inicio_mes?>'); $('#dataFinRanking').val('<?=$data_final_mes?>');">Mês</a>/

						<a href="#" onclick="$('#dataIniRanking').val('<?=$data_inicio_ano?>'); $('#dataFinRanking').val('<?=$data_final_ano?>');">Ano</a>

					</div>

					<div class="row">
						<div class="col-md-4">
							<label>Data Inicial</label>
							<input type="date" name="dataInicial" id="dataIniRanking" class="form-control" value="<?php echo $data_atual ?>">
						</div>

						<div class="col-md-4">
							<label>Data Final</label>
							<input type="date" name="dataFinal" id="dataFinRanking" class="form-control" value="<?php echo $data_atual ?>">
						</div>




					</div>      





				</div>
				<div class="modal-footer">       
					<button type="submit" class="btn btn-primary">Gerar</button>
				</div>
			</form>
		</div>
	</div>
</div>






<!-- Modal Rel Audiencias -->
<div class="modal fade" id="modalRelAudiencias" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel">Relatório de Audiências</h4>
				<button id="btn-fechar-config" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form method="POST" action="rel/audiencias_class.php" target="_blank">
				<div class="modal-body">    
					<div class="row">
						<a href="#" onclick="$('#dataIniAud').val('1900-01-01'); $('#dataFinAud').val('2200-01-01');">Tudo</a>/

						<a href="#" onclick="$('#dataIniAud').val('<?=$data_atual?>'); $('#dataFinAud').val('<?=$data_atual?>');">Hoje</a>/

						<a href="#" onclick="$('#dataIniAud').val('<?=$data_inicio_mes?>'); $('#dataFinAud').val('<?=$data_final_mes?>');">Mês</a>/

						<a href="#" onclick="$('#dataIniAud').val('<?=$data_inicio_ano?>'); $('#dataFinAud').val('<?=$data_final_ano?>');">Ano</a>

					</div>

					<div class="row">
						<div class="col-md-4">
							<label>Data Inicial</label>
							<input type="date" name="dataInicial" id="dataIniAud" class="form-control" value="<?php echo $data_atual ?>">
						</div>

						<div class="col-md-4">
							<label>Data Final</label>
							<input type="date" name="dataFinal" id="dataFinAud" class="form-control" value="<?php echo $data_atual ?>">
						</div>


						<div class="col-md-4">
							<label>Profissional</label>
							<select class="form-control sel_rel_aud " aria-label="Default select example" name="usuario_selecionado" style="width:230px;">  
								<option value="">Todos</option>

								<?php 
								if($nivel_usuario == 'Administrador' || $nivel_usuario == 'Escritório'){
									$query = $pdo->query("SELECT * FROM usuarios where nivel = 'Advogado'");

								}else{
									$query = $pdo->query("SELECT * FROM usuarios where id = '$id_usuario' ");
								}


								$res = $query->fetchAll(PDO::FETCH_ASSOC);

								for($i=0; $i < @count($res); $i++){

									if($res[$i]['visto_por'] == 'Não' and $res[$i]['usuario_lanc'] != $id_usuario and $res[$i]['id'] != $id_usuario){
										continue;
									}

									?>  

									<option value="<?php echo $res[$i]['id'] ?>" <?php if($res[$i]['id'] == $id_usuario){ ?> selected <?php } ?> ><?php echo $res[$i]['nome'] ?> </option>



								<?php } ?>          



							</select>
						</div>




					</div>      





				</div>
				<div class="modal-footer">       
					<button type="submit" class="btn btn-primary">Gerar</button>
				</div>
			</form>
		</div>
	</div>
</div>











<!-- Modal Rel Beneficios -->
<div class="modal fade" id="modalRelBeneficios" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel">Relatório de Benefícios</h4>
				<button id="btn-fechar-config" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form method="POST" action="rel/beneficios_class.php" target="_blank">
				<div class="modal-body">    
					<div class="row">
						<a href="#" onclick="$('#dataIniBen').val('1900-01-01'); $('#dataFinBen').val('2200-01-01');">Tudo</a>/

						<a href="#" onclick="$('#dataIniBen').val('<?=$data_atual?>'); $('#dataFinBen').val('<?=$data_atual?>');">Hoje</a>/

						<a href="#" onclick="$('#dataIniBen').val('<?=$data_inicio_mes?>'); $('#dataFinBen').val('<?=$data_final_mes?>');">Mês</a>/

						<a href="#" onclick="$('#dataIniBen').val('<?=$data_inicio_ano?>'); $('#dataFinBen').val('<?=$data_final_ano?>');">Ano</a>

					</div>

					<div class="row">
						<div class="col-md-4">
							<label>Data Inicial</label>
							<input type="date" name="dataInicial" id="dataIniBen" class="form-control" value="<?php echo $data_atual ?>">
						</div>

						<div class="col-md-4">
							<label>Data Final</label>
							<input type="date" name="dataFinal" id="dataFinBen" class="form-control" value="<?php echo $data_atual ?>">
						</div>


						<div class="col-md-4">
							<label>Tipo Data Filtro</label>
							<select name="data_filtro" class="form-select">
								<option value="dib">Início Benefício</option>
								<option value="dcb_prevista">Previsão Benefício</option>
								<option value="dcb">Cessão Benefício</option>

							</select>
						</div>      



					</div>  



					<div class="row">
						<div class="col-md-4">
							<label>Status</label>
							<select name="status" class="form-select">
								<option value="">Todos</option>
								<option value="ativo">Ativo</option>
								<option value="suspenso">Suspenso</option>
								<option value="cessado">Cessado</option>
								<option value="cancelado">Cancelado</option>
								<option value="convertido">Convertido</option>
							</select>
						</div>



						<div class="col-md-4">
							<label>Tipo Benefício</label>
							<select name="tipo" class="form-select">
								<option value="">Todos</option>
								<option value="Auxílio por Incapacidade Temporária">Auxílio por Incapacidade Temporária</option>
								<option value="Auxílio Acidente">Auxílio-acidente</option>
								<option value="Aposentadoria por Incapacidade">Aposentadoria por Incapacidade Permanente</option>
								<option value="Pensão por Morte">Pensão por Morte</option>
								<option value="BPC/LOAS">BPC/LOAS</option>
								<option value="Outros">Outros</option>
							</select>
						</div>

					</div>  





				</div>
				<div class="modal-footer">       
					<button type="submit" class="btn btn-primary">Gerar</button>
				</div>
			</form>
		</div>
	</div>
</div>






<!-- Modal Assinatura (SEM backdrop, para não bugar o scroll) -->
<div class="modal fade" id="modalAssinatura" tabindex="-1"
     aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 760px;">
    <div class="modal-content modal-assinatura-content">
      <div class="modal-header">
        <h5 class="modal-title">Assinar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"> x </button>
      </div>

      <div class="modal-body">
        <div class="assinatura-box">
          <canvas id="canvasAssinatura" style="width:100%; height:140px; display:block;"></canvas>
        </div>
        <small class="text-muted d-block mt-2">
          Assine com o mouse ou com o dedo (celular).
        </small>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" onclick="limparAssinatura()">Limpar</button>
        <button type="button" class="btn btn-primary" onclick="salvarAssinatura()">Salvar</button>
      </div>
    </div>
  </div>
</div>

<style type="text/css">
/* modal pequena na frente */
.modal-assinatura-content{
  border-radius: 14px;
  border: 1px solid rgba(0,0,0,.15);
  box-shadow: 0 25px 70px rgba(0,0,0,.35);
}

.assinatura-box{
  border: 1px solid #ddd;
  border-radius: 12px;
  overflow: hidden;
  background: #fff;
}
</style>


<script>
	let isDrawing = false;
	let lastX = 0, lastY = 0;

let _scrollConfig = 0;

function abrirModalAssinatura(){
  const cfgEl = document.getElementById('modalConfig');
  const assEl = document.getElementById('modalAssinatura');

  // guarda scroll da modalConfig (para voltar no mesmo ponto)
  const bodyCfg = cfgEl.querySelector('.modal-body');
  _scrollConfig = bodyCfg ? bodyCfg.scrollTop : 0;

  // quando a config terminar de fechar, abre a assinatura
  cfgEl.addEventListener('hidden.bs.modal', function onHiddenCfg(){
    cfgEl.removeEventListener('hidden.bs.modal', onHiddenCfg);

    const modalAss = bootstrap.Modal.getOrCreateInstance(assEl, {
      backdrop: 'static', // aqui pode ter backdrop normal, pois não tem outra modal aberta
      keyboard: true,
      focus: true
    });

    modalAss.show();

    setTimeout(() => {
      prepararCanvasAssinatura();
    }, 150);
  });

  // fecha a config
  bootstrap.Modal.getInstance(cfgEl).hide();
}

function prepararCanvasAssinatura(){
	const canvas = document.getElementById('canvasAssinatura');
	const ctx = canvas.getContext('2d');

// Ajusta resolução real (pra não ficar "borrado")
const rect = canvas.getBoundingClientRect();
const ratio = window.devicePixelRatio || 1;

canvas.width  = Math.floor(rect.width * ratio);
canvas.height = Math.floor(rect.height * ratio);
ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

// estilo do traço
ctx.lineWidth = 2.5;
ctx.lineCap = 'round';
ctx.lineJoin = 'round';
ctx.strokeStyle = '#111';

// limpa
ctx.clearRect(0, 0, rect.width, rect.height);

// eventos mouse/touch
canvas.onmousedown = (e) => startDraw(getPos(canvas, e));
canvas.onmousemove = (e) => moveDraw(getPos(canvas, e));
window.onmouseup = endDraw;

canvas.ontouchstart = (e) => { e.preventDefault(); startDraw(getPos(canvas, e.touches[0])); };
canvas.ontouchmove  = (e) => { e.preventDefault(); moveDraw(getPos(canvas, e.touches[0])); };
canvas.ontouchend   = (e) => { e.preventDefault(); endDraw(); };
}

function getPos(canvas, e){
	const r = canvas.getBoundingClientRect();
	return { x: e.clientX - r.left, y: e.clientY - r.top };
}

function startDraw(pos){
	isDrawing = true;
	lastX = pos.x; lastY = pos.y;
}

function moveDraw(pos){
	if(!isDrawing) return;
	const canvas = document.getElementById('canvasAssinatura');
	const ctx = canvas.getContext('2d');

	ctx.beginPath();
	ctx.moveTo(lastX, lastY);
	ctx.lineTo(pos.x, pos.y);
	ctx.stroke();

	lastX = pos.x; lastY = pos.y;
}

function endDraw(){
	isDrawing = false;
}

function limparAssinatura(){
	const canvas = document.getElementById('canvasAssinatura');
	const ctx = canvas.getContext('2d');
	const r = canvas.getBoundingClientRect();
	ctx.clearRect(0, 0, r.width, r.height);
}

function canvasTemAlgo(){
	const canvas = document.getElementById('canvasAssinatura');
	const ctx = canvas.getContext('2d');
	const { width, height } = canvas;
	const data = ctx.getImageData(0, 0, width, height).data;

// se achar algum pixel com alpha > 0, tem traço
for (let i = 3; i < data.length; i += 4) {
	if (data[i] > 0) return true;
}
return false;
}

function salvarAssinatura(){
	if(!canvasTemAlgo()){
		alert('Assine antes de salvar.');
		return;
	}

	const canvas = document.getElementById('canvasAssinatura');

// PNG com fundo transparente
const dataURL = canvas.toDataURL('image/png');

$.ajax({
	url: 'salvar_assinatura.php',
	method: 'POST',
	dataType: 'json',
	data: { img: dataURL },
	success: function(resp){
		if(resp.ok){
			$('#assinatura_arquivo').val(resp.arquivo);

// atualiza preview (cache-bust)
$('#preview_assinatura').attr('src', resp.url + '?v=' + Date.now());

// fecha modal
bootstrap.Modal.getInstance(document.getElementById('modalAssinatura')).hide();
}else{
	alert(resp.msg || 'Erro ao salvar assinatura.');
}
},
error: function(){
	alert('Falha na requisição ao salvar.');
}
});
}

function removerAssinatura(){
	$.ajax({
		url: 'remover_assinatura.php',
		method: 'POST',
		dataType: 'json',
		success: function(resp){
			if(resp.ok){
				$('#assinatura_arquivo').val('');
				$('#preview_assinatura').attr('src', '../img/assinatura_vazia.png?v=' + Date.now());
			}else{
				alert(resp.msg || 'Erro ao remover assinatura.');
			}
		},
		error: function(){
			alert('Falha ao remover.');
		}
	});
}




document.addEventListener('DOMContentLoaded', () => {
  const cfgEl = document.getElementById('modalConfig');
  const assEl = document.getElementById('modalAssinatura');

  assEl.addEventListener('hidden.bs.modal', () => {
    const modalCfg = bootstrap.Modal.getOrCreateInstance(cfgEl, {
      backdrop: 'static',
      keyboard: true,
      focus: true
    });

    modalCfg.show();

    // volta no scroll anterior
    setTimeout(() => {
      const bodyCfg = cfgEl.querySelector('.modal-body');
      if (bodyCfg) bodyCfg.scrollTop = _scrollConfig;
    }, 200);
  });
});
</script>









<!-- Modal Assinatura Usuário -->
<div class="modal fade" id="modalAssinaturaUsuario" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:760px;">
    <div class="modal-content" style="border-radius:14px; border:1px solid rgba(0,0,0,.15); box-shadow:0 25px 70px rgba(0,0,0,.35);">
      <div class="modal-header">
        <h5 class="modal-title">Assinar (Usuário)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div style="border:1px solid #ddd; border-radius:12px; overflow:hidden; background:#fff;">
          <canvas id="canvasAssUsuario" style="width:100%; height:240px; display:block;"></canvas>
        </div>
        <small class="text-muted d-block mt-2">Assine com o mouse ou com o dedo.</small>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" onclick="limparAssUsuario()">Limpar</button>
        <button type="button" class="btn btn-primary" onclick="salvarAssinaturaUsuario()">Salvar</button>
      </div>
    </div>
  </div>
</div>




<script>
let _usr_isDrawing = false;
let _usr_lastX = 0, _usr_lastY = 0;

function abrirModalAssinaturaUsuario(){
  const el = document.getElementById('modalAssinaturaUsuario');
  const modal = bootstrap.Modal.getOrCreateInstance(el, { backdrop: 'static', keyboard: true });
  modal.show();

  setTimeout(() => prepararCanvasAssUsuario(), 150);
}

function prepararCanvasAssUsuario(){
  const canvas = document.getElementById('canvasAssUsuario');
  const ctx = canvas.getContext('2d');

  const rect = canvas.getBoundingClientRect();
  const ratio = window.devicePixelRatio || 1;

  canvas.width  = Math.floor(rect.width * ratio);
  canvas.height = Math.floor(rect.height * ratio);
  ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

  ctx.lineWidth = 2.5;
  ctx.lineCap = 'round';
  ctx.lineJoin = 'round';
  ctx.strokeStyle = '#111';

  ctx.clearRect(0, 0, rect.width, rect.height);

  canvas.onmousedown = (e) => usrStart(usrPos(canvas, e));
  canvas.onmousemove = (e) => usrMove(usrPos(canvas, e));
  window.onmouseup = usrEnd;

  canvas.ontouchstart = (e) => { e.preventDefault(); usrStart(usrPos(canvas, e.touches[0])); };
  canvas.ontouchmove  = (e) => { e.preventDefault(); usrMove(usrPos(canvas, e.touches[0])); };
  canvas.ontouchend   = (e) => { e.preventDefault(); usrEnd(); };
}

function usrPos(canvas, e){
  const r = canvas.getBoundingClientRect();
  return { x: e.clientX - r.left, y: e.clientY - r.top };
}

function usrStart(pos){
  _usr_isDrawing = true;
  _usr_lastX = pos.x; _usr_lastY = pos.y;
}

function usrMove(pos){
  if(!_usr_isDrawing) return;
  const canvas = document.getElementById('canvasAssUsuario');
  const ctx = canvas.getContext('2d');

  ctx.beginPath();
  ctx.moveTo(_usr_lastX, _usr_lastY);
  ctx.lineTo(pos.x, pos.y);
  ctx.stroke();

  _usr_lastX = pos.x; _usr_lastY = pos.y;
}

function usrEnd(){
  _usr_isDrawing = false;
}

function limparAssUsuario(){
  const canvas = document.getElementById('canvasAssUsuario');
  const ctx = canvas.getContext('2d');
  const r = canvas.getBoundingClientRect();
  ctx.clearRect(0, 0, r.width, r.height);
}

function salvarAssinaturaUsuario(){
  const canvas = document.getElementById('canvasAssUsuario');
  const dataURL = canvas.toDataURL('image/png');

  $.ajax({
    url: 'salvar_assinatura_usuario.php',
    method: 'POST',
    dataType: 'json',
    data: {
      img: dataURL,
      id_usuario: $('input[name="id_usuario"]').val(),
      nome: $('#nome_perfil').val()
    },
    success: function(resp){
      if(resp.ok){
        $('#assinatura_usuario').val(resp.arquivo);
        $('#preview_assinatura_usuario').attr('src', resp.url + '?v=' + Date.now());
        bootstrap.Modal.getInstance(document.getElementById('modalAssinaturaUsuario')).hide();
      }else{
        alert(resp.msg || 'Erro ao salvar assinatura.');
      }
    },
    error: function(){
      alert('Falha na requisição ao salvar.');
    }
  });
}

function removerAssinaturaUsuario(){
  $.ajax({
    url: 'remover_assinatura_usuario.php',
    method: 'POST',
    dataType: 'json',
    data: {
      id_usuario: $('input[name="id_usuario"]').val(),
      arquivo: $('#assinatura_usuario').val()
    },
    success: function(resp){
      if(resp.ok){
        $('#assinatura_usuario').val('');
        $('#preview_assinatura_usuario').attr('src', 'images/assinaturas/sem-assinatura.png?v=' + Date.now());
      } else {
        alert(resp.msg || 'Erro ao remover assinatura.');
      }
    },
    error: function(){
      alert('Falha ao remover.');
    }
  });
}
</script>


<script>
// ==============================
// Fluxo: Modal Perfil -> Assinatura Usuário -> Modal Perfil
// ==============================
let _scrollPerfilAss = 0;

// abre a assinatura FECHANDO a modal Perfil
function abrirModalAssinaturaUsuario(){
  const perfilEl = document.getElementById('modalPerfil');
  const assEl    = document.getElementById('modalAssinaturaUsuario');

  // salva scroll atual da modal Perfil (pra voltar no mesmo ponto)
  const bodyPerfil = perfilEl.querySelector('.modal-body');
  _scrollPerfilAss = bodyPerfil ? bodyPerfil.scrollTop : 0;

  // quando terminar de fechar o perfil, abre a assinatura
  perfilEl.addEventListener('hidden.bs.modal', function onHiddenPerfil(){
    perfilEl.removeEventListener('hidden.bs.modal', onHiddenPerfil);

    const modalAss = bootstrap.Modal.getOrCreateInstance(assEl, {
      backdrop: 'static',
      keyboard: true,
      focus: true
    });

    modalAss.show();

    setTimeout(() => {
      prepararCanvasAssUsuario();
    }, 150);
  });

  // fecha o perfil
  const instPerfil = bootstrap.Modal.getInstance(perfilEl) || bootstrap.Modal.getOrCreateInstance(perfilEl);
  instPerfil.hide();
}

// ao fechar a assinatura, reabre o perfil e volta o scroll
document.addEventListener('DOMContentLoaded', () => {
  const perfilEl = document.getElementById('modalPerfil');
  const assEl    = document.getElementById('modalAssinaturaUsuario');

  if (!perfilEl || !assEl) return;

  assEl.addEventListener('hidden.bs.modal', () => {
    const modalPerfil = bootstrap.Modal.getOrCreateInstance(perfilEl, {
      backdrop: 'static',
      keyboard: true,
      focus: true
    });

    modalPerfil.show();

    setTimeout(() => {
      const bodyPerfil = perfilEl.querySelector('.modal-body');
      if (bodyPerfil) bodyPerfil.scrollTop = _scrollPerfilAss;
    }, 200);
  });
});
</script>











<script type="text/javascript" src="js/monthly.js"></script>


<!-- Mascaras JS -->
<script type="text/javascript" src="js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 

<script type="text/javascript">

	$(document).ready(function() {
//$("#btn_tema").click();
mudarMascaraTel();  


$('.sel_rel_aud').select2({
	dropdownParent: $('#modalRelAudiencias')
});     


});

</script>


<script type="text/javascript">
	$(document).on('select2:open', () => {
		document.querySelector('.select2-search__field').focus();
	}); 
</script>


<script type="text/javascript">
	function carregarImgPerfil() {
		var target = document.getElementById('target-usu');
		var file = document.querySelector("#foto_perfil").files[0];

		var reader = new FileReader();

		reader.onloadend = function () {
			target.src = reader.result;
		};

		if (file) {
			reader.readAsDataURL(file);

		} else {
			target.src = "";
		}
	}
</script>






<script type="text/javascript">
	$("#form-perfil").submit(function () {

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "editar-perfil.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#msg-perfil').text('');
				$('#msg-perfil').removeClass()
				if (mensagem.trim() == "Editado com Sucesso") {

					$('#btn-fechar-perfil').click();
					location.reload();              


				} else {

					$('#msg-perfil').addClass('text-danger')
					$('#msg-perfil').text(mensagem)
				}


			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>






<script type="text/javascript">
	$("#form-config").submit(function () {

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "editar-config.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#msg-config').text('');
				$('#msg-config').removeClass()
				if (mensagem.trim() == "Editado com Sucesso") {

					$('#btn-fechar-config').click();
					location.reload();              


				} else {

					$('#msg-config').addClass('text-danger')
					$('#msg-config').text(mensagem)
				}


			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>




<script type="text/javascript">
	function carregarImgLogo() {
		var target = document.getElementById('target-logo');
		var file = document.querySelector("#foto-logo").files[0];

		var reader = new FileReader();

		reader.onloadend = function () {
			target.src = reader.result;
		};

		if (file) {
			reader.readAsDataURL(file);

		} else {
			target.src = "";
		}
	}
</script>





<script type="text/javascript">
	function carregarImgLogoRel() {
		var target = document.getElementById('target-logo-rel');
		var file = document.querySelector("#foto-logo-rel").files[0];

		var reader = new FileReader();

		reader.onloadend = function () {
			target.src = reader.result;
		};

		if (file) {
			reader.readAsDataURL(file);

		} else {
			target.src = "";
		}
	}
</script>





<script type="text/javascript">
	function carregarImgIcone() {
		var target = document.getElementById('target-icone');
		var file = document.querySelector("#foto-icone").files[0];

		var reader = new FileReader();

		reader.onloadend = function () {
			target.src = reader.result;
		};

		if (file) {
			reader.readAsDataURL(file);

		} else {
			target.src = "";
		}
	}
</script>



<script type="text/javascript">
	function carregarImgAssinatura() {
		var target = document.getElementById('target-assinatura');
		var file = document.querySelector("#assinatura_rel").files[0];

		var reader = new FileReader();

		reader.onloadend = function () {
			target.src = reader.result;
		};

		if (file) {
			reader.readAsDataURL(file);

		} else {
			target.src = "";
		}
	}
</script>


<script type="text/javascript">
	function carregarImgPainel() {
		var target = document.getElementById('target-painel');
		var file = document.querySelector("#foto-painel").files[0];

		var reader = new FileReader();

		reader.onloadend = function () {
			target.src = reader.result;
		};

		if (file) {
			reader.readAsDataURL(file);

		} else {
			target.src = "";
		}
	}
</script>



<script type="text/javascript">
	function testarApi() {
		var seletor_api = $('#api_whatsapp').val();
		var token = $('#token_whatsapp').val();
		var instancia = $('#instancia_whatsapp').val();
		var telefone_sistema = $('#telefone_sistema').val();
		var url_evolution = $('#url_evolution').val();

		$.ajax({
			url: 'apis/teste_whatsapp.php',
			method: 'POST',
			data: {
				seletor_api,
				token,
				instancia,
				telefone_sistema,
				url_evolution
			},
			dataType: "html",

			success: function(result) {
				alert(result)
			}
		});
	}
</script>




<script type="text/javascript">
	function mudarMascaraTel(){
		var tipo = $('#tipo_tel').val();

		if(tipo == 'Fixo'){
			$('#telefone_sistema').mask('(00) 0000-0000');
			$('#telefone_sistema').attr("placeholder", "Telefone Fixo");
		}else{
			$('#telefone_sistema').mask('(00) 00000-0000');
			$('#telefone_sistema').attr("placeholder", "Telefone Celular");
		}
	}
</script>



<script type="text/javascript">
	function carregarImgPainel() {
		var target = document.getElementById('target-painel');
		var file = document.querySelector("#foto-painel").files[0];

		var reader = new FileReader();

		reader.onloadend = function() {
			target.src = reader.result;
		};

		if (file) {
			reader.readAsDataURL(file);

		} else {
			target.src = "";
		}
	}
</script>



<script type="text/javascript">
	function excluirImg(p){


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
	url: 'excluir_imagens.php',
	method: 'POST',
	data: { p },
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
location.reload();
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
</script>





<style>
	#url_da_evo {
		display: none;
	}
</style>


<script>
	function habilitarUrl() {
		var api = document.getElementById("api_whatsapp").value;
		var divUrl = document.getElementById("url_da_evo");

		if (api === "evolution") {
			divUrl.style.display = "block";
		} else {
			divUrl.style.display = "none";
		}
	}

// Executa ao carregar a página
document.addEventListener("DOMContentLoaded", function() {
	habilitarUrl();
});
</script>



<!-- Trumbowyg CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trumbowyg@2.27.3/dist/ui/trumbowyg.min.css">

<!-- Trumbowyg JS -->
<script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.27.3/dist/trumbowyg.min.js"></script>