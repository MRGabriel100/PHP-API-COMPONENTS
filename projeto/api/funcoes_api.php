<?php

if (session_status() == 1){
    session_start();
}
function funcionario($atts = null){
    $id = $atts;

    if(isset($_SESSION['tab_id'])){ return $_SESSION['tab_id'];}
    else if(isset($id)){ return $id;}
    else { return null;}

};
