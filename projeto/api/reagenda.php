<?php 
 session_start();
 require("connect.php");
    $id = $_SESSION['user_id'];
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);
if($_SERVER['REQUEST_METHOD'] === 'POST' && $id != 0){

    $stmt = $conn->prepare("UPDATE wp_agenda SET data = :data, hora = :hora, hora_fim = :horaf
      WHERE id_agenda = :ag_id AND user_id = :id");
      $stmt->bindParam(":data", $dados['data']);
      $stmt->bindParam(":hora", $dados['horario']);
      $stmt->bindParam(":horaf", $dados['hora_f']);
      $stmt->bindParam(":ag_id", $dados['id_agenda']);
      $stmt->bindParam(":id", $id);

      if($stmt->execute()){
       
        echo json_encode(['Sucess' => true, 'response' => 'Horário Reagendado com Sucesso']);
      } else
      {
        echo json_encode(['response' => 'erro ao Reagendar',   "code" => $e]);
      }
} 

?>