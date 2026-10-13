<?php 
@session_start();
require_once("../../../conexao.php");

$usuario = @$_POST['usuario'];
$data = @$_POST['data'];
$data_inicio_mes = @$_POST['data_inicio_mes'];
$data_final_mes = @$_POST['data_final_mes'];


if($data_inicio_mes == ""){
	$data_inicio_mes = date('Y').'-'.date('m').'-01';
}

if($data_final_mes == ""){
	$data_final_mes = date('Y-m-d');
}



if($usuario == ""){
	$query = $pdo->query("SELECT * FROM usuarios where nivel != 'Cliente'  order by nome asc limit 1");									
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$usuario = $res[0]['id'];

}

echo <<<HTML
<small><small>
HTML;
$query = $pdo->query("SELECT * FROM jornada where funcionario = '$usuario' and data >= '$data_inicio_mes' and data <= '$data_final_mes' ORDER BY data asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if($total_reg > 0){
echo <<<HTML
	<table class="table table-hover" id="tabela">
		<thead> 
			<tr> 
				<th>Data</th> 
				<th>Entrada</th> 
				<th>Almoço</th>
				<th>Saída</th>
				<th>Total</th>
				<th>Extras</th>
				<th>Ações</th>
			</tr> 
		</thead> 
		<tbody> 
HTML;
for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
$id = $res[$i]['id'];
$data = $res[$i]['data'];
$entrada = $res[$i]['entrada'];
$entrada_almoco = $res[$i]['entrada_almoco'];
$saida_almoco = $res[$i]['saida_almoco'];
$saida = $res[$i]['saida'];
$total_horas = $res[$i]['total_horas'];
$hora_extra = $res[$i]['hora_extra'];
$folga = $res[$i]['folga'];
$falta = $res[$i]['falta'];
$feriado = $res[$i]['feriado'];

$dataF = implode('/', array_reverse(explode('-', $data)));
$entrada = date("H:i", strtotime($entrada));
$entrada_almoco = date("H:i", strtotime($entrada_almoco));
$saida_almoco = date("H:i", strtotime($saida_almoco));
$saida = date("H:i", strtotime($saida));
$total_horas = date("H:i", strtotime($total_horas));
$hora_extra = date("H:i", strtotime($hora_extra));

$classe_linha = '';

if($hora_extra != '00:00'){
	$classe_extra = 'text-danger';
}else{
	$classe_extra = '';
}

if($folga != ""){
	$entrada = 'Folga';
	$entrada_almoco = 'Folga';
	$saida_almoco = '';
	$saida = 'Folga';
	$total_horas = 'Folga';
	$hora_extra = 'Folga';
	$classe_linha = 'text-primary';
}

if($falta != ""){
	$entrada = 'Falta';
	$entrada_almoco = 'Falta';
	$saida_almoco = '';
	$saida = 'Falta';
	$total_horas = 'Falta';
	$hora_extra = 'Falta';
	$classe_linha = 'text-danger';
}


if($feriado != ""){
	$entrada = 'Feriado';
	$entrada_almoco = 'Feriado';
	$saida_almoco = '';
	$saida = 'Feriado';
	$total_horas = 'Feriado';
	$hora_extra = 'Feriado';
	$classe_linha = 'verde';
}

$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_usuario = $res2[0]['nome'];

echo <<<HTML
			<tr class="{$classe_linha}"> 
				<td>{$dataF}</td> 
				<td>{$entrada}</td>
				<td>{$entrada_almoco} : {$saida_almoco}</td>
				<td>{$saida}</td>
				<td>{$total_horas}</td>
				<td class="{$classe_extra}">{$hora_extra}</td>
				<td>

					<big><a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}', '{$usuario}', '{$data}')" title="Editar Dados"><i class="fa fa-edit "></i></a></big>



						<big><a href="#" class="btn btn-danger-light btn-sm" onclick="excluirAlert('{$id}')" title="Excluir"><i class="fa fa-trash"></i></a></big>		

											
						
				</td>  
			</tr> 
HTML;
}
echo <<<HTML
		</tbody> 
	</table>
</small></small>
HTML;
}else{
	echo 'Não possui nenhum registro cadastrado!';
}

?>



