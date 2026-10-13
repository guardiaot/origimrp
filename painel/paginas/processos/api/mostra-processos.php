<?php 
require_once("../../../../conexao.php");

foreach ($retornoDadosProcesso[0]->hits->hits as $contador => $registros) { ?>

	 <div class="accordion-item">
            <h4 class="accordion-header">
            <button style="height: 25px" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $contador ?>" aria-expanded="false" aria-controls="collapse<?php echo $contador ?>">

                <?php

            if ($registros->_source->grau == 'G1') {
                $titulo_processo = "1a instância - " . $registros->_source->classe->nome . " " . $registros->_source->orgaoJulgador->nome;
                echo $titulo_processo;
            } else if ($registros->_source->grau == 'G2'){
                $titulo_processo = "2a instância - " . $registros->_source->orgaoJulgador->nome;
                 echo $titulo_processo;
            } else if ($registros->_source->grau == 'JE'){
                $titulo_processo = "Juizado Especial - " . $registros->_source->orgaoJulgador->nome;
                 echo $titulo_processo;
            } else {
                $titulo_processo = "Não definido";
                 echo $titulo_processo;
            }



            ?>
           
                - Movimentação
            </button>
            </h4>
            
            <div id="collapse<?php echo $contador ?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="row align-items-start" style="font-size:13px">
                        <div class="col">
                            <p><b>Tipo de processo: </b> <?php echo $registros->_source->classe->nome . " (" . $registros->_source->classe->codigo . ")" ?></p>
                        </div>
                        <div class="col">
                            <p><b>Formato do processo: </b> <?php echo $registros->_source->formato->nome ?></p>
                        </div>
                        <div class="col">
                            <p><b>Data de instauração nesta instância:</b> <?php 
                            $dataAjuizamento = new datetime($registros->_source->dataAjuizamento);
                            echo $dataAjuizamento->format('d/m/Y');


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

                           

                            ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table  " id="tabela2" style="width:100% !important">
                            <thead style="font-weight: bold; background-color: #d5d5d5; display:none"> 
                            <tr >                             
                            <th style="width:100px">Data</th>                                
                            <th>Movimentação</th>                                    
                            </tr>
                            </thead> 
                            <tbody> 
                        <?php
                        foreach ($registros->_source->movimentos as $movimentacao) { 

                            echo '</tr><td style="width:100px">';
                            $dataMovimentacao = '';
                            $dataMovimentacao = new datetime ($movimentacao->dataHora);
                            $data_movimentacao = $dataMovimentacao->format('Y/m/d');

                            echo '<span style="display:none">'.$dataMovimentacao->format('Y-m-d').'</span><i class="fa fa-calendar-o " ></i> <b>'.$dataMovimentacao->format('d/m/Y').'</b> </td> ';
                            ?>
                            <?php echo '<td>'.$movimentacao->nome ?> (
                            <?php 
                            if (isset($movimentacao->complementosTabelados[0])) {
                                foreach ($movimentacao->complementosTabelados as $complementoMovimentacao) {
                                    echo '<span>'.$complementoMovimentacao->descricao . " - " . $complementoMovimentacao->nome.'</span>';
                                }
                            } 
                            ?>
                            )
                        <?php echo '</td></tr>'; 

                        $texto_movimentacao = $movimentacao->nome.' ('.$complementoMovimentacao->descricao . " - " . $complementoMovimentacao->nome.')';

                         $query3 = $pdo->query("SELECT * from movimentacoes_api where data = '$data_movimentacao' and processo_api = '$numero_api_processo'");
                         $res3 = $query3->fetchAll(PDO::FETCH_ASSOC);

                            if(@count($res3) == 0){
                                 //salvar as informações do processo na tabela processos_api
                                 $pdo->query("INSERT INTO movimentacoes_api SET numero = '$numero', processo_api = '$numero_api_processo', data = '$data_movimentacao', texto = '$texto_movimentacao' ");
                                 
                            }
                    } ?>
                    </tbody>
                </table>
                    </div>
                </div>
            </div>
        </div>

<?php } ?>


<script type="text/javascript">
    
    $('#tabela2').DataTable({
            "language" : {
            //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
            },
            "aaSorting": [[0, "desc"]],
            info: false,
            bFilter: false,
            paging: false
            
        }); 
        
</script>