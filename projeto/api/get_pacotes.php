<?php

session_start();
$id = $_SESSION['user_id'];
require('connect.php');

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    try{

        $stmt = $conn->prepare("SELECT id, nome, valor, quantidade FROM pacotes WHERE id_user = :id AND ativo = 1");
        $stmt-> bindParam(':id', $id);
        $stmt->execute();
        $resultados = array();
        while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){

            $resultados[] = $row;
        }
 
        echo json_encode($resultados);
    }catch(PDOException $e){
        echo json_encode(["response" => "erro ao buscar Pacotes", "code" => $e->getMessage()]);

    }
}