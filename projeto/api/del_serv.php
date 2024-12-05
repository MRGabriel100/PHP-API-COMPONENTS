<?php

require('connect.php');
    session_start();


    $id = $_SESSION['user_id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        $dados = json_decode(file_get_contents('php://input'), true);
        if($dados !== null && $id !== 0){
    $stmt = $conn->prepare('UPDATE wp_servicos SET ativo = 0 WHERE id_serv = :ids AND id_user = :uid');
    $stmt->bindParam(':ids', $dados['aux1']);
    $stmt->bindParam(':uid', $id);
    
   
    try{
        $stmt->execute();
        echo json_encode(['success' => true, 'response' => 'Item Removido com Sucesso']);
   } catch(PDOException $e){
    echo json_encode(["response" => "erro ao remover item",  "code" => $e]);

}
    }
} 
?>