<?php

session_start();
$id = $_SESSION['user_id'];
require('connect.php');
    $dados = $_GET;
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    try{

        $stmt = $conn->prepare("SELECT id, nome, DATE_FORMAT(nascimento, '%d/%m/%Y') AS nascimento, telefone, telefone_2, rua, numero_casa, bairro,
        funcao, salario, data_pag, comicao
        FROM funcionarios
        WHERE id_user = :id AND ativo = 1");
        $stmt-> bindParam(':id', $id);
        $stmt->execute();
        $resultados = array();
        while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){

            $resultados[] = $row;
        }
        
        echo json_encode($resultados);
    }catch(PDOException $e){
        echo json_encode(['response' => "erro ao buscar Funcionários", 'code' => $e->getMessage()]);

    }
}