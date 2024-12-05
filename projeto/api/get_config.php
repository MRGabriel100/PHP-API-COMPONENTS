<?php
require('connect.php');

session_start();
$id = $_SESSION['user_id'];
if($_SERVER['REQUEST_METHOD'] === 'GET'){

    try {

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT TIME_FORMAT(hora_inicio, '%H:%i') as hora_inicio,
     TIME_FORMAT(hora_fim, '%H:%i') as hora_fim
    FROM user_config WHERE id_user = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/JSON');
  echo json_encode($result);
}
catch(PDOException $e) {
    // Em caso de erro, retorna uma mensagem de erro
    echo "Erro: " . $e->getMessage();
}
}