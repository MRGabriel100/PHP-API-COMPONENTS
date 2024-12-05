<?php
    require_once("./funcoes_api.php");
    require("connect.php");
    $id = $_SESSION['user_id'];

    $fun = funcionario();
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $json = file_get_contents('php://input');
        $dados = json_decode($json, true);
        $checkbox = $dados['recorrente'] ??  0;

        $stmt = $conn->prepare("INSERT INTO wp_financeiro(user_id, fin_nome, fin_valor, fin_recorr, fin_data, tipo, status, fun_id)
         VALUES (:id, :nome, :valor, :recorr, :data, :tipo, 'OK', :id_fun)");

         $stmt->bindParam(":id", $id);
         $stmt->bindParam(":nome", $dados['gasto']);
         $stmt->bindParam(":valor", $dados['valor']);
         $stmt->bindParam(":recorr", $checkbox, PDO::PARAM_BOOL);
         $stmt->bindParam(":data", $dados['data']);
         $stmt->bindParam(":tipo", $dados['tipo']);
         ($fun != null) ? $stmt->bindParam(':id_fun', $fun) : 
         $stmt->bindParam(':id_fun', $fun, PDO::PARAM_NULL);

         try{
         $stmt->execute();
         echo json_encode(['response' => strtolower($dados['tipo']) . ' adicionada']);
    } catch(PDOException $e){
        echo json_encode(["response" => "erro ao inserir saída",  "code" => $e]);
  
    }
}