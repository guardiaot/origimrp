<?php 
require_once("../../../conexao.php");

$id = $_POST['id'];

$statuss = [
    'Preparação' => 'Em Preparação',
    'Andamento' => 'Em Andamento',
    'Finalizado' => 'Finalizado / Baixado',
    'Cancelado' => 'Cancelado',
    'Arquivado' => 'Arquivado'
];

echo '<div class="list-group list-group-flush">';

foreach ($statuss as $status_bd => $status_nome) {

    $query = $pdo->prepare("
        SELECT COUNT(*) AS total 
        FROM processos 
        WHERE status = :status
        AND (
            advogado1 = :id 
            OR advogado2 = :id 
            OR advogado3 = :id 
            OR advogado4 = :id
        )
    ");

    $query->bindValue(":status", $status_bd);
    $query->bindValue(":id", $id);
    $query->execute();

    $res = $query->fetch(PDO::FETCH_ASSOC);
    $total = $res['total'];

    echo '
        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
            <span style="font-size: 17px; font-weight: 600;">
                '.$status_nome.'
            </span>
            <span class="badge bg-primary"
                  style="
                    font-size: 16px;
                    padding: 10px 16px;
                    border-radius: 20px;
                  ">
                '.$total.'
            </span>
        </div>
    ';
}

echo '</div>';
?>
