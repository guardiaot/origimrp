<?php 
@session_start();
$mostrar_registros = @$_SESSION['registros'];
$id_usuario = @$_SESSION['id'];
$tabela = 'processos_api';
require_once("../../../conexao.php");

$numero = @$_POST['numero'];

$query = $pdo->query("SELECT * from processos_api where numero = '$numero' order by id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);

if ($linhas > 0) {
?>
    <div class="accordion" id="accordionProcesso">
        <?php 
        for ($i = 0; $i < $linhas; $i++) {
            $id = $res[$i]['id'];
            $titulo = $res[$i]['titulo']; // Título do processo
            $tipo = $res[$i]['tipo'];
            $formato = $res[$i]['formato'];
            $data = $res[$i]['data'];
            $dataF = implode('/', array_reverse(explode('-', $data)));

            // Buscar as movimentações do processo
            $query2 = $pdo->query("SELECT * from movimentacoes_api where processo_api = '$id' order by id desc");
            $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div class="accordion-item">
            <h2 class="accordion-header" id="heading<?php echo $id; ?>">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $id; ?>" aria-expanded="false" aria-controls="collapse<?php echo $id; ?>">
                    <?php echo $titulo; ?>
                </button>
            </h2>
            <div id="collapse<?php echo $id; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $id; ?>" data-bs-parent="#accordionProcesso">
                <div class="accordion-body">
                    <div class="row text-center" style="font-size: 12px">
                        <div class="col-md-4"><strong>Tipo:</strong> <?php echo $tipo; ?></div>
                        <div class="col-md-4"><strong>Formato:</strong> <?php echo $formato; ?></div>
                        <div class="col-md-4"><strong>Data de Instauração:</strong> <?php echo $dataF; ?></div>
                    </div>

                    <hr>
                   
                    <ul class="list-group">
                        <?php 
                        if (count($res2) > 0) {
                            foreach ($res2 as $mov) {
                                $data_mov = $mov['data'];
                                $texto_mov = $mov['texto'];
                                $data_movF = implode('/', array_reverse(explode('-', $data_mov)));
                        ?>
                            <li class="list-group-item">
                                <strong><?php echo $data_movF; ?>:</strong> <?php echo $texto_mov; ?>
                            </li>
                        <?php 
                            }
                        } else {
                            echo "<li class='list-group-item text-muted'>Nenhuma movimentação encontrada.</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <?php } // Fechando o loop ?>
    </div>

<?php 
} else {
    echo '<p class="text-danger">Não há processo lançado para esse número '.$numero.'</p>';
}
?>
