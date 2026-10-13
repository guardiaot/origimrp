<?php
require_once("conexao.php");

$senha = password_hash('1234567', PASSWORD_DEFAULT);

$query = $pdo->prepare("UPDATE usuarios SET senha_crip = :senha WHERE email = :email");

$query->bindValue(":senha", "$senha");
$query->bindValue(":email", "guardiaot@gmail.com"); // Replace with actual email
$query->execute();

echo 'Senha alterada com Sucesso';
