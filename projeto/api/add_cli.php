<?php
session_start();
$id = $_SESSION['user_id'];
require('connect.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);
    try{
        $stmt = $conn->prepare('INSERT INTO clientes(nome, telefone, nascimento, rua, numero_casa, bairro, id_user)
         VALUES (:nome, :tel, :nasc, :rua, :num, :bairro, :id_user)');
         $stmt->bindParam(':nome', $dados['nome']);
         $stmt->bindParam(':tel', $dados['fone']);
         $stmt->bindParam(':nasc', $dados['nascimento']);
         $stmt->bindParam(':rua', $dados['rua']);
         $stmt->bindParam(':num', $dados['numero']);
         $stmt->bindParam(':bairro', $dados['bairro']);
         $stmt->bindParam(':id_user', $id);
         if($stmt->execute()){
            echo json_encode(['response' => 'Cliente adicionado']);
         }
    } catch(PDOException $e){
        echo json_encode(['response' => "erro ao adicionar cliente",  "code" => $e]);

    }
}