<?php
    
    require_once("./funcoes_api.php");
    require("connect.php");

    $id = $_SESSION['user_id'];
    $fun = funcionario();

    $queryx0 = "";
    $queryx = "";
    $queryx2 = "";

    if($fun != null){
        $queryx0 = " AND clp.id_fun = :fun_id ";
        $queryx = " AND WP_F.fun_id = :fun_id ";
        $queryx2 = " AND wa.profissional_id = :fun_id ";
    }
    //$dados = $_GET['filtro1'];

    parse_str($_GET['filtro1'], $dados);

        $query0 = "SELECT f.telefone AS fone, clp.id AS id, CONCAT('Pacote ', ws.nome_serv) AS servico, c.nome AS cliente,
        DATE_FORMAT(clp.data, '%d/%m/%Y') AS dia, 'ENTRADA' AS tipo, ws.valor_serv AS valor,
        (ws.valor_serv - clp.desconto) AS total, 1 AS quantidade, 
        clp.desconto AS desconto, f.nome as profissional, f.comicao as comicao
        from cli_pacote clp
        inner join wp_servicos ws on ws.id_serv = clp.id_pacote
        inner join clientes c on c.id = clp.id_cliente
        LEFT JOIN funcionarios f ON f.id = clp.id_fun
        WHERE clp.data BETWEEN :inicio AND :fim AND clp.id_user = :id" . $queryx0;

        $query1 = "SELECT f.telefone AS fone, fin_id AS id, fin_nome
         AS servico,'' AS cliente, DATE_FORMAT(fin_data, '%d/%m/%Y') AS dia, tipo, fin_valor AS valor,
         WP_F.fin_valor AS total, 1 AS quantidade, '' AS desconto , f.nome as profissional, f.comicao as comicao
         FROM wp_financeiro WP_F
         LEFT JOIN funcionarios f ON WP_F.fun_id = f.id
        WHERE  WP_F.fin_data BETWEEN :inicio AND :fim
       AND WP_F.user_id = :id AND WP_F.status = 'OK'";

       $query2 = " UNION ALL SELECT f.telefone AS fone, wa.id_agenda as id,
       ws.nome_serv as servico,
        wa.nome_cli as cliente,
        DATE_FORMAT(wa.`data`, '%d/%m/%Y') as dia,
        'ENTRADA' as tipo,
        wa.serv_valor  as valor,
        (wa.serv_valor - wa.desconto) AS total,
        1 AS quantidade,
        wa.desconto as desconto,
        f.nome as profissional,
        f.comicao as comicao 
        from wp_agenda wa inner join wp_servicos ws on wa.serv_id = ws.id_serv
      left join funcionarios f on wa.profissional_id = f.id 
       WHERE wa.data BETWEEN :inicio AND :fim AND wa.user_id = :id AND ws.tipo = 'serviço'" ;

       $query3 = "SELECT f.telefone AS fone, fin_id AS id, fin_nome AS servico,'' AS cliente, DATE_FORMAT(fin_data, '%d/%m/%Y') AS dia, tipo, SUM(fin_valor) AS valor,
        WP_F.fin_valor AS total, 1 AS quantidade ,'' AS desconto , f.nome as profissional,
       f.comicao as comicao FROM wp_financeiro WP_F
       LEFT JOIN funcionarios f ON WP_F.fun_id = f.id
      WHERE  WP_F.fin_data BETWEEN :inicio AND :fim
     AND WP_F.user_id = :id AND WP_F.status = 'OK' " . $queryx . " group by fin_id
     UNION ALL SELECT f.telefone AS fone, wa.id_agenda as id,
     ws.nome_serv as servico,
      '' as cliente,
      DATE_FORMAT(wa.`data`, '%d/%m/%Y') as dia,
      'ENTRADA' as tipo,
      wa.serv_valor  as valor,
      SUM(wa.serv_valor - wa.desconto) AS total,
      COUNT(wa.id_agenda) as quantidade,
      SUM(wa.desconto) as desconto,
      f.nome as profissional,
      f.comicao as comicao 
      from wp_agenda wa inner join wp_servicos ws on wa.serv_id = ws.id_serv
    left join funcionarios f on wa.profissional_id = f.id 
     WHERE wa.data BETWEEN :inicio AND :fim AND wa.user_id = :id AND ws.tipo = 'serviço' " . $queryx2 . " 
      group by wa.serv_id, profissional";
        
        switch($dados['tipo_relatorio']){

            case "SAIDA":$query =  $query1 . $queryx. " AND WP_F.tipo = 'SAIDA' ORDER BY dia";
            break;

            case "ENTRADA":$query = $query0 . " UNION ALL " .  $query1 . $queryx . " AND WP_F.tipo = 'ENTRADA'" . $query2 . $queryx2 . " ORDER BY dia, profissional";
            break;

            case "COMPLETO":$query = $query0 . " UNION ALL " .  $query1 . $queryx . $query2 . $queryx2 . "  ORDER BY dia,tipo, profissional";
            break;
            
            case "SIMPLES":$query = $query0 . " UNION ALL " .  $query3 . $queryx2 . $queryx2. "  ORDER BY dia,tipo, profissional";
            break;

            default: null;
        };

    $params = array(":id" => $id, ":inicio" => $dados['de'],":fim" => $dados['ate']);
 
    //echo $query;
    function bindParams(PDOStatement $stmt, array $params){
        foreach($params as $key => &$value){
            $stmt->bindParam($key, $value);
        }
    }
    if($_SERVER['REQUEST_METHOD'] === 'GET'){

        $stmt = $conn->prepare($query);
       bindParams($stmt, $params);
       ($fun != null) ? $stmt->bindParam(":fun_id", $fun) : null;
        try{
            $stmt->execute();
         //   echo $stmt->queryString;
            $resultados = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $resultados[] = $row;
            }
        echo json_encode($resultados);
        }
        catch(PDOException $e){
            echo json_encode(["response" => "erro ao gerar relatório",  "code" => $e->getMessage() ]);
 
        }
    }