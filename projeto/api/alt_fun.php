<?php
 session_start();
 require("connect.php");
    $id = $_SESSION['user_id'];
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);

if($_SERVER['REQUEST_METHOD'] === 'POST' && $id != 0){
    try{
    $stmt = $conn->prepare("UPDATE funcionarios SET nome = :nome, telefone = :tel, telefone_2 = :tel2,
     rua = :rua, numero_casa = :num, bairro = :bairro, funcao = :funcao, salario = :salario,
       comicao = :comicao
    WHERE id = :id_fun AND id_user = :id");
      $stmt->bindParam(':nome', $dados['nome']);
      $stmt->bindParam(':tel', $dados['telefone']);
      $stmt->bindParam(':tel2', $dados['telefone2']);
      $stmt->bindParam(':rua', $dados['rua']);
      $stmt->bindParam(':num', $dados['numero']);
      $stmt->bindParam(':bairro', $dados['bairro']);
      $stmt->bindParam(':funcao', $dados['funcao']);
      $stmt->bindParam(':salario', $dados['salario']);
      $stmt->bindParam(':comicao', $dados['comicao']);
      $stmt->bindParam(':id_fun', $dados['id']);
      $stmt->bindParam(":id", $id);
      $stmt->execute();
   
        echo json_encode(['Sucess' => true, 'response' => 'Funcionário Alterado com Sucesso']);
 }
      catch(PDOException $e){
        echo json_encode(['response' => "erro ao alterar funcionário", 'error' => $e->getMessage()]);
  
    }
} 
