<?php
 session_start();
 require("connect.php");
    $id = $_SESSION['user_id'];
     $dados = json_decode(file_get_contents('php://input'), true);

if($_SERVER['REQUEST_METHOD'] === 'POST' && $id != 0){
    try{

        foreach($dados as $dado){
    $stmt = $conn->prepare("UPDATE wp_servicos SET nome_serv = :serv, valor_serv = :valor, tempo_serv = :tempo,
    tipo = :tipo, quantidade = :quantidade
    WHERE id_serv = :id_serv AND id_user = :id");
      $stmt->bindParam(":serv", $dado['servico']);
      $stmt->bindParam(":valor", $dado['valor']);
      $stmt->bindParam(":tempo", $dado['horario']);
      $stmt->bindParam(":id_serv", $dado['id']);
      $stmt->bindParam(":tipo", $dado['tipo']);
      $stmt->bindParam(":quantidade", $dado['quantidade']);
      $stmt->bindParam(":id", $id);
      $stmt->execute();
    }   
  
        echo json_encode(['Sucess' => true, 'response' => 'Serviço Alterado com Sucesso']);
 }
      catch(PDOException $e){
        echo json_encode(['response' => "erro ao alterar serviço",  "code" => $e]);
  
    }
} 
