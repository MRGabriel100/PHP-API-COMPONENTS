<?php

// No início do seu functions.php ou projeto.php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Se acessado diretamente, saia
}

// Carregue o wp-load.php usando ABSPATH
require_once ABSPATH . 'wp-load.php';

function start(){
    if (session_status() == 1){
        session_start();
    }
    if(is_user_logged_in()){
    
        $user = get_current_user_id();
        $membership_level = pmpro_getMembershipLevelForUser(get_current_user_id());

        
        $parent_id = get_user_meta($user, 'parent_id', true);
        $tab = get_user_meta($user, 'tab_id', true);

        if($parent_id){
            $_SESSION['user_id'] = $parent_id;
            $_SESSION['fun_id'] = $user;
            $_SESSION['tab_id'] = $tab;
        } else {
            $_SESSION['user_id'] = $user;
            $_SESSION['fun_id'] = null;
            $_SESSION['tab_id'] = null;
        }

        $_SESSION['tipo'] = $membership_level->id;}
    }
    

add_shortcode( 'start-all', 'start');
