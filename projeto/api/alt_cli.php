<?php
 session_start();
 require("connect.php");
    $id = $_SESSION['user_id'];
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);

if($_SERVER['REQUEST_METHOD'] === 'POST' && $id != 0){
    try{
    $stmt = $conn->prepare("UPDATE clientes SET nome = :nome, telefone = :fone, rua = :rua,
    numero_casa = :numero, bairro = :bairro
    WHERE id = :id_cli AND id_user = :id");
      $stmt->bindParam(":nome", $dados['nome']);
      $stmt->bindParam(":fone", $dados['telefone']);
      $stmt->bindParam(":rua", $dados['rua']);
      $stmt->bindParam(":numero", $dados['rua_nu']);
      $stmt->bindParam(":bairro", $dados['bairro']);
      $stmt->bindParam(":id_cli", $dados['id']);
      $stmt->bindParam(":id", $id);
      $stmt->execute();
   
        echo json_encode(['Sucess' => true, 'response' => 'Cliente Alterado com Sucesso']);
 }
      catch(PDOException $e){
        echo json_encode(['response' => "erro ao alterar cliente",  "code" => $e]);
  
    }
} 
