<?php 

if($dias_criar_parcelas != ""){


	$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sábado");
	$diasemana_numero = date('w', strtotime($nova_data_vencimento));

	if($dias_criar_parcelas == "Final de Semana"){
		if ($diasemana_numero == 6) { 
		    $nova_data_vencimento = date('Y-m-d', strtotime("+2 days",strtotime($nova_data_vencimento )));
		}
	}

	if ($diasemana_numero == 0 ) {
		$nova_data_vencimento = date('Y-m-d', strtotime("+1 days",strtotime($nova_data_vencimento )));
	}

}


for ($if=0; $if < 7; $if++) { 
		
	
	$query_f = $pdo->query("SELECT * from feriados where data = '$nova_data_vencimento'");
	$res_f = $query_f->fetchAll(PDO::FETCH_ASSOC);
	$total_reg_f = @count($res_f);
	if($total_reg_f > 0){
		$nova_data_vencimento = date('Y-m-d', strtotime("+1 days",strtotime($nova_data_vencimento)));
	}

	}



if($dias_criar_parcelas != ""){


	$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sábado");
	$diasemana_numero = date('w', strtotime($nova_data_vencimento));

	if($dias_criar_parcelas == "Final de Semana"){
		if ($diasemana_numero == 6) { 
		    $nova_data_vencimento = date('Y-m-d', strtotime("+2 days",strtotime($nova_data_vencimento )));
		}
	}

	if ($diasemana_numero == 0 ) {
		$nova_data_vencimento = date('Y-m-d', strtotime("+1 days",strtotime($nova_data_vencimento )));
	}

}

 ?>