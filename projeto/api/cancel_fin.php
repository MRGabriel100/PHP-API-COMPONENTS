<?php
 session_start();
 require("connect.php");
    $id = $_SESSION['user_id'];
     $dados = json_decode(file_get_contents('php://input'), true);

if($_SERVER['REQUEST_METHOD'] === 'POST' && $id != 0){
    try{

    $stmt = $conn->prepare("UPDATE wp_financeiro 
    SET status = :status
    WHERE fin_id = :fin_id AND user_id = :id");
      $stmt->bindParam(":status", $dados['status']);
      $stmt->bindParam(":fin_id", $dados['id']);
      $stmt->bindParam(":id", $id);
      $stmt->execute(); 

        echo json_encode(['Sucess' => true, 'response' => 'Cancelado']);
 }
      catch(PDOException $e){
        echo json_encode(["response" => "erro ao Cancelar",  "code" => $e]);
  
    }
} 
