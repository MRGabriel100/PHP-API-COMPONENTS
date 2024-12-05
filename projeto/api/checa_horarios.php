<?php
require_once("./funcoes_api.php");

   

        try {
            require("connect.php");
            $dados = $_GET;

            $func = isset($dados['filtro2']) && !empty($dados['filtro2']) && $dados['filtro2'] > 0 ?
             funcionario($dados['filtro2']) : funcionario();


            $query = "SELECT COUNT(id_agenda) AS agendamentos
            FROM wp_agenda wa
            WHERE wa.user_id = :id
              AND wa.data = :data
              AND  (wa.hora >= :inicio AND wa.hora < :fim
              OR wa.hora_fim > :inicio AND wa.hora_fim < :fim)
            ";

if(!isset($_SESSION['user_id'])){
    $id = $_GET['filtro2'];
    $func = null;
} else {
    $id = $_SESSION['user_id'];
    if($func == "null" || $func == ""){ $query = $query . " AND wa.profissional_id IS NULL ";}
    else { $query = $query . " AND wa.profissional_id = :profissional ";}
}
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":data", $dados['filtro1']);
            $stmt->bindParam(":inicio", $dados['inicio']);
            $stmt->bindParam(":fim", $dados['fim']);
            ($func != "null") ? $stmt->bindParam(":profissional", $func) : null;
            $stmt->execute();
            $resultados = array();
            while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
               
                $resultados[] = $row;
            }
          // $stmt->debugDumpParams();
            echo json_encode($resultados);
 
        }
        catch(PDOException $e) {
            // Em caso de erro, retorna uma mensagem de erro
            echo json_encode(["response" => "Erro ao Buscar Agenda",  "code" => $e->getMessage()]);
        }
    
