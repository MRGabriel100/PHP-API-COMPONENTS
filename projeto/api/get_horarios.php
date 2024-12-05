<?php
require('connect.php');

if($_SERVER['REQUEST_METHOD'] === 'GET'){

    try {

    $query = "SELECT TIME_FORMAT(hora, '%H:%i') FROM hora_disponivel";
    if($_GET['filtro1'] == 1){
      $query = $query . ' WHERE id_hora BETWEEN 19 AND 37';
    } else {
        $query = $query . ' WHERE id_hora > 14';
    }
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($query);
    $stmt->execute();
         // $stmt->debugDumpParams();
    $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
  echo json_encode($result);
}
catch(PDOException $e) {
    // Em caso de erro, retorna uma mensagem de erro
    echo json_encode(["response" => "Erro ao buscar horários",   "code" => $e]);
}
}