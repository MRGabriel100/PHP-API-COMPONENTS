<?php
    session_start();
if ( !defined('ABSPATH') ) {
    define('ABSPATH', dirname(__FILE__, 5) . '/');
}
// Carregue o wp-load.php usando ABSPATH
require_once ABSPATH . 'wp-load.php';
require_once( ABSPATH . 'wp-admin/includes/user.php');
require('connect.php');



    $id = $_SESSION['user_id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        $dados = json_decode(file_get_contents('php://input'), true);
        if($dados !== null && $id !== 0){
      
    $stmt = $conn->prepare('UPDATE funcionarios SET ativo = 0 WHERE id = :ids AND id_user = :uid');
    $stmt->bindParam(':ids', $dados['aux1']);
    $stmt->bindParam(':uid', $id);
    
    $args = array(
        'meta_key' => 'tab_id',
        'meta_value' => $dados['aux1'],
        'number' => 1,
    );

    $user = get_users($args);
    $user = $user[0]->ID;
    try{
        wp_delete_user($user);
        $stmt->execute();
        echo json_encode(['success' => true, 'response' => 'Colaborador Removido com Sucesso']);
   } catch(PDOException $e){
    echo json_encode(["response" => "erro ao remover colaborador"]);

}
    }
} 
?>