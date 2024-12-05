<?php

        try {
            require("connect.php");
            $dados = $_GET;

            $query = "SELECT AG.id_agenda,
            AG.data,
             TIME_FORMAT(AG.hora, '%H:%i') AS hora,
              TIME_FORMAT(AG.hora_fim, '%H:%i') AS hora_fim
           FROM wp_agenda AG
           WHERE AG.user_id = :id AND AG.data = :data and hora between '09:00:00' AND '18:00:00'";
           $query = $query . " ORDER BY hora";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $dados['filtro2']);
            $stmt->bindParam(":data", $dados['filtro1']);
           
            $stmt->execute();
            $resultados = array();
            while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $result = array(
                    'id' => $row['id_agenda'],
                    'data' => $row['data'],
                    'hora' => $row['hora'],
                    'ate' => $row['hora_fim']
                   
                );
                $resultados[] = $result;
            }
          // $stmt->debugDumpParams();
            echo json_encode($resultados);
 
        }
        catch(PDOException $e) {
            // Em caso de erro, retorna uma mensagem de erro
            echo json_encode(["response" => "Erro ao Buscar Agenda",  "code" => $e->getMessage()]);
        }
    