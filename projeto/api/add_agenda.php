<?php
require("connect.php");

$json = file_get_contents('php://input');
$dados = json_decode($json, true);
$infos = explode( ', ', $dados['servico']);
if (isset($dados['tipo'])){

   try {
      $cli = 0;
 
      //var_dump($_POST);
         $stmt = $conn -> prepare("INSERT INTO wp_agenda (user_id, data, hora, hora_fim, nome_cli, 
          tel_cli, serv_id, serv_valor)
          VALUES (:id, :data, :hora, :hora_fim,
           :nome_cli, :tel_cli, :serv_id, :serv_valor)");
  
          $stmt->bindParam(':id', $dados['tipo']);
           $stmt->bindParam(':data', $dados['data']);
           $stmt->bindParam(':hora', $dados['horario']);
           $stmt->bindParam(':hora_fim', $dados['hora_fim']);
           $stmt->bindParam(':nome_cli', $dados['cliente']);
          // $stmt->bindParam(':endereco_cli',"");
           $stmt->bindParam(':tel_cli', $dados['cli_tel']);
           $stmt->bindParam(':serv_id', $infos[0]);
           //$stmt->bindParam(':serv_id', "");
           $stmt->bindParam(':serv_valor', $dados['valor']);
  
       
              $stmt->execute();
              echo json_encode(['success' => true, 'response' => 'Horário Marcado']);
           } catch(PDOException $e){
              echo json_encode(["response" => "erro ao agendar", "CODE" => $e->getMessage()]);
              exit();
          }

} else {
require_once("./funcoes_api.php");

   $id = $_SESSION['user_id'];

    try{
    
    $pct = null;
    if(isset($infos[1]) && $infos[1] == 'pacote' ){
      $pct = $infos[2];
      $stmt = $conn-> prepare("UPDATE cli_pacote CLP
            INNER JOIN wp_servicos WP ON WP.id_serv = CLP.id_pacote
             SET CLP.qtd_feitos = 
      CASE WHEN CLP.qtd_feitos < WP.quantidade THEN CLP.qtd_feitos + 1
         ELSE CLP.qtd_feitos
         END,
         CLP.ativo = CASE WHEN CLP.qtd_feitos >=  WP.quantidade then 0
         ELSE CLP.ativo
         END
       WHERE 
      CLP.id_cliente = :id_cli AND CLP.id_user = :id AND CLP.ativo = 1 AND CLP.id_pacote = :id_pacote
      AND CLP.id = :id_pct");

      $stmt->bindParam(':id', $id);
      $stmt->bindParam(':id_cli', $dados['id_cli']);
      $stmt->bindParam(':id_pacote', $infos[0]);
      $stmt->bindParam(':id_pct', $pct);
      $stmt->execute();
} } catch(PDOException $e){
   echo json_encode(["response" => "erro"]);
   exit();
} 
   
   try {
    $cli = $dados['id_cli'] ?? '';
      
    $prof2 = isset($dados['funcionario']) && !empty($dados['funcionario']) && $dados['funcionario'] > 0 ? funcionario($dados['funcionario']) : funcionario();

    //var_dump($_POST);
       $stmt = $conn -> prepare("INSERT INTO wp_agenda (user_id, data, hora, hora_fim, id_cli, nome_cli, 
        tel_cli, serv_id, serv_valor, desconto, profissional_id, cli_pct_id)
        VALUES (:id, :data, :hora, :hora_fim, :id_cli,
         :nome_cli, :tel_cli, :serv_id, :serv_valor, :desconto, :prof_id, :id_pct)");

        $stmt->bindParam(':id', $id);
         $stmt->bindParam(':data', $dados['data']);
         $stmt->bindParam(':hora', $dados['horario']);
         $stmt->bindParam(':hora_fim', $dados['hora_fim']);
         $stmt->bindParam(':id_cli', $cli);
         $stmt->bindParam(':nome_cli', $dados['cliente']);
        // $stmt->bindParam(':endereco_cli',"");
         $stmt->bindParam(':tel_cli', $dados['cli_tel']);
         $stmt->bindParam(':serv_id', $infos[0]);
         //$stmt->bindParam(':serv_id', "");
         $stmt->bindParam(':serv_valor', $dados['valor']);
         $stmt->bindParam(':desconto', $dados['desconto']);
        ($prof2 === null) ? $stmt->bindParam(':prof_id', $prof2, PDO::PARAM_NULL) :
        $stmt->bindParam(':prof_id', $prof2);
        ($pct === null) ? $stmt->bindParam(":id_pct", $pct, PDO::PARAM_NULL) :
        $stmt->bindParam(':id_pct', $pct);

     
            $stmt->execute();
            echo json_encode(['success' => true, 'response' => 'Horário Marcado']);
         } catch(PDOException $e){
            echo json_encode(["response" => "erro ao agendar", "CODE" => $e->getMessage()]);
            exit();
        }
      }