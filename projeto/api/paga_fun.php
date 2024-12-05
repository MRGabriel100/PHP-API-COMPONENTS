<?php
    session_start();
    require("connect.php");
    $id = $_SESSION['user_id'];



    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $json = file_get_contents('php://input');
        $dados = json_decode($json, true);
        $partes = explode(' ',  $dados['nome']);
        $nome = 'Salário ' . $partes[0];
        $data =  date("Y-m-d");
        $checkbox = $dados['recorrente'] ??  0;

        $stmt = $conn->prepare("INSERT INTO wp_financeiro(user_id, fin_nome, fin_valor, fin_data, tipo, status)
         VALUES (:id, :nome, :valor, :data, 'SAIDA', 'OK')");

         $stmt->bindParam(":id", $id);
         $stmt->bindParam(":nome", $nome);
         $stmt->bindParam(":valor", $dados['salario']);
         $stmt->bindParam(":data", $data);

         try{
         $stmt->execute();
         echo json_encode(['response' => 'Pagamento Realizado']);
    } catch(PDOException $e){
        echo json_encode(["response" => "erro ao realizar pagamento",  "code" => $e]);
  
    }
}