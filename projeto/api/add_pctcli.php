<?php
require_once("./funcoes_api.php");
require('connect.php');

$id = $_SESSION['user_id'];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);

    $fun = funcionario();
    $nome = "Pacote ";
    try{
        $stmt = $conn->prepare('INSERT INTO cli_pacote(id_cliente, id_pacote,id_user, id_fun, pagamento_status, desconto)
         VALUES (:id_cli, :id_pct,:id_user, :id_fun, :pag_status, :desconto)');
         $stmt->bindParam(':id_cli', $dados['id']);
         $stmt->bindParam(':id_pct', $dados['pacotes']);
         $stmt->bindParam(':pag_status', $dados['pagamento']);
         $stmt->bindParam(':desconto', $dados['desconto']);
         ($fun != null) ?          $stmt->bindParam(':id_fun', $fun) : 
         $stmt->bindParam(':id_fun', $fun, PDO::PARAM_NULL);
         $stmt->bindParam(':id_user', $id);

     

         if($stmt->execute()){
            echo json_encode(['response' => 'Pacote adicionado']);
         }
    } catch(PDOException $e){
        echo json_encode(['response' => "erro ao adicionar pacote",  "code" => $e]);

    }
}