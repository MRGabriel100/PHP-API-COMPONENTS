<?php    
session_start();
if ( !defined('ABSPATH') ) {
    define('ABSPATH', dirname(__FILE__, 5) . '/');
}
// Carregue o wp-load.php usando ABSPATH
require_once ABSPATH . 'wp-load.php'; // Caminho correto para carregar o WordPress
require('connect.php');  // Inclua seu arquivo de conexão com o banco de dados aqui

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);
    $id = $_SESSION['user_id'];  // Obtém o ID do usuário da sessão

    // Função para adicionar subassociacao
    function add_subassociacao($parent_id, $sub_id, $conn) {
        add_user_meta($sub_id, 'parent_id', $parent_id);

        // Obter o tab_id do novo funcionário
        $stmt = $conn->prepare("SELECT id FROM funcionarios WHERE id_user = :id AND id = LAST_INSERT_ID()");
        $stmt->bindParam(':id', $parent_id);
        $stmt->execute();
        $tab_id = $stmt->fetchColumn();
        add_user_meta($sub_id, 'tab_id', $tab_id);
    }

    try {
        // Inserir dados do funcionário na tabela 'funcionarios'
        $stmt = $conn->prepare('INSERT INTO funcionarios(nome, nascimento, telefone, telefone_2,
            rua, numero_casa, bairro, funcao, salario, comicao, id_user)
            VALUES (:nome, :nasc, :tel, :tel2, :rua, :num, :bairro, :funcao, :salario, :comicao, :id_user)');
        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':tel', $dados['fone']);
        $stmt->bindParam(':tel2', $dados['fone2']);
        $stmt->bindParam(':nasc', $dados['nascimento']);
        $stmt->bindParam(':rua', $dados['rua']);
        $stmt->bindParam(':num', $dados['numero']);
        $stmt->bindParam(':bairro', $dados['bairro']);
        $stmt->bindParam(':funcao', $dados['funcao']);
        $stmt->bindParam(':salario', $dados['salario']);
        $stmt->bindParam(':comicao', $dados['comicao']);
        $stmt->bindParam(':id_user', $id);

        if ($stmt->execute()) {
            // Se a inserção for bem-sucedida, cria o usuário no WordPress
            $user = $dados['ema'];
            $senha = $dados['pass'];
            $sub_user_id = wp_create_user($user, $senha, $user);

            if (is_wp_error($sub_user_id)) {
                throw new Exception($sub_user_id->get_error_message());
            }

            // Adiciona a subassociacao e atribui o nível de associação
            add_subassociacao($id, $sub_user_id, $conn);

            $level = pmpro_changeMembershipLevel(5, $sub_user_id);

            if (!$level) {
                throw new Exception('Erro ao atribuir associação');
            }

            echo json_encode(['response' => 'Colaborador adicionado']);
        }
    } catch (Exception $e) {
        echo json_encode(['response' => 'Erro ao adicionar colaborador']);
        exit;
    }
}
