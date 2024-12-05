<?php
require_once("./funcoes_api.php");
require('connect.php');

$id = '';

if(isset($_SESSION['user_id'])){
    $id = $_SESSION['user_id'];
    $nivel = $_SESSION['tipo'];
} else {

    $id = $_GET['filtro2'];
    $nivel = '';
}

$cliente = '';
$fun = funcionario();

if($_SERVER['REQUEST_METHOD'] === 'GET'){

    $dados = $_GET;
    $cliente = $dados['filtro2'];
    $extra = "";
  
    if($fun){
        $extra = "  AND fun_id IS NULL OR fun_id = :fun_id ";
    }
    try{

        switch($dados['filtro1']){

            case "pacotes": $query ="SELECT id_serv AS id,fun_id AS funcionario, nome_serv AS servico, valor_serv AS valor,
            TIME_FORMAT(tempo_serv, '%H:%i') AS tempo,
             tipo, quantidade FROM wp_servicos WHERE id_user = :id AND 
           ativo = 1 AND tipo = 'pacote'" . $extra. " AND tipo = 'pacote'";
            break;
            
            case "completo": $query = "SELECT id_serv AS id,fun_id AS funcionario, nome_serv AS servico, valor_serv AS valor,
            TIME_FORMAT(tempo_serv, '%H:%i') AS tempo,
             tipo, quantidade FROM wp_servicos WHERE id_user = :id AND 
           ativo = 1" . $extra;
           break;

           case "simples": $query = "SELECT id_serv AS id,fun_id AS funcionario, nome_serv AS servico, valor_serv AS valor,
           TIME_FORMAT(tempo_serv, '%H:%i') AS tempo,
            tipo, quantidade FROM wp_servicos WHERE id_user = :id AND 
          ativo = 1  AND tipo = 'serviço' " . $extra. "  AND tipo = 'serviço'  AND ativo = 1 ";
          break;

          case "cliente": $query = "SELECT id_serv AS id, nome_serv AS servico, valor_serv AS valor,
          TIME_FORMAT(tempo_serv, '%H:%i') AS tempo,
           tipo, quantidade, '' AS id_pct FROM wp_servicos 
                   WHERE id_user = :id AND 
         ativo = 1 and tipo = 'serviço' " .$extra." AND tipo = 'serviço' AND ativo = 1
         
          union all select id_serv as id, nome_serv as servico, valor_serv as valor,
         TIME_FORMAT(tempo_serv, '%H:%i') AS tempo,
          tipo, quantidade, cp.id AS id_pct
          from wp_servicos ws 
          inner join cli_pacote cp on ws.id_serv = cp.id_pacote
          where ws.id_user = :id and cp.ativo = 1 and cp.id_cliente = :cliente" .$extra;
          break;

          default: echo 'erro'; break;
        }
        $stmt = $conn->prepare($query);
        $stmt-> bindParam(':id', $id);
        if($dados['filtro1'] == 'cliente') {
            $stmt->bindParam(':cliente', $cliente);
        }
        ($fun != null) ? $stmt->bindParam(':fun_id', $fun) : null;
        $stmt->execute();
        $resultados = array();
        while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
            $row['nivel'] = $nivel;
            $resultados[] = $row;
        }
        echo json_encode($resultados);
    }catch(PDOException $e){
        echo json_encode(["response" => "erro ao buscar Serviços", "code" => $e->getMessage()]);

    }
}