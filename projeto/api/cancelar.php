<?php

require('connect.php');
    session_start();

    try{

    $id = $_SESSION['user_id'];


       
    $dados = json_decode(file_get_contents('php://input'), true);
        if($dados['aux1'] !== null && $id !== 0){

    if($dados['aux2'] == "pacote"){

        $stmt2 = $conn->prepare('UPDATE cli_pacote SET qtd_feitos = qtd_feitos - 1,
        ativo = CASE WHEN ativo = 0 THEN 1 ELSE ativo END WHERE id_user = :id
        AND id = :idp');
        $stmt2->bindParam(':idp', $dados['aux3']);
        $stmt2->bindParam(':id', $id);
        $stmt2->execute();
    }
    $stmt = $conn->prepare('DELETE FROM wp_agenda WHERE id_agenda = :ida AND user_id = :uid');
    $stmt->bindParam(':ida', $dados['aux1']);
    $stmt->bindParam(':uid', $id);
    $stmt->execute();

        echo json_encode(['response' => 'Item Removido com Sucesso']);
    } 
} catch(PDOException $e){
    echo json_encode(["response" => "erro ao remover item", "code" => $e->getMessage()]);
 
}
