<?php

add_shortcode('new_shortcode', 'new_function_shortcode');

function new_function_shortcode(){
    ob_start();


    return "amar shortcode";

    ob_get_clean();
}
