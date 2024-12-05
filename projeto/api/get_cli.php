<?php

session_start();

$id = $_SESSION['user_id'];
require('connect.php');
    $dados = $_GET;
    $extra = "";
 
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    try{

        $stmt = $conn->prepare("SELECT CL.id, CL.nome, CL.telefone, 
        DATE_FORMAT(CL.nascimento, '%d/%m/%Y') AS nascimento, CL.rua, CL.numero_casa, CL.bairro, 
        group_concat( 
        concat ( coalesce(CP.id_pacote, ''), ' - ',
            coalesce ( DATE_FORMAT(CP.`data`, '%d/%m/%Y'), '') , ' - ',
        coalesce (CP.qtd_feitos, '') , ' - ',
        coalesce (CP.pagamento_status, '')  , ' - ',
        coalesce (P.nome_serv, '')  , ' - ',
        coalesce (P.quantidade, '')  , ' - ') separator ', ') as PACOTES
        FROM clientes CL 
        left join cli_pacote CP on CP.id_cliente = CL.id AND CP.ativo = 1
        left join wp_servicos P on P.id_serv = CP.id_pacote 
        WHERE CL.id_user = :id AND CL.ativo = 1 AND CL.nome 
         LIKE CONCAT('%', :nome, '%') GROUP BY CL.id
        ");

        $stmt-> bindParam(':id', $id);
        $stmt-> bindParam(':nome', $dados['filtro1']);
        $stmt->execute();

        $resultados = array();
        while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){

            $pacotes = explode(',', $row['PACOTES']);
            $pacotes_array = array();

            foreach($pacotes as $pacote){
                $dados_pacote = explode('-', $pacote);
            
                 $pacotes_array[] = ($dados_pacote[5] <= $dados_pacote[2]) ?
                '' : ['id_pct' => $dados_pacote[0],
                    'data_pct' => $dados_pacote[1],
                    'qtd_pct' => $dados_pacote[2],
                    'paga_pct' => $dados_pacote[3],
                    'nome_pct' => $dados_pacote[4],
                    'total_pct' => $dados_pacote[5]];
            }
            $row['PACOTES'] = $pacotes_array;
            $resultados[] = $row;
        }
        
        echo json_encode($resultados);
    }catch(PDOException $e){
        echo json_encode(['response' => "erro ao buscar Cliente"]);

    }
}