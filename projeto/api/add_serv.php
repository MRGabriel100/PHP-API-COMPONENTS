<?php

require_once("./funcoes_api.php");
require('connect.php');

$id = $_SESSION['user_id'];
$fun = funcionario();


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);
    try{
        $stmt = $conn->prepare('INSERT INTO wp_servicos(id_user, nome_serv, valor_serv, tempo_serv,
        tipo, quantidade, fun_id)
         VALUES (:id, :servico, :valor, :hora, :tipo, :qtd, :id_fun)');
         $stmt->bindParam(':id', $id);
         $stmt->bindParam(':servico', $dados['servico']);
         $stmt->bindParam(':valor', $dados['valor']);
         $stmt->bindParam(':hora', $dados['tempo']);
         $stmt->bindParam(':tipo', $dados['tipo']);
         $stmt->bindParam(':qtd', $dados['quantidade']);
         ($fun != null) ? $stmt->bindParam(':id_fun', $fun) : 
         $stmt->bindParam(':id_fun', $fun, PDO::PARAM_NULL);
         if($stmt->execute()){
            echo json_encode(['response' => 'serviço adicionado']);
         }
    } catch(PDOException $e){
        echo json_encode(["response" => "erro ao adicionar serviço",  "code" => $e]);

    }
}