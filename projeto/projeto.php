<?php
/*
Plugin Name: Projeto
Description: Descrição do projeto.
Version: 1.0
Author: Gabriel Aguiar
*/
// Adiciona um item ao menu do painel de administração



// Carrega o arquivo functions.php do plugin
require_once plugin_dir_path( __FILE__ ) . 'functions.php';

function meu_plugin_adicionar_menu() {
    // Adiciona um item ao menu principal
    add_menu_page(
        'projeto', // Título da página
        'projeto', // Título do menu
        'manage_options', // Capacidade requerida para acessar o menu
        'projeto-config', // Identificador único da página
        'meu_plugin_pagina_configuracoes', // Função que exibirá o conteúdo da página
        'dashicons-admin-generic', // Ícone para o menu (opcional)
        99 // Posição do menu na barra lateral
    );
}
add_action('admin_menu', 'meu_plugin_adicionar_menu');

// Função para exibir o conteúdo da página de configurações do plugin
function meu_plugin_pagina_configuracoes() {
    // Adicione aqui o código HTML e PHP para exibir as configurações do seu plugin
    echo '<div class="wrap">';
    echo '<h2>Configurações do projeto</h2>';
    echo '<p>Aqui você pode configurar as opções do seu plugin.</p>';
    echo '</div>';
}


// Shortcode para incluir o arquivo agenda.php
function incluir_agenda_php() {
    ob_start();
    include plugin_dir_path( __FILE__ ) . 'agenda.php';
    return ob_get_clean();
}
add_shortcode('inclui_agenda_php', 'incluir_agenda_php');

function incluir_servicos_php(){
    ob_start();
    include plugin_dir_path( __FILE__ ) . 'servicos.php';
    return ob_get_clean();
}

add_shortcode( 'servicos_php', 'incluir_servicos_php' );

function incluir_financeiro_php(){
    ob_start();
    include plugin_dir_path( __FILE__ ) . 'financeiro.php';
    return ob_get_clean();
}
add_shortcode( 'financeiro_php', 'incluir_financeiro_php' );

function incluir_clientes_php(){
    ob_start();
    include plugin_dir_path( __FILE__ ) . 'clientes.php';
    return ob_get_clean();
}
add_shortcode( 'clientes_php', 'incluir_clientes_php' );

function incluir_funcionarios_php(){
    ob_start();
    include plugin_dir_path( __FILE__ ) . 'funcionarios.php';
    return ob_get_clean();
}
add_shortcode( 'funcionarios_php', 'incluir_funcionarios_php' );

function incluir_agendamentos_php(){
    ob_start();
    include plugin_dir_path( __FILE__ ) . 'agendamentos.php';
    return ob_get_clean();
}
add_shortcode( 'agendamentos_php', 'incluir_agendamentos_php' );

function incluir_teste(){
    ob_start();
    include plugin_dir_path( __FILE__ ) . 'pagina-teste.php';
    return ob_get_clean();
}
add_shortcode( 'teste_php', 'incluir_teste' );
// Função para adicionar páginas ao plugin de agenda
function adicionar_paginas_agenda() {
    // Array com as informações das páginas a serem adicionadas
    $paginas = array(
        array(
            'post_title'    => 'Minha Agenda',
            'post_content'  => '[inclui_agenda_php]', // Usa o shortcode personalizado para incluir o arquivo agenda.php
            'post_status'   => 'publish',
            'post_author'   => 1, // ID do autor das páginas
            'post_type'     => 'page'
        ),
        array(
            'post_title' => 'Serviços',
            'post_content' => '[servicos_php]',
            'post_status'   => 'publish',
            'post_author'   => 1, // ID do autor das páginas
            'post_type'     => 'page'
        ),
        array(
            'post_title' => 'Financeiro',
            'post_content' => '[financeiro_php]',
            'post_status'   => 'publish',
            'post_author'   => 1, // ID do autor das páginas
            'post_type'     => 'page'
        ),
        array(
            'post_title' => 'Clientes',
            'post_content' => '[clientes_php]',
            'post_status'   => 'publish',
            'post_author'   => 1, // ID do autor das páginas
            'post_type'     => 'page'
        ),
        array(
            'post_title' => 'Funcionários',
            'post_content' => '[funcionarios_php]',
            'post_status'   => 'publish',
            'post_author'   => 1, // ID do autor das páginas
            'post_type'     => 'page'
        ),
        array(
            'post_title' => 'Agendamentos',
            'post_content' => '[agendamentos_php]',
            'post_status'   => 'publish',
            'post_author'   => 1, // ID do autor das páginas
            'post_type'     => 'page'
        ),
        array(
            'post_title' => 'Teste_pagina',
            'post_content' => '[teste_php]',
            'post_status'   => 'publish',
            'post_author'   => 1, // ID do autor das páginas
            'post_type'     => 'page'
        )
        // Adicione mais páginas conforme necessário
    );

    // Loop para adicionar cada página
    foreach ($paginas as $pagina) {
        // Verifica se a página já existe antes de adicioná-la novamente
        if (get_page_by_title($pagina['post_title']) === null) {
            // Insere a página usando wp_insert_post()
            wp_insert_post($pagina);
        }
    }
}

function enqueue_custom_styles() {
    wp_enqueue_style( 'custom-style', plugins_url( '/css/estilo_v2.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_styles' );
// Adiciona as páginas quando o plugin é ativado
register_activation_hook(__FILE__, 'adicionar_paginas_agenda');

function registrar_scripts_personalizados() {
    
    if (!is_page('481')){
    wp_enqueue_script('script', plugin_dir_url(__FILE__) . 'js/script.js', array(), '1.0', true);}
}
add_action('wp_enqueue_scripts', 'registrar_scripts_personalizados');
