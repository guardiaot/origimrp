<?php 

foreach ($retornoDadosProcesso[0]->hits->hits as $contador => $registros) { 
           
            if ($registros->_source->grau == 'G1') {
                $titulo_processo = "1a instância - " . $registros->_source->classe->nome . " " . $registros->_source->orgaoJulgador->nome;
                //echo $titulo_processo;
            } else if ($registros->_source->grau == 'G2'){
                $titulo_processo = "2a instância - " . $registros->_source->orgaoJulgador->nome;
                 //echo $titulo_processo;
            } else if ($registros->_source->grau == 'JE'){
                $titulo_processo = "Juizado Especial - " . $registros->_source->orgaoJulgador->nome;
                 //echo $titulo_processo;
            } else {
                $titulo_processo = "Não definido";
                 //echo $titulo_processo;
            }

        $dataAjuizamento = new datetime($registros->_source->dataAjuizamento);                           
//criar as variaveis
                            $tipo_processo = $registros->_source->classe->nome . " (" . $registros->_source->classe->codigo . ")";
                            $formato_processo = $registros->_source->formato->nome;
                            $data_instauracao = $dataAjuizamento->format('Y/m/d');

                            
                            $query2 = $pdo->query("SELECT * from processos_api where titulo = '$titulo_processo' and numero = '$numero'");
                            $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);

                            if(@count($res2) == 0){
                                 //salvar as informações do processo na tabela processos_api
                                 $pdo->query("INSERT INTO processos_api SET numero = '$numero', titulo = '$titulo_processo', tipo = '$tipo_processo', formato = '$formato_processo', data = '$data_instauracao' ");
                                 $numero_api_processo = $pdo->lastInsertId();
                            }else{
                                $numero_api_processo = $res2[0]['id'];
                            }

                           

                        foreach ($registros->_source->movimentos as $movimentacao) { 

                            
                            $dataMovimentacao = '';
                            $dataMovimentacao = new datetime ($movimentacao->dataHora);
                            $data_movimentacao = $dataMovimentacao->format('Y/m/d');

                             if (isset($movimentacao->complementosTabelados[0])) {
                                foreach ($movimentacao->complementosTabelados as $complementoMovimentacao) {
                                    //echo '<span>'.$complementoMovimentacao->descricao . " - " . $complementoMovimentacao->nome.'</span>';
                                }
                            } 
                         
                        $texto_movimentacao = $movimentacao->nome.' ('.$complementoMovimentacao->descricao . " - " . $complementoMovimentacao->nome.')';

                         $query3 = $pdo->query("SELECT * from movimentacoes_api where data = '$data_movimentacao' and processo_api = '$numero_api_processo'");
                         $res3 = $query3->fetchAll(PDO::FETCH_ASSOC);
                         $tem_movimentacao = @count($res3);
                            if($tem_movimentacao == 0){
                                 //salvar as informações do processo na tabela processos_api
                                 $pdo->query("INSERT INTO movimentacoes_api SET numero = '$numero', processo_api = '$numero_api_processo', data = '$data_movimentacao', texto = '$texto_movimentacao' ");
                                 $movimentacao_encontrada = 'Sim';
                                 
                            }
                    } 

}