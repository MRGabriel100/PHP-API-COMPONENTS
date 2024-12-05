<?php
 session_start();
 require("connect.php");
    $id = $_SESSION['user_id'];
     $dados = json_decode(file_get_contents('php://input'), true);

if($_SERVER['REQUEST_METHOD'] === 'POST' && $id != 0){
    try{

    foreach($dados as $dado){
    $stmt = $conn->prepare("UPDATE wp_financeiro 
    SET fin_nome = :nome, fin_valor = :valor 
    WHERE fin_id = :fin_id AND user_id = :id");
      $stmt->bindParam(":fin_id", $dado['id']);
      $stmt->bindParam(":valor", $dado['valor']);
      $stmt->bindParam(":nome", $dado['nome']);
      $stmt->bindParam(":id", $id);
      $stmt->execute();


    }   
  
        echo json_encode(['Sucess' => true, 'response' => 'Alterado com Sucesso']);
 }
      catch(PDOException $e){
        echo json_encode(["response" => "erro ao alterar",  "code" => $e]);
  
    }
} 
