<?php

require_once("./funcoes_api.php");
    $id = '';
    if(isset($_SESSION['user_id'])){
        $id = $_SESSION['user_id'];

        $func = isset($dados['filtro2']) && !empty($dados['filtro2']) && $dados['filtro2'] > 0
        && $dados['filtro2'] != 'undefined' ? funcionario($dados['filtro2']) : funcionario();
    } else {
        $id = $_GET['filtro2'];

        $func = funcionario();
    }
    if($id > 0){

        try {
            require("connect.php");
            $dados = $_GET;

          

            $query = "SELECT AG.id_agenda,
            AG.data,
             TIME_FORMAT(AG.hora, '%H:%i') AS hora,
              TIME_FORMAT(AG.hora_fim, '%H:%i') AS hora_fim, 
              AG.nome_cli, 
              AG.tel_cli, 
              AG.serv_valor,
              AG.desconto,
              AG.cli_pct_id,
              PF.nome,
           SE.nome_serv,
           SE.tipo
           FROM wp_agenda AG
           JOIN wp_servicos SE ON AG.serv_id = SE.id_serv AND AG.user_id = SE.id_user
           LEFT JOIN funcionarios PF ON AG.profissional_id = PF.id
           WHERE AG.user_id = :id AND AG.data = :data";

           if($func == null){ $query = $query . " AND AG.profissional_id IS NULL";}
           else { $query = $query . " AND AG.profissional_id = :profissional";}

           $query = $query . " ORDER BY hora";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":data", $dados['filtro1']);
            ($func != null) ? $stmt->bindParam(":profissional", $func) : null;
            $stmt->execute();
            $resultados = array();
            while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $result = array(
                    'id' => $row['id_agenda'],
                    'data' => $row['data'],
                    'hora' => $row['hora'],
                    'ate' => $row['hora_fim'],
                    'cliente' => $row['nome_cli'],
                    'id_pct' => $row['cli_pct_id'],
                    'telefone' => $row['tel_cli'],
                    'serviço' => $row['nome_serv'],
                    'valor' => $row['serv_valor'],
                    'desconto' => $row['desconto'],
                    'profissional' => $row['nome'],
                    'tipo_serv' => $row['tipo']
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
    }
